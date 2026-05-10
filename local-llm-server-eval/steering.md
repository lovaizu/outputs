# Goal

MBA M5 32GB でローカルLLM × Claude Code を本格運用する構成を決定する

フェーズ1：Qwen3.5-27B を固定して vllm-mlx / oMLX / Rapid-MLX を順番に試す。各サーバーで tool_use の動作・生成速度（tok/s）・TTFT・長セッションでのKVキャッシュ維持を確認する。

フェーズ2：フェーズ1で選んだサーバーに Qwen3.5-35B-A3B を乗せて比較する。32GB での安定性（スワップ・クラッシュ）・速度と品質のトレードオフが27Bに対して許容範囲かを判断する。

判断基準：tool_use が確実に動くことが最低条件。その上で速度・長セッション安定性でサーバーを選ぶ。35B は安定して動けば採用、不安定なら27B に戻す。

# Assumptions

- ハードウェア：MacBook Air M5 32GB（Apple Silicon）
- Claude Code はインストール済み
- GUI 不要、CLI のみ
- コスト観点は除外
- 各サーバーは OpenAI-compatible API を提供する前提（CC の --api-key / baseURL 経由で接続）
- モデルは HuggingFace / MLX Community からダウンロード

# Rules

- 各サーバーのテストは同一プロンプト・同一条件で実施して比較可能にする
- tool_use テストは実際にファイル編集・コマンド実行が発火することを確認する（応答テキストだけでは不可）
- 数値（tok/s, TTFT ms）は実測値のみ記録する（推定・カタログ値は不可）
- フェーズ1の結果を `results/phase1.md` にまとめてからフェーズ2に進む

# Tasks

- [ ] #1: research 各サーバー（vllm-mlx / oMLX / Rapid-MLX）のインストール手順・OpenAI API 互換性・MLX 対応状況を調査する
- [ ] #2: setup vllm-mlx に Qwen3.5-27B を乗せてサーバーを起動する
- [ ] #3: test vllm-mlx の tool_use・tok/s・TTFT・KVキャッシュを計測して記録する
- [ ] #4: setup oMLX に Qwen3.5-27B を乗せてサーバーを起動する
- [ ] #5: test oMLX の tool_use・tok/s・TTFT・KVキャッシュを計測して記録する
- [ ] #6: setup Rapid-MLX に Qwen3.5-27B を乗せてサーバーを起動する
- [ ] #7: test Rapid-MLX の tool_use・tok/s・TTFT・KVキャッシュを計測して記録する
- [ ] #8: decide フェーズ1結果を results/phase1.md にまとめてサーバーを選定する
- [ ] #9: setup 選定サーバーに Qwen3.5-35B-A3B を乗せて起動する
- [ ] #10: test 35B の安定性・速度・品質を計測して27Bと比較する
- [ ] #11: decide 最終構成（サーバー＋モデル）を決定して results/final.md に記録する
