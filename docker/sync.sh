#!/bin/bash
# =============================================================================
# SITE Enterprise Promotion — one-command Docker sync
#
# Aligns the Docker deployment (http://localhost:2026) with:
#   1. The latest code on git main   (repo files ARE the webroot — just pull)
#   2. A fresh dump of the live LocalWP database, OR a seed dump if LocalWP
#      is not running (with URL search-replace)
#
# Usage:  ./sync.sh            from the docker/ directory
# =============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_DIR="$(dirname "$SCRIPT_DIR")"
DUMP_DIR="$HOME/docker-site/db"
DUMP_FILE="$DUMP_DIR/site-latest.sql"
SEED_FILE="$DUMP_DIR/site-seed.sql"
LAN_IP="$(hostname -I | awk '{print $1}')"
SITE_URL="http://${LAN_IP}:2026"

echo "==> Repo:    $REPO_DIR"
echo "==> Site:    http://localhost:2026  (LAN: $SITE_URL)"

# --- 1. Code -----------------------------------------------------------------
echo "==> Pulling latest main (files are live-mounted, so this is the whole sync)..."
if git -C "$REPO_DIR" pull --ff-only origin main; then
    echo "    Code synced to origin/main."
else
    echo "    !! git pull failed (offline or local changes) — using current working tree."
fi

# --- 2. Containers -----------------------------------------------------------
echo "==> Ensuring containers are up and image is current..."
cd "$SCRIPT_DIR"
docker compose -p sitenet up -d --build

# --- 3. Database -------------------------------------------------------------
SOCKET="$(find "$HOME/.config/Local/run" -maxdepth 3 -name mysqld.sock 2>/dev/null | head -1 || true)"

mkdir -p "$DUMP_DIR"

if [ -n "$SOCKET" ]; then
    echo "==> LocalWP MySQL socket found ($SOCKET) — dumping live database..."
    mysqldump --socket="$SOCKET" -uroot -proot \
        --single-transaction --routines --triggers \
        --default-character-set=utf8mb4 --column-statistics=0 \
        local > "$DUMP_FILE"
    echo "    Dump saved to $DUMP_FILE"
    echo "    (To use this as the offline seed, copy it to $SEED_FILE)"
    IMPORT_FILE="$DUMP_FILE"
elif [ -f "$SEED_FILE" ]; then
    echo "==> LocalWP not running — using seed database: $SEED_FILE"
    IMPORT_FILE="$SEED_FILE"
else
    echo "!! LocalWP MySQL socket not found and no seed database at $SEED_FILE" >&2
    echo "!! Skipping database refresh (code is still synced)." >&2
    echo "!! To create a seed dump, start LocalWP and run:" >&2
    echo "!!   mysqldump --socket=<socket> -uroot -proot local > $SEED_FILE" >&2
    exit 0
fi

echo "==> Importing dump into Docker MySQL (replaces previous data)..."
docker exec -i sitenet-db mysql -uroot -proot \
    -e "DROP DATABASE IF EXISTS wordpress; CREATE DATABASE wordpress CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
docker exec -i sitenet-db mysql -uroot -proot wordpress < "$IMPORT_FILE"

echo "==> Rewriting URLs (localhost:10003 / site.local -> $SITE_URL)..."
docker exec -u 1000 sitenet-wordpress wp search-replace \
    "http://localhost:10003" "$SITE_URL" --all-tables --path=/var/www/html --quiet || true
docker exec -u 1000 sitenet-wordpress wp search-replace \
    "https://site.local" "$SITE_URL" --all-tables --path=/var/www/html --quiet || true
docker exec -u 1000 sitenet-wordpress wp search-replace \
    "http://site.local" "$SITE_URL" --all-tables --path=/var/www/html --quiet || true

# --- 4. Public tunnel (if running) --------------------------------------------
# Content URLs must end up pointing at whatever friends actually use. If a
# Cloudflare Quick Tunnel is running, its hostname is the public face of the
# site, so re-point stored URLs (including any stale tunnel hostnames from
# previous runs) at the current tunnel URL.
FINAL_URL="$SITE_URL"
if pgrep -f 'cloudflared tunnel' >/dev/null 2>&1; then
    TUNNEL_URL="$(grep -oE 'https://[a-z0-9-]+\.trycloudflare\.com' /tmp/cloudflared.log 2>/dev/null | head -1 || true)"
    if [ -n "$TUNNEL_URL" ]; then
        echo "==> Quick Tunnel detected — re-pointing content URLs at $TUNNEL_URL ..."
        docker exec -u 1000 sitenet-wordpress wp search-replace \
            "$SITE_URL" "$TUNNEL_URL" --all-tables --path=/var/www/html --quiet
        # Sweep any leftover hostnames from earlier tunnel runs.
        docker exec -u 1000 sitenet-wordpress wp search-replace \
            --regex 'https://[a-z0-9-]+\.trycloudflare\.com' "$TUNNEL_URL" \
            --all-tables --path=/var/www/html --quiet || true
        FINAL_URL="$TUNNEL_URL"
    else
        echo "    !! cloudflared is running but no URL in /tmp/cloudflared.log — keeping LAN URLs."
    fi
else
    echo "==> No Quick Tunnel running — content URLs stay on the LAN address."
fi

docker exec -u 1000 sitenet-wordpress wp cache flush --path=/var/www/html --quiet || true

echo ""
echo "✅ Sync complete."
echo "   Local:       http://localhost:2026"
echo "   LAN/friends: $SITE_URL"
echo "   Public face: $FINAL_URL"
echo "   phpMyAdmin:  http://localhost:2027 (root/root)"
