# Setup Guide

This guide walks through the one-time setup for the HHKB Emacs keybinding system on Mac and Windows.

## Prerequisites

| Item | Mac | Win |
|------|-----|-----|
| HHKB Studio | required | required |
| Karabiner-Elements | required | — |
| AutoHotkey v2 | — | required |
| AltTab (window switcher) | required | — |

---

## L1: HHKB Studio Keymap

Open **HHKB Studio Keymap Tool** and configure both profiles.

### Profile 2 (Mac) and Profile 1 (Win) — shared changes

| Key | Assignment | Note |
|-----|-----------|------|
| Fn1 layer `` ` `` (top-left) | `Print Screen` | HHKB has no PS key; this creates the `SPL2+PS` chord |
| Top-right key | `Backspace` | Standard HHKB preference |
| Fn1 + SPR (Kana) | `Eisu` keycode | English IME switch chord (`SPL2+SPR`) |

**Bottom row — Profile 1 (Win):**

| Position | Key |
|----------|-----|
| SPL2 | Fn1 |
| SPL | Alt |
| SP | Space |
| SPR | Kana |
| SPR2 | Win |

**Bottom row — Profile 2 (Mac):**

| Position | Key |
|----------|-----|
| SPL2 | Fn1 |
| SPL | Opt |
| SP | Space |
| SPR | Kana |
| SPR2 | Cmd |

> For all other Fn1 layer keys, follow the HHKB Studio standard reference video.

---

## L2 (Mac): Karabiner-Elements

### Install

```
brew install --cask karabiner-elements
```

### Import the rule file

1. Copy `hhkb-emacs-keybindings.json` to:
   ```
   ~/.config/karabiner/assets/complex_modifications/hhkb-emacs-keybindings.json
   ```
2. Open **Karabiner-Elements → Complex Modifications → Add rule**.
3. Enable all four rule groups:
   - HHKB only — apply when HHKB Studio is connected
   - Edit (Emacs) — all apps except terminal/editor apps
   - File / Pane: Ctrl+X prefix sequences
   - Browser (Chrome only)

The rules apply **only when the HHKB Studio is connected** (vendor_id: 1278, product_id: 22). No action is needed when switching to the built-in keyboard.

### One-time system settings

#### Disable Ctrl+Space IME shortcut

System Settings → Keyboard → Keyboard Shortcuts → Input Sources → uncheck **Select the previous input source** (Ctrl+Space).

This frees `Ctrl+Space` for Emacs set-mark.

#### Install AltTab

```
brew install --cask alt-tab
```

Configure AltTab to activate on `Cmd+Tab`. This replaces the macOS default app switcher and enables `SPL+Tab → Cmd+Tab` to behave like `Alt+Tab` on Windows.

---

## L2 (Win): AutoHotkey v2

### Install

Download AutoHotkey v2 from https://www.autohotkey.com/ and install it.

### Usage

**Do not register the script to startup.** Launch it manually:

1. Double-click `emacs-keybind.ahk` (or right-click → Run with AutoHotkey).
2. A green tray icon confirms it is active.
3. Right-click the tray icon → **Exit** when switching to the built-in keyboard.

This replaces per-device filtering. The script remaps keys globally, so running it with the built-in keyboard active would cause conflicts — always exit before unplugging HHKB.

### Windows Terminal pane shortcuts

The Ctrl+X 2 / 3 pane splits for Windows Terminal require these keybindings in Windows Terminal settings (`settings.json`):

```json
{
  "actions": [
    { "command": { "action": "splitPane", "split": "horizontal" }, "keys": "alt+shift+-" },
    { "command": { "action": "splitPane", "split": "vertical" },   "keys": "alt+shift+=" }
  ]
}
```

---

## Verification checklist

After setup, test each category in order:

### IME
- [ ] `SPR` (Kana) → switches to Japanese
- [ ] `SPL2+SPR` (Fn1+Kana) → switches to English

### App
- [ ] `SPL+Tab` → AltTab app switcher (Mac) / native Alt+Tab (Win)
- [ ] `SPL+SP` → Spotlight (Mac) / Windows Search (Win)
- [ ] `SPL+Q` → quits the frontmost app

### Window / Tab
- [ ] `SPL+N` → new window
- [ ] `SPL+T` → new tab
- [ ] `SPL+K` → closes current tab (in tabbed apps) or window
- [ ] `SPL+M` → minimizes window
- [ ] `Ctrl+SPL+F` → toggles fullscreen
- [ ] `SPL+Shift+T` → reopens last closed tab

### Screenshot
- [ ] `SPL2+PS` → full-screen capture to clipboard
- [ ] `SPL2+PS+S` → region capture (crosshair)
- [ ] `SPL2+PS+W` → window capture

### Edit (Emacs) — test in a plain text field, not in VS Code / terminal
- [ ] `Ctrl+F/B/N/P` → arrow keys
- [ ] `Ctrl+A/E` → Home / End
- [ ] `SPL+F/B` → word forward / backward
- [ ] `Ctrl+V / SPL+V` → Page Down / Page Up
- [ ] `Ctrl+D` → Delete forward
- [ ] `SPL+D` → kill word forward
- [ ] `SPL+DEL` → kill word backward
- [ ] `Ctrl+K` → kill to end of line (content goes to clipboard)
- [ ] `Ctrl+Space` → start selection; move cursor to extend it
- [ ] `Ctrl+G` → deselect
- [ ] `SPL+W` → copy (Cmd+C / Ctrl+C)
- [ ] `Ctrl+W` → cut
- [ ] `Ctrl+Y` → paste
- [ ] `Ctrl+/` → undo
- [ ] `Ctrl+Shift+/` → redo
- [ ] `Ctrl+S` → find (Mac: Cocoa native; Win: Ctrl+F dialog)

### File (Ctrl+X prefix)
- [ ] `Ctrl+X Ctrl+S` → save
- [ ] `Ctrl+X Ctrl+F` → open file dialog
- [ ] `Ctrl+X Ctrl+W` → save as
- [ ] `Ctrl+X K` → close tab/file
- [ ] `Ctrl+X H` → select all

### Pane (VS Code)
- [ ] `Ctrl+X 2` → horizontal split
- [ ] `Ctrl+X 3` → vertical split
- [ ] `Ctrl+X O` → focus next pane
- [ ] `Ctrl+X 0` → close current pane

### Browser (Chrome)
- [ ] `SPL+R` → reload
- [ ] `SPL+L` → focus URL bar
- [ ] `SPL+I` → bookmark
- [ ] `SPL+←/→` → back / forward

---

## TODO

- Verify HHKB vendor_id / product_id (1278 / 22) matches the user's unit in Karabiner device list.
- `SPL+Y` (clipboard history): no universal solution. On Mac, install a clipboard manager and bind it to `Opt+Y`. On Win, `Win+V` opens the built-in history.
- `Ctrl+Space` set-mark (Mac): currently a no-op in the Karabiner rule — selection is extended by holding Shift during cursor movement. A proper mark-mode implementation requires a Karabiner variable + Shift injection on movement keys.
- `SPL+Click` (Cmd+Click for new tab in Chrome on Mac): Karabiner mouse button remapping is separate from keyboard rules; add a mouse manipulator if needed.
- VS Code keybindings for `Ctrl+X 0` (`Ctrl+K W` chord) may conflict with existing VS Code defaults — verify in the Keyboard Shortcuts editor.
