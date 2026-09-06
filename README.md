# SITE Website Revamp
Development environment for SITE Enterprise Promotion Kenya website revamp.

## Project Overview
This project is a comprehensive 10-day website revamp for SITE Enterprise Promotion Kenya, applying Designing Data-Intensive Applications (DDIA) principles (Reliability, Scalability, Maintainability) to address 13 critical improvement areas.

## Current State Analysis
- **Overall Rating**: 3/10 against modern standards
- **Theme**: The Landscaper (inappropriate for NGO - designed for landscaping companies)
- **Content**: Minimal - mostly WooCommerce placeholders and demo content
- **Architecture**: Heavy page builder dependency (SiteOrigin Panels, Elementor)
- **Critical Issues**: Theme mismatch, dummy content, broken widgets, no custom content architecture

## Development Environment
- **Platform**: LocalWP
- **PHP**: 8.2.29
- **MySQL**: 8.4.0
- **WordPress**: Current version
- **Git**: Initialized with .gitignore for version control

## Theme
- **Current**: The Landscaper (to be replaced with custom child theme)
- **Target**: Custom child theme with NGO-specific customizations
- **Approach**: Child theme for maintainability, custom post types for content architecture

## Key Plugins
- Advanced Custom Fields Pro
- SiteOrigin Panels (being phased out)
- Elementor
- WooCommerce
- UpdraftPlus

## Documentation
Detailed implementation plans are organized in the `Docs/` folder:
- `README.md` - This file, project overview
- `DAY-01.md` - Foundation & Production Restoration
- `DAY-02.md` - Theme Architecture & Content Structure
- `DAY-03.md` - Homepage Redesign
- `DAY-04.md` - "Our Work" Landing Page Rebuild
- `DAY-05.md` - Image Placeholder Replacement & Media Gallery
- `DAY-06.md` - Media Metadata Standards
- `DAY-07.md` - Partners, Stories & Resources
- `DAY-08.md` - Taxonomy, Branding & SEO
- `DAY-09.md` - Content Quality & Architecture
- `DAY-10.md` - Testing, Performance & Launch Preparation

## Development Workflow
1. All theme customizations in child theme (`wp-content/themes/site-child/`)
2. Custom post types via mu-plugins (`wp-content/mu-plugins/`)
3. Git version control for theme and custom files
4. Database changes documented in migration files
5. Testing in local environment before production deployment

## Project Structure
- `wp-content/themes/site-child/` - Custom child theme
- `wp-content/mu-plugins/` - Must-use plugins for reliability
- `wp-content/uploads/` - Media files (excluded from Git)
- `Docs/` - Project documentation and daily implementation plans

## DDIA Principles Applied
- **Reliability**: Fault-tolerant architecture, backup procedures, rollback plans
- **Scalability**: Performance optimization, efficient data models, CDN integration
- **Maintainability**: Clean code architecture, modular design, documentation
- **Trade-offs**: Informed technology choices with clear cost-benefit analysis

## 13 Revamp Priorities
1. Homepage Redesign - Make SITE understandable within seconds
2. "Our Work" Landing Page - Rebuild with reusable project components
3. Image Placeholder Replacement - Remove/replace all 850×450 placeholders
4. Interactive Media Gallery - Filterable gallery with lightbox and metadata
5. Media Metadata Standards - Naming conventions and comprehensive metadata
6. Partners & Funders Carousel - Interactive logo carousel with relationships
7. Story Readability - Enhanced typography and presentation
8. Knowledge Resources Hub - Searchable, filterable resource library
9. Taxonomy Cleanup - Primary program + secondary theme/tag system
10. Brand Identity - Favicon, logo, structured data, social sharing
11. SEO Optimization - Meta titles, descriptions, structured data
12. Content Quality - Remove dummy content, fix inconsistencies
13. WordPress Architecture - Reusable content types and CMS workflow

## Backup Procedures
- Daily automated database backups
- Manual backups before major changes
- UpdraftPlus for full site backups
- Git for code version control

## Performance Targets
- Page load time < 3 seconds (Core Web Vitals)
- Mobile usability score > 90
- Core Web Vitals passing
- SEO score improvement > 30%

## Getting Started
1. Review the documentation in the `Docs/` folder
2. Start with `DAY-01.md` for production restoration
3. Follow the daily sequence through `DAY-10.md`
4. Refer to this README for project context and workflows

## Next Steps
The 10-day implementation plan provides a structured approach to modernize the SITE website from its current 3/10 rating to an expected 8.5/10 post-modernization score, with each day building on the previous one following DDIA principles.# sitenet
