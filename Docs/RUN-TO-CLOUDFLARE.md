# Run the SITE WordPress Site from LocalWP → Docker → Cloudflare (Step-by-Step)

This guide takes you from a fresh machine to a **public, shareable HTTPS link**
for the SITE Enterprise Promotion WordPress site.

> **TL;DR:**  
> `cd docker` → `docker compose -p sitenet up -d --build` → `./tunnel-start.sh` → share the printed `https://*.trycloudflare.com` link.

## What You Need

| Requirement | Why |
|-------------|-----|
| Docker + Docker Compose | Runs WordPress, MySQL, phpMyAdmin |
| `cloudflared` CLI | Creates the free public tunnel |
| This repository checked out | The repo working tree **is** the webroot |
| LocalWP site (optional) | Source of truth for the live database |

## 1. Get the Code

```bash
git clone https://github.com/TabbyMichael/sitenet.git
cd sitenet
# or, if already cloned:
git pull origin main
```

## 2. Start the Docker Stack

```bash
cd docker
docker compose -p sitenet up -d --build
```

Services:

| Service | URL / Port |
|---------|-----------|
| WordPress | `http://localhost:2026` |
| MySQL 8.4 | host port `3307` |
| phpMyAdmin | `http://localhost:2027` (root / root) |

Verify:

```bash
curl -I -L http://localhost:2026
# Expected: HTTP/1.1 200 OK
```

## 3. Seed the Database

### A. Automatic (LocalWP must be running)

```bash
./sync.sh
```

`sync.sh` pulls main, builds containers, dumps LocalWP DB, imports it, and fixes URLs.

### B. Manual (from SQL dump)

```bash
# Create database
docker exec -i sitenet-db mysql -uroot -proot \
  -e "DROP DATABASE IF EXISTS wordpress; CREATE DATABASE wordpress CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import dump
docker exec -i sitenet-db mysql -uroot -proot wordpress < /path/to/your-dump.sql

# Fix URLs
LAN_IP=$(hostname -I | awk '{print $1}')
docker exec -u 1000 sitenet-wordpress wp search-replace \
  "http://localhost:10003" "http://${LAN_IP}:2026" \
  --all-tables --path=/var/www/html
```

## 4. Share on the Same WiFi / LAN

Find your LAN IP:

```bash
hostname -I
```

Share:

```
http://<YOUR-LAN-IP>:2026
```

Example: `http://192.168.100.167:2026`

Open firewall if needed:

```bash
sudo ufw allow 2026/tcp
```

## 5. Share over the Internet with Cloudflare Quick Tunnel

Start the tunnel:

```bash
cd docker
./tunnel-start.sh
```

Output example:

```
✅ Public link:  https://grounds-lime-invision-relating.trycloudflare.com
```

Copy that URL and send it to friends.

### Why `--protocol http2`?

The script uses `cloudflared tunnel --protocol http2`. On this network,
outbound QUIC/UDP 7844 to some Cloudflare regions is blocked. HTTP/2 works
reliably even when the tunnel pre-check reports region-2 failures.

### After every tunnel restart

Quick Tunnels get a **new random hostname each time**. Rewrite stored content
URLs to the new hostname:

```bash
./retarget-tunnel.sh https://<OLD>.trycloudflare.com https://<NEW>.trycloudflare.com
```

Or run `./sync.sh` while the tunnel is running — it retargets automatically.

### Stop the tunnel

```bash
pkill -f 'cloudflared tunnel'
```

Only run **one** `cloudflared` process. Logs: `/tmp/cloudflared.log`.

## 6. Test the Public Link

Friends open the `https://*.trycloudflare.com` link.

If you test from the same machine and get `Could not resolve host`, your local
systemd-resolved may not resolve `trycloudflare.com`. Confirm with:

```bash
nslookup your-hostname.trycloudflare.com 1.1.1.1
```

The link works for people using public DNS. This is only a local DNS quirk.

## 7. Useful Commands

| Task | Command |
|------|---------|
| Start stack | `cd docker && docker compose -p sitenet up -d --build` |
| Stop stack (keep DB) | `docker compose -p sitenet down` |
| Stop stack (delete DB) | `docker compose -p sitenet down -v` |
| View logs | `docker compose -p sitenet logs -f wordpress` |
| Start tunnel | `./tunnel-start.sh` |
| Retarget URLs | `./retarget-tunnel.sh <old> <new>` |
| Full sync | `./sync.sh` |
| Backup DB | `docker exec sitenet-db mysqldump -uroot -proot wordpress > backup.sql` |

## 8. Credentials (local only)

| Service | User / Password |
|---------|-----------------|
| Docker MySQL root | `root` / `root` (port 3307) |
| Docker WordPress DB | `wpuser` / `wppass123` |
| phpMyAdmin | `root` / `root` |

## Summary Flow

```bash
cd /path/to/sitenet
git pull origin main

cd docker
docker compose -p sitenet up -d --build

./sync.sh          # or import dump manually

./tunnel-start.sh  # copy https://*.trycloudflare.com
```

## Notes

- The repo working tree is the webroot — there is no separate "Docker copy".
- The database dump is written to `~/docker-site/db/site-latest.sql`, outside
  the webroot.
- `wp-config-docker.php` makes WordPress proxy-aware so HTTPS assets work
  correctly through Cloudflare.

## Running Without LocalWP (Offline Seed DB)

By default `./sync.sh` dumps the live LocalWP database. If LocalWP is stopped,
it automatically falls back to a seed dump:

```bash
# Seed dump location
~/docker-site/db/site-seed.sql
```

### Create or refresh the seed dump

When LocalWP **is** running:

```bash
SOCKET=$(find "$HOME/.config/Local/run" -maxdepth 3 -name mysqld.sock | head -1)
mysqldump --socket="$SOCKET" -uroot -proot \
    --single-transaction --routines --triggers \
    --default-character-set=utf8mb4 --column-statistics=0 \
    local > ~/docker-site/db/site-seed.sql
```

Or simply copy the latest live dump:

```bash
cp ~/docker-site/db/site-latest.sql ~/docker-site/db/site-seed.sql
```

### Start the site when LocalWP is off

```bash
cd docker
./sync.sh
# Output will say:
# ==> LocalWP not running — using seed database: /home/kibuguian/docker-site/db/site-seed.sql
```

The site now starts with the seed database, and URLs are rewritten to your LAN
address.

> **Keep the seed fresh:** after making content changes in LocalWP, refresh
> `site-seed.sql` so the offline Docker deployment stays current.
