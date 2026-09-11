# SITE Website Revamp — Documentation Index

> **Read `Docs/REVAMP-STATUS.md` first.** It is the single source of truth for
> what is actually built, verified against the live site on 2026-09-10.
>
> This folder was restructured on 2026-09-10. The ten `DAY-01.md` … `DAY-10.md`
> planning documents were removed: they specified files that were never created
> (11 of 13 MU plugins, ~24 theme files) and claimed all 13 priorities were
> complete, which verification proved false. Their accurate content survives in
> `Docs/day-01/` and `Docs/day-02/`. All removals are recoverable via git.

---

## Start here

| Document | Purpose |
| --- | --- |
| [`REVAMP-STATUS.md`](REVAMP-STATUS.md) | **Verified status** of all 13 specification items, with evidence |
| [`ROADMAP.md`](ROADMAP.md) | Phased plan (0–7) with acceptance criteria and effort estimates |
| [`CONTENT-PIPELINE.md`](CONTENT-PIPELINE.md) | The five supplied beneficiary stories and how they populate `site_story` |

## Architecture specifications (Day 2 — accurate)

| Document | Purpose |
| --- | --- |
| [`day-02/CONTENT-ARCHITECTURE.md`](day-02/CONTENT-ARCHITECTURE.md) | CPTs, taxonomies, entity relationships, architectural rationale |
| [`day-02/ACF-FIELD-ARCHITECTURE.md`](day-02/ACF-FIELD-ARCHITECTURE.md) | All four field groups, field by field |
| [`day-02/THEME-AUDIT.md`](day-02/THEME-AUDIT.md) | Parent theme audit: enqueues, features, menus, image sizes |
| [`day-02/CHILD-THEME-VERIFICATION.md`](day-02/CHILD-THEME-VERIFICATION.md) | Child theme activation verification and regression findings |
| [`day-02/CONTENT-MIGRATION-STRATEGY.md`](day-02/CONTENT-MIGRATION-STRATEGY.md) | Migration approach for existing content |

## Foundation record (Day 1 — historical)

| Document | Purpose |
| --- | --- |
| [`DAY-01-STATUS.md`](DAY-01-STATUS.md) | Day 1 completion record with verification evidence |
| [`day-01/RESTORATION.md`](day-01/RESTORATION.md) | Production restoration procedure and outcome |
| [`day-01/BACKUP-PROCEDURE.md`](day-01/BACKUP-PROCEDURE.md) | Backup sets and verification |
| [`day-01/ENVIRONMENT.md`](day-01/ENVIRONMENT.md) | LocalWP environment record |
| [`day-01/PLUGIN-CONFIGURATION.md`](day-01/PLUGIN-CONFIGURATION.md) | Plugin inventory, dispositions, and current-vs-Day-1 environment values |

## Operations

| Document | Purpose |
| --- | --- |
| [`DEVELOPMENT-WORKFLOW.md`](DEVELOPMENT-WORKFLOW.md) | Branch strategy, commit standards, testing, deployment |
| [`DOCKER-DEPLOYMENT.md`](DOCKER-DEPLOYMENT.md) | Running the repo with Docker |
| [`RUN-TO-CLOUDFLARE.md`](RUN-TO-CLOUDFLARE.md) | Public sharing via Cloudflare Quick Tunnel |
| [`RECOVERY-PROCEDURE.md`](RECOVERY-PROCEDURE.md) | Rollback and recovery checklist |

## Agent rules

| Document | Purpose |
| --- | --- |
| `.clinerules/wordpress-agent.md` | Role, verified architecture, DO-NOT-TOUCH list, report format |
| `.clinerules/environment-and-testing.md` | Verified command set, broken commands, forbidden operations |
| `.clinerules/design-rules.md` | Enqueue chain, CSS tokens, dark mode, responsive, accessibility |

---

## The 13 specification priorities

Source: the client's *Proposed SITE Website Revamp*. Status verified 2026-09-10 —
see `REVAMP-STATUS.md` for the evidence behind each.

| # | Priority | Status |
| --- | --- | --- |
| 1 | Homepage redesign | 🟢 ~85% |
| 2 | "Our Work" landing page rebuild | 🟡 ~60% |
| 3 | Placeholder image replacement | 🔴 not started |
| 4 | Interactive media gallery | 🔴 not started |
| 5 | Media metadata standards | 🔴 not started |
| 6 | Partners & funders carousel | 🟢 done |
| 7 | Story readability | 🟡 list only |
| 8 | Knowledge Resources hub | 🔴 not started |
| 9 | Taxonomy cleanup | 🟡 registered, not applied |
| 10 | Brand identity | 🟢 ~90% |
| 11 | SEO optimisation | 🟡 plugin-driven only |
| 12 | Content quality | 🔴 not started |
| 13 | WordPress architecture | 🟡 built but empty |

---

## Current environment (verified)

| Item | Value |
| --- | --- |
| Site URL | `http://localhost:2026` (Docker) |
| Active theme | `site-child` v1.1.0 |
| Parent theme | `the-landscaper` v2.6.1 |
| WordPress | 7.1 |
| PHP | 8.2.23 (container) |
| MySQL | 8.4 (`sitenet-db`, host port 3307) |
| LocalWP | **not running** |

> The root `README.md` still states WordPress 6.6.1 and PHP 8.2.29. That is stale;
> the values above were read from the running container and the repo working tree.

---

## Development principles

Carried forward from the original plan and still governing:

* **Reliability** — fault tolerance, data integrity, safe recovery
* **Data integrity** — pre-change snapshots, verification before destructive actions
* **Reproducibility** — version-controlled custom code, explicit migration steps
* **Separation of concerns** — content architecture in `mu-plugins/`, presentation in the child theme
* **Local testing** — full verification before any deployment

Performance targets: page load **< 3s**, mobile usability **> 90**, Core Web Vitals passing.

---

## Key technologies

* **WordPress 7.1** with the `site-child` child theme
* **ACF Pro 5.9.5** — field groups registered programmatically, not via the admin UI
* **SiteOrigin Panels / Elementor** — present on legacy content (464 / 438 posts); being phased out for new work
* **Swiper 11** — the only approved JS library, already loaded
* **Bootstrap 3.4.1** and **Font Awesome 4.7.0** — inherited from the parent theme
* **Docker** — the live test environment; the repo working tree *is* the webroot

---

## Documentation maintenance

* Update `REVAMP-STATUS.md` whenever a specification item's status changes.
* Record new content decisions in `CONTENT-PIPELINE.md`.
* Do not re-introduce day-numbered planning documents that describe unbuilt work.
  If a plan is needed, add it to `ROADMAP.md` with acceptance criteria.
* If a document and the filesystem disagree, **the filesystem wins** — fix the document.

---
**Last updated**: 2026-09-10

