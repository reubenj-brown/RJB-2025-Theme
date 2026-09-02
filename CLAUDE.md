# CLAUDE.md

Guidance for Claude Code when working in this repository.

> **📖 Read [`PROJECT.md`](PROJECT.md) before any styling, template, or
> architecture work.** It is the authority on design standards, design tokens,
> file structure, the content model, the shortcode reference, and the project's
> known traps. This file is only the always-loaded index.

## No Browser Preview

This is a live WordPress site, not a local app with a dev server. There is no
`npm run dev` (or equivalent) that renders these PHP templates or CSS files —
the `.claude/launch.json` entries only start the unrelated
`interactive`/`interactive-map` widgets from the portfolio plugin.

**Do not call `preview_start` or any other `preview_*` tool to try to verify
PHP/CSS changes in this repo.** It cannot work and wastes tokens.

**Do not mention preview/verification at all** — not "I'll skip the preview
workflow," not "no local preview capability," nothing. That narration is itself
the waste this note exists to prevent. Silently skip straight to reporting the
change and where it lives (file/line). Verification happens on the live/staging
WordPress site by the human, not in a local preview, and doesn't need to be said
out loud.

## The two repos

A WordPress portfolio system for Reuben J. Brown, multimedia journalist.

| | Path | Holds |
|---|---|---|
| **Theme** (Astra child) | `/Users/reubenj.brown/RJB-2025-Theme` | chrome, design tokens, page + story templates |
| **Plugin** | `/Users/reubenj.brown/RJB-2025-portfolio-plugin` | shortcodes, section templates + CSS, `story` CPT, viz islands |

**Where a change goes:** a section rendered inside the page body → **plugin**.
The wrapper, the type system, or a story-article layout → **theme**. Separate
git checkouts; a change touching both needs two commits and two pushes.

## Non-negotiables

1. **Design tokens live only in the theme's `style.css` `:root`.** Plugin CSS
   consumes them and defines none. Never redefine a token in a section file.
   (Exceptions — the `header-branded.php` inline `:root`, per-page accent
   overrides, and genuinely local scoped tokens — are catalogued in `PROJECT.md` §5.)
2. **Global element typography lives only in the plugin's
   `assets/base-sections.css`** (`h1`–`h3`, `p`, `.caption`,
   `.display-headline`). Section files override, never restate.
3. **Never edit the generated CSS bundle**
   (`wp-content/uploads/reuben-portfolio/portfolio-combined.css`). Edit the
   source file in `assets/`. `base-sections.css` must stay first in
   `portfolio_css_files()`.
4. **Copy the breakpoints verbatim** from the reference block at the top of
   `base-sections.css`. The compound `max-height` clauses are load-bearing for
   landscape phones and short tablets.
5. **Reuse before adding.** Check `stories-section.css`, `base-sections.css` and
   `style.css` first. `.story-item` / `.story-link` / `.story-image` /
   `.story-content` / `.story-meta` / `.caption` / `.strategy-intro` /
   `.display-headline` / `.content-section` are the shared vocabulary, and the
   AJAX handler emits exactly that markup. New CSS is fine when justified — put
   anything reusable in the shared stylesheet.
6. **`<body>` has no `body_class()`** on branded pages. Scope single-story CSS
   with `body:has(.story-single-container)`.
7. **Always `git push` immediately after `git commit`**, in both repos. Never
   leave commits unpushed. Never chain diagnostic `git stash`/`reset` into a
   "just checking" one-liner.
8. **`interactive/` needs `npm run build`** before deploying — `dist/` is
   committed and the server has no build step.

## Deploying

Both server checkouts are plain git on `main`. Use the script, not the hPanel
Deploy button. Commit and push first.

```bash
./deploy.sh            # theme + plugin + cache clear
```

`./deploy.sh theme|plugin|cache` for a subset. Bumping `Version:` in `style.css`
is the cache-bust lever for everything in `story-templates/` (hand-echoed
`?v=` links); plugin assets bust on `filemtime()` automatically.

Details, SSH access, cacheless dev mode, and the deploy failure modes are in
`PROJECT.md` §8 and the `deploy-live` / `dev-mode` skills.
