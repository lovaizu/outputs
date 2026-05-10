# ローカルLLM × Claude Code 構成設計書

> 公式 README（waybarrios/vllm-mlx、jundot/omlx、raullenchai/Rapid-MLX）を直接読んで確認した内容を記載。

## 0. 前提確認

### モデル
| モデル | HuggingFace | MLX版（4bit） | 備考 |
|--------|-------------|-------|------|
| Qwen3.5-27B | Qwen/Qwen3.5-27B | mlx-community/Qwen3.5-27B-4bit | dense |
| Qwen3.5-35B-A3B | Qwen/Qwen3.5-35B-A3B | mlx-community/Qwen3.5-35B-A3B-4bit | MoE, active params ~3B → 推論メモリは dense 27B より軽い見込み |

---

## 1. サーバー比較（公式 README 確認済み）

| 項目 | vllm-mlx | oMLX | Rapid-MLX |
|------|----------|------|-----------|
| GitHub | waybarrios/vllm-mlx | jundot/omlx | raullenchai/Rapid-MLX |
| インストール | `pip install vllm-mlx` | `brew install omlx` | `pip install rapid-mlx` |
| OpenAI /v1/chat/completions | ✓ | ✓ | ✓ |
| Anthropic /v1/messages | ✓（README明示） | ✓（README "API Compatibility" セクションに明示） | **✗**（OpenAI互換のみ） |
| Claude Code 直接接続 | ✓ `ANTHROPIC_BASE_URL` | ✓ `ANTHROPIC_BASE_URL`（vllm-mlx と同様の方式で動作するはず） | **✗**（CC本体とは接続不可） |
| tool_use | MCP tool calling 12 parsers（Qwen含む） | OpenAI互換 function calling（詳細未記載） | 100% tool calling, 17 parsers（OpenAI互換） |
| KV キャッシュ | paged KV + prefix cache + SSD tier | RAM hot + SSD cold 2層 | prompt cache |
| CLI 運用 | ✓ | ✓（`omlx serve --model-dir ~/models` でGUI不要） | ✓ |
| Qwen3 reasoning分離 | ✓ `--reasoning-parser qwen3` | 未記載 | ✓（`--no-thinking`で無効化も可） |

### ⚠️ Rapid-MLX の重大制約（公式 README より確認）

Rapid-MLX の README は以下を明示している：

> "Want a Claude Code-like TUI? Rapid-MLX is the *backend* — pair it with an open-source agent CLI like OpenCode or codex"

**Claude Code（Anthropic製）との直接接続には対応していない。**
OpenAI互換 API のみのため、`ANTHROPIC_BASE_URL` は使えない。
"OpenClaude"（サードパーティのCC代替ツール）経由でのみ類似動作が可能。

→ **CC × Rapid-MLX の組み合わせは評価対象から外すか、代替接続方法を別途検討が必要。**

### oMLX の CC 接続（確認済み）

README "API Compatibility" セクションに `POST /v1/messages`（Anthropic endpoint）が明示されている。
また公式サイト（omlx.ai）にも "Native /v1/messages Anthropic endpoint" と記載あり。
接続方式は vllm-mlx と同様に `ANTHROPIC_BASE_URL=http://localhost:8000` で動作するはず。

---

## 2. Claude Code 接続方式

### vllm-mlx（公式 README の例そのまま）
```bash
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

### oMLX
```bash
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

### Rapid-MLX（CC直接接続不可）
```bash
# 接続方法なし。評価対象から外すか要判断。
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

## 4. インストール・起動手順（公式 README ベース）

### 4-1. vllm-mlx

```bash
# インストール
pip install vllm-mlx

# モデルダウンロード（初回のみ）
huggingface-cli download mlx-community/Qwen3.5-27B-4bit

# サーバー起動（公式 README の例に準拠）
vllm-mlx serve mlx-community/Qwen3.5-27B-4bit --port 8000 --continuous-batching

# Qwen3 の reasoning を分離したい場合
# vllm-mlx serve mlx-community/Qwen3.5-27B-4bit --port 8000 --continuous-batching --reasoning-parser qwen3

# CC 接続
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

### 4-2. oMLX

```bash
# インストール（Homebrew）
brew tap jundot/omlx https://github.com/jundot/omlx
brew install omlx

# モデルを配置するディレクトリを用意
mkdir -p ~/models
# mlx-community/Qwen3.5-27B-4bit を ~/models 以下に配置
huggingface-cli download mlx-community/Qwen3.5-27B-4bit --local-dir ~/models/Qwen3.5-27B-4bit

# サーバー起動（公式 README の CLI 例）
omlx serve --model-dir ~/models

# CC 接続（OpenAI互換のみの場合、この方法が動くか実機確認が必要）
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

> `/v1/messages` 対応は公式 README と omlx.ai で確認済み。

### 4-3. Rapid-MLX（参考：CC接続不可のため評価保留）

```bash
# インストール
pip install rapid-mlx

# サーバー起動（モデルエイリアスで指定）
rapid-mlx serve qwen3.5-27b

# → CC には接続不可。OpenCode 等の代替 CLI エージェントと組み合わせる想定。
```

---

## 5. 計測方法

すべて OpenAI互換エンドポイント（`/v1/chat/completions`）で統一。

### 5-1. tok/s

```bash
MODEL="mlx-community/Qwen3.5-27B-4bit"
PROMPT="Write a 500-word essay about the history of computing."

curl -s http://localhost:8000/v1/chat/completions \
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
# tok/s は completion_tokens ÷ 実測経過時間で算出
"
```

> tok/s の算出：`time curl ...` の実測秒数 ÷ completion_tokens で計算する。

### 5-2. TTFT（streaming で計測）

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

```bash
# 同一の長いプロンプトを2回送信してTTFTを比較
# 2回目が1回目より大幅に短ければ prefix cache が効いている

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

```bash
# CC を起動して以下の指示を出す
claude  # (ANTHROPIC_BASE_URL 設定後)

# CC プロンプトで:
#   "Create a file /tmp/llm_tool_test.txt with the content 'tool_use OK'"
# 確認:
cat /tmp/llm_tool_test.txt
# → "tool_use OK" が出力されれば tool_use が実際に発火している
```

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

## 7. 残存する未確認事項

| # | 不明点 | 影響 |
|---|--------|------|
| 1 | 35B-A3B 4bit の実際のメモリ使用量 | フェーズ2の実行可否 |

※ 以下は解消済み：

| # | 結論 |
|---|------|
| Rapid-MLX の CC 接続 | **不可**（OpenAI互換のみ。CC本体との接続方法なし） |
| oMLX の CLI 完結性 | **可**（`omlx serve --model-dir ~/models`） |
| vllm-mlx の起動コマンド | `vllm-mlx serve <model> --port 8000 --continuous-batching` |
| 各サーバーのモデル指定 | HF repo名をそのまま指定（vllm-mlx / Rapid-MLX）、ローカルdir指定（oMLX） |

---

## 8. 評価対象の整理（要確認）

現時点での評価可否：

| サーバー | CC接続 | 評価可否 |
|---------|--------|---------|
| vllm-mlx | ✓ 確認済み | **評価対象** |
| oMLX | ✓（公式 README + omlx.ai で確認） | **評価対象** |
| Rapid-MLX | ✗ 不可 | **評価対象外**（CC本体と接続できないため） |

→ **Rapid-MLX を除外してよいか、あるいは代替接続方法（LiteLLMプロキシ等）を試すか、判断をお願いします。**

---

## 9. フェーズ2の判断基準

| 判断軸 | 合格基準 |
|--------|---------|
| 安定性 | 30分以上のセッションでクラッシュ・スワップなし |
| 速度 | 27B比で tok/s が 80% 以上を維持 |
| tool_use | 27B と同様に確実に動作 |

上記を満たせば 35B-A3B を採用、満たさなければ 27B に戻す。
