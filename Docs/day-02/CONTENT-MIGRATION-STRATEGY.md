# Day 2 Content Migration Strategy

## Primary Engineering Principle
> **Transform → Validate → Import → Verify** (Avoid destructive bulk deletion)

---

## 1. Existing Production Content Inventory

| Content Type | Count | Nature of Content | Action / Disposition |
| :--- | :--- | :--- | :--- |
| **Pages** | 42 | Core site pages, SiteOrigin/Elementor layouts, shop pages | Audit & restructure template architecture in Day 3 - 4 |
| **Posts** | 18 | News articles, updates, press releases | Map beneficiary stories to `site_story` |
| **Portfolio (`portfolio`)** | 8 | Legacy demo entries ("Mediterrán Garden", "Town Pond", etc.) | Deprecate demo entries; replace with authentic SITE initiatives in `site_project` |
| **Media Attachments** | 237 | Uploaded images, documents, logos | Standardize metadata & alt tags in Day 5 - 6 |

---

## 2. Proposed Content Type Mapping

```text
Existing Content               Target Architecture Type             Notes
----------------------------   ----------------------------------   -----------------------------------
Legacy Portfolio Items (8)  →  Projects (site_project)              Replace demo data with SITE projects
Blog Posts (Impact/Stories) →  Stories (site_story)                 Extract beneficiary narratives
Static Resource Pages/PDFs  →  Resources (site_resource)            Consolidate into searchable resource CPT
Partner Logos / Pages       →  Partners (site_partner)              Migrate to dedicated Partner CPT
```

---

## 3. Risk Assessment & Mitigation Plan

### Risk 1: Broken Links & SEO Loss (URL Structure Changes)
* **Risk**: Switching from `/portfolio/item-name` to `/projects/item-name` will cause 404 errors for external visitors or indexed pages.
* **Mitigation**: Implement 301 redirect rules for mapped URLs using standard WordPress rewrite / redirect filters.

### Risk 2: Legacy Page Builder Shortcode Pollution
* **Risk**: Existing pages contain legacy SiteOrigin Panels (`[siteorigin_widget...]`) and Elementor markup.
* **Mitigation**: Extract raw post content, strip page-builder shortcodes, and re-render using clean theme template parts.

### Risk 3: Media & Attachment Disconnection
* **Risk**: Re-assigning images from old portfolio items to new CPT instances could break thumbnail associations.
* **Mitigation**: Preserve attachment IDs during migration scripts (`post_parent` update or ACF image ID assignment).

---

## 4. Phase-by-Phase Migration Workflow (Deferred to Days 3-8)

1. **Phase 1 (Day 3-4)**: Draft authentic CPT records for SITE Projects and Stories.
2. **Phase 2 (Day 5-6)**: Clean and standardize media attachment metadata.
3. **Phase 3 (Day 7-8)**: Assign `site_program`, `site_location`, and `site_theme` taxonomy terms to newly created entities.
4. **Phase 4 (Day 9)**: Verify permalink 301 redirects and deactivate legacy CPTs (`portfolio`).
