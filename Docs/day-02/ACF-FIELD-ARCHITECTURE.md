# Day 2 ACF Field Architecture Specification

## Overview
Custom field architecture implemented via programmatic `acf_add_local_field_group` definitions in `wp-content/mu-plugins/site-acf-fields.php`.

---

## 1. Project Details (`group_site_project_fields`)
**Target CPT**: `site_project`

| Field Name | Key | Type | Required | Allowed Values / Options | Relationship / Format | Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `project_status` | `field_project_status` | Select | Yes | `planned` (Planned), `active` (Active), `completed` (Completed) | Return Value | Project lifecycle status |
| `start_date` | `field_project_start_date` | Date Picker | No | Date | `Y-m-d` | Project kickoff date |
| `end_date` | `field_project_end_date` | Date Picker | No | Date | `Y-m-d` | Project conclusion date |
| `donor_partner` | `field_project_donor` | Post Object | No | Post type `site_partner` | Post Object | Lead donor / funder reference |
| `impact_statistics` | `field_project_impact_stats` | Repeater | No | Subfields: `number` (Text), `label` (Text) | Array | Key quantifiable metric counters |
| `featured_story` | `field_project_featured_story` | Post Object | No | Post type `site_story` | Post Object | Case study highlight |
| `project_gallery` | `field_project_gallery` | Gallery | No | Image attachment IDs | Array of images | Project photo gallery |

---

## 2. Story Details (`group_site_story_fields`)
**Target CPT**: `site_story`

| Field Name | Key | Type | Required | Allowed Values / Options | Relationship / Format | Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `hero_image` | `field_story_hero_image` | Image | No | Image attachment | Image Array | Featured story header image |
| `impact_statement` | `field_story_impact_statement` | Textarea | No | Multiline text | Text | Highlighted beneficiary quote |
| `story_year` | `field_story_year` | Text | No | Year string (e.g. `2026`) | String | Publication / event year |
| `story_author` | `field_story_author` | Text | No | Author name | String | Field reporter / author |
| `impact_statistics` | `field_story_impact_stats` | Repeater | No | Subfields: `number`, `label` | Array | Story-specific impact metrics |
| `story_gallery` | `field_story_gallery` | Gallery | No | Image attachment IDs | Array of images | Story image grid |

---

## 3. Resource Details (`group_site_resource_fields`)
**Target CPT**: `site_resource`

| Field Name | Key | Type | Required | Allowed Values / Options | Relationship / Format | Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `publication_date` | `field_resource_pub_date` | Date Picker | No | Date | `Y-m-d` | Report release date |
| `related_project` | `field_resource_related_project` | Post Object | No | Post type `site_project` | Post Object | Associated project context |
| `resource_file` | `field_resource_file` | File | No | PDF / DOCX file | File Array | Downloadable publication file |
| `external_link` | `field_resource_external_link` | URL | No | Valid URL | URL | Link to external publication |
| `resource_description` | `field_resource_description` | Textarea | No | Multiline text | Text | Executive summary |

---

## 4. Partner Details (`group_site_partner_fields`)
**Target CPT**: `site_partner`

| Field Name | Key | Type | Required | Allowed Values / Options | Relationship / Format | Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `partner_logo` | `field_partner_logo` | Image | Yes | Image attachment | Image Array | High-res partner logo |
| `partner_website` | `field_partner_website` | URL | No | Valid URL | URL | Official organization URL |
| `partner_link_type` | `field_partner_link_type` | Select | No | `funder`, `implementing`, `technical`, `government` | Return Value | Strategic role categorization |
| `partnership_period` | `field_partner_period` | Text | No | e.g. `2021 - Present` | String | Duration of collaboration |
| `partnership_focus` | `field_partner_focus` | Textarea | No | Multiline text | Text | Scope of work summary |
| `related_projects` | `field_partner_related_projects` | Relationship | No | Post type `site_project` | Array of Objects | Co-funded / co-managed projects |
