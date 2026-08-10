#!/usr/bin/env bash
#
# Deploy the theme and plugin to the live site, then clear server-side cache.
# Replaces clicking "Deploy" twice in hPanel and then clearing cache by hand.
#
#   ./deploy.sh              # deploy both repos + clear cache
#   ./deploy.sh theme        # theme only
#   ./deploy.sh plugin       # plugin only
#   ./deploy.sh cache        # just clear the cache
#
# Cache clearing prefers the Hostinger API (also purges the CDN) and falls back
# to wp-cli's LiteSpeed purge if HOSTINGER_API_TOKEN isn't set.

set -euo pipefail

SSH_HOST="u240680038@82.197.80.158"
SSH_PORT=65002
USERNAME="u240680038"
DOMAIN="reubenjbrown.com"

WP_CONTENT="~/domains/${DOMAIN}/public_html/wp-content"
THEME_DIR="${WP_CONTENT}/themes/astra-child"
PLUGIN_DIR="${WP_CONTENT}/plugins/reuben-portfolio-sections"

# LogLevel=ERROR suppresses the server's post-quantum banner on every call.
sh() { ssh -p "$SSH_PORT" -o BatchMode=yes -o LogLevel=ERROR "$SSH_HOST" "$@"; }

# Pull a checkout on the server. --ff-only so a dirty or diverged checkout
# fails loudly instead of silently merging.
pull() {
    local label="$1" dir="$2"
    echo "==> ${label}"
    if ! sh "git -C ${dir} pull --ff-only -q origin main"; then
        echo "    FAILED. The server checkout may be dirty or diverged. Inspect with:"
        echo "    ssh -p ${SSH_PORT} ${SSH_HOST} 'git -C ${dir} status'"
        return 1
    fi
    sh "git -C ${dir} log --oneline -1" | sed 's/^/    now at /'
}

clear_cache() {
    echo "==> Clearing cache"
    if [[ -n "${HOSTINGER_API_TOKEN:-}" ]]; then
        local code
        code=$(curl -s -o /tmp/hcache.out -w '%{http_code}' -X DELETE \
            "https://developers.hostinger.com/api/hosting/v1/caches/${USERNAME}/${DOMAIN}" \
            -H "Authorization: Bearer ${HOSTINGER_API_TOKEN}" \
            -H 'Content-Type: application/json')
        if [[ "$code" == 2* ]]; then
            echo "    cleared via Hostinger API (includes CDN)"
            return 0
        fi
        echo "    API returned HTTP ${code}: $(cat /tmp/hcache.out)"
        echo "    falling back to wp-cli"
    else
        echo "    HOSTINGER_API_TOKEN not set; using wp-cli"
    fi
    sh "cd ~/domains/${DOMAIN}/public_html && wp litespeed-purge all 2>/dev/null" \
        | grep -v '^Deprecated:' | sed 's/^/    /' || echo "    wp-cli purge failed"
}

case "${1:-all}" in
    theme)  pull "Theme"  "$THEME_DIR";  clear_cache ;;
    plugin) pull "Plugin" "$PLUGIN_DIR"; clear_cache ;;
    cache)  clear_cache ;;
    all)    pull "Theme" "$THEME_DIR"; pull "Plugin" "$PLUGIN_DIR"; clear_cache ;;
    *)      echo "usage: $0 [all|theme|plugin|cache]" >&2; exit 1 ;;
esac

echo "==> Done. https://${DOMAIN}/"
