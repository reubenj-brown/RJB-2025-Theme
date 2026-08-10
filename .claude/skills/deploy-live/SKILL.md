---
name: deploy-live
description: Deploy theme and/or plugin changes to the live site reubenjbrown.com and clear its cache. Use when the user says "deploy", "push this live", "put it on the site", "update the live site", asks why a pushed change isn't showing up, or asks to clear/purge the site cache. Also use to check whether the server is in sync with GitHub.
---

# Deploy to the live site

The live site is Hostinger shared hosting. Both the theme and the plugin are
plain git checkouts on `main` on the server, so deploying is `git pull` over SSH
plus a cache clear. **Do not tell the user to click Deploy in hPanel** — that is
the manual process this replaces.

## Before deploying

Changes must be committed and pushed to GitHub first — the server pulls from
`origin/main`, so an unpushed local commit will not deploy. Check both repos:

- Theme: `/Users/reubenj.brown/RJB-2025-Theme`
- Plugin: `/Users/reubenj.brown/RJB-2025-portfolio-plugin`

## Deploying

Run from the theme repo:

```bash
./deploy.sh            # both repos + clear cache
./deploy.sh theme      # theme only
./deploy.sh plugin     # plugin only
./deploy.sh cache      # cache clear only
```

The script prints the commit each checkout landed on. Confirm it matches what
was just pushed.

If a pull fails, the server checkout is dirty or has diverged. It uses
`--ff-only` deliberately so this fails loudly instead of silently merging.
Inspect rather than force:

```bash
ssh -p 65002 -o BatchMode=yes -o LogLevel=ERROR u240680038@82.197.80.158 \
  'git -C ~/domains/reubenjbrown.com/public_html/wp-content/themes/astra-child status'
```

## Verifying

Curl the affected asset and confirm HTTP 200 plus the expected content. For CSS
changes, check that the version query string moved if `style.css` was bumped:

```bash
curl -s https://reubenjbrown.com/ | grep -o 'themes/astra-child/style.css[^"]*'
```

Report the deployed commit and the verification result. Never claim a change is
live without checking.

## If a change still isn't visible

Order of likelihood:

1. **Browser cache** — ask the user to hard-reload before investigating further.
2. **Cache not actually cleared** — re-run `./deploy.sh cache`.
3. **Wrong repo** — section templates and their CSS live in the *plugin*, not the
   theme. See the shortcode/template/CSS mapping in `CLAUDE.md`.
4. **CSS bundle** — this project concatenates CSS; confirm the source file feeds
   the generated bundle rather than editing the bundle directly.

For repeated CSS iteration, use the `dev-mode` skill instead of clearing cache
after every change.

## Reference

- SSH: `ssh -p 65002 u240680038@82.197.80.158` (key auth, `id_ed25519`)
- Theme: `~/domains/reubenjbrown.com/public_html/wp-content/themes/astra-child`
- Plugin: `~/domains/reubenjbrown.com/public_html/wp-content/plugins/reuben-portfolio-sections`
- `wp-cli` is installed on the server; `wp litespeed-purge all` is the cache clear
  that actually works. Hostinger's API cache-clear endpoint returns HTTP 500 for
  this account as of 2026-08-10.
