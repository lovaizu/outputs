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

**実装フェーズ — 設計完了、セットアップ手順・設定ファイル作成待ち**

### 全体の進め方（このPRで完結）

1. ~~**要件定義** — `requirements.md` の各項目を確定~~ ✅
2. ~~**設計**~~ ✅
   - ~~a. レイヤー分類（L1/L2/L3 → L1/L2 に統一）~~ ✅
   - ~~b. 検証項目の解決（13件 → 全解決）~~ ✅
   - ~~c. 設計書作成 (`hhkb-keybinding-design.md`)~~ ✅
3. **セットアップ手順・設定ファイル作成** ← **次ここ**
   - a. `setup.md` — セットアップ手順書
   - b. `hhkb-emacs-keybindings.json` — Karabiner 設定ファイル
   - c. `emacs-keybind.ahk` — AHK v2 スクリプト

---

## Key Design Decisions (確定済み)

- **レイヤー構成**: L1 (HHKB) + L2 (Karabiner/AHK) のみ。L3 なし
- **AHK 運用**: スタートアップ登録なし。HHKB 使用時のみ手動起動（デバイスフィルタ不要）
- **衝突方針**: Ctrl+\*/Alt+\* が既存 OS ショートカットを上書きするのは意図的
- **設計書**: `cross-platform-key-bindings-with-hhkb/hhkb-keybinding-design.md` ✅

---

## Design: Layer Classification

Three layers:
- **L1 (HHKB Keymap)**: Physical key mapping in HHKB Studio
- **L2 (OS Remap)**: Karabiner (Mac) / AHK (Win)
- **L3 (App Config)**: VS Code keybindings.json, iTerm2, etc.

### Classification Summary

| Category | Mac | Win |
|----------|-----|-----|
| IME | L1 (HHKB Eisu/Kana keycode) | L1 (same) |
| App | L2 (Opt+key → Cmd+key) | L2 (Alt+key → varies) |
| Window | L2 (Opt+key → Cmd+key) | L2 (Alt+key → varies) |
| Tab | L2 (Opt+key → Cmd+key) | L2 (Alt+key → Ctrl+key) |
| File | L3 (app-specific, Ctrl+X prefix free) | L3 (app-specific, Ctrl+X conflicts with cut) |
| Pane | L3 (same as File) | L3 (same as File) |
| SS | L2 (Fn1+PS → Cmd+Ctrl+Shift+3/4) | L2 (Fn1+PS → PrintScreen/Win+Shift+S) |
| Edit (Ctrl) | Mostly macOS Cocoa native | L2 (AHK, conflicts with OS shortcuts) |
| Edit (Meta) | L2 (Opt+key → per-key conversion) | L2 (Alt+key, conflicts with menus) |
| Browser | L2 (Opt+key → Cmd+key) | L2 (Alt+key, mostly native) |

### Full classification detail

See plan file: `~/.claude/plans/fuzzy-popping-candy.md`

---

## Verification Items (13 件)

### High risk (design changes likely)

| ID | Issue | Impact |
|----|-------|--------|
| VERIFY-11 | Win: Ctrl+key conflicts (Ctrl+N=New, Ctrl+P=Print, Ctrl+F=Find...) | Edit Ctrl 10+ bindings |
| VERIFY-2 | Win: Alt+letter activates menu bar | Edit Meta 5+ bindings |
| VERIFY-6 | Mac: Opt+Arrow (word movement) vs Browser navigation conflict | Browser 2 vs all text editing |
| VERIFY-5 | Win: Ctrl+X = cut, blocks prefix sequences | File/Pane 10 bindings |

### Medium risk

| ID | Issue | Impact |
|----|-------|--------|
| VERIFY-1 | Mac US layout: HHKB Eisu/Kana keycode recognition | IME 2 bindings |
| VERIFY-3 | Mac: Karabiner per-key Opt+letter rules feasibility | Edit Meta + App/Window/Tab |
| VERIFY-9 | Win: SPL+K close — tab vs window distinction | Close 2 bindings |
| VERIFY-12 | Ctrl+V = Paste vs Page Down | 1 binding (critical op) |
| VERIFY-13 | Ctrl+S = Save vs Emacs search | 2 bindings |

### Low risk / user clarification needed

| ID | Issue | Impact |
|----|-------|--------|
| VERIFY-4 | HHKB "PS" key — which physical key? | SS 3 bindings |
| VERIFY-7 | Clipboard history (M-y) — no native equivalent | 1 binding |
| VERIFY-8 | Ctrl+Space conflict with IME toggle on Mac | 1 binding |
| VERIFY-10 | Win: Alt+Space = system menu, not search | 1 binding |

### Test procedures

Each VERIFY item has a concrete test procedure in the plan file (`~/.claude/plans/fuzzy-popping-candy.md`).

---

## 完了した決定事項

### カテゴリ構造（requirements.md 更新済み）

| カテゴリ | 内容 |
|----------|------|
| `IME` | IME 切り替え |
| `App` | アプリ切替・起動・終了 |
| `Window` | ウィンドウ操作（新規・閉じる・最小化・最大化） |
| `Tab` | タブ操作・全アプリ共通（Chrome・VS Code・Terminal） |
| `File` | ファイル操作（Emacs C-x 系） |
| `Pane` | ペイン・分割操作（Emacs C-x 0/1/2/3/o） |
| `SS` | スクリーンショット |
| `Edit` | Emacs キーバインド（テキスト編集） |
| `Browser` | ブラウザ固有操作（リロード・URLバー等） |

### 前提
- Mac のウィンドウ切替は AltTab アプリを使用（`Cmd+Tab` に割り当て）

### コンフリクト解消（確定済み）

- Edit 優先、衝突した他カテゴリは SPL+別キーへ移動
- Close window / tab → `SPL+K` に統一（K = Kill, Emacs `C-x k` parallel）
- Bookmark → `SPL+I`（I = Interest）
- Minimize → `SPL+M`（衝突なし）
- File / Pane 操作 → Emacs C-x 系（`Ctrl+X *`）に統一

### 固有キー（Win/Mac/Emacs どのデフォルトとも一致しない）

| HHKB | 操作 | 理由 |
|------|------|------|
| `SPL+K` | Close 統一 | W/F4 は Edit と衝突; K = Kill (Emacs parallel) |
| `SPL+I` | Bookmark | D は Edit（Delete forward）に使用中; I = Interest |
| `SPL2+PS*` | Screenshot | HHKB 固有チョード |
| `SPL2+SPR` | IME 英語切替 | HHKB 固有チョード |

---

## Session Context

- 作業ブランチ: worktree-keybind
- PR: https://github.com/lovaizu/outputs/pull/12
- 要件ファイル: `cross-platform-key-bindings-with-hhkb/requirements.md`
- プランファイル: `~/.claude/plans/fuzzy-popping-candy.md`（検証手順の詳細）
- 成果物（作成予定）:
  - `cross-platform-key-bindings-with-hhkb/hhkb-keybinding-design.md`
  - `cross-platform-key-bindings-with-hhkb/hhkb-emacs-keybindings.json`
  - `cross-platform-key-bindings-with-hhkb/emacs-keybind.ahk`
