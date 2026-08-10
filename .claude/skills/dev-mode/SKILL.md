---
name: dev-mode
description: Turn Hostinger's cacheless development mode on or off for reubenjbrown.com, so CSS and template edits appear immediately without clearing cache each time. Use when starting a run of visual/CSS iteration, when the user says "turn off caching", "dev mode on/off", or when repeated cache clears are slowing down a styling session.
---

# Cacheless development mode

Hostinger can disable caching entirely for the site. This is the right tool for a
styling session: it removes the clear-cache step from every iteration, and it
removes the reason to bump the theme version purely to bust cache.

**Cacheless mode makes the live site slower for real visitors.** It is a
temporary working state, not a default.

## Rules

- **Always ask the user before enabling it.** It changes the behaviour of the
  live production site.
- **Always turn it off when the styling session ends.** If a session ends with it
  on, say so explicitly in your final message so it is not left on silently.
- Prefer it over repeated cache clearing whenever more than about two visual
  iterations are expected.

## Turning it on

Use the `hostinger` MCP server:

- Tool: `mcp__hostinger__hosting_toggleCachelessModeV1`
- `username`: `u240680038`
- `domain`: `reubenjbrown.com`
- `enabled`: `true` to enable, `false` to restore normal caching

If the MCP server is unavailable, check that `HOSTINGER_API_TOKEN` is set in the
`env` block of `~/.claude/settings.json`. Setting it only in `~/.zshrc` does not
work — a GUI-launched Claude Code never sources that file, and the symptom is the
server connecting but every call returning `Unauthenticated`.

## During a styling session

With cacheless mode on, the loop is: edit source CSS → commit and push →
`./deploy.sh theme` → hard-reload. No cache clear needed.

Deploying is still required; cacheless mode affects serving, not the checkout on
the server.

## Turning it off

Same tool with `enabled: false`. Then confirm the site still serves correctly:

```bash
curl -s -o /dev/null -w "HTTP %{http_code}\n" https://reubenjbrown.com/
```
