# Environment & Testing — SITE WordPress

> Every command in this file was **executed and verified on 2026-09-10**.
> Commands marked ❌ are confirmed broken in this environment. Do not use them.

---

## THE CRITICAL FACT: the test target is Docker, not LocalWP

**LocalWP is not running** (no `~/.config/Local/run/*/mysqld.sock`). The live,
testable site is the Docker stack, which bind-mounts this repo working tree
directly as its webroot — so your edits are served immediately, no copy step.

| Container | Image | Host port | Purpose |
| --- | --- | --- | --- |
| `sitenet-wordpress` | `sitenet-wordpress:latest` | **2026** | WordPress + Apache |
| `sitenet-db` | `mysql:8.4` | 3307 | MySQL, db `wordpress` |
| `sitenet-phpmyadmin` | `phpmyadmin:latest` | 2027 | DB UI (root / root) |

Site URL: **http://localhost:2026**

> Any workflow that says "test in LocalWP" is wrong for this machine. Ignore it.

---

## PRE-FLIGHT — run this first, every session

```bash
docker ps --format '{{.Names}}\t{{.Status}}' | grep sitenet
```

If `sitenet-wordpress` and `sitenet-db` are not both `Up`, you cannot validate
anything. Say so and stop — do **not** start containers on your own initiative
(`docker compose up` rebuilds images and can disturb the user's setup).

---

## ✅ WORKING COMMANDS

### PHP syntax check — the authoritative one

Uses the PHP that actually serves the site (8.2.23):

```bash
docker exec sitenet-wordpress php -l /var/www/html/wp-content/themes/site-child/functions.php
```

Path inside the container is `/var/www/html/...`, mirroring the repo path after
`public/`. Example mapping:

```
repo:  wp-content/themes/site-child/functions.php
cont:  /var/www/html/wp-content/themes/site-child/functions.php
```

### PHP syntax check — host fallback

The host PHP is 8.3.6 and **only works with a clean environment**:

```bash
env -i /usr/bin/php -l wp-content/themes/site-child/functions.php
```

Prefer the container check: it matches production PHP. Use the host check for a
fast second opinion, and note the version difference (8.3.6 vs 8.2.23).

### WP-CLI — the ONLY working invocation

```bash
docker exec -u 1000 sitenet-wordpress wp <command> --path=/var/www/html
```

This is the project's own established convention — `docker/sync.sh` uses exactly
this form. `-u 1000` matters: it avoids creating root-owned files in the repo.

Verified examples:

```bash
docker exec -u 1000 sitenet-wordpress wp core version --path=/var/www/html
docker exec -u 1000 sitenet-wordpress wp option get stylesheet --path=/var/www/html
docker exec -u 1000 sitenet-wordpress wp option get template --path=/var/www/html
docker exec -u 1000 sitenet-wordpress wp plugin list --status=active --path=/var/www/html
docker exec -u 1000 sitenet-wordpress wp post list --post_type=page --format=count --path=/var/www/html
```

### Read-only database queries

```bash
docker exec sitenet-db mysql -uroot -proot wordpress -N -e "SELECT ... "
```

Useful for builder-ownership checks:

```bash
docker exec sitenet-db mysql -uroot -proot wordpress -N -e \
  "SELECT COUNT(*) FROM wp_postmeta WHERE meta_key='_elementor_data';"
docker exec sitenet-db mysql -uroot -proot wordpress -N -e \
  "SELECT meta_value, COUNT(*) FROM wp_postmeta WHERE meta_key='_wp_page_template' \
   AND meta_value!='default' GROUP BY meta_value;"
```

### HTTP check

```bash
curl -sI http://localhost:2026 | head -5
curl -s -o /dev/null -w '%{http_code}\n' http://localhost:2026/some-page/
```

### Git

```bash
git status --short
git branch --show-current
git --no-pager log --oneline -10
git check-ignore -v <path>     # confirm whether a path is version-controlled
```

---

## ❌ CONFIRMED BROKEN — never use these

```bash
wp <anything>       # fails: php: error while loading shared libraries: libtidy.so.5deb1
php -l <file>       # same libtidy failure (works ONLY as `env -i /usr/bin/php -l`)
php <script>        # same failure
```

**Cause:** LocalWP's auto-generated `app/.envrc` puts its bundled PHP on `PATH`
and exports an `LD_LIBRARY_PATH` that poisons the shell for both LocalWP's PHP
and the host `/usr/bin/php`. The missing `libtidy.so.5deb1` cannot be resolved.

**Do not try to fix this** by installing packages, editing `.envrc`, or changing
symlinks. Route around it: use `docker exec` for PHP and WP-CLI.

Also broken / unavailable:

- Host `wp-cli` at `/opt/Local/resources/extraResources/bin/wp-cli/` — same libtidy failure
- `mysqldump` against LocalWP — LocalWP is not running
- Any assumption that `localhost:10003` or `site.local` responds

---

## ⛔ FORBIDDEN without explicit, specific user approval

**Never run these. Not "carefully" — never.**

```bash
docker/sync.sh              # contains DROP DATABASE IF EXISTS wordpress
docker/retarget-tunnel.sh   # rewrites every stored URL in the DB
docker/tunnel-start.sh      # opens a public tunnel to the site
docker compose down -v      # DELETES the database volume
docker compose up --build   # rebuilds/restarts the user's stack
rm -rf <anything>
git reset --hard
git clean -fd
git checkout -- <file>
git push --force
```

Database operations — forbidden:

```sql
DROP DATABASE / DROP TABLE / TRUNCATE
DELETE without an explicitly verified WHERE
UPDATE without an explicitly verified WHERE
```

Also forbidden: `wp search-replace`, `wp db import`, `wp db reset`,
`wp plugin install`, `wp plugin activate/deactivate`, `wp theme activate`,
`wp option update`, `wp post delete`, `wp media regenerate`.

> ⚠️ **`docker/sync.sh` is a destructive script sitting in this repo.** It runs
> `DROP DATABASE IF EXISTS wordpress` and then reimports a dump. It is a legitimate
> tool *for the user*, and a catastrophic one for an agent. Never invoke it.

**Prefer read-only investigation.** `SELECT`, `wp ... list`, `wp ... get`,
`php -l`, `curl -sI`, `git status` are always safe.

---

## ⚠️ HAZARD: a tunnel URL is baked into the database

Verified: `siteurl` and `home` are currently

```
https://glad-injection-window-gibson.trycloudflare.com
```

not `localhost:2026`. The database was last synced for public sharing via a
Cloudflare Quick Tunnel.

Consequences:

- Stored content URLs point at a hostname that may be **dead**. A broken image or
  link may be a URL-rewrite artefact, **not** a bug in your code. Check this
  before "fixing" anything.
- Any URL rewrite is high-risk: it would strand content on a hostname nobody uses.
- Do not "correct" `siteurl`/`home` — that is the user's deliberate sharing setup.

Verify current values before diagnosing link/image problems:

```bash
docker exec -u 1000 sitenet-wordpress wp option get siteurl --path=/var/www/html
docker exec -u 1000 sitenet-wordpress wp option get home --path=/var/www/html
```

---

## ⚠️ HAZARD: three different PHP versions

| Context | Version | Authority |
| --- | --- | --- |
| Docker container (serves the site) | **8.2.23** | ✅ authoritative — lint against this |
| LocalWP bundle (not running) | 8.2.29 | irrelevant right now |
| Host `/usr/bin/php` | 8.3.6 | second opinion only |

Do not use PHP 8.3-only syntax. Target **8.2**.

---

## WHAT YOU CANNOT VERIFY

There is **no browser automation** in this environment. You cannot check:

- Visual rendering, layout, or spacing
- Light mode vs dark mode appearance
- Desktop / tablet / mobile breakpoints
- Browser console errors
- `prefers-reduced-motion` behaviour
- Colour contrast

Never claim these passed. Always list them under **RISKS** as manual steps for
the user to perform against http://localhost:2026.

---

## KNOWN DOCUMENTATION DRIFT

`README.md` states WordPress 6.6.1 and PHP 8.2.29. Reality is WordPress **7.1**
and PHP **8.2.23**. `docker/Dockerfile` still pins `wordpress:6.6.1-php8.2-apache`
as its base image, but because the repo working tree *is* the webroot, the core
files served come from git — hence 7.1.

Trust the filesystem and the running container over the prose documentation.
If you notice further drift, report it; do not silently rewrite `README.md`.

