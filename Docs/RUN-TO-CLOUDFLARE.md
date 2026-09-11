# Cloudflare Quick Tunnel — Share Your Site Publicly

> **TL;DR** — Your Docker stack runs at `http://localhost:2026`. A Cloudflare
> Quick Tunnel exposes it to the internet at a public `https://*.trycloudflare.com`
> link. This doc gives you every command to start, share, and re-point that link.

---

## Current State (as of 2026-09-10)

| Item | Value |
|------|-------|
| Docker WordPress | `http://localhost:2026` (already running) |
| Docker MySQL | port `3307` (root / root) |
| Docker phpMyAdmin | `http://localhost:2027` (root / root) |
| Active tunnel | `https://alto-apnic-gerald-illinois.trycloudflare.com` |
| Tunnel log | `/tmp/cloudflared.log` |

> The tunnel URL **changes every time you restart it**. After every restart you
> must re-point stored content URLs to the new address (step 4 below).

---

## Quick Reference — All Commands

```bash
# Check if the tunnel is already running
pgrep -f 'cloudflared tunnel'

# Start the tunnel (only if NOT already running)
cd "/home/kibuguian/Local Sites/site/app/public/docker"
bash tunnel-start.sh

# Find the current URL (if you lost it)
grep -oE 'https://[a-z0-9-]+\.trycloudflare\.com' /tmp/cloudflared.log | head -1

# Re-point stored content URLs after a restart
cd "/home/kibuguian/Local Sites/site/app/public/docker"
bash retarget-tunnel.sh https://<OLD>.trycloudflare.com https://<NEW>.trycloudflare.com

# Verify the public link works
curl -sI https://<your-hostname>.trycloudflare.com | head -5

# Stop the tunnel
pkill -f 'cloudflared tunnel'

# View tunnel logs
tail -f /tmp/cloudflared.log
```

---

## Step-by-Step Walkthrough

### 1. Make sure Docker is running

```bash
docker ps --format '{{.Names}}\t{{.Status}}' | grep sitenet
```

You should see `sitenet-wordpress` and `sitenet-db` both `Up`. If not, ask me —
**do not** run `docker compose up --build` yourself (it rebuilds images and can
disturb the setup).

### 2. Start the tunnel

```bash
cd "/home/kibuguian/Local Sites/site/app/public/docker"
bash tunnel-start.sh
```

If a tunnel is already running it will tell you and print the existing URL.
Otherwise it starts one and prints the new public link:

```
✅ Public link:  https://alto-apnic-gerald-illinois.trycloudflare.com
   Re-point stored content URLs (needed after every restart):
     ./retarget-tunnel.sh <previous-url> https://alto-apnic-gerald-illinois.trycloudflare.com
```

**Copy that URL — it is your shareable link.**

### 3. Share the link

Send `https://<hostname>.trycloudflare.com` to anyone. It works on any device,
anywhere on the internet. No VPN, no port forwarding, no firewall changes.

### 4. Re-point stored URLs (do this after EVERY restart)

Quick Tunnels get a **new random hostname each time**. Images and links stored
in your posts/pages still point to the old hostname until you rewrite them.

```bash
cd "/home/kibuguian/Local Sites/site/app/public/docker"
bash retarget-tunnel.sh https://<OLD>.trycloudflare.com https://<NEW>.trycloudflare.com
```

Example:

```bash
bash retarget-tunnel.sh \
  https://glad-injection-window-gibson.trycloudflare.com \
  https://alto-apnic-gerald-illinois.trycloudflare.com
```

This runs `wp search-replace` across all tables (1310 replacements last time),
then flushes the cache.

### 5. Verify

```bash
curl -sI https://<your-hostname>.trycloudflare.com | head -5
```

Expect `HTTP/2 200`. Then open it in a browser and check a few pages
(Our Work, Resources, galleries) — images and links should all resolve.

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| `tunnel-start.sh` says "A tunnel is already running" | You are fine — it prints the current URL. Use that. |
| `Could not resolve host` from the same machine | Local DNS quirk. The link works for everyone else. Confirm with `nslookup your-hostname.trycloudflare.com 1.1.1.1` |
| Images/links broken after a restart | You forgot step 4. Run `retarget-tunnel.sh <old> <new>`. |
| Tunnel died (no URL works) | Restart it: `pkill -f cloudflared tunnel` then `bash tunnel-start.sh`, then re-point. |
| Need the current URL but terminal was closed | `grep -oE "https://[a-z0-9-]+\.trycloudflare\.com" /tmp/cloudflared.log \| head -1` |

---

## How It Works

```
Your browser
     |
     v
Cloudflare edge (HTTPS, public URL)
     |
     v
cloudflared tunnel process (on your machine)
     |
     v
http://localhost:2026  ->  Docker Apache + PHP + WordPress
```

- `cloudflared` creates an outbound-only tunnel to Cloudflare — no open ports,
  no firewall changes, no public IP needed.
- The repo working tree **is** the webroot (bind-mounted), so any edit you make
  is served immediately.
- `wp-config-docker.php` makes WordPress proxy-aware so HTTPS assets load
  correctly through Cloudflare.

---

## Important Notes

- **One tunnel only.** Never run two `cloudflared` processes at once.
- **URL is temporary.** Each restart = new hostname = must re-point URLs.
- **The tunnel is public.** Anyone with the link can see every page, including
  the redesigned Our Work / Resources / galleries. There is no password
  protection on a Quick Tunnel.
- **Do not run `docker/sync.sh`** — it contains `DROP DATABASE IF EXISTS
  wordpress` and will wipe your database. It is a legitimate tool *for the
  user*, not for routine tunnel operation.
- **Do not run `docker compose up --build`** — it rebuilds images and can
  disturb the running setup.

---

## Credentials (local only)

| Service | User / Password |
|---------|-----------------|
| Docker MySQL root | `root` / `root` (port 3307) |
| Docker WordPress DB | `wpuser` / `wppass123` |
| phpMyAdmin | `root` / `root` |
| WordPress admin | see WP admin user in the DB |
