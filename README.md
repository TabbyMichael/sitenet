# SITE Enterprise Promotion Kenya Website Revamp

## Project
SITE Enterprise Promotion Kenya Website Revamp

## Environment
* **Platform**: LocalWP
* **PHP**: 8.2.29
* **MySQL**: 8.4.0
* **WordPress**: 6.6.1 (Core checksums verified)
* **Site Path**: `/home/kibuguian/Local Sites/site/app/public`

## Current Theme
* **Active Theme**: The Landscaper (v2.6.1)

## Target Architecture
* **Custom WordPress Child Theme**: `wp-content/themes/site-child/`
* **Custom Functionality**: Must-use plugins in `wp-content/mu-plugins/`
* **Documentation**: `Docs/`
* **Custom Content Architecture**:
  * Projects (`site_project`)
  * Stories (`site_story`)
  * Resources (`site_resource`)
  * Partners (`site_partner`)

## Development Principles
* **Reliability**: Fault tolerance, data integrity, and safe recovery
* **Data Integrity**: Pre-restoration snapshots and checksum validation
* **Reproducibility**: Version-controlled custom code and explicit migration steps
* **Separation of Concerns**: Separation of content architecture and presentation
* **Version Control**: Git-tracked custom themes, MU plugins, and documentation
* **Database Migrations**: All schema and taxonomy adjustments documented
* **Local Testing**: Complete local verification before deployment
* **Safe Recovery**: Rigorous backup verification prior to destructive actions

## Performance Targets
* Page load time < 3 seconds
* Mobile usability score > 90
* Core Web Vitals passing

## Day 1 Foundation Deliverables
* Production backup set verified and restored
* Pre-restoration snapshot stored in `../backups/`
* Development debugging (`WP_DEBUG`, `WP_DEBUG_LOG`, `SCRIPT_DEBUG`) configured
* Production URL preserved (`https://sitenet.org`)
* Version control initialized with `.gitignore`
* Comprehensive environment, restoration, plugin, and workflow documentation created in `Docs/`

---

## 🐳 Running the Repo with Docker

This repository ships a complete Docker setup in `docker/`. The repo's working
tree **is** the webroot — it is bind-mounted directly into the WordPress
container, so whatever commit you have checked out is exactly what the site
serves. There is no file copying between LocalWP, GitHub, and Docker, so there
is no drift.

### How it works

| Container | Image | Host port | Purpose |
|-----------|-------|-----------|---------|
| `sitenet-wordpress` | `wordpress:6.6.1-php8.2-apache` (custom) | `2026` | WordPress + Apache |
| `sitenet-db` | `mysql:8.4` | `3307` | MySQL database |
| `sitenet-phpmyadmin` | `phpmyadmin:latest` | `2027` | DB admin UI (root / root) |

### URLs after startup

| Service | URL |
|---------|-----|
| WordPress | http://localhost:2026 |
| phpMyAdmin | http://localhost:2027 (root / root) |
| MySQL | `127.0.0.1:3307`, db `wordpress`, user `wpuser` / `wppass123` |

### Prerequisites

| Tool | Windows | macOS | Linux |
|------|---------|-------|-------|
| Docker + Compose | Docker Desktop | Docker Desktop | Docker Engine + compose plugin |
| Git | Git for Windows | Xcode CLT / Homebrew | distro package |
| cloudflared (optional) | winget / choco | Homebrew | .deb / .rpm / binary |

---

### 1. Install Docker & Git

#### Windows

1. Install **Docker Desktop**: https://docs.docker.com/desktop/install/windows-install/
   — enable the **WSL 2 backend** when prompted (recommended).
2. Install **Git for Windows** (includes Git Bash): https://git-scm.com/download/win
3. (Optional) Install **cloudflared**:

```powershell
winget install --id Cloudflare.cloudflared
# or
choco install cloudflared
```

#### macOS

```bash
brew install --cask docker
brew install git
brew install cloudflared   # optional
```

Open Docker Desktop once before continuing.

#### Linux (Debian/Ubuntu)

```bash
# Docker Engine + Compose plugin
curl -fsSL https://get.docker.com | sudo sh
sudo usermod -aG docker "$USER"   # then log out and back in

# git
sudo apt-get update && sudo apt-get install -y git

# cloudflared (optional) — x86_64; see releases for other arches
curl -L https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 \
  -o cloudflared && chmod +x cloudflared && sudo mv cloudflared /usr/local/bin/
```

Verify Docker on any platform:

```bash
docker --version
docker compose version
```

---

### 2. Get the code

```bash
git clone https://github.com/TabbyMichael/sitenet.git
cd sitenet
```

> On **Windows**, run these commands in **Git Bash** or **WSL**, not CMD or
> PowerShell, because the repo's helper scripts are bash scripts.

---

### 3. Start the stack

```bash
cd docker
docker compose -p sitenet up -d --build
```

The first build downloads the WordPress image and installs PHP extensions
(`mysqli`, `pdo_mysql`, `gd`, `zip`, `mbstring`, `exif`, `bcmath`) — allow a
few minutes.

Check status:

```bash
docker compose -p sitenet ps
docker compose -p sitenet logs -f wordpress   # Ctrl+C to stop following
```

---

### 4. Seed the database

The site shows content only after the database is loaded. Pick **one** method.

#### Method A — Auto-sync (Linux only, easiest)

`sync.sh` pulls `main`, builds the stack, dumps the live LocalWP database (or
falls back to a seed dump), imports it, and rewrites URLs:

```bash
cd docker
./sync.sh
```

> **Windows/macOS note:** `sync.sh` finds LocalWP via the Linux socket path
> `~/.config/Local/run/*/mysqld.sock`. On Windows/macOS the socket lives
> elsewhere, so use Method B or C below.

#### Method B — Import a SQL dump manually (all platforms)

Export a dump from LocalWP (LocalWP → Database → Open Adminer → Export, or
phpMyAdmin) and save it as `site.sql`, then:

```bash
# from the docker/ directory
docker exec -i sitenet-db mysql -uroot -proot \
  -e "DROP DATABASE IF EXISTS wordpress; CREATE DATABASE wordpress CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
docker exec -i sitenet-db mysql -uroot -proot wordpress < /path/to/site.sql
```

Then rewrite the stored URLs (use your LAN IP — see Step 6):

```bash
docker exec -u 1000 sitenet-wordpress wp search-replace \
  "http://localhost:10003" "http://YOUR-LAN-IP:2026" \
  --all-tables --path=/var/www/html
```

> If WP-CLI reports a permission error from `-u 1000`, run the same command
> without `-u 1000` (runs as `www-data`) and add `--allow-root`.

#### Method C — Offline seed DB (all platforms)

Place a seed dump at `~/docker-site/db/site-seed.sql`:

- **Linux:** run `./sync.sh` — it auto-detects the seed when LocalWP is off.
- **Windows/macOS:** import it manually (as in Method B):

```bash
mkdir -p ~/docker-site/db
# copy your seed dump to ~/docker-site/db/site-seed.sql, then:
docker exec -i sitenet-db mysql -uroot -proot wordpress < ~/docker-site/db/site-seed.sql
```

---

### 5. Verify the site

```powershell
# Windows PowerShell
Invoke-WebRequest -Uri http://localhost:2026 -UseBasicParsing | Select-Object StatusCode
```

```bash
# macOS / Linux
curl -I -L http://localhost:2026
```

Expected: `HTTP/1.1 200 OK`. Open http://localhost:2026 in a browser.

---

### 6. Share on the same network (LAN)

Find your machine's LAN IP:

| OS | Command |
|----|---------|
| Windows | `ipconfig` → look for "IPv4 Address" |
| macOS | `ipconfig getifaddr en0` (or `en1` for Thunderbolt) |
| Linux | `hostname -I \| awk '{print $1}'` |

Share:

```
http://<YOUR-LAN-IP>:2026
```

Example: `http://192.168.100.167:2026`

Open the firewall if needed:

```bash
sudo ufw allow 2026/tcp   # Linux
# macOS: allow Docker Desktop incoming connections in the firewall settings
```

---

### 7. Share over the internet (Cloudflare Quick Tunnel)

A Quick Tunnel gives a free public HTTPS link with no account needed. The URL
is **random and changes on every restart**.

#### Start the tunnel

```bash
# macOS / Linux / Windows (Git Bash or WSL)
cd docker
./tunnel-start.sh
```

Or manually on any platform:

```bash
cloudflared tunnel --protocol http2 --url http://localhost:2026
```

Copy the printed `https://*.trycloudflare.com` link and send it to friends.

> **Why `--protocol http2`?** Some networks block outbound QUIC/UDP 7844 to
> Cloudflare; HTTP/2 works reliably even then.

#### After every tunnel restart

Quick Tunnels get a new hostname each time. Re-point stored content URLs:

```bash
./retarget-tunnel.sh https://<OLD>.trycloudflare.com https://<NEW>.trycloudflare.com
```

Or run `./sync.sh` while the tunnel is running — it retargets automatically.

#### Stop the tunnel

```bash
pkill -f 'cloudflared tunnel'
```

> Keep only **one** cloudflared process running at a time.

---

### Useful commands

| Task | Command (run from `docker/`) |
|------|------------------------------|
| Start stack | `docker compose -p sitenet up -d --build` |
| Stop stack (keep DB) | `docker compose -p sitenet down` |
| Stop stack (delete DB) | `docker compose -p sitenet down -v` |
| View logs | `docker compose -p sitenet logs -f wordpress` |
| Rebuild image | `docker compose -p sitenet build --no-cache` |
| Full sync (Linux) | `./sync.sh` |
| Start tunnel | `./tunnel-start.sh` |
| Retarget URLs | `./retarget-tunnel.sh <old> <new>` |
| Backup DB | `docker exec sitenet-db mysqldump -uroot -proot wordpress > backup.sql` |
| Shell into WordPress | `docker exec -it sitenet-wordpress bash` |
| Shell into MySQL | `docker exec -it sitenet-db mysql -uroot -proot wordpress` |

---

### Local-only credentials

| Service | User / Password |
|---------|-----------------|
| Docker MySQL root | `root` / `root` (host port 3307) |
| Docker WordPress DB | `wpuser` / `wppass123` |
| phpMyAdmin | `root` / `root` |

---

### Cross-platform caveats

- **The helper scripts (`sync.sh`, `tunnel-start.sh`, `retarget-tunnel.sh`) are
  bash scripts.** On Windows run them in Git Bash or WSL, not CMD/PowerShell.
- **`sync.sh` live-LocalWP detection is Linux-specific** (it looks for
  `~/.config/Local/run/*/mysqld.sock`). On macOS/Windows use the manual import
  (Method B or C above).
- **`-u 1000` in the WP-CLI commands** assumes the repo files are owned by
  uid 1000 (the Linux host user). On Docker Desktop (macOS/Windows), if WP-CLI
  reports permission errors, drop `-u 1000` and add `--allow-root`.
- **The database dump lives outside the webroot** at `~/docker-site/db/`, so it
  is never committed or served.
- **Ports 2026/2027 must be free.** Change them in `docker/docker-compose.yml`
  if they conflict.

> Full Cloudflare walkthrough: [`Docs/RUN-TO-CLOUDFLARE.md`](Docs/RUN-TO-CLOUDFLARE.md)
