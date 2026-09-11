# Revamp Roadmap — Phases 0 to 7

> Sequenced plan to close the gaps recorded in `Docs/REVAMP-STATUS.md`.
>
> **Sequencing rationale:** Phase 1 (content) unblocks Phases 2, 3 and 4. Building
> a story or resource template before any `site_story` / `site_resource` posts
> exist produces an empty page and cannot be verified. Do not reorder.
>
> Every phase obeys `.clinerules/wordpress-agent.md` (DO-NOT-TOUCH list) and
> `.clinerules/design-rules.md` (enqueue chain, tokens, light + dark).

---

## Phase 0 — Stabilise (½ day) · prerequisite

Nothing else should start until the working tree is safe and the known defects
are fixed.

| Task | Detail |
| --- | --- |
| 0.1 | Commit the 6 uncommitted child-theme files on a `wip/` branch so work is recoverable |
| 0.2 | ✅ **Done 2026-09-10** — Fix FA5→FA4 icon names. Three names were invalid, not two: `fa-book-open`→`fa-book`, `fa-file-alt`→`fa-file-text-o`, `fa-newspaper`→`fa-newspaper-o`. All 18 `fa-*` names in the child theme now validated against FA 4.7.0 |
| 0.3 | 🟡 **Partly done 2026-09-10** — `page-our-work.php:45` now uses `journeyofgrowth-850x567.jpg`. **3 references remain** in committed files: `focus-areas.php:26`, `featured-ecosystem.php:25`, `featured-ecosystem.php:100` |
| 0.4 | Verify: `php -l` clean, HTTP 200, light/dark, desktop/tablet/mobile |
| 0.5 | **New** — Re-point the two Our Work collection links that target demo pages (`page-our-work.php:87` → `stone-and-hardscaping`, `:94` → `irrigation-and-drainage`) |
| 0.6 | **New** — Assign `page-our-work.php` to a page so the Our Work rebuild is reachable and testable |

**Acceptance:** no FA5 icon names remain in the child theme ✅ (verified); zero
references to `wrm-848x450.png` ⚠️ (3 remain); all 6 files committed ⬜; homepage
and Our Work return 200 ⚠️ (Our Work not yet routed to a page).

---

## Phase 1 — Populate `site_story` (1–2 days) · highest value — ✅ DONE 2026-09-10

Source material: the five documents in `Docs/CONTENT-PIPELINE.md`.

| Task | Detail |
| --- | --- |
| 1.1 ✅ | Extracted the 13 embedded photos from the `.docx` files (`word/media/`) |
| 1.2 ✅ | Renamed to the item-5 standard: `site-<subject>-<location>-NN.jpg` |
| 1.3 ✅ | Imported as attachments **8450–8462** with title, caption, alt text |
| 1.4 ✅ | Created 5 `site_story` **drafts** (#8463–#8467) via `wp eval-file`, filling the existing ACF fields |
| 1.5 ✅ | Assigned one primary `site_program` + `site_theme` terms + `site_location` per story |
| 1.6 ✅ | **Published 2026-09-10 on the user's explicit instruction.** The sign-off checklist below remains recommended follow-up (consent records, figure confirmation, Hambress Water Pan split, attribution) |

**Acceptance (met 2026-09-10):** `wp post list --post_type=site_story
--format=count` = **5** (all **published**); every post has a hero image, impact
statement, year and terms; each has exactly **one** primary program term.
Abdia (#8463) deliberately has **no** `impact_statistics` — the source document
contains no verified numbers.

**Gate:** requires the editorial sign-off listed in `Docs/CONTENT-PIPELINE.md`.

---

## Phase 2 — Story detail template (1–2 days) · item 7 — ✅ DONE 2026-09-10

| Task | Detail |
| --- | --- |
| 2.1 ✅ | `single-site_story.php` built (uses all 6 ACF fields + 3 taxonomies) |
| 2.2 ✅ | `assets/css/story-single.css` — `--ss-*` tokens, light **and** dark |
| 2.3 ✅ | Conditional enqueue `site_child_enqueue_story_single_assets()` at priority 25, deps `site-child-style`/`site-header`/`site-footer`, `filemtime()` versioning |
| 2.4 ✅ | Related stories by primary program (published posts only) |
| 2.5 ✅ | Render-tested live on #8463 and #8466: HTTP 200, hero/stats/gallery/meta/CTA verified, 0 PHP warnings |

**Note:** the ACF `hero_image` field uses `return_format => 'array'`; the template
normalises array/ID values for hero, gallery and related cards.
| 2.3 | Conditional enqueue, registered **below priority 30** so `dark.css` still wins |
| 2.4 | Layout: hero photo → title → one-line impact statement → Program \| Location \| Year → body → pull quotes → impact-stat boxes → gallery → related stories from the same program → share buttons → "Partner With SITE" CTA |
| 2.5 | Readability: larger type, ~65ch measure, 1.7 line-height, clear heading hierarchy |
| 2.6 | Empty-state handling for missing ACF fields |

**Acceptance:** a published story renders with all six ACF fields visible; related
stories are drawn from the same `site_program` term; dark mode correct; header and
footer provably untouched.

---

## Phase 3 — Our Work completion (1–2 days) · item 2

| Task | Detail |
| --- | --- |
| 3.1 | Add **program-based** filters driven by `site_program` terms: Youth · Women · Dairy · Camel Milk · Beekeeping · Soapstone · Agriculture · Climate · Informal Economy |
| 3.2 | Keep the existing content-type filters as a secondary axis |
| 3.3 | Replace hardcoded program cards with a `site_program` term query, keeping a hardcoded fallback until terms exist |
| 3.4 | Sanitize and validate `$_GET` filter values (already partly done with `sanitize_key`) |

**Acceptance:** filtering by program returns the correct subset; an empty program
shows a designed empty state, not a blank grid.

---

## Phase 4 — Knowledge Resources hub (2 days) · item 8

| Task | Detail |
| --- | --- |
| 4.1 | `archive-site_resource.php` + `single-site_resource.php` |
| 4.2 | `assets/css/resources.css` with `--rs-*` tokens, light and dark |
| 4.3 | Filter by `site_resource_type`: Research Papers · Case Studies · Evaluations · Policy Briefs · Reports · Stories |
| 4.4 | Cards: cover, title, date, type, program, 1–2 line description, View + Download |
| 4.5 | Wire the existing `resource_file` and `external_link` ACF fields — no new fields |
| 4.6 | Populate with real publications (SITE currently has 2 papers on page ID 670) |

**Acceptance:** `/resources/` archive resolves; filtering by type works; download
and external-link buttons both function; empty state designed.

---

## Phase 5 — Interactive media gallery (2–3 days) · item 4

| Task | Detail |
| --- | --- |
| 5.1 | Decide the data source: media attachments with program metadata, or a `site_gallery` CPT. **Propose before building** |
| 5.2 | Masonry/grid, lazy loading, responsive |
| 5.3 | Lightbox with metadata: project, year, location, caption, credit |
| 5.4 | Reuse the **already-loaded Swiper 11** for the lightbox — no new dependency |
| 5.5 | Keyboard navigation (desktop) + swipe (mobile) |
| 5.6 | Filters/search + pagination or "Load More" |
| 5.7 | `loading="lazy"`, `decoding="async"`, explicit `width`/`height` to protect CLS |

**Acceptance:** filtering by program shows only matching images; lightbox metadata
populated; keyboard and swipe both work; `prefers-reduced-motion` honoured.

---

## Phase 6 — Content audit (1 day) · item 12

**Report-only phase. Nothing is deleted or unpublished without approval.**

| Task | Detail |
| --- | --- |
| 6.1 | Inventory the 16 published demo pages (list in `Docs/REVAMP-STATUS.md`) and check each against live navigation menus |
| 6.2 | Inventory the 128 posts containing landscaping demo copy |
| 6.3 | Propose draft/unpublish — **never delete** |
| 6.4 | Reconcile the address conflict: ISMA Building (Ngong Road) vs Rose Avenue Court (Hurlingham) |
| 6.5 | Check spelling, broken sentences, inconsistent program names, duplicated text, broken links, empty components, outdated menu items |
| 6.6 | Decide the fate of the 23 `woocommerce-placeholder-*.png` files |

**Acceptance:** a written inventory with a per-page recommendation, approved by the
stakeholder before any status change.

---

## Phase 7 — Media standards & SEO (1 day) · items 5, 11

| Task | Detail |
| --- | --- |
| 7.1 | Document the naming convention and required attachment metadata |
| 7.2 | Backfill alt text, program, year and location for new uploads |
| 7.3 | Configure AIOSEO titles for the new CPT archives — pattern: `Camel Milk Value Chain Development \| SITE Enterprise Promotion Kenya` |
| 7.4 | Verify `og:image` resolves to a real SITE social-share image |
| 7.5 | Confirm canonical URLs and internal linking on the new templates |

**Acceptance:** CPT archives have distinct, descriptive titles; `og:image` returns
a valid SITE image; new uploads follow the naming standard.

---

## Cross-cutting requirements (every phase)

* Child theme only — never `the-landscaper/` (it is `.gitignore`d, so edits there are unrecoverable)
* Header, navbar and footer are **locked** — see the DO-NOT-TOUCH list
* Ship light **and** dark styles together, or neither
* Conditional enqueue with `filemtime()` versioning, registered below priority 30
* Vanilla JS only, deferred, in footer; Swiper 11 is the sole existing exception
* Font Awesome **4** syntax (`fa fa-*`); Bootstrap **3.4.1** grid (`col-md-*`)
* `prefers-reduced-motion` honoured in both CSS and JS
* Verify with the commands in `.clinerules/environment-and-testing.md`
* Browser checks (console, light/dark, breakpoints) are **manual** — list them as risks

---

## Estimated total

| Phase | Effort |
| --- | --- |
| 0 Stabilise | ½ day |
| 1 Populate `site_story` | 1–2 days |
| 2 Story template | 1–2 days |
| 3 Our Work | 1–2 days |
| 4 Resources hub | 2 days |
| 5 Media gallery | 2–3 days |
| 6 Content audit | 1 day |
| 7 Media standards & SEO | 1 day |
| **Total** | **~9–13 working days** |

Phases 6 and 7 can run in parallel with 4 and 5.

---
**Last updated**: 2026-09-10

