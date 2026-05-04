# HHKB Studio Cross-Platform Keybinding Design

## Architecture

| Layer | Role | Tool |
|-------|------|------|
| **L1 (HHKB Keymap)** | Physical key layout | HHKB Studio Keymap Tool |
| **L2 (OS Remap)** | All key conversion rules | Karabiner-Elements (Mac) / AHK v2 (Win) |

No L3 (app-specific config). All rules are handled at L2, including app-conditional rules.

Keybinding requirements: [requirements.md](./requirements.md)

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

### Basics

- Rules apply **only when HHKB is connected** (vendor_id: 1278, product_id: 22 — verify with Karabiner EventViewer).
- **SPL = Opt**, SPL2 = Fn1.
- Prerequisite: disable Mac's default Ctrl+Space IME shortcut in System Settings.

### Standard conversion

All categories in requirements.md (IME, App, Window, Tab, SS, Edit, Browser) apply with **SPL → Opt** and **Opt+\* → Cmd+\*** unless noted below.

On Mac, `Ctrl+F/B/N/P/A/E/D/S/R/V` are handled by macOS Cocoa natively and require no explicit rule (passthrough).

### Non-trivial conversions (explicit Karabiner rules required)

These differ from a simple Opt → Cmd substitution:

| Input | Output | Note |
|-------|--------|------|
| Ctrl+K | Shift+End → Cmd+X | Kill to EOL: select to end then cut to clipboard |
| Ctrl+W | Cmd+X | Cut region |
| Ctrl+Y | Cmd+V | Paste |
| Ctrl+/ | Cmd+Z | Undo |
| Ctrl+Shift+/ | Cmd+Shift+Z | Redo |
| Ctrl+G | → | Deselect (move right to clear selection) |
| Opt+F (SPL+F) | Opt+→ | Word forward |
| Opt+B (SPL+B) | Opt+← | Word backward |
| Opt+V (SPL+V) | Page Up | Scroll up |
| Opt+W (SPL+W) | Cmd+C | Copy region |
| Opt+D (SPL+D) | Opt+→ → Delete | Word kill forward (no clipboard) |
| Opt+DEL (SPL+DEL) | Opt+Backspace | Word kill backward |

### Terminal apps (Ghostty, Terminal.app) — passthrough override

In terminal apps, readline manages its own kill ring. The following keys must passthrough so readline handles them natively instead of the standard L2 conversion above:

| Key | Standard L2 output | Terminal override |
|-----|-------------------|-------------------|
| Ctrl+K | Shift+End → Cmd+X | passthrough |
| Ctrl+W | Cmd+X | passthrough |
| Ctrl+Y | Cmd+V | passthrough |

All other keys follow the standard conversion even in terminals.

### File / Pane: Ctrl+X prefix

Karabiner tracks a `ctrl_x_pressed` variable. While active, the next key is treated as a chord:

| Chord | Output |
|-------|--------|
| Ctrl+X → Ctrl+F | Cmd+O |
| Ctrl+X → Ctrl+S | Cmd+S |
| Ctrl+X → Ctrl+W | Cmd+Shift+S |
| Ctrl+X → K | Cmd+W |
| Ctrl+X → B | Cmd+T |
| Ctrl+X → H | Cmd+A |
| Ctrl+X → 2 | Cmd+K Cmd+\ (VS Code) / Cmd+D (Terminal) |
| Ctrl+X → 3 | Cmd+K Cmd+- (VS Code) / Cmd+Shift+D (Terminal) |
| Ctrl+X → O | Cmd+K Cmd+→ (VS Code) |
| Ctrl+X → 0 | Cmd+K W (VS Code) |

Variable resets after 3 s or on any unrecognized key.

---

## L2: AHK v2 (Win)

### Basics

- **Do not register to startup.** Launch manually when using HHKB; quit when switching to built-in keyboard.
- **SPL = Alt**, SPL2 = Fn1.
- No device filter needed (manual start/stop is equivalent).

### Standard conversion

All categories in requirements.md apply with **SPL → Alt** and **Alt+\* → Ctrl+\*** (or Win+\* for system ops) unless noted below.

On Win, Ctrl+\* keys are actively remapped by AHK (no OS-level native handling unlike macOS Cocoa).

### Non-trivial conversions

| Input | Output | Note |
|-------|--------|------|
| Alt+Space (SPL+SP) | Win+S | Alt+Space opens system menu; remap to search |
| Alt+Q (SPL+Q) | Alt+F4 | Quit app |
| Alt+M (SPL+M) | Win+↓ | Minimize |
| Ctrl+Alt+F (Ctrl+SPL+F) | F11 | Fullscreen |
| Ctrl+K | Shift+End → Ctrl+X | Kill to EOL |
| Ctrl+W | Ctrl+X | Cut region |
| Ctrl+Y | Ctrl+V | Paste |
| Ctrl+/ | Ctrl+Z | Undo |
| Ctrl+Shift+/ | Ctrl+Shift+Z | Redo |
| Ctrl+S | Ctrl+F | Search forward (Win Save moved to Ctrl+X Ctrl+S) |
| Alt+K (SPL+K) | Ctrl+W or Alt+F4 | Close: tabbed apps → Ctrl+W; others → Alt+F4 |

### Terminal apps (Windows Terminal) — passthrough override

| Key | Standard L2 output | Terminal override |
|-----|-------------------|-------------------|
| Ctrl+K | Shift+End → Ctrl+X | passthrough |
| Ctrl+W | Ctrl+X | passthrough |
| Ctrl+Y | Ctrl+V | passthrough |

### File / Pane: Ctrl+X prefix

AHK tracks a `CtrlXActive` flag. Resets after 2 s or on unrecognized key.

| Chord | Output |
|-------|--------|
| Ctrl+X → Ctrl+F | Ctrl+O |
| Ctrl+X → Ctrl+S | Ctrl+S |
| Ctrl+X → Ctrl+W | Ctrl+Shift+S |
| Ctrl+X → K | Ctrl+W |
| Ctrl+X → B | Ctrl+Tab |
| Ctrl+X → H | Ctrl+A |
| Ctrl+X → 2 | Ctrl+K Ctrl+\ (VS Code) / Alt+Shift+- (WT) |
| Ctrl+X → 3 | Ctrl+K Ctrl+- (VS Code) / Alt+Shift+= (WT) |
| Ctrl+X → O | Ctrl+K Ctrl+→ (VS Code) |
| Ctrl+X → 0 | Ctrl+K W (VS Code) |

---

## Design Decisions

| Decision | Reason |
|----------|--------|
| No L3 | Karabiner and AHK support app-conditional rules at L2; no per-app config files needed |
| AHK manual start | AHK v2 has no per-device filtering without a kernel driver. Manual start/stop is the safe equivalent |
| Ctrl+\* overwrites Win OS shortcuts | Intentional: Emacs bindings take priority. App ops use SPL+\* (Alt+\*) |
| Alt+\* overwrites Win menu bar | Intentional: SPL = Alt; menu bar access is sacrificed |
| Ctrl+S → Ctrl+F on Win | Emacs C-s = search forward; Save is Ctrl+X Ctrl+S |
| SPL+K unifies close window/tab | K = Kill (Emacs C-x k parallel); Cmd+W / Alt+F4 close tab or window by context |
| Terminal passthrough (Ctrl+K/W/Y) | Terminal readline uses its own kill ring; OS clipboard ops don't apply |
| Clipboard history (M-y) | No universal equivalent; delegates to OS clipboard manager (Win+V on Win) |

---

## Verification Items

| ID | Item | Risk | Test |
|----|------|------|------|
| V-M1 | HHKB vendor_id / product_id | All rules silently inactive if wrong | Karabiner EventViewer → confirm values while HHKB connected |
| V-M2 | Ctrl+Space IME conflict | Set-mark eaten by macOS IME | Disable in System Settings → confirm Ctrl+Space reaches Karabiner |
| V-M3 | Karabiner Ctrl+X variable reset | Prefix stuck ON | Press Ctrl+X, wait 3 s, press a key → confirm no chord fires |
| V-M4 | Screenshot chords (PS / PS+S / PS+W) | Wrong capture mode | Press each chord individually; confirm correct mode |
| V-M5 | Ctrl+K wrapped text (Shift+End) | Selects visual line, not logical EOL | In wrapped paragraph, press Ctrl+K → confirm logical EOL kill |
| V-W1 | AHK Ctrl+X prefix reset | Prefix stuck ON | Press Ctrl+X, wait 2 s, press a key → confirm no chord fires |
| V-W2 | SPL+K context (tab vs window) | Wrong close action | Chrome: SPL+K → close tab; Desktop: SPL+K → close window |
| V-W3 | Alt+letter menu bar intercept | Menu opens before AHK fires | Notepad: SPL+N → confirm new window, not Format menu |
| V-W4 | Windows Terminal Ctrl+X 2/3 | Default WT keybindings differ | Requires WT settings.json update (document in setup) |
