#Requires AutoHotkey v2.0
#SingleInstance Force

; HHKB Emacs Keybindings for Windows
; Launch manually when HHKB is connected. Quit when switching to built-in keyboard.
; No startup registration needed — manual start/stop replaces device filtering.

; ---------------------------------------------------------------------------
; Helpers
; ---------------------------------------------------------------------------

; Apps that handle Emacs keys natively — bypass all Edit remaps
IsNativeEmacsApp() {
    active := WinGetProcessName("A")
    return (active = "Code.exe" || active = "WindowsTerminal.exe")
}

; Tabbed apps where SPL+K should close a tab (Ctrl+W) rather than the window (Alt+F4)
IsTabbedApp() {
    active := WinGetProcessName("A")
    return (active = "chrome.exe" || active = "Code.exe" || active = "WindowsTerminal.exe")
}

IsChrome() {
    return WinGetProcessName("A") = "chrome.exe"
}

; ---------------------------------------------------------------------------
; Ctrl+X prefix state
; ---------------------------------------------------------------------------

CtrlXActive := false

ResetCtrlX() {
    global CtrlXActive
    CtrlXActive := false
}

^x:: {
    global CtrlXActive
    CtrlXActive := true
    SetTimer(ResetCtrlX, -2000)  ; auto-reset after 2 s if no chord follows
}

; ---------------------------------------------------------------------------
; App operations  (SPL = Alt on Win)
; ---------------------------------------------------------------------------

; SPL+SP (Alt+Space) → Win+S (search)  [Alt+Space would open system menu]
!Space:: Send("{LWin down}s{LWin up}")

; SPL+Q (Alt+Q) → Alt+F4 (quit app)
!q:: Send("!{F4}")

; SPL+Tab (Alt+Tab) — passthrough (native app switcher)
; No remapping needed.

; ---------------------------------------------------------------------------
; Window operations
; ---------------------------------------------------------------------------

; SPL+N (Alt+N) → Ctrl+N (new window)
!n:: Send("^n")

; SPL+K (Alt+K) → close tab (Ctrl+W) or close window (Alt+F4)
!k:: {
    if IsTabbedApp()
        Send("^w")
    else
        Send("!{F4}")
}

; SPL+M (Alt+M) → Win+↓ (minimize)
!m:: Send("{LWin down}{Down}{LWin up}")

; Ctrl+SPL+F (Ctrl+Alt+F) → F11 (fullscreen)
^!f:: Send("{F11}")

; ---------------------------------------------------------------------------
; Tab operations
; ---------------------------------------------------------------------------

; SPL+T (Alt+T) → Ctrl+T (new tab)
!t:: Send("^t")

; SPL+Shift+T (Alt+Shift+T) → Ctrl+Shift+T (reopen closed tab)
!+t:: Send("^+t")

; Ctrl+Tab / Ctrl+Shift+Tab — passthrough (native)

; ---------------------------------------------------------------------------
; Screenshot
; ---------------------------------------------------------------------------

; SPL2+PS (Fn1+`) = PrintScreen keycode from HHKB L1 — Win native handles full capture.
; SPL2+PS+S → Win+Shift+S (region snip)
+PrintScreen:: Send("{LWin down}+s{LWin up}")

; SPL2+PS+W → Alt+PrintScreen (window capture)
!PrintScreen:: Send("!{PrintScreen}")

; ---------------------------------------------------------------------------
; Edit (Emacs) — skip if native Emacs app
; ---------------------------------------------------------------------------

; Cursor movement
^f:: {
    if IsNativeEmacsApp()
        return
    Send("{Right}")
}
^b:: {
    if IsNativeEmacsApp()
        return
    Send("{Left}")
}
^n:: {
    if IsNativeEmacsApp()
        return
    Send("{Down}")
}
^p:: {
    if IsNativeEmacsApp()
        return
    Send("{Up}")
}
^a:: {
    if IsNativeEmacsApp()
        return
    Send("{Home}")
}
^e:: {
    if IsNativeEmacsApp()
        return
    Send("{End}")
}

; SPL+F (Alt+F / M-f) → Ctrl+→ (word forward)
!f:: {
    if IsNativeEmacsApp()
        return
    Send("^{Right}")
}

; SPL+B (Alt+B / M-b) → Ctrl+← (word backward)
!b:: {
    if IsNativeEmacsApp()
        return
    Send("^{Left}")
}

; Ctrl+V → Page Down
^v:: {
    if IsNativeEmacsApp()
        return
    Send("{PgDn}")
}

; SPL+V (Alt+V / M-v) → Page Up
!v:: {
    if IsNativeEmacsApp()
        return
    Send("{PgUp}")
}

; Ctrl+L → passthrough (no universal recenter equivalent)
^l:: {
    if IsNativeEmacsApp()
        return
    ; no-op
}

; SPL+G SPL+G (Alt+G Alt+G) — go to line (passthrough; app handles it)
; Most apps don't support this chord — left as passthrough.

; Deletion
^d:: {
    if IsNativeEmacsApp()
        return
    Send("{Delete}")
}

; SPL+D (Alt+D / M-d) → Ctrl+Delete (word kill forward)
!d:: {
    if IsNativeEmacsApp()
        return
    Send("^{Delete}")
}

; SPL+DEL (Alt+Backspace / M-DEL) → Ctrl+Backspace (word kill backward)
!BS:: {
    if IsNativeEmacsApp()
        return
    Send("^{Backspace}")
}

; Ctrl+K → Shift+End then Ctrl+X (kill to EOL → clipboard)
^k:: {
    if IsNativeEmacsApp()
        return
    Send("+{End}")
    Send("^x")
}

; Mark / Region
^Space:: {
    if IsNativeEmacsApp()
        return
    ; set mark — no direct equivalent; just let cursor movement extend selection
    ; nothing to send here; the next movement key will select via Shift
}

^g:: {
    if IsNativeEmacsApp()
        return
    Send("{Right}")  ; deselect and move cursor
}

; Kill / Yank
; SPL+W (Alt+W / M-w) → Ctrl+C (copy)
!w:: {
    if IsNativeEmacsApp()
        return
    Send("^c")
}

; Ctrl+W → Ctrl+X (cut)
^w:: {
    if IsNativeEmacsApp()
        return
    Send("^x")
}

; Ctrl+Y → Ctrl+V (paste)
^y:: {
    if IsNativeEmacsApp()
        return
    Send("^v")
}

; SPL+Y (Alt+Y / M-y) → Win+V (clipboard history)
!y:: {
    if IsNativeEmacsApp()
        return
    Send("{LWin down}v{LWin up}")
}

; Undo / Redo
^/:: {
    if IsNativeEmacsApp()
        return
    Send("^z")
}

^+/:: {
    if IsNativeEmacsApp()
        return
    Send("^+z")
}

; Search / Replace
^s:: {
    if IsNativeEmacsApp()
        return
    Send("^f")  ; find (Ctrl+S is Emacs search forward)
}

^r:: {
    if IsNativeEmacsApp()
        return
    Send("+{F3}")  ; find previous
}

; SPL+Shift+5 (Alt+Shift+5 / M-%) → Ctrl+H (replace)
!+5:: {
    if IsNativeEmacsApp()
        return
    Send("^h")
}

; ---------------------------------------------------------------------------
; File operations (Ctrl+X prefix) — gated by #HotIf CtrlXActive
; ---------------------------------------------------------------------------

; Ctrl+F / Ctrl+S / Ctrl+W after Ctrl+X are handled inside the #HotIf block below.
; They shadow the Edit remaps only when CtrlXActive is true.

#HotIf CtrlXActive

^f:: {  ; Ctrl+X Ctrl+F → Ctrl+O (open file)
    ResetCtrlX()
    Send("^o")
}

^s:: {  ; Ctrl+X Ctrl+S → Ctrl+S (save)
    ResetCtrlX()
    Send("^s")
}

^w:: {  ; Ctrl+X Ctrl+W → Ctrl+Shift+S (save as)
    ResetCtrlX()
    Send("^+s")
}

k:: {  ; Ctrl+X K → Ctrl+W (close file/tab)
    ResetCtrlX()
    Send("^w")
}

b:: {  ; Ctrl+X B → Ctrl+Tab (switch tab/buffer)
    ResetCtrlX()
    Send("^{Tab}")
}

h:: {  ; Ctrl+X H → Ctrl+A (select all)
    ResetCtrlX()
    Send("^a")
}

2:: {  ; Ctrl+X 2 — split (app-specific)
    ResetCtrlX()
    active := WinGetProcessName("A")
    if (active = "Code.exe")
        Send("^k^\\")           ; VS Code: Cmd+K Cmd+\  (horiz split)
    else if (active = "WindowsTerminal.exe")
        Send("!+{-}")           ; Windows Terminal: Alt+Shift+- (horiz)
}

3:: {  ; Ctrl+X 3 — vertical split (app-specific)
    ResetCtrlX()
    active := WinGetProcessName("A")
    if (active = "Code.exe")
        Send("^k^-")            ; VS Code: Ctrl+K Ctrl+-
    else if (active = "WindowsTerminal.exe")
        Send("!+=")             ; Windows Terminal: Alt+Shift+=
}

o:: {  ; Ctrl+X O → next pane
    ResetCtrlX()
    active := WinGetProcessName("A")
    if (active = "Code.exe")
        Send("^k^{Right}")
}

0:: {  ; Ctrl+X 0 → close current pane (VS Code only)
    ResetCtrlX()
    if (WinGetProcessName("A") = "Code.exe")
        Send("^kw")
}

Escape:: {  ; Cancel Ctrl+X prefix
    ResetCtrlX()
}

#HotIf

; ---------------------------------------------------------------------------
; Browser (Chrome only)
; ---------------------------------------------------------------------------

#HotIf IsChrome()

; SPL+R (Alt+R) → Ctrl+R (reload)
!r:: Send("^r")

; SPL+L (Alt+L) → Ctrl+L (focus URL bar)
!l:: Send("^l")

; SPL+I (Alt+I) → Ctrl+D (bookmark)
!i:: Send("^d")

; SPL+← / SPL+→ — passthrough (Alt+← / Alt+→ are native back/forward in Chrome)

#HotIf
