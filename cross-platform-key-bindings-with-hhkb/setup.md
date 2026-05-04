# Setup Guide

## Overview

| Step | What | Tool |
|------|------|------|
| 1 | HHKB keymap configuration | HHKB Studio Keymap Tool |
| 2 | VSCode Emacs extension | VSCode |
| 3 | Uninstall OS remapping tools | — |

---

## Step 1: HHKB Keymap (Both Platforms)

Open **HHKB Studio Keymap Tool** and apply the following.

### Profile assignment

| Profile | OS |
|---------|----|
| Profile 1 | Windows |
| Profile 2 | Mac |

### Bottom row layout

| Profile | SPL2 | SPL | SP | SPR | SPR2 |
|---------|------|-----|----|-----|------|
| Win (1) | Fn1 | Alt | Space | Kana | Win |
| Mac (2) | Fn1 | Opt | Space | Kana | Cmd |

### Keymap changes

| Profile | Key | Assign |
|---------|-----|--------|
| Both | Top-right key | BS |
| Both | Fn1 layer — `` ` `` | Print Screen |
| Both | Fn1 layer — SPR (Kana) | Eisu |

Fn1 layer other keys: follow the standard HHKB video reference.

### Result: custom chords available after setup

| Chord | Operation | Win output | Mac output |
|-------|-----------|-----------|-----------|
| `Fn1 + Kana` | Switch to English (IME) | Eisu | Eisu |
| `Fn1 + `` ` `` ` | Full screen capture | Print Screen | Cmd+Ctrl+Shift+3 |
| `Fn1 + `` ` `` + S` | Region capture | Win+Shift+S | Cmd+Ctrl+Shift+4 |
| `Fn1 + `` ` `` + W` | Window capture | Alt+Print Screen | Cmd+Ctrl+Shift+4 Space |

---

## Step 2: VSCode Emacs Extension (Both Platforms)

### Install

Search for **"Awesome Emacs Keymap"** (`tuttieee.emacs-mcx`) in the Extensions panel and install.

Alternatively:

```
ext install tuttieee.emacs-mcx
```

### Configuration

Add to `settings.json`:

```json
{
  "emacs-mcx.useMetaKey": true
}
```

This maps `Alt` (Win) / `Opt` (Mac) as the Emacs Meta key, enabling `M-f`, `M-b`, `M-d`, etc.

### Key bindings provided by the extension

| Emacs | Operation |
|-------|-----------|
| `C-f / C-b / C-n / C-p` | Character / line movement |
| `C-a / C-e` | Beginning / end of line |
| `C-k` | Kill to end of line |
| `C-w / M-w` | Cut / copy region |
| `C-y` | Paste (yank) |
| `C-s / C-r` | Search forward / backward |
| `C-x C-s` | Save |
| `C-x C-f` | Open file |
| `C-x h` | Select all |
| `M-f / M-b` | Word forward / backward |

---

## Step 3: Uninstall OS Remapping Tools

### Mac

Uninstall **Karabiner-Elements** — it is no longer needed.

```
# Remove app
rm -rf /Applications/Karabiner-Elements.app

# Remove config (optional)
rm -rf ~/.config/karabiner
```

Or use the uninstaller bundled with Karabiner-Elements.

### Windows

Uninstall **AutoHotkey** — it is no longer needed.

Control Panel → Programs → Uninstall AutoHotkey.

---

## Notes

- On Mac, `Ctrl+F/B/N/P/A/E/K` work natively in all Cocoa text fields (TextEdit, Mail, Safari address bar, etc.) without any additional setup.
- Outside VSCode on Windows, use standard Windows shortcuts.
