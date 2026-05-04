# Progress

## Original Intent

> MacでCmdをスペースの左横に配置したいです。
> MacだとCmdを色々使うので、現状の一部Opt->Cmd切り替えでは不便でした。
> 影響範囲を調べて、どうするか検討したい。

---

## CONCLUDED

### Final Decision

**Cross-platform unification via OS-level remapping is abandoned.**

| Layer | Mac | Win |
|-------|-----|-----|
| L1 (HHKB Keymap) | Apply | Apply |
| L2 (OS Remap) | **None** — uninstall Karabiner | **None** — uninstall AHK |
| Editor (VSCode) | Emacs extension | Emacs extension |
| Other apps | macOS Cocoa native Emacs | Windows default |

### Why

AHK operates at the application layer on Windows, causing structural conflicts with `Ctrl+A/V/W/N/P/F` etc. that are fundamentally unresolvable. The wider community compromises for the same reason. Attempting full unification means sacrificing standard Windows shortcuts globally — not worth it.

On Mac, macOS Cocoa already provides native Emacs bindings (`Ctrl+F/B/N/P/A/E/K`) in text fields. Karabiner adds complexity without sufficient benefit given the reduced scope.

### Remaining work (user's manual steps — docs are complete)

- [x] Write `setup.md`: HHKB configuration steps + VSCode Emacs extension setup (both platforms)
- [x] Remove archive (Karabiner JSON / AHK script)
- [ ] Uninstall Karabiner-Elements (Mac) — use Preferences → Misc → Uninstall, not just trash
- [ ] Uninstall AHK (Win)
- [ ] Install VSCode extension: `tuttieee.emacs-mcx`, set `"emacs-mcx.useMetaKey": true`
- [ ] HHKB Keymap Tool: set BS, Fn1+backtick=PrintScreen, Fn1+Kana=Eisu
- [ ] Merge PR #12

---

## Session Context

- Branch: worktree-keybind
- PR: https://github.com/lovaizu/outputs/pull/12
- Design doc: `cross-platform-key-bindings-with-hhkb/hhkb-keybinding-design.md`
- Requirements: `cross-platform-key-bindings-with-hhkb/requirements.md`
