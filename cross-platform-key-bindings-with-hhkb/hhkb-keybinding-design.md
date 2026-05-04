# HHKB Studio Cross-Platform Keybinding Design

## Architecture

### Layers

| Layer | Role | Tool |
|-------|------|------|
| **L1 (HHKB Keymap)** | Physical key layout | HHKB Studio Keymap Tool |
| **L2 (OS Remap)** | All key conversion rules | Karabiner-Elements (Mac) / AHK v2 (Win) |

No L3 (app-specific config) is used. All rules are handled at L2.

---

## L1: HHKB Keymap

### Bottom Row Layout

| OS  | SPL2 | SPL | SP | SPR | SPR2 |
|-----|------|-----|----|-----|------|
| Win (Profile 1) | Fn1 | Alt | Space | Kana | Win |
| Mac (Profile 2) | Fn1 | Opt | Space | Kana | Cmd |

### Required Keymap Changes

| Profile | Key | Assign | Note |
|---------|-----|--------|------|
| Both | Fn1 layer `` ` `` | Print Screen | No PS key on HHKB; chord via Fn1 |
| Both | Top-right | BS | — |
| Both | Fn1 + SPR (Kana) | Eisu keycode | English IME switch chord |

Fn1 layer other keys: follow the standard video reference.

---

## L2: Karabiner-Elements (Mac)

All rules apply **only when HHKB is connected** (vendor_id: 1278, product_id: 22).

### IME

| HHKB | Karabiner rule | Result |
|------|----------------|--------|
| SPR (Kana) | passthrough | Switch to Japanese |
| SPL2+SPR (Fn1+Kana = Eisu keycode) | passthrough | Switch to English |

> Prerequisite: disable Mac's default Ctrl+Space IME shortcut in System Settings.

### App

| HHKB | Input | Output | Note |
|------|-------|--------|------|
| SPL+Tab | Opt+Tab | Cmd+Tab | App switch (AltTab app) |
| SPL+SP | Opt+Space | Cmd+Space | Spotlight |
| SPL+Q | Opt+Q | Cmd+Q | Quit app |

### Window

| HHKB | Input | Output |
|------|-------|--------|
| SPL+N | Opt+N | Cmd+N |
| SPL+K | Opt+K | Cmd+W |
| SPL+M | Opt+M | Cmd+M |
| Ctrl+SPL+F | Ctrl+Opt+F | Ctrl+Cmd+F |

### Tab

| HHKB | Input | Output |
|------|-------|--------|
| SPL+T | Opt+T | Cmd+T |
| SPL+K | Opt+K | Cmd+W (same as Window — Cmd+W closes tab or window depending on context) |
| SPL+Shift+T | Opt+Shift+T | Cmd+Shift+T |
| Ctrl+Tab | passthrough | — |
| Ctrl+Shift+Tab | passthrough | — |

### Screenshot

| HHKB | Input | Output |
|------|-------|--------|
| SPL2+PS | Fn1+`` ` `` | Cmd+Ctrl+Shift+3 |
| SPL2+PS+S | Fn1+`` ` ``+S | Cmd+Ctrl+Shift+4 |
| SPL2+PS+W | Fn1+`` ` ``+W | Cmd+Ctrl+Shift+4, then Space |

### Edit (Emacs — all apps except Emacs-native apps)

Excluded apps: VS Code, Ghostty, cmux, Terminal.app (these handle Emacs keys natively).

**Cursor movement:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+F | passthrough | macOS Cocoa native (→) |
| Ctrl+B | passthrough | macOS Cocoa native (←) |
| Ctrl+N | passthrough | macOS Cocoa native (↓) |
| Ctrl+P | passthrough | macOS Cocoa native (↑) |
| Ctrl+A | passthrough | macOS Cocoa native (Home) |
| Ctrl+E | passthrough | macOS Cocoa native (End) |
| SPL+F (M-f) | Opt+F | Opt+→ (word forward) |
| SPL+B (M-b) | Opt+B | Opt+← (word backward) |
| Ctrl+V | passthrough | scroll down (page down) |
| SPL+V (M-v) | Opt+V | Page Up |
| Ctrl+L | passthrough | recenter (no universal equiv; passthrough) |
| SPL+G SPL+G | Opt+G Opt+G | go to line (app-specific) |

**Deletion:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+D | passthrough | macOS Cocoa native (Delete fwd) |
| SPL+D (M-d) | Opt+D | Opt+→ then Delete (word kill fwd, no clipboard) |
| SPL+DEL (M-DEL) | Opt+DEL | Opt+Backspace (word kill back, OS native) |
| Ctrl+K | Ctrl+K | Shift+End → Cmd+X (kill to EOL → clipboard) |

**Mark / Region:**

| HHKB | Input | Output | Note |
|------|-------|--------|------|
| Ctrl+Space | Ctrl+Space | set mark (state ON) | Disable Mac IME Ctrl+Space first |
| Ctrl+G | Ctrl+G | clear mark + → (deselect) | mark state only |
| Ctrl+X H | Ctrl+X H | Cmd+A | select all |

**Kill / Yank:**

| HHKB | Input | Output |
|------|-------|--------|
| SPL+W (M-w) | Opt+W | Cmd+C (copy) |
| Ctrl+W | Ctrl+W | Cmd+X (cut) |
| Ctrl+Y | Ctrl+Y | Cmd+V (paste) |
| SPL+Y (M-y) | Opt+Y | clipboard history (best effort) |

**Undo / Redo:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+/ | Ctrl+/ | Cmd+Z |
| Ctrl+Shift+/ | Ctrl+Shift+/ | Cmd+Shift+Z |

**Search / Replace:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+S | passthrough | macOS Cocoa native (find) |
| Ctrl+R | passthrough | macOS Cocoa native (find prev) |
| SPL+Shift+5 (M-%) | Opt+Shift+5 | query replace (app-specific) |

### File (Ctrl+X prefix — all apps)

Karabiner monitors Ctrl+X state and converts the following chord:

| HHKB sequence | Output (Mac) |
|---------------|--------------|
| Ctrl+X → Ctrl+F | Cmd+O (open file) |
| Ctrl+X → Ctrl+S | Cmd+S (save) |
| Ctrl+X → Ctrl+W | Cmd+Shift+S (save as) |
| Ctrl+X → K | Cmd+W (close file/tab) |
| Ctrl+X → B | Cmd+T (switch tab/buffer) |

### Pane (Ctrl+X prefix — app-specific)

| HHKB sequence | VS Code (Mac) | Terminal (Mac) |
|---------------|---------------|----------------|
| Ctrl+X → 0 | Cmd+K W | — |
| Ctrl+X → 1 | close other editors | — |
| Ctrl+X → 2 | Cmd+K Cmd+\ (horiz split) | Cmd+D (horiz) |
| Ctrl+X → 3 | Cmd+K Cmd+- (vert split) | Cmd+Shift+D (vert) |
| Ctrl+X → O | Cmd+K Cmd+→ (next pane) | — |

### Browser (Chrome only)

| HHKB | Input | Output |
|------|-------|--------|
| SPL+R | Opt+R | Cmd+R (reload) |
| SPL+L | Opt+L | Cmd+L (focus URL) |
| SPL+I | Opt+I | Cmd+D (bookmark) |
| SPL+Click | Opt+Click | Cmd+Click (new tab) |
| SPL+← | Opt+← | Cmd+← (back) |
| SPL+→ | Opt+→ | Cmd+→ (forward) |

---

## L2: AHK v2 (Win)

### Operation policy

- **Do not register to startup.**
- Launch `.ahk` manually when using HHKB; quit when switching to the built-in keyboard.
- No device filter required (manual start/stop serves the same purpose).

### IME

| HHKB | Input | Note |
|------|-------|------|
| SPR (Kana) | passthrough | Switch to Japanese (OS native) |
| SPL2+SPR (Fn1+Kana) | passthrough | Switch to English (Eisu keycode, OS native) |

### App

| HHKB | Input | Output |
|------|-------|--------|
| SPL+Tab | Alt+Tab | passthrough (native) |
| SPL+SP | Alt+Space | Win+S (search) |
| SPL+Q | Alt+Q | Alt+F4 (quit) |

### Window

| HHKB | Input | Output |
|------|-------|--------|
| SPL+N | Alt+N | Ctrl+N (new window) |
| SPL+K | Alt+K | Ctrl+W or Alt+F4 (see note below) |
| SPL+M | Alt+M | Win+↓ (minimize) |
| Ctrl+SPL+F | Ctrl+Alt+F | F11 (fullscreen) |

### Tab

| HHKB | Input | Output |
|------|-------|--------|
| SPL+T | Alt+T | Ctrl+T (new tab) |
| SPL+K | Alt+K | same as Window SPL+K |
| SPL+Shift+T | Alt+Shift+T | Ctrl+Shift+T (reopen tab) |
| Ctrl+Tab | passthrough | — |
| Ctrl+Shift+Tab | passthrough | — |

> **SPL+K on Win**: AHK uses app context. Tabbed apps (Chrome, VS Code, Windows Terminal) → Ctrl+W (close tab). Other apps → Alt+F4 (close window).

### Screenshot

| HHKB | Input | Note |
|------|-------|------|
| SPL2+PS | Fn1+`` ` `` = Print Screen | HHKB L1 handles this; Win native PS = full capture |
| SPL2+PS+S | Fn1+`` ` ``+S | AHK: Win+Shift+S (region snip) |
| SPL2+PS+W | Fn1+`` ` ``+W | AHK: Alt+Print Screen (window capture) |

### Edit (Emacs — all apps except Emacs-native apps)

Excluded apps: VS Code (`Code.exe`), Windows Terminal (`WindowsTerminal.exe`).

**Cursor movement:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+F | Ctrl+F | → |
| Ctrl+B | Ctrl+B | ← |
| Ctrl+N | Ctrl+N | ↓ |
| Ctrl+P | Ctrl+P | ↑ |
| Ctrl+A | Ctrl+A | Home |
| Ctrl+E | Ctrl+E | End |
| SPL+F (M-f) | Alt+F | Ctrl+→ (word forward) |
| SPL+B (M-b) | Alt+B | Ctrl+← (word backward) |
| Ctrl+V | Ctrl+V | Page Down |
| SPL+V (M-v) | Alt+V | Page Up |
| Ctrl+L | Ctrl+L | passthrough (recenter no equiv) |
| SPL+G SPL+G | Alt+G Alt+G | go to line (app-specific) |

**Deletion:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+D | Ctrl+D | Delete |
| SPL+D (M-d) | Alt+D | Ctrl+Delete (word kill fwd) |
| SPL+DEL (M-DEL) | Alt+DEL | Ctrl+Backspace (word kill back) |
| Ctrl+K | Ctrl+K | Shift+End → Ctrl+X (kill to EOL → clipboard) |

**Mark / Region:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+Space | Ctrl+Space | set mark (state ON) |
| Ctrl+G | Ctrl+G | clear mark + → (deselect) |
| Ctrl+X H | Ctrl+X H | Ctrl+A (select all) |

**Kill / Yank:**

| HHKB | Input | Output |
|------|-------|--------|
| SPL+W (M-w) | Alt+W | Ctrl+C (copy) |
| Ctrl+W | Ctrl+W | Ctrl+X (cut) |
| Ctrl+Y | Ctrl+Y | Ctrl+V (paste) |
| SPL+Y (M-y) | Alt+Y | clipboard history (best effort) |

**Undo / Redo:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+/ | Ctrl+/ | Ctrl+Z |
| Ctrl+Shift+/ | Ctrl+Shift+/ | Ctrl+Shift+Z |

**Search / Replace:**

| HHKB | Input | Output |
|------|-------|--------|
| Ctrl+S | Ctrl+S | Ctrl+F (find) |
| Ctrl+R | Ctrl+R | passthrough or Shift+F3 (find prev) |
| SPL+Shift+5 | Alt+Shift+5 | Ctrl+H (replace) |

### File (Ctrl+X prefix — all apps)

AHK monitors Ctrl+X state and converts:

| HHKB sequence | Output (Win) |
|---------------|--------------|
| Ctrl+X → Ctrl+F | Ctrl+O (open file) |
| Ctrl+X → Ctrl+S | Ctrl+S (save) |
| Ctrl+X → Ctrl+W | Ctrl+Shift+S (save as) |
| Ctrl+X → K | Ctrl+W (close file/tab) |
| Ctrl+X → B | Ctrl+Tab (switch tab/buffer) |

### Pane (Ctrl+X prefix — app-specific)

| HHKB sequence | VS Code (Win) | Windows Terminal |
|---------------|---------------|-----------------|
| Ctrl+X → 0 | Ctrl+K W | — |
| Ctrl+X → 1 | close other editors | — |
| Ctrl+X → 2 | Ctrl+K Ctrl+\ (horiz split) | Alt+Shift+- (horiz) |
| Ctrl+X → 3 | Ctrl+K Ctrl+- (vert split) | Alt+Shift+= (vert) |
| Ctrl+X → O | Ctrl+K Ctrl+→ (next pane) | — |

### Browser (Chrome only)

| HHKB | Input | Output |
|------|-------|--------|
| SPL+R | Alt+R | Ctrl+R (reload) |
| SPL+L | Alt+L | Ctrl+L (focus URL) |
| SPL+I | Alt+I | Ctrl+D (bookmark) |
| SPL+← | Alt+← | passthrough (native back) |
| SPL+→ | Alt+→ | passthrough (native forward) |

---

## Design Decisions

| Decision | Reason |
|----------|--------|
| No L3 | Karabiner and AHK both support prefix sequences and app conditions; no need to push config into individual apps |
| AHK manual start, no startup | Standard AHK v2 has no per-device filtering without AutoHotInterception (kernel driver with stability risks). Manual start/stop serves the same purpose safely |
| Ctrl+\* overwrites Win OS shortcuts (Ctrl+N, Ctrl+F, etc.) | Intentional: Emacs bindings take priority. App operations use SPL+\* (Alt+\*) instead |
| Alt+\* overwrites Win menu bar activation | Intentional: SPL key is Alt; menu bar access is sacrificed |
| Ctrl+S → Ctrl+F (Find) on Win | Ctrl+S is Emacs search forward; existing Win Save is Ctrl+X Ctrl+S |
| SPL+K unifies close window and close tab | K = Kill (Emacs C-x k parallel). Alt+F4 closes focused window or tab depending on context |
| Clipboard history (M-y) | No universal OS clipboard history. Delegates to system clipboard manager (e.g., Windows built-in Win+V, or a third-party tool on Mac) |
