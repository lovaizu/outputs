# ローカルLLM × Claude Code 構成設計書

> 公式 README（waybarrios/vllm-mlx、jundot/omlx、raullenchai/Rapid-MLX）を直接読んで確認した内容を記載。

## 0. 前提確認

### モデル
| モデル | HuggingFace | MLX版（4bit） | 備考 |
|--------|-------------|-------|------|
| Qwen3.5-27B | Qwen/Qwen3.5-27B | mlx-community/Qwen3.5-27B-4bit | dense |
| Qwen3.5-35B-A3B | Qwen/Qwen3.5-35B-A3B | mlx-community/Qwen3.5-35B-A3B-4bit | MoE, active params ~3B → 推論メモリは dense 27B より軽い見込み |

---

## 1. サーバー比較

| 項目 | vllm-mlx | oMLX | Rapid-MLX |
|------|----------|------|-----------|
| GitHub | waybarrios/vllm-mlx | jundot/omlx | raullenchai/Rapid-MLX |
| インストール | `pip install vllm-mlx` | `brew install omlx` | `pip install rapid-mlx` |
| OpenAI /v1/chat/completions | ✓ | ✓ | ✓ |
| Anthropic /v1/messages | ✓ | ✓ | ✗ |
| Claude Code 直接接続 | ✓ `ANTHROPIC_BASE_URL` | ✓ `ANTHROPIC_BASE_URL` | ✗ |
| tool_use | MCP tool calling 12 parsers（Qwen含む） | OpenAI互換 function calling | 100% tool calling, 17 parsers |
| KV キャッシュ | paged KV + prefix cache + SSD tier | RAM hot + SSD cold 2層 | prompt cache |
| CLI 運用 | ✓ | ✓（`omlx serve --model-dir ~/models`） | ✓ |
| Qwen3 reasoning分離 | ✓ `--reasoning-parser qwen3` | 未記載 | ✓ `--no-thinking` |

### 評価対象

| サーバー | CC接続 | 評価 |
|---------|--------|------|
| vllm-mlx | ✓ | 対象 |
| oMLX | ✓ | 対象 |
| Rapid-MLX | ✗（OpenAI互換のみ、CC本体と接続不可） | 対象外 |

---

## 2. Claude Code 接続方式

vllm-mlx・oMLX 共通：

```bash
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

---

## 3. 作業フロー

| ステップ | 担当 | 内容 |
|---------|------|------|
| モデルダウンロード | あなた | `huggingface-cli download` |
| サーバーインストール | あなた | pip / brew |
| サーバー起動 | あなた | CLIコマンド |
| 計測 | あなた | curl + タイムスタンプ |
| CC 接続テスト | あなた | 実際に CC を起動して操作 |
| 結果記録 | あなた → 私 | results/phase1.md に記入、私が整理 |

---

## 4. インストール・起動手順

### 4-1. vllm-mlx

```bash
pip install vllm-mlx

huggingface-cli download mlx-community/Qwen3.5-27B-4bit

vllm-mlx serve mlx-community/Qwen3.5-27B-4bit --port 8000 --continuous-batching

export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

### 4-2. oMLX

```bash
brew tap jundot/omlx https://github.com/jundot/omlx
brew install omlx

mkdir -p ~/models
huggingface-cli download mlx-community/Qwen3.5-27B-4bit --local-dir ~/models/Qwen3.5-27B-4bit

omlx serve --model-dir ~/models

export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

---

## 5. 計測方法

### 5-1. tok/s

```bash
MODEL="mlx-community/Qwen3.5-27B-4bit"
PROMPT="Write a 500-word essay about the history of computing."

time curl -s http://localhost:8000/v1/chat/completions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer not-needed" \
  -d "{
    \"model\": \"$MODEL\",
    \"messages\": [{\"role\": \"user\", \"content\": \"$PROMPT\"}],
    \"stream\": false
  }" | python3 -c "
import json, sys
r = json.load(sys.stdin)
u = r.get('usage', {})
print('prompt_tokens:', u.get('prompt_tokens'))
print('completion_tokens:', u.get('completion_tokens'))
"
```

> tok/s = completion_tokens ÷ `time` の実測秒数

### 5-2. TTFT

```bash
MODEL="mlx-community/Qwen3.5-27B-4bit"

START=$(date +%s%N)
curl -s http://localhost:8000/v1/chat/completions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer not-needed" \
  -d "{
    \"model\": \"$MODEL\",
    \"messages\": [{\"role\": \"user\", \"content\": \"Hello\"}],
    \"stream\": true
  }" | while IFS= read -r line; do
    if [[ "$line" == data:* ]] && [[ "$line" != "data: [DONE]" ]]; then
      END=$(date +%s%N)
      echo "TTFT: $(( (END - START) / 1000000 )) ms"
      break
    fi
  done
```

### 5-3. KV キャッシュ（prefix cache ヒット確認）

同一の長いプロンプトを2回送信して TTFT を比較。2回目が大幅に短縮されれば prefix cache が効いている。

```bash
MODEL="mlx-community/Qwen3.5-27B-4bit"
LONG_PROMPT="$(python3 -c "print('Please summarize the following: ' + 'word ' * 2000)")"

for i in 1 2; do
  echo "=== Request $i ==="
  START=$(date +%s%N)
  curl -s http://localhost:8000/v1/chat/completions \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer not-needed" \
    -d "{
      \"model\": \"$MODEL\",
      \"messages\": [{\"role\": \"user\", \"content\": \"$LONG_PROMPT\"}],
      \"stream\": true
    }" | while IFS= read -r line; do
      if [[ "$line" == data:* ]] && [[ "$line" != "data: [DONE]" ]]; then
        END=$(date +%s%N)
        echo "TTFT: $(( (END - START) / 1000000 )) ms"
        break
      fi
    done
done
```

### 5-4. tool_use 動作確認

CC を起動して以下を指示する：

```
Create a file /tmp/llm_tool_test.txt with the content 'tool_use OK'
```

確認：

```bash
cat /tmp/llm_tool_test.txt
```

"tool_use OK" が出力されれば tool_use が実際に発火している。

---

## 6. 記録フォーマット（results/phase1.md）

```markdown
## [サーバー名]

| 項目 | 結果 |
|------|------|
| CC 接続 | ✓ / ✗ |
| tool_use 動作 | ✓ / ✗ |
| tok/s | XX tok/s |
| TTFT（初回） | XX ms |
| TTFT（キャッシュヒット） | XX ms |
| KV キャッシュ維持 | ✓ / ✗ |
| 安定性メモ | （クラッシュ・スワップ等） |
```

---

## 7. 未確認事項

| 不明点 | 影響 |
|--------|------|
| 35B-A3B 4bit の実際のメモリ使用量 | フェーズ2の実行可否（実機確認） |

---

## 8. フェーズ2の判断基準

| 判断軸 | 合格基準 |
|--------|---------|
| 安定性 | 30分以上のセッションでクラッシュ・スワップなし |
| 速度 | 27B比で tok/s が 80% 以上を維持 |
| tool_use | 27B と同様に確実に動作 |

上記を満たせば 35B-A3B を採用、満たさなければ 27B に戻す。
