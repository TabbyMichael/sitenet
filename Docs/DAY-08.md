# Day 8: Taxonomy, Branding & SEO (Priority #9, #10, #11)

## DDIA Focus: Reliability - Consistency & Data Integrity

## Overview
Day 8 focuses on taxonomy cleanup, brand identity implementation, and SEO optimization. This follows DDIA reliability principles by ensuring data consistency through taxonomy validation, implementing brand identity for recognition, and optimizing for search engine discoverability.

## Objectives
- Audit current categorization inconsistencies
- Implement primary program + secondary theme/tag system
- Fix cross-category story assignments
- Install SITE favicon and logo configuration
- Implement Organization structured data/schema
- Configure Open Graph and Twitter Card metadata
- Add SEO metadata templates

## Files to Create

### 1. `wp-content/mu-plugins/site-taxonomy-cleanup.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Taxonomy Cleanup and Standardization
 * Primary program + secondary theme/tag system
 */

// Add primary program field to stories and projects
function site_add_primary_program_field() {
    // Using ACF to add primary program selector
    // This ensures proper categorization
}
add_action('acf/init', 'site_add_primary_program_field');

// Cleanup duplicate categorizations
function site_cleanup_taxonomy_assignments() {
    $args = array(
        'post_type' => array('site_story', 'site_project'),
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'site_program',
                'operator' => 'EXISTS',
            ),
        ),
    );
    
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        $programs = wp_get_post_terms($post->ID, 'site_program');
        
        if (count($programs) > 1) {
            // If multiple programs assigned, use primary program field
            $primary_program = get_field('primary_program', $post->ID);
            
            if ($primary_program) {
                // Remove all program assignments except primary
                foreach ($programs as $program) {
                    if ($program->term_id !== $primary_program->term_id) {
                        wp_remove_object_terms($post->ID, $program->term_id, 'site_program');
                    }
                }
                
                // Move secondary programs to themes
                foreach ($programs as $program) {
                    if ($program->term_id !== $primary_program->term_id) {
                        wp_set_object_terms($post->ID, $program->name, 'site_theme', true);
                    }
                }
            }
        }
    }
}

// Validate taxonomy relationships
function site_validate_taxonomy_relationships($post_id) {
    $post_type = get_post_type($post_id);
    
    if (!in_array($post_type, array('site_story', 'site_project'))) return;
    
    $primary_program = get_field('primary_program', $post_id);
    $assigned_programs = wp_get_post_terms($post_id, 'site_program');
    
    // Ensure primary program is assigned
    if ($primary_program && !in_array($primary_program->term_id, wp_list_pluck($assigned_programs, 'term_id'))) {
        wp_set_object_terms($post_id, $primary_program->term_id, 'site_program', false);
    }
}
add_action('save_post', 'site_validate_taxonomy_relationships');

// Add admin notice for taxonomy issues
function site_taxonomy_admin_notices() {
    $problem_posts = site_find_taxonomy_problem_posts();
    
    if (!empty($problem_posts)) {
        echo '<div class="notice notice-warning">';
        echo '<p><strong>Taxonomy Issues:</strong> ' . count($problem_posts) . ' posts have categorization issues. ';
        echo '<a href="' . admin_url('admin.php?page=site-taxonomy-cleanup') . '">Fix Now</a></p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'site_taxonomy_admin_notices');

function site_find_taxonomy_problem_posts() {
    $args = array(
        'post_type' => array('site_story', 'site_project'),
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'site_program',
                'operator' => 'EXISTS',
            ),
        ),
    );
    
    $posts = get_posts($args);
    $problem_posts = array();
    
    foreach ($posts as $post) {
        $programs = wp_get_post_terms($post->ID, 'site_program');
        $primary_program = get_field('primary_program', $post->ID);
        
        if (count($programs) > 1 && !$primary_program) {
            $problem_posts[] = $post->ID;
        }
    }
    
    return $problem_posts;
}
```

**Purpose**: Automated taxonomy cleanup and validation system.

### 2. `wp-content/mu-plugins/site-seo-optimization.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * SEO Optimization Automation
 * Meta titles, descriptions, structured data, Open Graph
 */

// Generate SEO meta titles
function site_generate_seo_title($title) {
    if (is_singular(array('site_project', 'site_story', 'site_resource'))) {
        $post_type = get_post_type_object(get_post_type());
        $program = get_field('primary_program');
        
        if ($program) {
            return single_post_title('', false) . ' | ' . $program->name . ' | SITE Enterprise Promotion Kenya';
        }
        
        return single_post_title('', false) . ' | SITE Enterprise Promotion Kenya';
    }
    
    return $title;
}
add_filter('pre_get_document_title', 'site_generate_seo_title');

// Generate meta descriptions
function site_generate_meta_description() {
    if (is_singular(array('site_project', 'site_story', 'site_resource'))) {
        $excerpt = get_the_excerpt();
        $program = get_field('primary_program');
        
        if ($program) {
            return $program->name . ' - ' . wp_trim_words($excerpt, 20);
        }
        
        return wp_trim_words($excerpt, 25);
    }
    
    return 'SITE Enterprise Promotion Kenya transforms livelihoods through skills development, enterprise promotion, and resilient communities across Kenya.';
}
add_action('wp_head', 'site_add_meta_description');

function site_add_meta_description() {
    echo '<meta name="description" content="' . esc_attr(site_generate_meta_description()) . '">' . "\n";
}

// Add canonical URLs
function site_add_canonical_url() {
    if (is_singular()) {
        echo '<link rel="canonical" href="' . get_permalink() . '">' . "\n";
    }
}
add_action('wp_head', 'site_add_canonical_url');

// Add Open Graph metadata
function site_add_open_graph() {
    if (is_singular()) {
        global $post;
        
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(site_generate_meta_description()) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . get_permalink() . '">' . "\n";
        
        if (has_post_thumbnail()) {
            echo '<meta property="og:image" content="' . get_the_post_thumbnail_url(get_the_ID(), 'large') . '">' . "\n";
        }
        
        echo '<meta property="og:site_name" content="SITE Enterprise Promotion Kenya">' . "\n";
    }
}
add_action('wp_head', 'site_add_open_graph');

// Add Twitter Card metadata
function site_add_twitter_card() {
    if (is_singular()) {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr(site_generate_meta_description()) . '">' . "\n";
        
        if (has_post_thumbnail()) {
            echo '<meta name="twitter:image" content="' . get_the_post_thumbnail_url(get_the_ID(), 'large') . '">' . "\n";
        }
    }
}
add_action('wp_head', 'site_add_twitter_card');

// Add Organization Schema
function site_add_organization_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'SITE Enterprise Promotion Kenya',
        'url' => 'https://sitenet.org',
        'logo' => 'https://sitenet.org/wp-content/uploads/2025/03/site-logo.png',
        'description' => 'SITE transforms livelihoods through skills development, enterprise promotion, and resilient communities.',
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => 'ISMA Building, Ngong Road',
            'addressLocality' => 'Nairobi',
            'addressCountry' => 'KE',
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => '+254-XXX-XXX-XXX',
            'contactType' => 'customer service',
        ),
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
}
add_action('wp_head', 'site_add_organization_schema');

// Add Article Schema for stories and projects
function site_add_article_schema() {
    if (is_singular(array('site_project', 'site_story'))) {
        global $post;
        $program = get_field('primary_program');
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => site_generate_meta_description(),
            'author' => array(
                '@type' => 'Organization',
                'name' => 'SITE Enterprise Promotion Kenya',
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => 'SITE Enterprise Promotion Kenya',
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => 'https://sitenet.org/wp-content/uploads/2025/03/site-logo.png',
                ),
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
        );
        
        if ($program) {
            $schema['articleSection'] = $program->name;
        }
        
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
    }
}
add_action('wp_head', 'site_add_article_schema');
```

**Purpose**: Automated SEO optimization with structured data.

### 3. Brand Identity Functions (add to child theme functions.php)
**Location**: `wp-content/themes/site-child/functions.php`

**Add to existing functions.php**:
```php
/**
 * Brand Identity Functions
 */

// Add SITE favicon
function site_add_favicon() {
    echo '<link rel="icon" type="image/x-icon" href="' . get_stylesheet_directory_uri() . '/favicon.ico">' . "\n";
    echo '<link rel="apple-touch-icon" sizes="180x180" href="' . get_stylesheet_directory_uri() . '/apple-touch-icon.png">' . "\n";
}
add_action('wp_head', 'site_add_favicon');

// Configure site logo
function site_configure_logo() {
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 400,
        'flex-width' => true,
        'flex-height' => true,
    ));
}
add_action('after_setup_theme', 'site_configure_logo');

// Add admin branding
function site_custom_admin_logo() {
    echo '<style type="text/css">
        #login h1 a {
            background-image: url(' . get_stylesheet_directory_uri() . '/site-logo.png) !important;
            background-size: contain !important;
            width: 320px !important;
            height: 80px !important;
        }
    </style>';
}
add_action('login_head', 'site_custom_admin_logo');

// Custom admin footer
function site_custom_admin_footer() {
    echo 'Developed for SITE Enterprise Promotion Kenya';
}
add_filter('admin_footer_text', 'site_custom_admin_footer');
```

**Purpose**: Brand identity implementation across the site.

## Morning Tasks: Taxonomy Cleanup

### Step 1: Audit Current Categorization
1. Go to Stories and Projects
2. Check for items with multiple program assignments
3. Identify inconsistencies in program names
4. Note cross-category assignments

### Step 2: Create Taxonomy Cleanup Plugin
Create site-taxonomy-cleanup.php in mu-plugins directory.

### Step 3: Add Primary Program Field
1. Go to Custom Fields
2. Add "Primary Program" field to Stories and Projects
3. Set as relationship field to Programs taxonomy
4. Make required field

### Step 4: Run Cleanup Script
Create a simple admin page to run cleanup:
```php
// Add to site-taxonomy-cleanup.php
function site_taxonomy_cleanup_admin_page() {
    add_menu_page(
        'Taxonomy Cleanup',
        'Taxonomy Cleanup',
        'manage_options',
        'site-taxonomy-cleanup',
        'site_display_taxonomy_cleanup',
        'dashicons-groups',
        30
    );
}
add_action('admin_menu', 'site_taxonomy_cleanup_admin_page');

function site_display_taxonomy_cleanup() {
    if (isset($_POST['run_cleanup'])) {
        site_cleanup_taxonomy_assignments();
        echo '<div class="notice notice-success"><p>Taxonomy cleanup completed.</p></div>';
    }
    
    echo '<div class="wrap">';
    echo '<h1>Taxonomy Cleanup</h1>';
    echo '<form method="post">';
    echo '<input type="submit" name="run_cleanup" class="button button-primary" value="Run Cleanup">';
    echo '</form>';
    echo '</div>';
}
```

### Step 5: Validate Taxonomy Relationships
Test the validation function by editing a story/project and changing the primary program.

## Afternoon Tasks: Branding & SEO

### Step 1: Create SEO Optimization Plugin
Create site-seo-optimization.php in mu-plugins directory.

### Step 2: Add Brand Identity Functions
Add the brand identity functions to child theme functions.php.

### Step 3: Create Favicon Files
Create or obtain:
- favicon.ico (32x32)
- apple-touch-icon.png (180x180)
- site-logo.png (for admin and schema)

Place in child theme directory.

### Step 4: Configure Site Logo
1. Go to Appearance → Customize
2. Site Identity → Select Logo
3. Upload SITE logo
4. Set appropriate dimensions

### Step 5: Test SEO Metadata
1. View page source
2. Check for meta description
3. Verify Open Graph tags
4. Check Twitter Card tags
5. Validate structured data using Google's Rich Results Test

### Step 6: Test Brand Identity
1. Check browser tab for favicon
2. Verify admin login page shows SITE logo
3. Check admin footer
4. Test social sharing previews

### Step 7: Validate Structured Data
1. Use Google Structured Data Testing Tool
2. Test Organization schema
3. Test Article schema on story/project pages
4. Fix any validation errors

## Deliverables Checklist

- [ ] Taxonomy audit completed
- [ ] Taxonomy cleanup plugin created
- [ ] Primary program field added
- [ ] Cleanup script tested
- [ ] Taxonomy validation working
- [ ] SEO optimization plugin created
- [ ] Meta titles generating correctly
- [ ] Meta descriptions generating correctly
- [ ] Canonical URLs added
- [ ] Open Graph tags configured
- [ ] Twitter Card tags configured
- [ ] Organization schema implemented
- [ ] Article schema implemented
- [ ] Brand identity functions added
- [ ] Favicon files created
- [ ] Site logo configured
- [ ] Admin branding working
- [ ] Structured data validated

## Risk Mitigation

### Potential Issues:
1. **Taxonomy Cleanup Affects Live Content**
   - Mitigation: Run on backup first, test thoroughly
   - Fallback: Restore from backup if issues occur

2. **SEO Metadata Conflicts with Plugins**
   - Mitigation: Check for conflicts with All-in-One SEO
   - Fallback: Disable conflicting plugin features

3. **Structured Data Validation Errors**
   - Mitigation: Use Google's testing tool, fix iteratively
   - Fallback: Remove problematic schema elements

## Success Criteria
- Taxonomy assignments consistent and clean
- Primary program system working
- Secondary themes/tags functional
- SEO metadata automatically generated
- Open Graph tags working for social sharing
- Twitter Card tags configured
- Brand identity consistent across site
- Favicon displaying in browser
- Structured data validates correctly
- Social sharing previews show correct images

## Next Steps
After completing Day 8, proceed to [Day 9: Content Quality & Architecture](DAY-09.md) to audit content quality and improve WordPress architecture.