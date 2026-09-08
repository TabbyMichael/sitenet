#!/bin/bash
# =============================================================================
# SITE Enterprise Promotion — one-command Docker sync
#
# Aligns the Docker deployment (http://localhost:2026) with:
#   1. The latest code on git main   (repo files ARE the webroot — just pull)
#   2. A fresh dump of the live LocalWP database (with URL search-replace)
#
# Usage:  ./sync.sh            from the docker/ directory
# =============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_DIR="$(dirname "$SCRIPT_DIR")"
DUMP_DIR="$HOME/docker-site/db"
DUMP_FILE="$DUMP_DIR/site-latest.sql"
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
SOCKET="$(find "$HOME/.config/Local/run" -maxdepth 3 -name mysqld.sock 2>/dev/null | head -1)"
if [ -z "$SOCKET" ]; then
    echo "    !! LocalWP MySQL socket not found — is the LocalWP site started?"
    echo "    Skipping database refresh (code is still synced)."
    exit 0
fi

echo "==> Dumping live LocalWP database (socket: $SOCKET)..."
mkdir -p "$DUMP_DIR"
mysqldump --socket="$SOCKET" -uroot -proot \
    --single-transaction --routines --triggers \
    --default-character-set=utf8mb4 --column-statistics=0 \
    local > "$DUMP_FILE"

echo "==> Importing dump into Docker MySQL (replaces previous data)..."
docker exec -i sitenet-db mysql -uroot -proot \
    -e "DROP DATABASE IF EXISTS wordpress; CREATE DATABASE wordpress CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
docker exec -i sitenet-db mysql -uroot -proot wordpress < "$DUMP_FILE"

echo "==> Rewriting URLs (localhost:10003 / site.local -> $SITE_URL)..."
docker exec -u 1000 sitenet-wordpress wp search-replace \
    "http://localhost:10003" "$SITE_URL" --all-tables --path=/var/www/html --quiet
docker exec -u 1000 sitenet-wordpress wp search-replace \
    "https://site.local" "$SITE_URL" --all-tables --path=/var/www/html --quiet || true
docker exec -u 1000 sitenet-wordpress wp search-replace \
    "http://site.local" "$SITE_URL" --all-tables --path=/var/www/html --quiet || true
docker exec -u 1000 sitenet-wordpress wp cache flush --path=/var/www/html --quiet || true

echo ""
echo "✅ Sync complete."
echo "   Local:      http://localhost:2026"
echo "   LAN/friends: $SITE_URL"
echo "   phpMyAdmin:  http://localhost:2027 (root/root)"