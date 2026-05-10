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

<!-- paused: 2026-05-10 — next: #4 vllm-mlx で CC セッションを実施し tool_use・速度・安定性を確認する（あなたが実施） -->

- [x] #1: research 各サーバーの仕様・CC接続方法・計測アーキテクチャを設計する（design.md）
- [x] #2: setup vllm-mlx をインストールし Qwen3.5-27B を起動する（Claude が実行）
- [x] #3: verify vllm-mlx の計測手順（/metrics・TTFT・usage）が実際に動くか確認し、動かなければ修正する
- [ ] #4: test vllm-mlx で CC セッションを実施し tool_use・速度・安定性を確認する（あなたが実施）
- [ ] #5: setup oMLX をインストールし Qwen3.5-27B を起動する（Claude が実行）
- [ ] #6: verify oMLX の計測手順（server.log・usage）が実際に動くか確認し、動かなければ修正する
- [ ] #7: test oMLX で CC セッションを実施し tool_use・速度・安定性を確認する（あなたが実施）
- [ ] #8: decide フェーズ1結果を results/phase1.md にまとめてサーバーを選定する
- [ ] #9: setup 選定サーバーに Qwen3.5-35B-A3B を乗せて起動する（Claude が実行）
- [ ] #10: test 35B で CC セッションを実施し安定性・速度・品質を27Bと比較する（あなたが実施）
- [ ] #11: decide 最終構成（サーバー＋モデル）を決定して results/final.md に記録する
- [ ] #12: test 最終構成で CC 2セッション並行起動し安定動作・メモリ使用量を確認する
