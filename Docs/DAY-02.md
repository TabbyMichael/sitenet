# Day 2: Theme Architecture & Content Structure

## DDIA Focus: Maintainability - Simplicity & Evolvability

## Overview
Day 2 establishes the maintainable architecture for the SITE website by creating a proper child theme structure and implementing custom content types. This follows DDIA principles of simplicity (avoiding unnecessary complexity) and evolvability (easy to change and extend over time).

## Objectives
- Create child theme structure based on The Landscaper
- Register custom post types (Projects, Stories, Resources, Partners)
- Define custom taxonomies (Programs, Locations, Themes, Resource Types)
- Set up ACF field groups for content management
- Document content migration strategy
- Test post type and taxonomy registration

## Files to Create

### 1. Child Theme Structure
**Directory**: `wp-content/themes/site-child/`

### 2. `wp-content/themes/site-child/style.css`
**Location**: New file

**Content**:
```css
/*
Theme Name: SITE Child Theme
Theme URI: https://sitenet.org
Description: Child theme for SITE Enterprise Promotion Kenya
Author: SITE Development Team
Author URI: https://sitenet.org
Template: the-landscaper
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: site-child
*/

/* SITE-specific customizations */
/* Homepage customizations */
.hero-section {
    /* Hero section styles will be added in Day 3 */
}

/* Program cards */
.program-card {
    /* Program card styles will be added in Day 4 */
}

/* Media gallery */
.media-gallery {
    /* Gallery styles will be added in Day 5 */
}
```

**Purpose**: Establishes the child theme and provides structure for SITE-specific customizations.

### 3. `wp-content/themes/site-child/functions.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * SITE Child Theme Functions
 */

// Enqueue child theme styles
function site_child_enqueue_styles() {
    wp_enqueue_style( 'site-child-style', 
        get_stylesheet_directory_uri() . '/style.css',
        array( 'thelandscaper-main' ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_styles' );

// Remove parent theme unnecessary features
function site_child_cleanup() {
    // Remove landscaping-specific features that don't apply to NGO
    remove_theme_support( 'woocommerce' ); // Will be re-added if needed
}
add_action( 'after_setup_theme', 'site_child_cleanup', 10 );

// Add SITE-specific image sizes
function site_add_image_sizes() {
    add_image_size( 'site-hero', 1920, 730, true );
    add_image_size( 'site-program-card', 600, 400, true );
    add_image_size( 'site-story-thumb', 400, 300, true );
}
add_action( 'after_setup_theme', 'site_add_image_sizes' );
```

**Purpose**: Child theme functions for enqueuing styles and removing unnecessary parent theme features.

### 4. `wp-content/mu-plugins/site-custom-post-types.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * SITE Custom Post Types
 * Must-use plugin for reliability - always active
 */

// Register Projects Post Type
function site_register_projects() {
    $labels = array(
        'name' => 'Projects',
        'singular_name' => 'Project',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Project',
        'edit_item' => 'Edit Project',
        'new_item' => 'New Project',
        'view_item' => 'View Project',
        'search_items' => 'Search Projects',
        'not_found' => 'No projects found',
        'not_found_in_trash' => 'No projects found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'projects' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_project', $args );
}
add_action( 'init', 'site_register_projects' );

// Register Stories Post Type
function site_register_stories() {
    $labels = array(
        'name' => 'Stories',
        'singular_name' => 'Story',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Story',
        'edit_item' => 'Edit Story',
        'new_item' => 'New Story',
        'view_item' => 'View Story',
        'search_items' => 'Search Stories',
        'not_found' => 'No stories found',
        'not_found_in_trash' => 'No stories found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-book',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'stories' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_story', $args );
}
add_action( 'init', 'site_register_stories' );

// Register Resources Post Type
function site_register_resources() {
    $labels = array(
        'name' => 'Resources',
        'singular_name' => 'Resource',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Resource',
        'edit_item' => 'Edit Resource',
        'new_item' => 'New Resource',
        'view_item' => 'View Resource',
        'search_items' => 'Search Resources',
        'not_found' => 'No resources found',
        'not_found_in_trash' => 'No resources found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'resources' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_resource', $args );
}
add_action( 'init', 'site_register_resources' );

// Register Partners Post Type
function site_register_partners() {
    $labels = array(
        'name' => 'Partners',
        'singular_name' => 'Partner',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Partner',
        'edit_item' => 'Edit Partner',
        'new_item' => 'New Partner',
        'view_item' => 'View Partner',
        'search_items' => 'Search Partners',
        'not_found' => 'No partners found',
        'not_found_in_trash' => 'No partners found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'partners' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_partner', $args );
}
add_action( 'init', 'site_register_partners' );

// Flush rewrite rules on activation
function site_rewrite_flush() {
    site_register_projects();
    site_register_stories();
    site_register_resources();
    site_register_partners();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'site_rewrite_flush' );
```

**Purpose**: Registers four custom post types that form the content architecture foundation.

### 5. `wp-content/mu-plugins/site-taxonomies.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * SITE Custom Taxonomies
 * Must-use plugin for reliability - always active
 */

// Programs Taxonomy (for Projects, Stories, Resources)
function site_register_programs_taxonomy() {
    $labels = array(
        'name' => 'Programs',
        'singular_name' => 'Program',
        'search_items' => 'Search Programs',
        'all_items' => 'All Programs',
        'parent_item' => 'Parent Program',
        'parent_item_colon' => 'Parent Program:',
        'edit_item' => 'Edit Program',
        'update_item' => 'Update Program',
        'add_new_item' => 'Add New Program',
        'new_item_name' => 'New Program Name',
        'menu_name' => 'Programs',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'program' ),
    );

    register_taxonomy( 'site_program', array( 'site_project', 'site_story', 'site_resource' ), $args );
}
add_action( 'init', 'site_register_programs_taxonomy' );

// Locations Taxonomy
function site_register_locations_taxonomy() {
    $labels = array(
        'name' => 'Locations',
        'singular_name' => 'Location',
        'search_items' => 'Search Locations',
        'all_items' => 'All Locations',
        'edit_item' => 'Edit Location',
        'update_item' => 'Update Location',
        'add_new_item' => 'Add New Location',
        'new_item_name' => 'New Location Name',
        'menu_name' => 'Locations',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'location' ),
    );

    register_taxonomy( 'site_location', array( 'site_project', 'site_story', 'site_resource' ), $args );
}
add_action( 'init', 'site_register_locations_taxonomy' );

// Themes/Tags Taxonomy (for secondary categorization)
function site_register_themes_taxonomy() {
    $labels = array(
        'name' => 'Themes',
        'singular_name' => 'Theme',
        'search_items' => 'Search Themes',
        'all_items' => 'All Themes',
        'edit_item' => 'Edit Theme',
        'update_item' => 'Update Theme',
        'add_new_item' => 'Add New Theme',
        'new_item_name' => 'New Theme Name',
        'menu_name' => 'Themes',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'theme' ),
    );

    register_taxonomy( 'site_theme', array( 'site_project', 'site_story' ), $args );
}
add_action( 'init', 'site_register_themes_taxonomy' );

// Resource Types Taxonomy
function site_register_resource_types_taxonomy() {
    $labels = array(
        'name' => 'Resource Types',
        'singular_name' => 'Resource Type',
        'search_items' => 'Search Resource Types',
        'all_items' => 'All Resource Types',
        'edit_item' => 'Edit Resource Type',
        'update_item' => 'Update Resource Type',
        'add_new_item' => 'Add New Resource Type',
        'new_item_name' => 'New Resource Type Name',
        'menu_name' => 'Resource Types',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'resource-type' ),
    );

    register_taxonomy( 'site_resource_type', array( 'site_resource' ), $args );
}
add_action( 'init', 'site_register_resource_types_taxonomy' );
```

**Purpose**: Creates the taxonomy structure for organizing content across programs, locations, themes, and resource types.

## Morning Tasks: Theme Assessment & Structure

### Step 1: Audit Current Theme Dependencies
1. Review `wp-content/themes/the-landscaper/functions.php`
2. Identify all parent theme dependencies
3. Note any theme-specific features that will be removed
4. Document current customizations

### Step 2: Create Child Theme Directory
```bash
mkdir -p wp-content/themes/site-child
```

### Step 3: Create Child Theme Files
Create the files listed above in the child theme directory.

### Step 4: Activate Child Theme
1. Go to Appearance → Themes in WordPress admin
2. Activate "SITE Child Theme"
3. Verify site still loads correctly
4. Check that parent theme styles are loading

### Step 5: Test Child Theme Functionality
- Verify navigation menus still work
- Check that widgets are intact
- Test customizer settings
- Verify any existing custom post types still function

## Afternoon Tasks: Content Type Architecture

### Step 1: Create mu-plugins Directory
```bash
mkdir -p wp-content/mu-plugins
```

### Step 2: Create Custom Post Types Plugin
Create `site-custom-post-types.php` with the content above.

### Step 3: Create Taxonomies Plugin
Create `site-taxonomies.php` with the content above.

### Step 4: Flush Rewrite Rules
```bash
# In WordPress admin, go to Settings → Permalinks
# Click "Save Changes" to flush rewrite rules
```

Or via WP-CLI if available:
```bash
wp rewrite flush
```

### Step 5: Verify Post Type Registration
1. Check WordPress admin sidebar for new menu items:
   - Projects
   - Stories
   - Resources
   - Partners
2. Verify taxonomies appear in each post type editor
3. Test creating a new item in each post type
4. Check that archive pages work (e.g., /projects/, /stories/)

### Step 6: Set Up ACF Field Groups
1. Go to Custom Fields → Add New
2. Create field group for Projects:
   - Primary Program (relationship field to Programs taxonomy)
   - Project Location (text field)
   - Project Status (select: Active/Completed)
   - Start Date (date picker)
   - End Date (date picker)
   - Donor (text field)
   - Impact Statistics (repeater: number, label)
   - Featured Story (relationship to Stories)
   - Project Gallery (gallery field)

3. Create field group for Stories:
   - Hero Image (image field)
   - Impact Statement (text area)
   - Primary Program (relationship field)
   - Story Location (text field)
   - Story Year (number field)
   - Story Author (text field)
   - Impact Statistics (repeater)
   - Story Gallery (gallery field)

4. Create field group for Resources:
   - Resource Type (relationship to Resource Types)
   - Publication Date (date picker)
   - Primary Program (relationship field)
   - Related Project (relationship to Projects)
   - Resource File (file field)
   - External Link (URL field)
   - Resource Description (text area)

5. Create field group for Partners:
   - Partner Logo (image field)
   - Partner Website (URL field)
   - Partner Link Type (select: External/Relationship)
   - Partnership Period (text field)
   - Partnership Focus (text field)
   - Related Projects (relationship to Projects)

### Step 7: Document Content Migration Strategy
Create migration document:
```markdown
# Content Migration Strategy

## Existing Content Analysis
- Current pages using SiteOrigin widgets: [List]
- Current portfolio items: [Count]
- Current blog posts that should become stories: [List]
- Current resources/papers: [List]

## Migration Mapping
- Portfolio items → Projects post type
- Blog posts → Stories post type
- Pages with resources → Resources post type
- Partner pages → Partners post type

## Migration Steps
1. Export existing content
2. Transform data to match new structure
3. Import to new post types
4. Verify taxonomy assignments
5. Test archive pages
6. Update internal links
```

## Deliverables Checklist

- [ ] Child theme directory created
- [ ] Child theme style.css created
- [ ] Child theme functions.php created
- [ ] Child theme activated successfully
- [ ] mu-plugins directory created
- [ ] Custom post types plugin created
- [ ] Taxonomies plugin created
- [ ] Post types visible in WordPress admin
- [ ] Taxonomies visible in WordPress admin
- [ ] ACF field groups created for all post types
- [ ] Content migration strategy documented
- [ ] Rewrite rules flushed
- [ ] Post type archive pages tested

## Risk Mitigation

### Potential Issues:
1. **Child Theme Activation Fails**
   - Mitigation: Keep parent theme as fallback
   - Fallback: Revert to parent theme and debug child theme

2. **Post Type Registration Conflicts**
   - Mitigation: Use unique prefixes (site_)
   - Fallback: Deactivate conflicting plugins

3. **ACF Field Groups Not Saving**
   - Mitigation: Test with simple field first
   - Fallback: Use standard WordPress custom fields

## Success Criteria
- Child theme successfully activated without breaking site
- All four custom post types registered and functional
- All taxonomies registered and assigned to correct post types
- ACF field groups created and visible in post editors
- Content can be created in all new post types
- Archive pages for each post type work correctly
- Migration strategy documented for next steps

## Next Steps
After completing Day 2, proceed to [Day 3: Homepage Redesign](DAY-03.md) to begin implementing the visual redesign starting with the homepage.