#!/bin/bash
# =============================================================================
# Re-point stored content URLs at a new Cloudflare Quick Tunnel hostname.
#
# Quick Tunnels get a NEW random URL every restart. Theme/plugin asset URLs
# adapt automatically (wp-config-docker.php derives them from the request),
# but URLs stored in post/page content (images, links) need this rewrite.
#
# Usage:  ./retarget-tunnel.sh <old-url> <new-url>
# Example:
#   ./retarget-tunnel.sh \
#     https://involves-accessory-invest-expansion.trycloudflare.com \
#     https://conference-pierce-samoa-rebel.trycloudflare.com
# =============================================================================
set -euo pipefail

if [ $# -ne 2 ]; then
    echo "Usage: $0 <old-url> <new-url>" >&2
    exit 1
fi

OLD="$1"
NEW="$2"

echo "==> Rewriting $OLD -> $NEW ..."
docker exec -u 1000 sitenet-wordpress wp search-replace \
    "$OLD" "$NEW" --all-tables --path=/var/www/html | tail -2
docker exec -u 1000 sitenet-wordpress wp cache flush --path=/var/www/html --quiet

echo "✅ Done. Site now served at: $NEW"