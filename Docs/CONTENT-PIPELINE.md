# Content Pipeline — Supplied Beneficiary Stories

> Five beneficiary stories were supplied as Word documents on **2026-09-10**.
> They are the fastest route to unblocking the revamp: the `site_story` CPT and
> its six ACF fields already exist and are **empty** (0 posts). These documents
> populate them without any new field or schema work.
>
> Status: **✅ IMPORTED AND PUBLISHED — 2026-09-10.** All 5 stories (#8463–#8467)
> are live at `http://localhost:2026/stories/<slug>/` with hero images,
> galleries, impact statistics and program/theme/location terms. Publishing was
> explicitly authorised by the site owner; the **editorial sign-off list below
> still applies as follow-up** (consent records, figures, attribution).
>
> ### Import record
>
> | Story | Post ID | Slug | Hero | Gallery | Program | Location |
> | --- | --- | --- | --- | --- | --- | --- |
> | Abdia Mohamed | **8463** | `abdia-mohamed-agrovet-mulanjo` | 8450 | — | Enterprise Development | Tana River |
> | Abdi Gedi | **8464** | `abdi-gedi-cahw-tana-river` | 8461 | 8462 | Enterprise Development | Tana River |
> | A Cup of Health | **8465** | `camel-milk-child-nutrition-fafi-bare` | 8452 | 8455 | Food Security & Climate | Tana River |
> | Abdi Hassan herd | **8466** | `abdi-hassan-camel-herd-recovery` | 8451 | 8454, 8456–8460 | Food Security & Climate | Tana River |
> | Dubey Sirat | **8467** | `dubey-sirat-camel-milk-garissa` | 8453 | — | Enterprise Development | Garissa |
>
> Attachments **8450–8462** (13 images) were imported with Title/Caption/Alt per
> the media standard. Deviations from the original plan:
>
> * Themes were limited to the **9 existing** `site_theme` terms — "Employment"
>   and "Maternal & Child Health" (below) do not exist and were not invented.
> * All stories recorded as **2026** (import date); per-story years below are
>   from the source documents and may need correcting on review.
> * **Abdia (8463)** has no `impact_statistics` — the source has no verified
>   numbers; do not invent any.
> * The Abdia doc's embedded photo could not be visually verified as depicting
>   her shop; captions are written from the document text. **Review the media
>   library entries before publishing.**
> * Trailing figure-caption paragraphs were stripped from gedi/cup/dubey bodies;
>   the first (title-duplicate) paragraph of each body was removed (it becomes
>   `post_title`).

---

## Source documents

| # | File | Extracted chars | Embedded photos |
| --- | --- | --- | --- |
| 1 | `~/Documents/Abdia story.docx` | 1,478 | 1 |
| 2 | `~/Documents/Abdi Gedi CAHW  case study. Mar.docx` | 2,388 | 2 |
| 3 | `~/Documents/A cup of health outreach story. March 2026.docx` | 4,664 | 2 |
| 4 | `~/Documents/Case Study Abdi herd..docx` | 4,963 | 7 |
| 5 | `~/Documents/Success Story Dubey Sirat .Qtr 4docx.docx` | 2,858 | 1 |

**13 embedded images total**, extractable from each `.docx` under `word/media/`.

> These files live **outside** the repo (in `~/Documents/`), so they are not
> version-controlled. Copy any originals you want preserved into the project
> before importing.

---

## Story inventory and taxonomy mapping

Mapping uses the existing taxonomies: `site_program` (**primary**), `site_theme`
(**secondary**), `site_location`. This delivers specification item 9 — one primary
program plus secondary themes, rather than a story appearing under every category.

### 1. Abdia Mohamed — agrovet retailer and community leader
* **Suggested slug**: `abdia-mohamed-agrovet-mulanjo`
* **Program (primary)**: Enterprise Development & Value Chains
* **Themes**: Women Empowerment · Entrepreneurship · Employment
* **Location**: Tana River (Mulanjo, Madogo Ward)
* **Year**: 2026
* **Impact statement**: From household commodities to a veterinary medicines business serving pastoral herders.
* **Impact stats**: age 42 · mother of four · Treasurer, Bula Asli Water Management Committee

### 2. Abdi Gedi — Community Animal Health Worker
* **Suggested slug**: `abdi-gedi-cahw-tana-river`
* **Program (primary)**: Enterprise Development & Value Chains (camel milk)
* **Themes**: Camel Milk · Livestock Health · Knowledge Transfer
* **Location**: Tana River (Sala Ward)
* **Year**: 2026 (March)
* **Impact statement**: A trained elder who now protects herds across Sala Ward.
* **Impact stats**: **30** camels treated · **36** herders trained · age 65

### 3. A Cup of Health — camel milk and child malnutrition
* **Suggested slug**: `camel-milk-child-nutrition-outreach-fafi-bare`
* **Program (primary)**: Food Security & Climate Action
* **Themes**: Nutrition · Camel Milk · Maternal & Child Health
* **Location**: Tana River (Fafi Bare village)
* **Year**: 2026 (March)
* **Impact statement**: Community-led outreach turning a trusted local food into a defence against malnutrition.
* **Impact stats**: **40** children under 5 screened (MUAC) · **65** mothers counselled
* **Note**: longest narrative and the best candidate for showcasing the story
  template. The partnership with the Tana River County Health Department is worth
  naming in the body.

### 4. Abdi Hassan — herd treatment case study + Hambress Water Pan
* **Suggested slug**: `abdi-hassan-camel-herd-recovery`
* **Program (primary)**: Food Security & Climate Action
* **Themes**: Livestock Health · Water & Climate Resilience · Camel Milk
* **Location**: Tana River (Mulanjo village)
* **Year**: 2025 (September–December)
* **Impact statement**: Early reporting and practical training saved four of five critically ill camels.
* **Impact stats**: **80%** herd survival · **KES 200,000–250,000** value preserved · 19 camels owned
* **⚠️ Contains a second, separate subject**: the Hambress Water Pan before/after
  (September / November / December 2025) with 3 photos. **Recommend splitting this
  into two posts** — the herd case study and a water-pan climate story — rather
  than publishing one mixed article. Needs an editorial decision.

### 5. Dubey Sirat — camel milk bulker and lead entrepreneur
* **Suggested slug**: `dubey-sirat-camel-milk-garissa`
* **Program (primary)**: Enterprise Development & Value Chains
* **Themes**: Women Empowerment · Value Addition · Market Linkages
* **Location**: Garissa (Maalim Aden milk market)
* **Year**: 2025 (Q4)
* **Impact statement**: Better hygiene and value addition turned a milk stall into a growing wholesale business.
* **Impact stats**: **300 → 360** litres/day · **10 → 15** secondary traders · **Ksh 49,000**/month

---

## Target ACF fields (already registered — no new fields needed)

Group `group_site_story_fields` on `site_story`:

| Field | Type | Populate with |
| --- | --- | --- |
| `hero_image` | Image | Best embedded photo from the source document |
| `impact_statement` | Textarea | The one-line impact statement above |
| `story_year` | Text | Year above |
| `story_author` | Text | SITE Enterprise Promotion (confirm attribution) |
| `impact_statistics` | Repeater (`number`, `label`) | The impact stats above |
| `story_gallery` | Gallery | Remaining embedded photos |

Reference: `Docs/day-02/ACF-FIELD-ARCHITECTURE.md`.

---

## Media naming standard (specification item 5)

Rename on import, per the client's proposed standard — never `IMG_00584.jpg`:

```
site-<subject>-<location>-<year>.jpg
```

Examples:

```
site-camel-milk-outreach-fafi-bare-2026.jpg
site-abdi-gedi-cahw-training-tana-river-2026.jpg
site-dubey-sirat-milk-market-garissa-2025.jpg
site-hambress-water-pan-after-2025.jpg
```

Each attachment needs **Title**, **Caption**, **Alt text**, and the program / year /
location recorded. Compress before upload; prefer WebP where practical.

---

## Editorial sign-off required before publishing

The text will be imported as written, but these must be confirmed by SITE staff
before the posts go public:

* Named individuals and their consent — Abdia Mohamed, Abdi Gedi, Abdi Hassan, Dubey Sirat
* Quoted figures: "80% survival", "KES 200,000–250,000", "Ksh 49,000/month", "300 → 360 litres"
* Whether the Hambress Water Pan material should be a separate post
* Author attribution and any donor-acknowledgement requirements — the
  *Imarisha Maisha Camel Milk Project* and *Imarisha Jamii* are both named
* Minor copy-editing: the source documents contain typos
  (e.g. "trypanosomiasis", "al the camels", "to herders on the correct use")

---

## Import method

Preferred: `wp post create` via the verified container invocation, so posts are
reproducible and reviewable.

```bash
docker exec -u 1000 sitenet-wordpress wp post create \
  --post_type=site_story --post_status=draft \
  --post_title="..." --post_name="..." \
  --path=/var/www/html
```

Create as **draft** first, preview, then publish. Never bulk-publish unreviewed
beneficiary stories. See `.clinerules/environment-and-testing.md` for the full
command set and the forbidden operations.

---
**Last updated**: 2026-09-10 · **Status**: Phase 1 import complete (drafts #8463–#8467) · awaiting editorial sign-off + Phase 2 story template

