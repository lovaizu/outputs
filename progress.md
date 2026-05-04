# Progress

## Original Intent

> MacでCmdをスペースの左横に配置したいです。
> MacだとCmdを色々使うので、現状の一部Opt->Cmd切り替えでは不便でした。
> 影響範囲を調べて、どうするか検討したい。

## 方針転換（仕切り直し）

Cmd/Opt スワップの検討を経て、根本から再設計することに決定。

**新方針：Ctrl/Alt で全操作を統一し、Karabiner（Mac）/AHK（Win）で OS ネイティブに変換する。**

- Karabiner は全アプリに適用（除外なし — ターミナルは passthrough override で対応）
- Win/Mac でできるだけ同一のキー操作を実現する
- Cmd/Opt スワップは行わない（HHKB Mac プロファイルは標準配置）

## Current Phase

**設計フェーズ — 設計書レビュー中、実装前**

### 全体の進め方（このPRで完結）

1. ~~**要件定義** — `requirements.md` の各項目を確定~~ ✅
2. **設計** ← **今ここ**
   - ~~a. レイヤー分類（L1/L2/L3 → L1/L2 に統一）~~ ✅
   - ~~b. 設計書作成 (`hhkb-keybinding-design.md`)~~ ✅
   - c. 設計書レビュー・修正 ← **継続中**
3. **セットアップ手順・設定ファイル作成**（設計確定後）
   - a. `setup.md` — セットアップ手順書
   - b. `hhkb-emacs-keybindings.json` — Karabiner 設定ファイル
   - c. `emacs-keybind.ahk` — AHK v2 スクリプト

---

## 今セッションで確定した設計変更

### 除外アプリの廃止と passthrough への置き換え

- **旧設計（誤り）**: Edit ルールを「Emacs ネイティブアプリを除外」していた → L3 的発想で L2 の原則と矛盾
- **新設計**: 除外なし。ターミナルは「passthrough override」として L2 の中で明示的に定義
- ターミナルで `Ctrl+K/W/Y` を passthrough にする理由：シェルの ZLE ウィジェット（`.zshrc`）が kill ring → OS クリップボードへ橋渡しするため

### ターミナル環境の確定

| OS | ターミナルエミュレータ | シェル環境 |
|----|---------------------|-----------|
| Mac | Ghostty / cmux（同一設定 — cmux は libghostty ベース） | zsh |
| Win | Windows Terminal | WSL + zsh + tmux |

### シェル前提条件（L2 enabler）

Karabiner/AHK の変換を成立させるために必要な設定（L2 のための前提、L3 ではない）：

- **Mac `.zshrc`**: ZLE ウィジェットで kill ring → `pbcopy` に同期
- **Win `.zshrc`（WSL）**: ZLE ウィジェットで kill ring → `clip.exe` に同期
- **Win `.tmux.conf`**: `set -g set-clipboard on`

### 設計書の構造変更

- requirements.md の転記を削除（300行 → 削除）
- 「要件と異なる部分だけ」を設計書に記載する構造に刷新
  - modifier マッピング（SPL=Opt/Alt）
  - 非自明な変換のみ列挙
  - ターミナル passthrough override
  - Ctrl+X prefix の仕組み
  - 検証項目（V-M1〜V-M5、V-W1〜V-W4）

### コミュニティ調査の結論

- Mac：全アプリ適用が標準。Karabiner で Electron/ブラウザをカバー → 今回の設計と一致
- Win：全アプリ適用が目標だが妥協が多い。ターミナル除外はほぼ全員のやり方 → 今回の設計と一致

---

## 次セッションの出発点

**未解決の問い：Win で全アプリ統一は本当にできるか？**

コミュニティ調査の結論：
- Mac：全アプリ統一が標準。Karabiner で実現可能 → **統一できそう**
- Win：全アプリ統一を目標にするが、以下の衝突が根本的に残る
  - `Ctrl+A/C/X/Z`（コピー・ペースト・アンドゥ）が Windows 慣習と衝突
  - `Alt+*`（M-f/M-b 等）がメニューバーを開く
  - コミュニティの多くはこれらをスキップまたは回避している
  - → **統一できないケースが残る可能性**

今回の設計では「衝突は意図的に上書き」としているが、これが本当に許容できるか確認が必要。

次セッションでまず議論すること：
1. Win の衝突（`Ctrl+A/C/X/Z`、`Alt+*` メニューバー）は許容できるか
2. 許容できない場合、設計をどう修正するか
3. 上記を踏まえて設計書を確定 → 実装へ

---

## Key Design Decisions (確定済み)

- **レイヤー構成**: L1 (HHKB) + L2 (Karabiner/AHK) のみ。L3 なし
- **AHK 運用**: スタートアップ登録なし。HHKB 使用時のみ手動起動
- **衝突方針**: Ctrl+\*/Alt+\* が既存 OS ショートカットを上書きするのは意図的
- **除外アプリ**: なし。ターミナルは L2 内で passthrough override として定義
- **ターミナル**: Mac=Ghostty/cmux、Win=WSL+zsh+tmux
- **設計書**: `cross-platform-key-bindings-with-hhkb/hhkb-keybinding-design.md` ✅

---

## Session Context

- 作業ブランチ: worktree-keybind
- PR: https://github.com/lovaizu/outputs/pull/12
- 要件ファイル: `cross-platform-key-bindings-with-hhkb/requirements.md`
- 設計書: `cross-platform-key-bindings-with-hhkb/hhkb-keybinding-design.md`
- 成果物（作成予定）:
  - `cross-platform-key-bindings-with-hhkb/setup.md`
  - `cross-platform-key-bindings-with-hhkb/hhkb-emacs-keybindings.json`
  - `cross-platform-key-bindings-with-hhkb/emacs-keybind.ahk`
