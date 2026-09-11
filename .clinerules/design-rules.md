# Design & Frontend Rules — SITE WordPress

> Conventions below were extracted from the existing child theme code, not
> invented. Match them. When in doubt, read `our-work.css` — it is the
> best-implemented reference file in the project.

---

## DESIGN DIRECTION

Modern NGO / institutional. Premium but trustworthy. Light mode **and** dark mode.
Fully responsive. Accessible. Subtle animation that serves usability.

Brand colours (verified in existing CSS):

| Colour | Hex | Used for |
| --- | --- | --- |
| SITE green | `#01622c` | header background, primary brand |
| Deep green | `#014720` | topbar |
| SITE yellow | `#f9c802` | accent, CTAs, tagline |

Animation must enhance usability, never decorate. No excessive motion.
Always respect `prefers-reduced-motion`.

---

## ENQUEUE CHAIN (verified in `functions.php`)

| Priority | Function | Scope | Handles |
| --- | --- | --- | --- |
| `wp_head` 0 | `site_child_theme_early_script` | site-wide | inline `theme-init.js` — sets `data-theme` before first paint |
| `wp_head` 5 | `site_child_output_favicon` | site-wide | favicon, apple-touch-icon |
| 15 | `site_child_enqueue_styles` | site-wide | `bootstrap` → `thelandscaper-parent-style` → `site-child-style` |
| 20 | `site_child_enqueue_carousel_assets` | `is_front_page()` | `site-carousel` css+js |
| 20 | `site_child_enqueue_partners_donors_assets` | `is_front_page()` | `swiper-bundle` (CDN) → `site-partners-donors` |
| 20 | `site_child_enqueue_header_assets` 🔒 | site-wide | `site-header` css+js |
| 20 | `site_child_enqueue_footer_assets` 🔒 | site-wide | `site-footer` css+js |
| 25 | `site_child_enqueue_homepage_responsive_assets` | `is_front_page()` | `site-homepage-responsive` |
| 25 | `site_child_enqueue_stories_assets` | `is_home()` | `site-stories` |
| 25 | `site_child_enqueue_our_work_assets` | `is_page_template('page-our-work.php')` | `site-our-work` |
| 26 | `site_child_enqueue_testimonials_assets` | `is_front_page()` | `site-testimonials` |
| **30** | `site_child_enqueue_dark_assets` | **site-wide, always** | `site-dark` — loads last, wins specificity ties |

🔒 = locked, do not modify.

---

## ASSET RULES

1. **Guard first.** Every enqueue function starts with a conditional
   (`is_front_page()`, `is_home()`, `is_page_template(...)`) and returns early.
   Never load a page-specific asset globally.
2. **Version with `filemtime()`** for cache busting during development:
   ```php
   file_exists( $path ) ? (string) filemtime( $path ) : '1.0.0'
   ```
3. **Scripts:** `array( 'in_footer' => true, 'strategy' => 'defer' )`.
4. **Declare dependencies** so load order is explicit. A new section stylesheet
   should depend on `site-child-style` (and on `site-header`/`site-footer` if it
   sits between them, as `site-our-work` and `site-stories` do).
5. **Register below priority 30.** `dark.css` must stay last or dark mode breaks.
6. **Vanilla JS only.** No jQuery in child theme code, no new libraries.
   Swiper 11 is the sole approved exception and is already loaded.
7. Use `wp_localize_script()` to pass PHP data to JS — never echo JSON by hand.

---

## CSS ARCHITECTURE

### Scope every section to a root class

Existing convention: `.site-our-work`, `.site-stories`, `.site-focus-areas-section`.
Scoped CSS guarantees a change cannot leak into the locked header/footer.

### Namespaced design tokens

Each section defines its own token set on its root class, prefixed to avoid
collisions — `--ow-*` for `.site-our-work`, `--st-*` for `.site-stories`.

Standard token vocabulary (copy this set for a new section, with a new prefix):

```css
.site-new-section {
    --ns-bg: #f7f7f5;
    --ns-surface: #ffffff;
    --ns-surface-raised: #fcfcfb;
    --ns-ink: #171717;
    --ns-heading: #111111;
    --ns-text: #4a4a48;
    --ns-muted: #737373;
    --ns-border: rgba(0, 0, 0, 0.08);
    --ns-border-strong: rgba(0, 0, 0, 0.12);
    --ns-accent: #f9c802;
    --ns-accent-dark: #7a6100;
    --ns-accent-text: #1a1500;
    --ns-green: #01622c;
    --ns-shadow-card: 0 1px 2px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06);
    --ns-shadow-card-hover: 0 4px 12px rgba(0,0,0,0.06), 0 16px 40px rgba(0,0,0,0.10);
    --ns-radius: 16px;
    --ns-radius-sm: 12px;
    --ns-max-width: 1280px;
    --ns-transition: 300ms cubic-bezier(0.4, 0, 0.2, 1);
    --ns-font: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
               "Helvetica Neue", Arial, sans-serif;
}
```

Then consume via `var(--ns-*)` — never hardcode hex values in rules.
(`our-work.css` uses 101 `var()` references; follow that.)

### File header comment

Every new stylesheet opens by declaring its boundary, as `our-work.css` does:

```css
/* ==========================================================================
   SITE — <Section name>
   --------------------------------------------------------------------------
   Locked boundaries: header + footer are untouched. This stylesheet styles
   ONLY <scope>.
   Supports light/dark modes via the existing html[data-theme="dark"]
   attribute set by assets/js/theme-init.js.
   ========================================================================== */
```

---

## DARK MODE CONTRACT

Dark mode is fully implemented site-wide. Any new UI must support it.

**How it works:**

1. `assets/js/theme-init.js` is inlined at `wp_head` priority **0** — before any
   stylesheet — so there is no flash of the wrong theme.
2. It reads `localStorage`, falling back to `prefers-color-scheme`, and sets
   `data-theme="dark"` or `"light"` on `<html>`.
3. It binds click handlers on any `[data-theme-toggle]` element.
4. `assets/css/dark.css` is enqueued at priority **30** — last, so it wins
   specificity ties.

**Two supported patterns — pick one and be consistent:**

*Pattern A (preferred, used by `our-work.css` / `stories.css`):* override the
same token names under a dark selector, so every rule downstream adapts for free.

```css
[data-theme="dark"] .site-new-section {
    --ns-bg: #090b0d;
    --ns-surface: #111417;
    --ns-surface-raised: #15191d;
    --ns-ink: #f5f5f5;
    --ns-heading: #ffffff;
    --ns-text: #a5a9b0;
    --ns-muted: #7d828a;
    --ns-border: rgba(255, 255, 255, 0.10);
    --ns-border-strong: rgba(255, 255, 255, 0.16);
    --ns-accent: #f9c802;
    --ns-accent-dark: #e0b402;
    --ns-accent-text: #111111;
    --ns-shadow-card: 0 1px 2px rgba(0,0,0,0.40), 0 8px 24px rgba(0,0,0,0.35);
    --ns-shadow-card-hover: 0 4px 12px rgba(0,0,0,0.45), 0 16px 40px rgba(0,0,0,0.50);
}
```

*Pattern B (used by `dark.css`):* explicit rules scoped under
`html[data-theme="dark"]`. Reference palette documented in the `dark.css` header:

| Token | Value | Purpose |
| --- | --- | --- |
| `--dm-bg` | `#0f1613` | page background |
| `--dm-bg-raised` | `#141d19` | section lift |
| `--dm-surface` | `#1a241f` | cards / panels |
| `--dm-surface-2` | `#18211c` | card alternate |
| `--dm-ink` | `#e7edea` | headings |
| `--dm-text` | `#aab7ae` | body copy |
| `--dm-muted` | `#8d9b92` | captions / meta |
| `--dm-line` | `rgba(255,255,255,0.14)` | borders |
| `--dm-green` | `#58c07f` | readable SITE green on dark |

**Rules:**

- Light mode is the default and must remain untouched by dark rules.
- Every new light rule needs a dark counterpart. Ship both or neither.
- Never use `prefers-color-scheme` in a stylesheet — the toggle is attribute-driven
  so users can override their OS. Only `theme-init.js` reads the media query.
- Verify contrast in **both** modes.

---

## RESPONSIVE DESIGN

Existing breakpoint bands (from `homepage-responsive.css`) — reuse these rather
than inventing new ones:

| Band | Query | Target |
| --- | --- | --- |
| Large monitor | `(min-width: 1600px)` | ≥1600 |
| Laptop | `(min-width: 1200px) and (max-width: 1439px)` | 1200–1439 |
| Small laptop | `(min-width: 1024px) and (max-width: 1199px)` | 1024–1199 |
| Tablet | `(min-width: 768px) and (max-width: 1023px)` | iPad landscape/portrait |
| Phone | `(max-width: 767px)` | ≤767 |
| Small phone | `(max-width: 480px)` | ≤480 |
| Legacy 4" phone | `(max-width: 360px)` | ≤360 |
| Touch devices | `(hover: none)` | disable hover-only affordances |

Every frontend change must be checked at desktop, tablet **and** mobile. Never
assume desktop-only.

Avoid: horizontal overflow, fixed widths that break mobile, text clipping,
overlapping elements, tap targets below ~44px, broken image aspect ratios.

Note: Bootstrap **3.4.1** is loaded from the parent theme. Existing markup uses
its grid (`container`, `row`, `col-md-*`) — that is a 12-column float grid, **not**
Bootstrap 4/5 flexbox. Do not use `col-lg-*`/`d-flex`/BS5 utilities.

---

## ACCESSIBILITY

Non-negotiable checks:

- Semantic HTML (`section`, `article`, `nav`, `main`, proper heading levels)
- One `h1` per page; no skipped heading levels
- Visible focus states — never `outline: none` without a replacement
- Colour contrast passes in light **and** dark mode
- Meaningful `alt` text; `alt=""` only for purely decorative images
- Descriptive button and link labels — never "click here"
- Form labels bound to inputs
- `aria-hidden="true"` on decorative icons (existing convention:
  `<i class="fa fa-angle-right" aria-hidden="true"></i>`)
- Animation is never the only channel conveying information
- `prefers-reduced-motion` honoured

**`prefers-reduced-motion` is already implemented in 10 files** — carousel,
testimonials, our-work, stories, header, footer CSS/JS. New animated work must
match. Existing pattern: gate the animation in CSS and also bail out in JS.

Font Awesome is **v4** syntax (`fa fa-*`), not v5/v6 (`fas fa-*`). Match existing
markup.

---

## PERFORMANCE

Targets (from `Docs/README.md`): page load **< 3s**, mobile usability **> 90**,
Core Web Vitals passing.

- Conditional loading — never enqueue page-specific assets globally
- Responsive images: `srcset` + `sizes` (see `focus-areas.php`)
- `loading="lazy"` and `decoding="async"` on below-the-fold images
- Explicit `width`/`height` on images to prevent layout shift (CLS)
- No new external requests without approval; Swiper's CDN is the existing exception
- No animations that trigger layout shifts
- No duplicate assets; compress images before adding them

---

## IMPLEMENTING A UI SECTION — checklist

Before coding:

1. Read the existing markup, CSS, classes, images and data source.
2. Determine whether PHP, Elementor, SiteOrigin or Gutenberg controls it.
3. Find the safest insertion point — prefer editing an existing template part.
4. Confirm nothing on the DO NOT TOUCH list is implicated.

While coding:

5. Scope CSS to a root class with a namespaced token set.
6. Ship light + dark together.
7. Vanilla JS, deferred, in footer.
8. Conditional enqueue with `filemtime()`, registered below priority 30.

After coding:

9. `docker exec sitenet-wordpress php -l /var/www/html/<path>` on every PHP file.
10. `curl -sI` the affected URLs — expect HTTP 200.
11. `git status --short` — only intended files changed.
12. List the manual browser checks under RISKS (you cannot run them).

Prefer component-level changes. Never blindly replace an entire page.

