# Day 1: Foundation & Production Restoration

## DDIA Focus: Reliability - Fault Tolerance & Data Integrity

## Overview
Day 1 focuses on establishing a reliable foundation by restoring the production backup, setting up the development environment, and implementing fault-tolerant practices. This ensures we have a solid base to build upon while maintaining data integrity throughout the revamp process.

## Objectives
- Restore production backup from UpdraftPlus archives
- Verify database integrity and functionality
- Set up development environment with proper debugging
- Initialize Git repository for version control
- Document current state and configurations
- Establish backup procedures

## Files to Modify/Create

### 1. `wp-config.php` - Enable Development Debugging
**Location**: `/home/kibuguian/Local Sites/site/app/public/wp-config.php`

**Changes**: Add after line 94 (after WP_ENVIRONMENT_TYPE definition)

```php
// Enable debugging for development
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
```

**Purpose**: Enables error logging to help identify issues during development while keeping errors off the live display for security.

### 2. `.gitignore` - Create Version Control Exclusions
**Location**: `/home/kibuguian/Local Sites/site/app/public/.gitignore` (new file)

**Content**:
```
# WordPress Core
wp-config.php
wp-content/advanced-cache.php
wp-content/uploads/
wp-content/backup-db/
wp-content/backups/
wp-content/cache/
wp-content/updraft/
wp-content/w3tc-config/

# Plugins (exclude from version control)
wp-content/plugins/all-in-one-seo-pack/
wp-content/plugins/akismet/
wp-content/plugins/antispam-bee/
wp-content/plugins/black-studio-tinymce-widget/
wp-content/plugins/breadcrumb-navxt/
wp-content/plugins/contact-form-7/
wp-content/plugins/copy-delete-posts/
wp-content/plugins/duplicate-page/
wp-content/plugins/easy-fancybox/
wp-content/plugins/elementor/
wp-content/plugins/essential-grid/
wp-content/plugins/google-analytics-for-wordpress/
wp-content/plugins/hello.php
wp-content/plugins/image-optimization/
wp-content/plugins/jetpack/
wp-content/plugins/one-click-demo-import/
wp-content/plugins/portfolio-post-type/
wp-content/plugins/revslider/
wp-content/plugins/simple-page-sidebars/
wp-content/plugins/siteorigin-panels/
wp-content/plugins/the-landscaper-toolkit/
wp-content/plugins/twentytwenty/
wp-content/plugins/updraftplus/
wp-content/plugins/woocommerce/
wp-content/plugins/wpforms-lite/
wp-content/plugins/wp-optimize/

# Operating System Files
.DS_Store
Thumbs.db
*.swp
*.swo

# IDE Files
.vscode/
.idea/
*.sublime-project
*.sublime-workspace

# Log Files
*.log
error_log
debug.log
```

**Purpose**: Excludes sensitive files, large uploads, and third-party plugins from version control while keeping custom development files tracked.

### 3. `README.md` - Project Documentation
**Location**: `/home/kibuguian/Local Sites/site/app/public/README.md` (new file)

**Content**:
```markdown
# SITE Website Revamp
Development environment for SITE Enterprise Promotion Kenya website revamp.

## Local Development
- Platform: LocalWP
- PHP: 8.2.29
- MySQL: 8.4.0
- WordPress: Current version

## Theme
- Current: The Landscaper (to be replaced with child theme)
- Target: Custom child theme with NGO-specific customizations

## Key Plugins
- Advanced Custom Fields Pro
- SiteOrigin Panels
- Elementor
- WooCommerce
- UpdraftPlus

## Development Workflow
1. All theme customizations in child theme
2. Custom post types via mu-plugins
3. Git version control for theme and custom files
4. Database changes documented in migration files
5. Testing in local environment before production deployment

## Project Structure
- `wp-content/themes/site-child/` - Custom child theme
- `wp-content/mu-plugins/` - Must-use plugins for reliability
- `wp-content/uploads/` - Media files (excluded from Git)
- `Docs/` - Project documentation

## Backup Procedures
- Daily automated database backups
- Manual backups before major changes
- UpdraftPlus for full site backups
- Git for code version control

## Performance Targets
- Page load time < 3 seconds
- Mobile usability score > 90
- Core Web Vitals passing
```

**Purpose**: Provides project context, development guidelines, and important information for anyone working on the project.

## Morning Tasks: Production Backup Restoration

### Step 1: Verify Backup Files
Check that all 6 backup files are present in `wp-content/updraft/`:
```bash
ls -lh wp-content/updraft/
```

Expected files:
- `backup_2026-09-06-0453_SITE_Enterprise_Promotion_a446b671be0f-db.gz`
- `backup_2026-09-06-0453_SITE_Enterprise_Promotion_a446b671be0f-others.zip`
- `backup_2026-09-06-0453_SITE_Enterprise_Promotion_a446b671be0f-plugins.zip`
- `backup_2026-09-06-0453_SITE_Enterprise_Promotion_a446b671be0f-themes.zip`
- `backup_2026-09-06-0453_SITE_Enterprise_Promotion_a446b671be0f-uploads.zip`
- `backup_2026-09-06-0453_SITE_Enterprise_Promotion_a446b671be0f-uploads2.zip`

### Step 2: Access WordPress Admin
1. Open LocalWP
2. Click "Open Admin" for the SITE site
3. Log in with temporary credentials (will be replaced by production database)

### Step 3: Install UpdraftPlus (if not already installed)
1. Go to Plugins → Add New
2. Search for "UpdraftPlus WordPress Backup Plugin"
3. Install and activate

### Step 4: Scan for Backup Files
1. Go to Settings → UpdraftPlus Backups
2. Click "Existing Backups"
3. Click "Rescan local folder for new backup sets"
4. Verify the September 6, 2026 backup appears

### Step 5: Restore Backup
1. Select the backup dated Sep 06, 2026 04:53
2. Check all components:
   - ☑ Database
   - ☑ Plugins
   - ☑ Themes
   - ☑ Uploads
   - ☑ Others
3. Click "Restore"
4. Wait for restoration to complete
5. Verify site loads correctly

### Step 6: Verify Database Integrity
```bash
# Check database connection
cd /home/kibuguian/Local\ Sites/site/app/public
php -r "require 'wp-load.php'; echo 'Database connection: ' . (mysqli_connect_errno() ? 'Failed' : 'Success') . PHP_EOL;"
```

### Step 7: Check Site URL Configuration
In WordPress admin:
1. Go to Settings → General
2. Verify WordPress Address (URL) and Site Address (URL)
3. Note if they still show `https://sitenet.org` (expected at this stage)

## Afternoon Tasks: Development Environment Setup

### Step 1: Initialize Git Repository
```bash
cd /home/kibuguian/Local\ Sites/site/app/public
git init
git add .gitignore README.md wp-config.php
git commit -m "Initial commit: Development environment setup"
```

### Step 2: Create Backup of Current State
```bash
# Create manual backup directory
mkdir -p ../backups/pre-revamp-$(date +%Y%m%d)
cp -r . ../backups/pre-revamp-$(date +%Y%m%d)/
```

### Step 3: Document Plugin Configurations
1. Go to Plugins → Installed Plugins
2. For each active plugin, document:
   - Plugin name and version
   - Key settings and configurations
   - Any customizations or shortcodes used
3. Save screenshot documentation in `Docs/plugin-configurations/`

### Step 4: Set Up Development Workflow
Create development workflow document:
```markdown
# Development Workflow

## Branch Strategy
- main: Production-ready code
- develop: Active development
- feature/*: Feature-specific branches

## Commit Standards
- Use descriptive commit messages
- Reference issue numbers when applicable
- Include [WIP] for work-in-progress commits

## Testing Requirements
- Test in local environment before pushing
- Verify no PHP errors
- Check browser console for JavaScript errors
- Test responsive design

## Deployment Process
1. All tests passing
2. Code reviewed
3. Documentation updated
4. Backup created
5. Deploy to staging for final testing
6. Deploy to production
```

### Step 5: Configure Error Logging
Verify error logging is working:
```bash
tail -f wp-content/debug.log
```

Test by temporarily breaking something and checking if error appears in log.

## Deliverables Checklist

- [ ] Production backup successfully restored
- [ ] Database integrity verified
- [ ] WP_DEBUG enabled in wp-config.php
- [ ] .gitignore file created
- [ ] README.md created with project documentation
- [ ] Git repository initialized
- [ ] Initial commit made
- [ ] Manual backup created
- [ ] Plugin configurations documented
- [ ] Development workflow documented
- [ ] Error logging verified
- [ ] Development environment fully functional

## Risk Mitigation

### Potential Issues:
1. **Backup Restoration Fails**
   - Mitigation: Keep original backup files untouched
   - Fallback: Manual database import from .gz file

2. **Database Connection Issues**
   - Mitigation: Verify MySQL service is running in LocalWP
   - Fallback: Recreate site in LocalWP

3. **Plugin Conflicts Post-Restoration**
   - Mitigation: Document current plugin states before restoration
   - Fallback: Selective restoration (database only, then plugins)

## Success Criteria
- Production site fully functional in local environment
- All content accessible and displaying correctly
- Development environment properly configured
- Version control system operational
- Backup procedures established
- Team has clear documentation for next steps

## Next Steps
After completing Day 1, proceed to [Day 2: Theme Architecture & Content Structure](DAY-02.md) to begin building the custom content types and theme structure.