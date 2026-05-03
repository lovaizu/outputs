# Keybinding Requirements

---

## Premises

| Item | Win | Mac |
|------|-----|-----|
| Keyboard layout | 日本語配列 | 英語配列 |
| Physical IME keys | 半角/全角・英数・かな あり | 英数・かな なし |
| Window switcher | OS standard (`Alt+Tab`) | AltTab app (`Cmd+Tab`) |

---

## HHKB Key Layout (Bottom Row)

| OS  | SPL2 (Space左2) | SPL (Space左) | SP (Space) | SPR (Space右) | SPR2 (Space右2) |
|-----|----------------|--------------|-----------|--------------|---------------|
| Win | Fn1 | Alt | Space | かな | Win |
| Mac | Fn1 | Opt | Space | かな | Cmd |

> **Fn1 layer:** Fn1 を押している間、Space右（かな）は `英数` になる。

---

## IME: IME Toggle

| Operation | Key (Win) | Key (Mac) | Key(HHKB) |
|-----------|-----------|-----------|-----------|
| Switch to English | `英数` | `Ctrl+Space` (toggle) | `SPL2+SPR` |
| Switch to Japanese | `かな` | `Ctrl+Space` (toggle) | `SPR` |

---

## App: App Operations

| Operation | Key (Win) | Key (Mac) | Key(HHKB) |
|-----------|-----------|-----------|-----------|
| Switch app / window | `Alt+Tab` | `Cmd+Tab` (AltTab) | `SPL+Tab` |
| Launch app (Search / Spotlight) | `Alt+Space` | `Cmd+Space` | `SPL+SP` |
| Quit app | `Alt+F4` | `Cmd+Q` | `SPL+Q` |

---

## Window: Window Operations

| Operation | Key (Win) | Key (Mac) | Key(HHKB) |
|-----------|-----------|-----------|-----------|
| New window | `Ctrl+N` | `Cmd+N` | `SPL+N` |
| Close window | `Alt+F4` | `Cmd+W` | `SPL+C` |
| Minimize | `Win+↓` | `Cmd+M` | `SPL+M` |
| Maximize / Fullscreen | `F11` | `Ctrl+Cmd+F` | `Ctrl+SPL+F` |

---

## Tab: Tab Operations (all tabbed apps)

| Operation | Key (Win) | Key (Mac) | Key(HHKB) |
|-----------|-----------|-----------|-----------|
| New tab | `Ctrl+T` | `Cmd+T` | `SPL+T` |
| Next tab | `Ctrl+Tab` | `Ctrl+Tab` | `Ctrl+Tab` |
| Previous tab | `Ctrl+Shift+Tab` | `Ctrl+Shift+Tab` | `Ctrl+Shift+Tab` |
| Close tab | `Ctrl+W` | `Cmd+W` | `SPL+C` |
| Reopen closed tab | `Ctrl+Shift+T` | `Cmd+Shift+T` | `SPL+Shift+T` |

---

## File: File Operations

| Operation | Key (Win) | Key (Mac) | Key(HHKB) |
|-----------|-----------|-----------|-----------|
| New file | `Ctrl+N` | `Cmd+N` | `SPL+N` |
| Open file | `Ctrl+O` | `Cmd+O` | `SPL+O` |
| Save | `Ctrl+S` | `Cmd+S` | `SPL+S` |
| Save as | `Ctrl+Shift+S` | `Cmd+Shift+S` | `SPL+Shift+S` |
| Close file | `Ctrl+W` | `Cmd+W` | `SPL+C` |
| Quick switch (VS Code) | `Ctrl+P` | `Cmd+P` | `SPL+P` |

---

## SS: Screenshot

| Operation | Key (Win) | Key (Mac) | Key(HHKB) |
|-----------|-----------|-----------|-----------|
| Capture region → clipboard | `Win+Shift+S` | `Cmd+Ctrl+Shift+4` | `SPL2+PS+S` |
| Capture full screen → clipboard | `Print Screen` | `Cmd+Ctrl+Shift+3` | `SPL2+PS` |
| Capture window → clipboard | `Alt+Print Screen` | `Cmd+Ctrl+Shift+4` then `Space` | `SPL2+PS+W` |

---

## Edit: Text Editing

### 4.1 Cursor Movement — Character / Line

| Operation | Key (Emacs) | Key(HHKB) |
|-----------|-------------|-----------|
| Move forward one character | `C-f` | `Ctrl+F` |
| Move backward one character | `C-b` | `Ctrl+B` |
| Move to next line | `C-n` | `Ctrl+N` |
| Move to previous line | `C-p` | `Ctrl+P` |
| Move to beginning of line | `C-a` | `Ctrl+A` |
| Move to end of line | `C-e` | `Ctrl+E` |

### 4.2 Cursor Movement — Word / Page / Buffer

| Operation | Key (Emacs) | Key(HHKB) |
|-----------|-------------|-----------|
| Move forward one word | `M-f` | `SPL+F` |
| Move backward one word | `M-b` | `SPL+B` |
| Scroll down (page down) | `C-v` | `Ctrl+V` |
| Scroll up (page up) | `M-v` | `SPL+V` |
| Recenter | `C-l` | `Ctrl+L` |
| Go to line number | `M-g M-g` | `SPL+G SPL+G` |

### 4.3 Deletion

| Operation | Key (Emacs) | Key(HHKB) |
|-----------|-------------|-----------|
| Delete character forward | `C-d` | `Ctrl+D` |
| Delete character backward (Backspace — passthrough) | `DEL` | `DEL` |
| Kill word forward | `M-d` | `SPL+D` |
| Kill word backward | `M-DEL` | `SPL+DEL` |
| Kill to end of line | `C-k` | `Ctrl+K` |

### 4.4 Mark / Region

| Operation | Key (Emacs) | Key(HHKB) |
|-----------|-------------|-----------|
| Set mark (start selection) | `C-Space` | `Ctrl+Space` |
| Cancel mark / deselect | `C-g` | `Ctrl+G` |
| Mark whole buffer (select all) | `C-x h` | `Ctrl+X H` |

### 4.5 Kill / Yank (Copy / Paste / Cut)

| Operation | Key (Emacs) | Key(HHKB) |
|-----------|-------------|-----------|
| Copy region to clipboard | `M-w` | `SPL+W` |
| Cut region to clipboard | `C-w` | `Ctrl+W` |
| Paste from clipboard | `C-y` | `Ctrl+Y` |
| Cycle clipboard history (yank-pop) | `M-y` | `SPL+Y` |

### 4.6 Undo / Redo

| Operation | Key (Emacs) | Key(HHKB) |
|-----------|-------------|-----------|
| Undo | `C-/` | `Ctrl+/` |
| Redo | `C-?` (`C-S-/`) | `Ctrl+Shift+/` |

### 4.7 Search / Replace

| Operation | Key (Emacs) | Key(HHKB) |
|-----------|-------------|-----------|
| Search forward | `C-s` | `Ctrl+S` |
| Search backward | `C-r` | `Ctrl+R` |
| Query replace | `M-%` | `SPL+Shift+5` |

---

## Browser: Browser Operations (Chrome)

| Operation | Key (Win) | Key (Mac) | Key(HHKB) |
|-----------|-----------|-----------|-----------|
| Reload page | `Ctrl+R` | `Cmd+R` | `SPL+R` |
| Focus URL bar | `Ctrl+L` | `Cmd+L` | `SPL+L` |
| Bookmark current page | `Ctrl+D` | `Cmd+D` | `SPL+M` |
| Open link in new tab | `Ctrl+Click` | `Cmd+Click` | `SPL+Click` |
| Navigate back | `Alt+←` | `Cmd+←` | `SPL+←` |
| Navigate forward | `Alt+→` | `Cmd+→` | `SPL+→` |
