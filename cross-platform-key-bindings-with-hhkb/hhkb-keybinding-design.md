# HHKB Studio Cross-Platform Keybinding Design

## Conclusion

Cross-platform unification via OS-level remapping was abandoned.

**Reason:** AHK operates at the application layer on Windows, causing structural conflicts with universal Windows shortcuts (`Ctrl+A/V/W/N/P/F` etc.) that cannot be resolved. The wider community has reached the same conclusion and compromises similarly.

**Final approach:**

| Layer | Mac | Win |
|-------|-----|-----|
| L1 (HHKB Keymap) | Apply | Apply |
| L2 (OS Remap) | **None** (Karabiner uninstalled) | **None** (AHK uninstalled) |
| Editor (VSCode) | Emacs extension | Emacs extension |
| Other apps | macOS Cocoa native Emacs (`Ctrl+F/B/N/P/A/E/K`) | Windows default |

---

## Architecture

| Layer | Role | Tool |
|-------|------|------|
| **L1 (HHKB Keymap)** | Physical key layout | HHKB Studio Keymap Tool |

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

## TODO

- Write `setup.md` covering HHKB configuration steps and VSCode Emacs extension setup
