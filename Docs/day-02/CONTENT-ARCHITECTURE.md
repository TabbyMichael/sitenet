# Day 2 Content Architecture Specification

## DDIA Focus: Separation of Concerns & Explicit Data Ownership

This document defines the content entities, taxonomies, and relationships for the SITE Enterprise Promotion Kenya platform.

---

## 1. Primary Entities (Custom Post Types)

### Projects (`site_project`)
* **Purpose**: Core institutional initiatives and programs implemented by SITE Enterprise Promotion Kenya.
* **Archive URL**: `/projects/`
* **Rest API**: `/wp-json/wp/v2/site_project`
* **Taxonomies**: `site_program`, `site_location`, `site_theme`
* **Key Fields**: `project_status`, `start_date`, `end_date`, `donor_partner`, `impact_statistics`, `featured_story`, `project_gallery`

### Stories (`site_story`)
* **Purpose**: Beneficiary success stories, field updates, and qualitative impact narratives.
* **Archive URL**: `/stories/`
* **Rest API**: `/wp-json/wp/v2/site_story`
* **Taxonomies**: `site_program`, `site_location`, `site_theme`
* **Key Fields**: `hero_image`, `impact_statement`, `story_year`, `story_author`, `impact_statistics`, `story_gallery`

### Resources (`site_resource`)
* **Purpose**: Publications, policy briefs, toolkits, research papers, and annual reports.
* **Archive URL**: `/resources/`
* **Rest API**: `/wp-json/wp/v2/site_resource`
* **Taxonomies**: `site_resource_type`, `site_program`, `site_location`
* **Key Fields**: `publication_date`, `related_project`, `resource_file`, `external_link`, `resource_description`

### Partners (`site_partner`)
* **Purpose**: Institutional donors, government ministries, technical partners, and implementation alliances.
* **Archive URL**: `/partners/`
* **Rest API**: `/wp-json/wp/v2/site_partner`
* **Taxonomies**: None (categorized via `partner_link_type` select field)
* **Key Fields**: `partner_logo`, `partner_website`, `partner_link_type`, `partnership_period`, `partnership_focus`, `related_projects`

---

## 2. Taxonomies (Cross-Cutting Categories)

```text
site_program (Hierarchical)
 ├── Attached to: Projects, Stories, Resources
 └── Examples: Agriculture & Value Chains, Renewable Energy, Youth & Women Empowerment

site_location (Hierarchical)
 ├── Attached to: Projects, Stories, Resources
 └── Examples: Nakuru, Meru, Kitui, Machakos, Nationwide

site_theme (Non-Hierarchical)
 ├── Attached to: Projects, Stories
 └── Examples: Climate Smart Agriculture, Access to Finance, Digital Skills

site_resource_type (Hierarchical)
 ├── Attached to: Resources
 └── Examples: Annual Reports, Research Papers, Policy Briefs, Toolkits
```

---

## 3. Entity Relationships Diagram

```text
Program (site_program)
 ├── Projects (site_project)
 ├── Stories (site_story)
 └── Resources (site_resource)

Project (site_project)
 ├── Program (site_program)
 ├── Location (site_location)
 ├── Themes (site_theme)
 ├── Funder / Donor (site_partner via ACF Post Object)
 ├── Featured Story (site_story via ACF Post Object)
 └── Gallery (ACF Gallery)

Story (site_story)
 ├── Program (site_program)
 ├── Location (site_location)
 ├── Themes (site_theme)
 └── Gallery (ACF Gallery)

Resource (site_resource)
 ├── Resource Type (site_resource_type)
 ├── Program (site_program)
 ├── Location (site_location)
 └── Related Project (site_project via ACF Post Object)

Partner (site_partner)
 └── Related Projects (site_project via ACF Relationship)
```

---

## 4. Architectural Rationale

1. **Taxonomy vs ACF Field**: Taxonomies are used strictly for multi-item query filtering and archive routing (`/program/agriculture/`). ACF fields are reserved for item-specific metadata (e.g. download links, impact metrics, dates).
2. **Framework Independence**: All post types and taxonomies are registered inside `wp-content/mu-plugins/`, guaranteeing that switching or replacing themes will never lose site content structure.
3. **Normalized Data Modeling**: A single Program term (`site_program`) categorizes Projects, Stories, and Resources simultaneously, preventing data duplication.
