# SITE Website Revamp — Verified Status

> **Single source of truth.** Every status below was verified on **2026-09-10**
> against the running Docker stack (`http://localhost:2026`), the filesystem, and
> the live database — not against earlier planning documents.
>
> This file replaces the completion claims previously made in the removed
> `Docs/DAY-03.md` … `Docs/DAY-10.md`, which described work that was never built.
>
> Source specification: the client's *Proposed SITE Website Revamp* (13 items).

---

## Executive summary

**The architecture is ahead of the content.** Days 1–2 were completed and verified
properly. The custom post types, taxonomies and ACF field groups are correctly
registered in `wp-content/mu-plugins/` — but they are **empty**, so every
CPT-driven template renders nothing. Items 3, 4, 5, 8 and 12 have not been started.

| Verified counts | Value |
| --- | --- |
| `site_project` posts | **0** |
| `site_story` posts | **5** (drafts #8463–#8467, imported 2026-09-10) |
| `site_resource` posts | **0** |
| `site_partner` posts | **1** |
| Pages / posts / media | 42 / 18 / 237 |
| Posts carrying `_elementor_data` | 438 |
| Posts carrying SiteOrigin `panels_data` | 464 |
| Published demo/leftover pages | **16** |
| Posts containing landscaping demo copy | **128** |
| Live references to the 848×450 placeholder | **3** (was 4 — `page-our-work.php` fixed 2026-09-10) |

---

## Status by specification item

| # | Item | Status | Evidence |
| --- | --- | --- | --- |
| 1 | Homepage understandable within seconds | 🟢 ~85% | `front-page.php` renders 7 template parts: `header-hero`, org-intro (About), `focus-areas`, `impact-counter`, `featured-ecosystem`, `testimonials`, `partner-carousel`, `location-map`. Gap: content is hardcoded PHP, not program-driven; "one story per program category" is not data-driven |
| 2 | Rebuild the "Our Work" landing page | 🟡 ~60% | `page-our-work.php` (388 lines, **uncommitted**) has a filterable `WP_Query` over `post` + `site_project` + `site_story` + `site_resource`. Icon and placeholder defects **fixed 2026-09-10**. Remaining gaps: filters are by **content type**, not the specified **programs** (Youth / Women / Dairy / Camel Milk / Beekeeping / Soapstone / Agriculture / Climate / Informal Economy); CPTs empty so only `post` items render; the template is **not assigned to any page**, so it is not yet reachable by URL; two collection links point at demo pages |
| 3 | Replace every 850×450 placeholder | 🔴 Not started | **3 live references** to `wrm-848x450.png` remain: `focus-areas.php:26`, `featured-ecosystem.php:25`, `featured-ecosystem.php:100`. (A fourth, in `page-our-work.php:45`, was fixed 2026-09-10.) Also 23 × `woocommerce-placeholder-*.png` in `uploads/`, never actioned |
| 4 | Interactive media gallery | 🔴 Not started | No gallery CPT, no `assets/css/gallery.css`, no `assets/js/gallery.js`, no `template-parts/media-gallery.php`. Existing galleries are parent-theme / Essential Grid pages. None of the 17 specified features implemented |
| 5 | Media naming & metadata standard | 🔴 Not started | Uploads still use original camera/theme names (`Gallery-1-850x567.jpg`, `wrm-848x450.png`, `bilatha-850x567.jpg`, `harbole-water-850x567.jpg`). No `site-media-standards.php`, no `site-media-bulk-tools.php` |
| 6 | Partners & funders interactive carousel | 🟢 Done | `template-parts/homepage/partner-carousel.php` + Swiper 11 (jsDelivr) + `partners-donors.css/js`, front-page only. `site_partner` CPT with 6 ACF fields. Gaps: only **1** partner post exists; the carousel is hardcoded from `assets/images/partners/`; the "relationship page" option (`single-site_partner.php`) was not built |
| 7 | Make stories genuinely readable | 🟢 Done | Stories listing now lives at **`/stories/`** via new `archive-site_story.php` (adapted from `home.php`; cards use hero_image ACF; **no dates/years** — program + reading time only). Navbar "Stories" item → `/stories/` (converted to custom link); footer Stories + Blog links → `/stories/` (`footer.php` edit, user-authorized); all `/blog/` references in templates replaced with `/stories/` (12 files). Story detail: `single-site_story.php` + `story-single.css` — hero, impact statement, location/author meta (**year removed** per request), stat boxes, gallery, share, CTA, related stories. 5 stories published (#8463–#8467). **The old `/blog/` page (18 posts) still exists but is no longer linked from anywhere** |
| 8 | Papers / Knowledge Resources hub | 🔴 Not started | `site_resource` CPT + 5 ACF fields registered, **0 posts**. No `archive-site_resource.php`, no `single-site_resource.php`, no `resources.css`, no `resource-card.php`. The legacy `papers` page (ID 670) is unchanged |
| 9 | Taxonomy & categorisation cleanup | 🟡 Partially applied | `site_program`, `site_location`, `site_theme`, `site_resource_type` all registered ✅. **15 terms created and applied to the 5 imported stories** (one primary program + secondary themes + location each) 2026-09-10. Legacy `post` content still uncategorised; no `site-taxonomy-cleanup.php` |
| 10 | Brand identity (favicon / structured data) | 🟢 ~90% | `favicon.ico` + `apple-touch-icon.png` in `assets/images/`, output by `site_child_output_favicon()` at `wp_head` priority 5. AIOSEO emits JSON-LD `Organization`, `WebSite`, `WebPage`, `BreadcrumbList`, plus `og:title/description/url/type/locale/site_name` and `twitter:card`. Gap: confirm `og:image` resolves to a real SITE social-share image |
| 11 | SEO & discoverability | 🟡 Plugin-driven only | All in One SEO 4.9.0 active and functioning. Gap: no per-page titles/meta for CPT archives, because the CPTs are empty — there is nothing to optimise yet |
| 12 | Content-quality inconsistencies | 🔴 Not started | **16 demo pages still published** (see list below). **128 posts** still contain "retaining wall" / "patio" / "great looking yard" copy. The ISMA Building (Ngong Road) vs Rose Avenue Court (Hurlingham) address conflict has not been reconciled |
| 13 | WordPress architecture | 🟡 Built but empty | 4 CPTs + 4 taxonomies + 4 ACF groups registered programmatically in `mu-plugins/` — framework-independent by design, which is the **correct** architecture. Blocking gap: the CPTs hold almost no content |

Legend: 🟢 substantially complete · 🟡 partially complete · 🔴 not started

---

## Published demo / leftover pages (item 12)

These are Landscaper theme demo or duplicate pages still publicly reachable.
**Report only — do not delete or unpublish without stakeholder approval**, as some
may be linked from live navigation menus.

| ID | Slug | ID | Slug |
| --- | --- | --- | --- |
| 2 | `sample-page-2` | 5333 | `case-study` |
| 667 | `irrigation-and-drainage` | 5905 | `gallery-lightbox` |
| 673 | `case-studys` | 6087 | `prices-and-delivery` |
| 676 | `stone-and-hardscaping` | 6593 | `home-transparent` |
| 831 | `shortcodes` | 6809 | `home-sidebar` |
| 3572 | `gallery-full-width-2` | 6911 | `front-page-overlay` |
| 4680 | `home-shop` | 7699 | `services-2` |
| 5268 | `make-an-appointment` | 7805 | `front-page-2-2` |

---

## Defects in uncommitted work

Found during the 2026-09-10 audit of `page-our-work.php` and `home.php`.

### ✅ Fixed 2026-09-10

1. **Font Awesome version mismatch — 3 icon names.** The site loads **Font Awesome
   4.7.0**, but the templates used **FA5-only** names, which render as nothing:

   | Was (FA5) | Now (FA4) | Locations |
   | --- | --- | --- |
   | `fa-book-open` | `fa-book` | `page-our-work.php:72`, `page-our-work.php:104`, `home.php:159` |
   | `fa-file-alt` | `fa-file-text-o` | `page-our-work.php:79`, `page-our-work.php:106` |
   | `fa-newspaper` | `fa-newspaper-o` | `page-our-work.php:86` |

   `fa-newspaper` was a **third** instance found while validating every icon in the
   child theme against the parent theme's `font-awesome.min.css`. All 18 `fa-*`
   names now used in the child theme are confirmed present in FA 4.7.0.

2. **Placeholder wired into new work.** `page-our-work.php:45` referenced
   `wrm-848x450.png` ("water resource management" — semantically wrong for
   *Enterprise Development & Value Chains*). Replaced with
   `uploads/2021/11/journeyofgrowth-850x567.jpg`: a real SITE photograph, unused
   elsewhere, and the same 850×567 crop as its three sibling focus-area images.

### ⚠️ Still open

3. **Two collection links point at demo pages.** `page-our-work.php:87` links
   "News" to `/services/stone-and-hardscaping/` and line 94 links "Press Releases"
   to `/services/irrigation-and-drainage/` — both are Landscaper demo pages
   (IDs 676 and 667). These need real destinations, which requires a content
   decision, so they were **not** changed.
4. **Template not assigned.** No page has `_wp_page_template = page-our-work.php`,
   so the Our Work rebuild is not reachable by URL and cannot be verified in a
   browser yet.
5. **Placeholder still on the live homepage.** Three references to
   `wrm-848x450.png` remain in **committed** files (`focus-areas.php:26`,
   `featured-ecosystem.php:25` and `:100`). These were outside the approved scope
   of the 2026-09-10 fix, which covered only the uncommitted files.

---

## Promised-but-missing files

The removed `Docs/DAY-*.md` planning documents specified files that were never
created. Recorded here so the gap is explicit rather than implied.

**MU plugins (11 of 13 missing):** `site-ajax-filters.php`,
`site-backup-automation.php`, `site-content-audit.php`, `site-content-validation.php`,
`site-image-optimization.php`, `site-media-bulk-tools.php`, `site-media-standards.php`,
`site-performance-monitoring.php`, `site-placeholder-replacement.php`,
`site-seo-optimization.php`, `site-taxonomy-cleanup.php`

**Theme files (~24 missing):** `single-site_story.php`, `single-site_resource.php`,
`single-site_partner.php`, `archive-site_resource.php`, `assets/css/gallery.css`,
`assets/js/gallery.js`, `assets/css/resources.css`, `assets/css/homepage.css`,
`assets/js/homepage.js`, `assets/js/project-filters.js`,
`admin/custom-admin-interfaces.php`, `admin/media-library-enhancements.php`, and
the `template-parts/` card and grid set (`hero-section`, `focus-areas`,
`impact-figures`, `featured-stories`, `media-gallery`, `partners-carousel`,
`program-cards`, `project-card`, `project-filters`, `project-grid`,
`resource-card`). The homepage parts were instead built under
`template-parts/homepage/` with different filenames.

---

## Related documents

* `Docs/ROADMAP.md` — the phased plan to close these gaps
* `Docs/CONTENT-PIPELINE.md` — the five supplied beneficiary stories and how they populate `site_story`
* `Docs/day-02/CONTENT-ARCHITECTURE.md` — CPT / taxonomy specification
* `Docs/day-02/ACF-FIELD-ARCHITECTURE.md` — field group specification
* `.clinerules/wordpress-agent.md` — agent boundaries and DO-NOT-TOUCH list
* `.clinerules/environment-and-testing.md` — verified command set

---
**Last verified**: 2026-09-10

