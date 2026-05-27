# Goal

CCとの協業でステアリングできるプラグインを作る。プラグインはCCのマーケットプレイスに公開する。

## Verification

- プラグインが https://github.com/lovaizu/ccpm に公開されている
- `/rn:gm`, `/rn:bb`, `/rn:hi` の3コマンドが動作する
- steering.mdベースの作業管理で中断・再開が可能である

# Assumptions

- 汎用ツール（どのリポジトリでも使える）
- aiyaとの棲み分けは考えない、独立したプラグインとして設計する
- コンテキスト閾値での自動bb→clear→hiは将来課題（CCのhookがコンテキスト使用量を受け取れないため）
- スキルから`/clear`は呼べない（CCの制約）。bb後のclearはユーザーが手動で行う

# Rules

- プロンプトはすべて提案ベース（質問で聞かない、提案して承認を得る）
- ルールファイル（AIが読む.md）は英語で書く

# Tasks

- [x] #1: プラグイン名を決定する
- [x] #2: コマンド名を決定する
- [x] #3: steering.mdテンプレートのセクション構成を設計する
- [x] #4: タスクフォーマットと定義ルールを設計する
- [x] #5: タスク完了プロセス（3ステップ/5ステップ）を設計する
- [x] #6: 各コマンド（gm/bb/hi）の作業ステップを設計する
- [x] #7: action.md原則のsteering.mdへの組み込み方を設計する
- [x] #8: 設計書を完成させる（本ファイルの「Design」セクションを完成）
- [x] #9: プラグインのディレクトリ構成を設計する（plugin.json, skills/, hooks/等）
- [ ] #10: design.md のユーザーレビュー
- [ ] #11: lovaizu/ccpm リポジトリにプラグインを実装してPR作成する

# Decisions

## D-1: プラグイン名は rn
- **Conclusion**: `rn`（Right Now）を採用
- **Rationale**: 「今すぐやる」のアメリカンネットスラング。コマンド名（gm/bb/hi）と同じカジュアルなトーンで統一

## D-2: コマンドは gm / bb / hi
- **Conclusion**: `/rn:gm`（開始）、`/rn:bb`（中断）、`/rn:hi`（再開）
- **Rationale**: 全2文字で打ちやすい。3つとも挨拶系で統一。gm=good morning（新しい一日の始まり＝開始）、bb=bye bye（またね＝中断）、hi=hello（ただいま＝再開）

## D-3: steering.mdのセクション構成
- **Conclusion**: Goal, Verification, Assumptions, Rules, Tasks, Decisions, State の7セクション
- **Rationale**: action.mdの4原則（A:Goal-oriented, B:Fact-oriented, C:Hypothesis-driven, D:Proposal-oriented）に対応。Goal+VerificationはC.1、AssumptionsはB.1/A.5、DecisionsはD.3形式、Stateはbb/hiサイクル専用

## D-4: タスクフォーマットはnablarch準拠
- **Conclusion**: 各タスクに目的・前提・作業内容・完了条件を記載。定義要件として粒度・具体性・客観性・前提明示を義務づける
- **Rationale**: nablarch steering.mdのプラクティスは最低限やるべきこと。action.md B.1（具体性）、C.1（客観的完了条件）に直結

## D-5: タスク完了プロセスは3ステップ/5ステップ
- **Conclusion**: 非コード変更は3ステップ（セルフチェック→QAレビュー→ユーザーレビュー）、コード変更は5ステップ（+言語エキスパート+SWエンジニア）。レビューはサブエージェントで実施
- **Rationale**: メインエージェントはバイアスがかかる。サブエージェントは会話コンテキストを引き継がず独立評価できる。assayと同じ原理。全件対応が原則（C.5）

## D-6: チェックファイル出力先
- **Conclusion**: `{steering.mdのディレクトリ}/checks/{タスクID}.md`
- **Rationale**: steering.mdの場所はPJによって変わるため、相対パスで統一

## D-7: steering.mdの探索方法
- **Conclusion**: gmは新規作成のみ（配置場所を提案）。hiはカレントブランチのコミット履歴から探す。bbはhiで特定済みのものを使う（不明ならコミット履歴から）
- **Rationale**: gmで既存を使うケースはない。hi/bbはgitが状態管理の権威なのでコミット履歴ベース

## D-8: カバレッジツールは一般化
- **Conclusion**: JaCoCo固定ではなく、プロジェクトの言語に応じたツール（Jest, pytest, gcov等）を使う
- **Rationale**: 汎用プラグインなのでJava固定にしない

## D-9: 全プロンプト提案ベース
- **Conclusion**: コマンド内のすべてのユーザー対話は質問ではなく提案にする
- **Rationale**: action.md D原則。「どこに置きますか？」ではなく「`work/x/steering.md`に作成します」

## D-10: プラグイン構成はコマンド3 + スキル1
- **Conclusion**: Archetype A（Command-driven workflow）。`commands/` に gm.md, bb.md, hi.md の3コマンド。`skills/rn-execution/` にタスク実行の共有知識。エージェントファイルは不要（Agent toolでインライン呼び出し）。フックなし
- **Rationale**: gm/bb/hiは明示的に呼ぶワークフロー→コマンド。タスク実行知識（完了プロセス・レビュー手順・チェックファイル形式）はgm・hiで共有→スキルに分離（三層分離原則）。レビューサブエージェントはタスク固有の完了条件を動的に受け取るため、静的なエージェントファイルよりインライン構築が適切

## D-11: 成果物は設計書のみ
- **Conclusion**: design.md を成果物とし、実装ファイルは設計レビュー後に別タスクで作成する
- **Rationale**: ユーザーレビューなしに実装を進めていた。設計書のレビュー→承認→実装の順序に修正

# State

(written by /rn:bb, read and removed by /rn:hi)
