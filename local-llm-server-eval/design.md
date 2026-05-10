# ローカルLLM × Claude Code 構成設計書

## 0. 前提確認

### モデル
| モデル | HuggingFace | MLX版 | 備考 |
|--------|-------------|-------|------|
| Qwen3.5-27B | Qwen/Qwen3.5-27B | mlx-community/Qwen3.5-27B-4bit | dense, ~27B params |
| Qwen3.5-35B-A3B | Qwen/Qwen3.5-35B-A3B | mlx-community/Qwen3.5-35B-A3B-4bit | MoE, active params ~3B → 32GBで動作可能な見込み |

> **注意**: 35B-A3B の "A3B" は active parameters 3B の MoE モデル。推論時のメモリ使用量は dense 27B より小さい可能性がある。

---

## 1. サーバー比較（調査結果）

| 項目 | vllm-mlx | oMLX | Rapid-MLX |
|------|----------|------|-----------|
| GitHub | waybarrios/vllm-mlx | jundot/omlx | raullenchai/Rapid-MLX |
| インストール | `pip install vllm-mlx` | `brew install omlx` | `pip install rapid-mlx` |
| OpenAI /v1/chat/completions | ✓ | ✓ | ✓ |
| Anthropic /v1/messages | ✓ | ✓ | 未確認 |
| tool_use / function calling | MCP tool calling 対応 | 未詳細確認 | 17 tool parsers, 100% tool calling を謳う |
| KV キャッシュ | paged KV cache + prefix cache + SSD tier | RAM hot + SSD cold の2層 | prompt cache あり |
| CLI 運用 | ✓ (pip install後 CLIコマンド) | △ (主体はmenubarアプリ、`omlx serve` でCLI起動可能) | ✓ |
| CC 接続方式 | ANTHROPIC_BASE_URL=http://localhost:8000 | ANTHROPIC_BASE_URL=http://localhost:8000 | OPENAI_BASE_URL=http://localhost:PORT |

### ⚠️ oMLX に関する注意
oMLX はmacOS menubar アプリとして設計されており、インストールはDMGまたはHomebrew経由。
Homebrew で `omlx serve` をCLIから実行することは可能だが、設計思想はGUI中心。
**「GUI不要、CLI のみ」という要件との齟齬があるため確認が必要。**

---

## 2. Claude Code 接続方式

### vllm-mlx / oMLX（Anthropic API互換）
```bash
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=dummy
claude
```

### Rapid-MLX（OpenAI API互換）
CC は Anthropic API として接続するため、Rapid-MLX に接続する場合は
LiteLLM プロキシ等が必要になる可能性がある。要確認。

---

## 3. 作業フロー（フェーズ1）

### ステップ概要
| ステップ | 担当 | 内容 |
|---------|------|------|
| モデルダウンロード | あなた | huggingface-cli download |
| サーバーインストール | あなた | pip / brew |
| サーバー起動 | あなた | CLIコマンド |
| 計測 | あなた | curl + タイムスタンプ |
| CC 接続テスト | あなた | 実際に CC を起動して操作 |
| 結果記録 | あなた → 私 | results/phase1.md に記入 |

---

## 4. 各サーバーのインストール・起動手順（案）

### 4-1. vllm-mlx

```bash
# インストール
pip install vllm-mlx

# モデルダウンロード（初回のみ）
huggingface-cli download mlx-community/Qwen3.5-27B-4bit

# サーバー起動
vllm-mlx serve mlx-community/Qwen3.5-27B-4bit \
  --port 8000 \
  --max-model-len 32768

# CC 接続
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=dummy
claude
```

### 4-2. oMLX

```bash
# インストール（Homebrew）
brew tap jundot/omlx https://github.com/jundot/omlx
brew install omlx

# サーバー起動
omlx serve --model-dir ~/.cache/huggingface/hub/mlx-community/Qwen3.5-27B-4bit

# CC 接続（Anthropic互換の場合）
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=dummy
claude
```

> **要確認**: omlx serve の正確なオプション名（--model-dir / --model / --port 等）は公式READMEで確認が必要。

### 4-3. Rapid-MLX

```bash
# インストール
pip install rapid-mlx

# サーバー起動
rapid-mlx serve mlx-community/Qwen3.5-27B-4bit \
  --enable-auto-tool-choice \
  --tool-call-parser hermes

# CC 接続
# Rapid-MLX はOpenAI互換のため、Anthropic→OpenAI変換が必要。
# LiteLLM または CC の --openai フラグを確認すること。
```

> **要確認**: Rapid-MLX の CC 接続方法（直接 ANTHROPIC_BASE_URL が使えるか、LiteLLM中継が必要か）。

---

## 5. 計測方法

### 5-1. tok/s・TTFT（curl で計測）

```bash
# 計測用プロンプト（標準化）
PROMPT="Write a 500-word essay about the history of computing."

# リクエスト送信と時間計測
time curl -s http://localhost:8000/v1/chat/completions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer dummy" \
  -d "{
    \"model\": \"mlx-community/Qwen3.5-27B-4bit\",
    \"messages\": [{\"role\": \"user\", \"content\": \"$PROMPT\"}],
    \"stream\": false
  }" | tee /tmp/response.json

# レスポンスから tok/s を確認
cat /tmp/response.json | python3 -c "
import json, sys
r = json.load(sys.stdin)
usage = r.get('usage', {})
print(f'prompt_tokens: {usage.get(\"prompt_tokens\")}')
print(f'completion_tokens: {usage.get(\"completion_tokens\")}')
"
```

### 5-2. TTFT（streaming で計測）

```bash
# streaming で最初のトークンが返るまでの時間を計測
START=$(date +%s%N)
curl -s http://localhost:8000/v1/chat/completions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer dummy" \
  -d "{
    \"model\": \"mlx-community/Qwen3.5-27B-4bit\",
    \"messages\": [{\"role\": \"user\", \"content\": \"Hello\"}],
    \"stream\": true
  }" | while IFS= read -r line; do
    if [[ $line == data:* ]] && [[ $line != "data: [DONE]" ]]; then
      END=$(date +%s%N)
      echo "TTFT: $(( (END - START) / 1000000 )) ms"
      break
    fi
  done
```

### 5-3. KV キャッシュ確認（同一プロンプトを2回送信）

```bash
# 長いコンテキストを2回送って TTFT を比較
# 1回目（キャッシュなし）と2回目（キャッシュあり）で TTFT が短縮されるか確認
LONG_CONTEXT="$(python3 -c "print('あ' * 10000)")"  # ~10K tokens

# 1回目
echo "=== 1st request (no cache) ==="
# (上記 TTFT 計測コマンドを実行)

# 2回目（同一プロンプト）
echo "=== 2nd request (should hit cache) ==="
# (同一コマンドを再実行)
```

### 5-4. tool_use 動作確認

```bash
# サーバー起動後、CC を接続して以下を実行する
# CC プロンプトで:
#   "create a file called /tmp/test_tool_use.txt with the content 'hello'"
# 確認:
ls -la /tmp/test_tool_use.txt
cat /tmp/test_tool_use.txt
# ファイルが実際に作成されていれば tool_use が機能している
```

---

## 6. 記録フォーマット

各サーバーのテスト後、以下を `results/phase1.md` に記入する（あなたが記入）：

```markdown
## [サーバー名]

| 項目 | 結果 |
|------|------|
| tool_use 動作 | ✓ / ✗ |
| tok/s（生成速度） | XX tok/s |
| TTFT（初回） | XX ms |
| TTFT（キャッシュヒット） | XX ms |
| KV キャッシュ維持 | ✓ / ✗ |
| 安定性メモ | （クラッシュ・スワップ等） |
```

---

## 7. 未確認事項（着手前に調べる or 実際に試して判断）

| # | 不明点 | 影響 |
|---|--------|------|
| 1 | Rapid-MLX が Anthropic /v1/messages に直接対応しているか | CCの接続方式が変わる |
| 2 | oMLX の `omlx serve` が CLI のみで完結するか | GUI要件との適合性 |
| 3 | vllm-mlx の `vllm-mlx serve` の正確なコマンド名 | 起動手順 |
| 4 | 各サーバーのモデル指定方法（HF repo名 or ローカルパス） | 起動コマンド |
| 5 | 35B-A3B 4bit の実際のメモリ使用量（32GBに収まるか） | フェーズ2の実行可否 |

---

## 8. フェーズ2の判断基準

フェーズ1で選定したサーバーで 35B-A3B を試す。

| 判断軸 | 合格基準 |
|--------|---------|
| 安定性 | 30分以上のセッションでクラッシュ・スワップなし |
| 速度 | 27B比で tok/s が 80% 以上を維持 |
| tool_use | 27B と同様に確実に動作 |

上記を満たせば 35B-A3B を採用。満たさなければ 27B に戻す。
