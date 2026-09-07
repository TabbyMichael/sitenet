# Day 1 Backup & Recovery Procedure

## Engineering Principle
> **Reliability — Fault Tolerance, Data Integrity, Reproducibility, and Safe Recovery**

## Backup Cadence & Triggers

### 1. Pre-Operation Snapshot (Mandatory Before Destructive Changes)
Before running any database migration, bulk plugin update, or theme structure refactoring:
1. Export current database dump using WP-CLI:
   ```bash
   wp db export "../backups/pre-op-$(date +%Y%m%d-%H%M%S).sql"
   ```
2. Save environment metadata to snapshot folder.

### 2. Daily Development Backups
* Perform automated or manual UpdraftPlus local backup daily during active development.
* Keep timestamped database exports in `/home/kibuguian/Local Sites/site/app/backups/`.

### 3. Pre-Deployment Snapshots
Before promoting changes to staging or production:
1. Full site backup via UpdraftPlus (Database, Plugins, Themes, Uploads, Others).
2. Git tagging of release commits on `main`.

## Version Control vs Data Backup Scope

| Asset Category | Tracked in Git? | Backup Strategy |
| :--- | :--- | :--- |
| **Custom Child Theme** (`wp-content/themes/site-child/`) | **YES** | Git repository + Daily DB snapshots |
| **Must-Use Plugins** (`wp-content/mu-plugins/`) | **YES** | Git repository |
| **Project Documentation** (`Docs/`, `README.md`) | **YES** | Git repository |
| **Database** (`wp_options`, `wp_posts`, etc.) | **NO** | WP-CLI dumps (`.sql`) + UpdraftPlus |
| **Media Uploads** (`wp-content/uploads/`) | **NO** | UpdraftPlus uploads archives |
| **Configuration** (`wp-config.php`) | **NO** | Environment template / sanitized doc |
