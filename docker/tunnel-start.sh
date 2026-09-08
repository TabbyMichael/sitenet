#!/bin/bash
# =============================================================================
# Start the Cloudflare Quick Tunnel in the background (survives closing the
# terminal) and print the shareable public URL.
#
# Usage:  ./tunnel-start.sh
#
# The URL changes on every start. After starting, re-point stored content
# URLs with:   ./retarget-tunnel.sh <old-url> <new-url>
# Logs: /tmp/cloudflared.log   |   Stop: pkill -f 'cloudflared tunnel'
# =============================================================================
set -euo pipefail

if pgrep -f 'cloudflared tunnel' >/dev/null; then
    echo "A tunnel is already running:"
    pgrep -af 'cloudflared tunnel'
    grep -oE 'https://[a-z0-9-]+\.trycloudflare\.com' /tmp/cloudflared.log 2>/dev/null | head -1 || true
    exit 0
fi

# http2: this network blocks outbound QUIC/7844 to some Cloudflare regions.
nohup cloudflared tunnel --protocol http2 --url http://localhost:2026 \
    > /tmp/cloudflared.log 2>&1 &
echo "Tunnel starting (pid $!)..."

for _ in $(seq 1 30); do
    URL="$(grep -oE 'https://[a-z0-9-]+\.trycloudflare\.com' /tmp/cloudflared.log 2>/dev/null | head -1 || true)"
    [ -n "$URL" ] && break
    sleep 2
done

if [ -z "${URL:-}" ]; then
    echo "!! Tunnel URL not found yet — check /tmp/cloudflared.log" >&2
    exit 1
fi

echo ""
echo "✅ Public link:  $URL"
echo "   Re-point stored content URLs (needed after every restart):"
echo "     ./retarget-tunnel.sh <previous-url> $URL"