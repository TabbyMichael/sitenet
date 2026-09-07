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
