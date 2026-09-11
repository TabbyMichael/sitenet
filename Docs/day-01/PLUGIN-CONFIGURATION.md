# Day 1 Plugin Inventory & Configuration

## Active Plugins Inventory

| Plugin Name | Version | Status | Core Purpose | Future Architecture Disposition |
| :--- | :--- | :--- | :--- | :--- |
| **Advanced Custom Fields Pro** | 5.9.5 | Active | Custom fields and metadata framework | **REMAIN (Core)** — Foundation for CPT custom fields |
| **Akismet Anti-spam** | 5.6 | Active | Automated comment spam protection | **REMAIN** |
| **All in One SEO Pack** | 4.9.0 | Active | Search Engine Optimization metadata | **REMAIN** — Optimize configuration in Day 8 |
| **Antispam Bee** | 2.11.8 | Active | Privacy-friendly comment spam defense | **REMAIN** |
| **Black Studio TinyMCE Widget** | 2.7.3 | Active | Visual editor widgets | **PHASE OUT** — Replace with native block widgets |
| **Breadcrumb NavXT** | 7.4.1 | Active | Breadcrumb navigation engine | **REMAIN** — Integrate with custom child theme |
| **Contact Form 7** | 6.1.3 | Active | Form handling engine | **REMAIN** — Consolidate forms |
| **Copy Delete Posts** | 1.5.0 | Active | Utility for duplicating post structures | **REMAIN** |
| **Easy FancyBox** | 2.3.17 | Active | Lightbox script overlay | **EVALUATE** — Replace with lightweight native lightbox in Day 5 |
| **Elementor** | 3.33.2 | Active | Legacy page builder engine | **PHASE OUT** — Migrate content to clean child theme templates |
| **Essential Grid** | 3.0.11 | Active | Grid gallery builder | **PHASE OUT** — Replace with modern interactive grid in Day 5 |
| **MonsterInsights (Google Analytics)** | 9.10.0 | Active | Google Analytics integration | **REMAIN** |
| **Image Optimization** | 1.6.9 | Active | Media compression utility | **REMAIN** |
| **One Click Demo Import** | 3.4.0 | Active | Import demo data | **REMOVE** — Post revamp cleanup |
| **Portfolio Post Type** | 1.0.1 | Active | Legacy portfolio CPT | **REFACTOR** — Migrate entries into `site_project` CPT |
| **Revolution Slider (revslider)**| 6.5.9 | Active | Legacy slider plugin | **PHASE OUT** — Replace with CSS/JS carousel in Day 6 |
| **Simple Page Sidebars** | 1.2.1 | Active | Per-page sidebar manager | **REMAIN** |
| **SiteOrigin Panels** | 2.33.3 | Active | Legacy page builder framework | **PHASE OUT** — Replace with structured template parts |
| **The Landscaper Toolkit** | 1.7.1 | Active | Helper plugin for parent theme | **PHASE OUT** — Replace with custom child theme |
| **UpdraftPlus** | 1.26.7 | Active | Backup & restoration engine | **REMAIN (Core)** — Automated backups |
| **WooCommerce** | 10.3.7 | Active | E-commerce engine | **EVALUATE** — Deactivate if store modules are unused |
| **WPForms Lite** | 1.9.8.4 | Active | Secondary form builder | **EVALUATE** — Consolidate with Contact Form 7 |

## Inactive Plugins
* **Duplicate Page** (4.5.6) — Inactive
* **Hello Dolly** (1.7.2) — Inactive (Scheduled for deletion)
* **Jetpack** (15.2.1) — Inactive
* **WP-Optimize** (4.3.1) — Inactive

## Must-Use (MU) Plugins Architecture (Historical Day 1 Snapshot)
* **`site-custom-post-types.php`**: Registers `site_project`, `site_story`, `site_resource`, and `site_partner`.
* **`site-taxonomies.php`**: Registers custom taxonomies for programs and thematic areas.

> **Note**: This represents the MU plugins architecture as of Day 1. Later additions include `site-acf-fields.php`, `site-hero-seed.php`, and REST-enabled taxonomy registrations.

---

## Production Snapshot Provenance

> Merged from the former `Docs/plugin-configurations.md` (removed 2026-09-10 as a
> near-duplicate). Version numbers below were read from each plugin's main file
> header on disk; the plugin table was extracted from the restored production
> database.

* **Source of truth**: `backup_2026-09-06-0539_SITE_Enterprise_Promotion_7e99c34293fb-db.gz` (79 tables)
* **Production origin path**: `/home/gundihil/public_html/s/site/` (visible in the `recently_edited` option)
* **Admin email**: gurphil@gmail.com
* **Active plugin count**: 23

### Content signals observed in the restored database

* `show_on_front` = `page` — the homepage is a real WP page (ID 7149)
* 35 `INSERT` batches into `wp_posts` — production content present
* 23 × `woocommerce-placeholder-*.png` in `uploads/` — still present, never actioned

### Disposition notes carried forward

The full plugin stack is the "heavy load" identified in the Day 1 audit. Cleanup
was scheduled from Day 2, on the principle that **nothing is removed until its
replacement content or pattern exists**. As of 2026-09-10 that cleanup has **not**
been performed — all 23 plugins remain active. See `Docs/REVAMP-STATUS.md`.

---

## ⚠️ Environment Values Have Changed Since Day 1

The Day 1 snapshot recorded the LocalWP environment. That is no longer the live
target. Current verified values (2026-09-10):

| Item | Day 1 (stale) | Current (verified) |
| --- | --- | --- |
| Site URL | `http://localhost:10003` | `http://localhost:2026` (Docker) |
| Active theme | `the-landscaper` (child inactive) | **`site-child` v1.1.0 active** |
| Parent theme | — | `the-landscaper` v2.6.1 |
| WordPress | 6.6.1 | **7.1** |
| PHP | 8.2.29 (LocalWP) | **8.2.23** (Docker container) |
| MySQL | 8.4.0 (LocalWP) | 8.4 (Docker `sitenet-db`) |
| DB `siteurl`/`home` | `http://localhost:10003` | Cloudflare Quick Tunnel hostname |

LocalWP is **not running**; the Docker stack in `docker/` serves the site directly
from this repo's working tree. See `.clinerules/environment-and-testing.md` for the
verified command set.
