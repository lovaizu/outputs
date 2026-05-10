# ローカルLLM × Claude Code 構成設計書

> 公式 README（waybarrios/vllm-mlx、jundot/omlx）および CC 公式ドキュメント（code.claude.com/docs）を直接読んで確認した内容を記載。

---

## 0. モデル

モデルの実体は **safetensors 形式のウェイトファイル群**（数GB〜数十GB）＋設定ファイル・トークナイザーのセット。HuggingFace からダウンロードしてローカルに置くだけで使える。インストール不要、ダウンロードのみ。

サーバー（vllm-mlx / oMLX）が起動時にこのディレクトリを読み込んでモデルをメモリに展開し、API リクエストを受け付ける。

| モデル | MLX版（4bit） | ダウンロードサイズ目安 |
|--------|-------|------|
| Qwen3.5-27B | mlx-community/Qwen3.5-27B-4bit | ~14GB |
| Qwen3.5-35B-A3B | mlx-community/Qwen3.5-35B-A3B-4bit | ~20GB（全パラメータ分。推論時のメモリ消費は少ない） |

---

## 1. サーバー選定

| 項目 | vllm-mlx | oMLX |
|------|----------|------|
| GitHub | waybarrios/vllm-mlx | jundot/omlx |
| インストール | `pip install vllm-mlx` | `brew install omlx` |
| Anthropic /v1/messages | ✓ | ✓ |
| CC 直接接続 | ✓ | ✓ |
| tool_use | MCP tool calling, 12 parsers | mlx-lm の built-in parsers |
| KV キャッシュ | paged KV + prefix cache + SSD tier | RAM hot + SSD cold 2層 |
| Prometheus `/metrics` | ✓（`--metrics` フラグ） | ✗ |
| サーバーログ | stdout | `~/.omlx/logs/server.log` |
| streaming usage stats | `stream_options.include_usage` | `stream_options.include_usage` |
| Qwen3 reasoning分離 | ✓ `--reasoning-parser qwen3` | 未記載 |

Rapid-MLX は CC 直接接続不可のため対象外。

---

## 2. 全体像（データフロー）

```
┌──────────────────────────────────────────────────────┐
│  Claude Code（`claude` コマンド）                     │
│  └─ ~/.claude/projects/{proj}/{session}.jsonl に記録  │
│     トークン数・ツールコール・各ターンの内容           │
└──────────────────────────┬───────────────────────────┘
                           │ ANTHROPIC_BASE_URL=http://localhost:8000
                           ▼
┌──────────────────────────────────────────────────────┐
│  ローカルサーバー（vllm-mlx or oMLX）                 │
│  ├─ vllm-mlx: GET /metrics → Prometheus 形式          │
│  │   tok/s・TTFT・KVキャッシュ使用率                  │
│  └─ oMLX: ~/.omlx/logs/server.log                    │
│           + streaming の usage フィールド             │
└──────────────────────────┬───────────────────────────┘
                           │ MLX（Apple Silicon）
                           ▼
                      モデル推論
```

---

## 3. UX・使い方

### セッションの起動

```bash
# ターミナル1：サーバーをバックグラウンドで起動
vllm-mlx serve mlx-community/Qwen3.5-27B-4bit --port 8000 --continuous-batching --metrics &

# ターミナル2：CC を起動
export ANTHROPIC_BASE_URL=http://localhost:8000
export ANTHROPIC_API_KEY=not-needed
claude
```

CC を起動すると通常の CC と同じ操作感で使える。ファイル編集・コマンド実行・会話すべてローカルモデルが処理する。

### 1セッション = 1サーバー

- CC は起動時の `ANTHROPIC_BASE_URL` に固定される。セッション中に変えても反映されない
- サーバーを切り替えるには CC を終了 → サーバー再起動 → CC 再起動
- 同時に複数のサーバーを立てることは可能（ポートを変える）

### セッション終了

```bash
# CC を終了（Ctrl+C または /exit）
# サーバーを停止
kill %1   # バックグラウンドジョブを終了
```

---

## 4. 役割分担

| ステップ | 担当 |
|---------|------|
| モデルダウンロード | Claude（`vllm-mlx model acquire` or `huggingface-cli download`） |
| サーバーインストール | Claude（pip / brew） |
| サーバー起動 | Claude |
| メトリクス取得（curl /metrics・ログ確認） | Claude |
| CC セッション（実際の操作・tool_use 発火確認） | あなた |
| 主観的な速度・UX 評価 | あなた |

---

## 5. インストール・起動手順

### 5-1. vllm-mlx

```bash
pip install vllm-mlx

vllm-mlx model acquire mlx-community/Qwen3.5-27B-4bit --target-dir ./models/qwen3.5-27b-4bit

vllm-mlx serve ./models/qwen3.5-27b-4bit \
  --port 8000 \
  --continuous-batching \
  --metrics \
  --reasoning-parser qwen3
```

### 5-2. oMLX

```bash
brew tap jundot/omlx https://github.com/jundot/omlx
brew install omlx

huggingface-cli download mlx-community/Qwen3.5-27B-4bit \
  --local-dir ~/models/Qwen3.5-27B-4bit

omlx serve --model-dir ~/models
```

---

## 6. 計測方法

### 6-1. tok/s・TTFT（vllm-mlx：Prometheus から取得）

```bash
# サーバーを --metrics フラグ付きで起動した場合
curl -s http://localhost:8000/metrics | grep -E "e2e_request_latency|generation_tokens|prompt_tokens"
```

取得できる主なメトリクス：
- `vllm:e2e_request_latency_seconds` → TTFT を含むリクエスト全体のレイテンシ
- `vllm:generation_tokens_total` → 累積生成トークン数（時間で割れば tok/s）
- `vllm:gpu_cache_usage_perc` → KVキャッシュ使用率

### 6-2. tok/s・トークン数（oMLX：streaming usage で取得）

oMLX は Prometheus を持たないため、レスポンスの `usage` フィールドから取得する。

```bash
time curl -s http://localhost:8000/v1/chat/completions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer not-needed" \
  -d '{
    "model": "default",
    "messages": [{"role": "user", "content": "Write a 300-word summary of the history of computing."}],
    "stream": false,
    "stream_options": {"include_usage": true}
  }' | python3 -c "
import json, sys
r = json.load(sys.stdin)
u = r.get('usage', {})
print('prompt_tokens:', u.get('prompt_tokens'))
print('completion_tokens:', u.get('completion_tokens'))
"
# tok/s = completion_tokens ÷ time の実測秒数
```

サーバーログも確認：
```bash
tail -f ~/.omlx/logs/server.log
```

### 6-3. CC セッションのトークン記録（CC JSONL）

CC は各セッションをローカルに記録している：

```bash
# 最新セッションの JSONL を確認
ls -t ~/.claude/projects/$(echo $PWD | sed 's|/|-|g')/*.jsonl | head -1 | xargs cat | \
  python3 -c "
import json, sys
for line in sys.stdin:
    r = json.loads(line)
    if 'usage' in str(r):
        print(json.dumps(r, indent=2, ensure_ascii=False))
" 2>/dev/null | head -100
```

CC の OTel テレメトリを有効にすることで、より詳細な計測も可能：

```bash
export CLAUDE_CODE_ENABLE_TELEMETRY=1
export OTEL_METRICS_EXPORTER=prometheus
claude
```

### 6-4. KVキャッシュ確認（vllm-mlx）

```bash
# セッション中に KVキャッシュ使用率を監視
watch -n 2 'curl -s http://localhost:8000/metrics | grep gpu_cache_usage_perc'
```

### 6-5. tool_use 動作確認（あなたが実施）

CC セッション内で：

```
Create a file /tmp/llm_tool_test.txt with the content 'tool_use OK'
```

確認（私が実行）：

```bash
cat /tmp/llm_tool_test.txt
```

---

## 7. 未確認事項（実機確認が必要なもの）

| 不明点 | 影響 | 確認方法 |
|--------|------|---------|
| vllm-mlx の `/metrics` が実際に TTFT を含むか | 計測手順の修正 | 起動後に `curl /metrics` して確認 |
| oMLX の server.log のフォーマット（tok/s が読み取れるか） | oMLX の計測方法 | 起動後に `tail -f server.log` して確認 |
| 35B-A3B 4bit の実際のメモリ使用量 | フェーズ2の実行可否 | 起動時の `vm_stat` or Activity Monitor |

---

## 8. タスク構成（steering.md との対応）

| タスク | 内容 |
|--------|------|
| #2 setup vllm-mlx | インストール・モデルDL・起動（私が実行） |
| #3 test vllm-mlx | `/metrics` の実動作確認 → 取れなければ手順修正。CC セッションであなたが tool_use 確認 |
| #4 setup oMLX | インストール・起動（私が実行） |
| #5 test oMLX | `server.log` の実動作確認 → 取れなければ手順修正。CC セッションであなたが tool_use 確認 |

**各 test タスクの中に「計測手順が実際に動くか確認し、動かなければ修正する」が含まれる。**

---

## 9. フェーズ2の判断基準

| 判断軸 | 合格基準 |
|--------|---------|
| 安定性 | 30分以上のセッションでクラッシュ・スワップなし |
| 速度 | 27B比で tok/s が 80% 以上を維持 |
| tool_use | 27B と同様に確実に動作 |
