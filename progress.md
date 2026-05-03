# Progress

## Original Intent

> MacでCmdをスペースの左横に配置したいです。
> MacだとCmdを色々使うので、現状の一部Opt->Cmd切り替えでは不便でした。
> 影響範囲を調べて、どうするか検討したい。

## 方針転換（仕切り直し）

Cmd/Opt スワップの検討を経て、根本から再設計することに決定。

**新方針：Ctrl/Alt で全操作を統一し、Karabiner（Mac）/AHK（Win）で OS ネイティブに変換する。**

- Karabiner は全アプリに適用（除外なし）
- Win/Mac でできるだけ同一のキー操作を実現する
- Cmd/Opt スワップは行わない（HHKB Mac プロファイルは標準配置）

## Current Phase

**要件定義レビュー中** — Window / Tab / File カテゴリ追加、コンフリクト解消済み

### 全体の進め方（このPRで完結）

1. **要件定義** — `requirements.md` の各項目を ✅/❌/❓ で確定（PR レビュー）
2. **設計** — 実現方法（AHK / Karabiner / HHKB キーマップ）を決定し設計書を作成
3. **セットアップ手順** — 設定ファイル（`.ahk`, `.json`）と手順書を作成

---

## 完了した決定事項

### カテゴリ構造（requirements.md 更新済み）

| カテゴリ | 内容 |
|----------|------|
| `IME` | IME 切り替え |
| `App` | アプリ切替・起動・終了 |
| `Window` | ウィンドウ操作（新規・閉じる・最小化・最大化） |
| `Tab` | タブ操作・全アプリ共通（Chrome・VS Code・Terminal） |
| `File` | ファイル操作（新規・開く・保存・閉じる・切替） |
| `SS` | スクリーンショット |
| `Edit` | Emacs キーバインド（テキスト編集） |
| `Browser` | ブラウザ固有操作（リロード・URLバー等） |

### 前提追加
- Mac のウィンドウ切替は AltTab アプリを使用（`Cmd+Tab` に割り当て）

### コンフリクト解消（確定済み）

| 重複キー | Edit 操作（残す） | 他カテゴリ（移動先） |
|---------|-----------------|-------------------|
| `SPL+W` | Copy region | Close 系 → `SPL+C` |
| `Ctrl+W` | Cut region | Close tab → `SPL+C` |
| `Ctrl+L` | Recenter | Focus URL bar → `SPL+L` |
| `Ctrl+R` | Search backward | Reload → `SPL+R` |
| `Ctrl+D` | Delete forward | Bookmark → `SPL+M` |
| `Ctrl+S` | Search forward | Save → `SPL+S` |
| `Ctrl+N` | Next line | New window/file → `SPL+N`（文脈依存） |

### Close 系の統一
- Close window / Close tab / Close file → すべて `SPL+C`

---

## Next Tasks

1. **PR レビュー** — requirements.md を確認、TBD（Minimize / Maximize の HHKB キー）を確定
2. **設計** — 要件確定後、設計書を作成（実現方法・キー割り当て）
3. **設定ファイル作成** — `emacs-keybind.ahk`、`hhkb-emacs-keybindings.json`
4. **手順書作成** — セットアップ手順を `hhkb-keybinding-design.md` に記述

## Session Context

- 作業ブランチ: worktree-keybind
- PR: https://github.com/lovaizu/outputs/pull/12
- 要件ファイル: `cross-platform-key-bindings-with-hhkb/requirements.md`
- 成果物（作成予定）:
  - `cross-platform-key-bindings-with-hhkb/hhkb-keybinding-design.md`
  - `cross-platform-key-bindings-with-hhkb/hhkb-emacs-keybindings.json`
  - `cross-platform-key-bindings-with-hhkb/emacs-keybind.ahk`
