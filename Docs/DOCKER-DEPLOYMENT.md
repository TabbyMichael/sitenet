# Docker Deployment Guide

The Docker deployment in this repo serves the site **directly from the git
working tree** — there are no file copies to keep in sync. Whatever commit is
checked out in this folder is exactly what the Docker site serves.

## Architecture

| Component | Image / Source | URL |
|---|---|---|
| WordPress + Apache | Built from `docker/Dockerfile` (PHP 8.2, WP 6.6.1 — same versions as LocalWP) | `http://localhost:2026` |
| MySQL | `mysql:8.4` (matches LocalWP's MySQL 8.4) | host port `3307` |
| phpMyAdmin | `phpmyadmin:latest` | `http://localhost:2027` (root / root) |

Volume mounts:

- `../` (the repo root) → `/var/www/html` — themes, plugins, mu-plugins,
  uploads, and WP core all come from the repository working tree.
- `docker/wp-config-docker.php` → `/var/www/html/wp-config.php` — Docker-only
  config (points at the `db` service, dynamic site URL). The LocalWP
  `wp-config.php` is never touched.

Apache workers run as uid/gid 1000 (same user as LocalWP) so both environments
can write to `wp-content/uploads` without ownership conflicts. Sensitive paths
(`.git`, `docker/`, `Docs/`, `*.sql`) are blocked by `docker/apache-deny.conf`.

## Quick start

```bash
cd docker
docker compose -p sitenet up -d --build
```

The database is seeded/refreshed separately (see below), because the repo
cannot contain the database or the gitignored assets (`wp-config.php`,
`wp-content/uploads/`, parent theme, third-party plugins) — those come from
the local working tree and the live LocalWP MySQL.

## Keeping Docker aligned with `main` (one command)

```bash
cd docker
./sync.sh
```

`sync.sh` does everything:

1. `git pull --ff-only origin main` — since the repo *is* the webroot, code
   sync is instant, no copying.
2. `docker compose up -d --build` — keeps containers/images current.
3. Dumps the **live LocalWP database** (`local` db via LocalWP's MySQL socket)
   to `~/docker-site/db/site-latest.sql`.
4. Reimports it into the Docker MySQL (replacing stale data).
5. Runs serialized-safe `wp search-replace` from LocalWP URLs
   (`http://localhost:10003`, `site.local`) → `http://<LAN-IP>:2026`.

**Workflow:** edit in LocalWP → commit & push to `main` → `./sync.sh` → the
Docker site matches `main` exactly.

## Sharing the link

- **Same WiFi/LAN:** send friends `http://<your-LAN-IP>:2026`
  (`hostname -I` shows your IP). Open the port if a firewall is active:
  `sudo ufw allow 2026/tcp`.
- **Internet (later):** Cloudflare Quick Tunnel:
  `cloudflared tunnel --url http://localhost:2026` — free HTTPS link; run
  `sync.sh`-style search-replace against the tunnel hostname if you want
  absolute URLs to use it (not required — `wp-config-docker.php` derives
  `WP_HOME`/`WP_SITEURL` from the request host automatically).

## Management commands

```bash
cd docker
docker compose -p sitenet ps        # status
docker compose -p sitenet logs -f wordpress
docker compose -p sitenet down      # stop (data volume persists)
docker compose -p sitenet down -v   # stop AND delete the database volume
```

Database backup (from the Docker DB):

```bash
docker exec sitenet-db mysqldump -uroot -proot wordpress > backup-$(date +%F).sql
```

## Credentials (local deployment only)

| What | Value |
|---|---|
| Docker MySQL root | `root` / `root` (host port 3307) |
| Docker WP DB user | `wpuser` / `wppass123` |
| phpMyAdmin | `root` / `root` |

> These are LAN-only development credentials. Change them before exposing the
> stack to the internet.

## Notes / limitations

- The DB dump is stored in `~/docker-site/db/` — **outside the webroot** so it
  is never served.
- Gitignored assets (uploads, parent theme `the-landscaper`, third-party
  plugins) are present in this working tree but **not** in the repo. A fresh
  clone on another machine needs those copied over (or restored from an
  UpdraftPlus backup) before `docker compose up` will produce a full site.
- WordPress salts in `wp-config-docker.php` intentionally match LocalWP so
  admin cookies work in both environments.