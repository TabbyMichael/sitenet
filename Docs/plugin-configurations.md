# Restored Plugin & Theme Configurations (Production Snapshot)

> **Source of truth**: `backup_2026-09-06-0539_SITE_Enterprise_Promotion_7e99c34293fb-db.gz`
> (79 tables), extracted directly from the restored database. Generated as part of
> Day 1, Step 8 of the 10-day revamp plan.

## Environment

| Item | Value |
|------|-------|
| Site URL | `http://localhost:10003` |
| Site name | SITE Enterprise Promotion |
| Active theme | The Landscaper 2.6.1 (parent) — `template` & `stylesheet` = `the-landscaper` |
| Child theme | SITE Child Theme 1.0.0 (scaffold present, **not active**) |
| WordPress DB version | 61833 |
| PHP / MySQL | 8.2.29 / 8.4.0 (LocalWP) |
| Production origin | `/home/gundihil/public_html/s/site/` (visible in `recently_edited` option) |
| Admin email | gurphil@gmail.com |
| Active plugins | 23 |

## Active Plugins (23)

| Plugin | Version | Purpose | Disposition (per 10-day plan) |
|--------|---------|---------|-------------------------------|
| Advanced Custom Fields PRO | 5.9.5 | Custom fields | **Keep** (content architecture, Day 2) |
| Akismet Anti-spam | 5.6 | Spam protection | Keep |
| All in One SEO | 4.9.0 | SEO | **Keep** (configure schema/OG, Day 8) |
| Antispam Bee | 2.11.8 | Spam protection | Review — duplicate of Akismet (remove one) |
| Black Studio TinyMCE Widget | 2.7.3 | Legacy widget editor | **Remove** (legacy) |
| Breadcrumb NavXT | 7.4.1 | Breadcrumbs | Keep (Day 8 SEO) |
| Contact Form 7 | 6.1.3 | Forms | Keep (Day 10 forms) |
| Copy & Delete Posts | 1.5.0 | Authoring utility | Keep |
| Firelight Lightbox (Easy Fancybox) | 2.3.17 | Lightbox | Keep (Day 5 gallery) |
| Elementor | 3.33.2 | Page builder | **Phase out** (Days 2–4) |
| Essential Grid | 3.0.11 | Grid gallery | **Phase out** (Day 5) |
| MonsterInsights | 9.10.0 | Analytics | Keep (reconfigure for local/prod) |
| Image Optimizer | 1.6.9 | Image compression | **Keep** (Day 5 performance) |
| One Click Demo Import | 3.4.0 | Demo importer | **Remove** (danger in production) |
| Portfolio Post Type | 1.0.1 | CPT registration | **Replace** (Day 2 custom CPTs) |
| Slider Revolution | 6.5.9 | Slider | **Phase out** (Day 3) |
| Simple Page Sidebars | 1.2.1 | Per-page sidebars | Review |
| Page Builder by SiteOrigin | 2.33.3 | Page builder | **Phase out** (Days 2–4) |
| The Landscaper Toolkit | 1.7.1 | Theme companion | **Remove** (theme being replaced) |
| TwentyTwenty | 1.0 | Unknown/custom | Investigate — likely misnamed custom plugin |
| UpdraftPlus | 1.26.7 | Backups | **Keep** (reliability) |
| WooCommerce | 10.3.7 | E-commerce | Review scope with stakeholders |
| WPForms Lite | 1.9.8.4 | Forms | Review — duplicate of CF7 |

## Content Signals (from DB)

- `show_on_front` = `page` (static front page — homepage is a real WP page)
- Front page REST ID observed: `wp/v2/pages/7149` (real production page IDs)
- 35 `INSERT` batches into `wp_posts` (production content present)
- 23 × `woocommerce-placeholder-*.png` files in uploads → Day 5 target

## Notes

- Version numbers read from each plugin's main file header on disk.
- The full plugin stack is the "heavy load" identified in the audit — cleanup
  begins Day 2, but **nothing is removed until replacement content/patterns exist**.
