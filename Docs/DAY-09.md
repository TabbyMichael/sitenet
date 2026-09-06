# Day 9: Content Quality & Architecture (Priority #12, #13)

## DDIA Focus: Maintainability - Simplicity & Error Prevention

## Overview
Day 9 focuses on content quality audit and WordPress architecture improvements. This follows DDIA maintainability principles by implementing simplicity through content validation and error prevention, creating a system that's easy to manage and less prone to mistakes.

## Objectives
- Remove dummy/template content (landscaping references)
- Fix spelling, broken sentences, outdated information
- Reconcile location inconsistencies (ISMA Building vs Rose Avenue)
- Validate contact information across all pages
- Build reusable content types with ACF Pro
- Create custom admin interfaces for content management
- Implement content validation rules

## Files to Create

### 1. `wp-content/mu-plugins/site-content-audit.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Content Quality Audit Automation
 * Find dummy content, broken links, inconsistencies
 */

function site_run_content_audit() {
    $audit_results = array(
        'dummy_content' => site_find_dummy_content(),
        'broken_links' => site_find_broken_links(),
        'inconsistent_programs' => site_find_inconsistent_programs(),
        'location_inconsistencies' => site_find_location_inconsistencies(),
        'empty_components' => site_find_empty_components(),
        'outdated_content' => site_find_outdated_content(),
    );
    
    return $audit_results;
}

function site_find_dummy_content() {
    $dummy_patterns = array(
        'lorem ipsum',
        'great looking yard',
        'retaining walls',
        'patios and outdoor fireplaces',
        'template content',
        'demo content',
        'sample text',
    );
    
    $problem_posts = array();
    
    $args = array(
        'post_type' => array('page', 'post', 'site_project', 'site_story'),
        'posts_per_page' => -1,
        's' => implode('|', $dummy_patterns), // Search for dummy content
    );
    
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        foreach ($dummy_patterns as $pattern) {
            if (stripos($post->post_content, $pattern) !== false) {
                $problem_posts[] = array(
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'type' => get_post_type($post),
                    'pattern' => $pattern,
                );
                break;
            }
        }
    }
    
    return $problem_posts;
}

function site_find_broken_links() {
    $args = array(
        'post_type' => array('page', 'post', 'site_project', 'site_story'),
        'posts_per_page' => -1,
    );
    
    $posts = get_posts($args);
    $broken_links = array();
    
    foreach ($posts as $post) {
        // Extract links from content
        preg_match_all('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1[^>]*>/i', $post->post_content, $matches);
        
        if (!empty($matches[2])) {
            foreach ($matches[2] as $link) {
                // Check for common broken link patterns
                if (strpos($link, 'http://example.com') !== false ||
                    strpos($link, 'http://localhost') !== false ||
                    strpos($link, '#') === 0 && strlen($link) > 1) {
                    $broken_links[] = array(
                        'post_id' => $post->ID,
                        'post_title' => $post->post_title,
                        'link' => $link,
                    );
                }
            }
        }
    }
    
    return $broken_links;
}

function site_find_inconsistent_programs() {
    $program_names = array();
    $inconsistencies = array();
    
    // Get all program terms
    $programs = get_terms(array('taxonomy' => 'site_program', 'hide_empty' => false));
    
    // Check for similar names that might be duplicates
    foreach ($programs as $program) {
        foreach ($programs as $other_program) {
            if ($program->term_id !== $other_program->term_id) {
                $similarity = similar_text(strtolower($program->name), strtolower($other_program->name), $percent);
                
                if ($percent > 80) {
                    $inconsistencies[] = array(
                        'program1' => $program->name,
                        'program2' => $other_program->name,
                        'similarity' => $percent,
                    );
                }
            }
        }
    }
    
    return $inconsistencies;
}

function site_find_location_inconsistencies() {
    // Check Contact page vs Book Us page addresses
    $contact_page = get_page_by_path('contact');
    $book_us_page = get_page_by_path('book-us');
    
    $inconsistencies = array();
    
    if ($contact_page && $book_us_page) {
        $contact_address = site_extract_address($contact_page->post_content);
        $book_us_address = site_extract_address($book_us_page->post_content);
        
        if ($contact_address !== $book_us_address) {
            $inconsistencies[] = array(
                'contact_page' => $contact_address,
                'book_us_page' => $book_us_address,
                'message' => 'Address inconsistency between Contact and Book Us pages',
            );
        }
    }
    
    return $inconsistencies;
}

function site_extract_address($content) {
    // Simple address extraction - would need refinement
    preg_match('/(ISMA Building|Rose Avenue Court|Ngong Road|Hurlingham)/i', $content, $matches);
    return !empty($matches) ? implode(', ', $matches) : '';
}

function site_find_empty_components() {
    $empty_components = array();
    
    // Check posts with empty required fields
    $args = array(
        'post_type' => array('site_project', 'site_story', 'site_resource'),
        'posts_per_page' => -1,
    );
    
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        $required_fields = array(
            'primary_program',
            'project_location', // for projects
            'story_location', // for stories
        );
        
        foreach ($required_fields as $field) {
            if (empty(get_field($field, $post->ID))) {
                $empty_components[] = array(
                    'post_id' => $post->ID,
                    'post_title' => $post->post_title,
                    'missing_field' => $field,
                );
            }
        }
    }
    
    return $empty_components;
}

function site_find_outdated_content() {
    $outdated_content = array();
    
    // Find content not updated in 2+ years
    $args = array(
        'post_type' => array('page', 'post', 'site_project', 'site_story'),
        'posts_per_page' => -1,
        'date_query' => array(
            array(
                'before' => '2 years ago',
            ),
        ),
    );
    
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        $outdated_content[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'last_updated' => $post->post_modified,
            'post_type' => get_post_type($post),
        );
    }
    
    return $outdated_content;
}

// Create admin page for audit results
function site_content_audit_admin_page() {
    add_menu_page(
        'Content Audit',
        'Content Audit',
        'manage_options',
        'site-content-audit',
        'site_display_content_audit_results',
        'dashicons-clipboard',
        30
    );
}
add_action('admin_menu', 'site_content_audit_admin_page');

function site_display_content_audit_results() {
    $audit_results = site_run_content_audit();
    
    echo '<div class="wrap">';
    echo '<h1>Content Quality Audit</h1>';
    
    echo '<h2>Dummy Content Found: ' . count($audit_results['dummy_content']) . '</h2>';
    if (!empty($audit_results['dummy_content'])) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Post</th><th>Type</th><th>Pattern</th></tr></thead>';
        echo '<tbody>';
        foreach ($audit_results['dummy_content'] as $item) {
            echo '<tr>';
            echo '<td><a href="' . get_edit_post_link($item['id']) . '">' . esc_html($item['title']) . '</a></td>';
            echo '<td>' . esc_html($item['type']) . '</td>';
            echo '<td>' . esc_html($item['pattern']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    
    echo '<h2>Broken Links: ' . count($audit_results['broken_links']) . '</h2>';
    // Similar display for other audit results
    
    echo '</div>';
}
```

**Purpose**: Automated content quality audit system.

### 2. `wp-content/mu-plugins/site-content-validation.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Content Validation Rules
 * Prevent publishing content with issues
 */

function site_validate_content_on_publish($post_id) {
    $post = get_post($post_id);
    $post_type = get_post_type($post_id);
    
    $validation_errors = array();
    
    // Check for dummy content
    $dummy_patterns = array('lorem ipsum', 'great looking yard', 'retaining walls');
    foreach ($dummy_patterns as $pattern) {
        if (stripos($post->post_content, $pattern) !== false) {
            $validation_errors[] = "Contains dummy content pattern: {$pattern}";
        }
    }
    
    // Validate required fields based on post type
    switch ($post_type) {
        case 'site_project':
            if (empty(get_field('primary_program', $post_id))) {
                $validation_errors[] = 'Missing primary program';
            }
            if (empty(get_field('project_location', $post_id))) {
                $validation_errors[] = 'Missing project location';
            }
            break;
            
        case 'site_story':
            if (empty(get_field('primary_program', $post_id))) {
                $validation_errors[] = 'Missing primary program';
            }
            if (empty(get_field('story_location', $post_id))) {
                $validation_errors[] = 'Missing story location';
            }
            if (empty(get_field('hero_image', $post_id))) {
                $validation_errors[] = 'Missing hero image';
            }
            break;
            
        case 'site_resource':
            if (empty(get_field('resource_type', $post_id))) {
                $validation_errors[] = 'Missing resource type';
            }
            if (empty(get_field('resource_file', $post_id)) && empty(get_field('external_link', $post_id))) {
                $validation_errors[] = 'Missing resource file or external link';
            }
            break;
    }
    
    // Check for broken internal links
    if (site_content_has_broken_links($post->post_content)) {
        $validation_errors[] = 'Contains potential broken links';
    }
    
    if (!empty($validation_errors)) {
        // Prevent publishing
        remove_action('save_post', 'site_validate_content_on_publish');
        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'draft',
        ));
        add_action('save_post', 'site_validate_content_on_publish');
        
        // Add admin notice
        add_action('admin_notices', function() use ($validation_errors) {
            echo '<div class="notice notice-error">';
            echo '<p><strong>Content Validation Failed:</strong></p>';
            echo '<ul>';
            foreach ($validation_errors as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        });
    }
}
add_action('save_post', 'site_validate_content_on_publish');

function site_content_has_broken_links($content) {
    preg_match_all('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1[^>]*>/i', $content, $matches);
    
    if (!empty($matches[2])) {
        foreach ($matches[2] as $link) {
            if (strpos($link, 'http://example.com') !== false ||
                strpos($link, 'http://localhost') !== false) {
                return true;
            }
        }
    }
    
    return false;
}
```

**Purpose**: Content validation to prevent publishing issues.

### 3. `wp-content/themes/site-child/admin/custom-admin-interfaces.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Custom Admin Interfaces
 * Improved content management UI
 */

// Custom admin columns for projects
function site_project_admin_columns($columns) {
    $columns['program'] = 'Program';
    $columns['location'] = 'Location';
    $columns['status'] = 'Status';
    return $columns;
}
add_filter('manage_site_project_posts_columns', 'site_project_admin_columns');

function site_project_admin_column_data($column, $post_id) {
    switch ($column) {
        case 'program':
            $program = get_field('primary_program', $post_id);
            echo $program ? esc_html($program->name) : 'Not set';
            break;
            
        case 'location':
            $location = get_field('project_location', $post_id);
            echo $location ? esc_html($location) : 'Not set';
            break;
            
        case 'status':
            $status = get_field('project_status', $post_id);
            echo $status ? esc_html($status) : 'Active';
            break;
    }
}
add_action('manage_site_project_posts_custom_column', 'site_project_admin_column_data', 10, 2);

// Custom admin columns for stories
function site_story_admin_columns($columns) {
    $columns['program'] = 'Program';
    $columns['location'] = 'Location';
    $columns['year'] = 'Year';
    return $columns;
}
add_filter('manage_site_story_posts_columns', 'site_story_admin_columns');

function site_story_admin_column_data($column, $post_id) {
    switch ($column) {
        case 'program':
            $program = get_field('primary_program', $post_id);
            echo $program ? esc_html($program->name) : 'Not set';
            break;
            
        case 'location':
            $location = get_field('story_location', $post_id);
            echo $location ? esc_html($location) : 'Not set';
            break;
            
        case 'year':
            $year = get_field('story_year', $post_id);
            echo $year ? esc_html($year) : 'Not set';
            break;
    }
}
add_action('manage_site_story_posts_custom_column', 'site_story_admin_column_data', 10, 2);

// Add quick edit fields
function site_add_quick_edit_fields($column_name, $post_type) {
    if ($post_type === 'site_project' && $column_name === 'program') {
        ?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <label>
                    <span class="title">Program</span>
                    <select name="quick_edit_program">
                        <option value="">Select Program</option>
                        <?php
                        $programs = get_terms(array('taxonomy' => 'site_program', 'hide_empty' => false));
                        foreach ($programs as $program) {
                            echo '<option value="' . $program->term_id . '">' . esc_html($program->name) . '</option>';
                        }
                        ?>
                    </select>
                </label>
            </div>
        </fieldset>
        <?php
    }
}
add_action('quick_edit_custom_box', 'site_add_quick_edit_fields', 10, 2);

// Save quick edit fields
function site_save_quick_edit_fields($post_id) {
    if (isset($_POST['quick_edit_program'])) {
        update_field('primary_program', intval($_POST['quick_edit_program']), $post_id);
    }
}
add_action('save_post', 'site_save_quick_edit_fields');

// Custom dashboard widget
function site_custom_dashboard_widget() {
    wp_add_dashboard_widget(
        'site_content_overview',
        'SITE Content Overview',
        'site_display_content_overview'
    );
}
add_action('wp_dashboard_setup', 'site_custom_dashboard_widget');

function site_display_content_overview() {
    $project_count = wp_count_posts('site_project')->publish;
    $story_count = wp_count_posts('site_story')->publish;
    $resource_count = wp_count_posts('site_resource')->publish;
    $partner_count = wp_count_posts('site_partner')->publish;
    
    echo '<div class="site-overview-stats">';
    echo '<div class="stat-item"><strong>Projects:</strong> ' . $project_count . '</div>';
    echo '<div class="stat-item"><strong>Stories:</strong> ' . $story_count . '</div>';
    echo '<div class="stat-item"><strong>Resources:</strong> ' . $resource_count . '</div>';
    echo '<div class="stat-item"><strong>Partners:</strong> ' . $partner_count . '</div>';
    echo '</div>';
    
    echo '<p><a href="' . admin_url('admin.php?page=site-content-audit') . '">Run Content Audit</a></p>';
}
```

**Purpose**: Enhanced admin interfaces for better content management.

### 4. `Docs/CONTENT-AUDIT-REPORT.md`
**Location**: New file

**Content**:
```markdown
# SITE Content Audit Report

## Audit Date: [Date]

## Executive Summary
- Total Posts Audited: [Number]
- Issues Found: [Number]
- Critical Issues: [Number]
- Recommendations: [Number]

## Detailed Findings

### Dummy Content
- **Found**: [Number] instances
- **Locations**: [List pages/posts]
- **Action Required**: Immediate removal
- **Status**: [Resolved/Pending]

### Broken Links
- **Found**: [Number] broken links
- **Types**: Internal, external, placeholder
- **Action Required**: Fix or remove
- **Status**: [Resolved/Pending]

### Inconsistent Program Names
- **Found**: [Number] inconsistencies
- **Examples**: [List]
- **Action Required**: Standardize taxonomy
- **Status**: [Resolved/Pending]

### Location Inconsistencies
- **Contact Page**: [Address]
- **Book Us Page**: [Address]
- **Action Required**: Reconcile addresses
- **Status**: [Resolved/Pending]

### Empty Components
- **Found**: [Number] components with missing required fields
- **Types**: Projects without location, stories without hero image, etc.
- **Action Required**: Complete or remove
- **Status**: [Resolved/Pending]

### Outdated Content
- **Found**: [Number] posts not updated in 2+ years
- **Action Required**: Review and update
- **Status**: [Resolved/Pending]

## Recommendations
1. [Priority recommendation]
2. [Priority recommendation]
3. [Priority recommendation]

## Next Steps
- [ ] Schedule content review meeting
- [ ] Assign responsibility for each issue
- [ ] Set deadlines for resolution
- [ ] Plan regular audit schedule
```

**Purpose**: Documentation template for content audit results.

## Morning Tasks: Content Quality Audit

### Step 1: Run Content Audit
Create the content audit plugin and run the audit:
```php
// Temporary script to run audit
add_action('admin_init', function() {
    if (isset($_GET['run_content_audit'])) {
        $results = site_run_content_audit();
        echo '<pre>' . print_r($results, true) . '</pre>';
        exit;
    }
});
```

Visit: `/wp-admin/?run_content_audit=1`

### Step 2: Analyze Results
- Review dummy content findings
- Identify broken links
- Check program name inconsistencies
- Note location inconsistencies

### Step 3: Remove Dummy Content
1. Go to each page/post with dummy content
2. Remove landscaping references and template text
3. Replace with SITE-appropriate content or remove component
4. Verify layout still works

### Step 4: Fix Location Inconsistencies
1. Contact page: ISMA Building, Ngong Road
2. Book Us page: Update to match contact page
3. Verify consistency across all pages

### Step 5: Fix Broken Links
1. Replace example.com links with actual links
2. Remove localhost references
3. Fix anchor link issues
4. Test all internal links

## Afternoon Tasks: Architecture & Validation

### Step 1: Create Content Validation Plugin
Create site-content-validation.php in mu-plugins directory.

### Step 2: Test Content Validation
1. Try to publish content with dummy text
2. Verify validation prevents publishing
3. Try to publish missing required fields
4. Verify validation catches issues

### Step 3: Create Admin Enhancements
Create custom-admin-interfaces.php in child theme admin directory.

### Step 4: Test Admin Interfaces
1. Check custom columns appear
2. Test quick edit functionality
3. Verify dashboard widget displays
4. Test content audit admin page

### Step 5: Document WordPress Architecture
Create architecture documentation:
```markdown
# WordPress Architecture

## Custom Post Types
- site_project: Projects and programs
- site_story: Success stories and case studies
- site_resource: Research papers, case studies, reports
- site_partner: Funding partners and collaborators

## Taxonomies
- site_program: Primary program categorization
- site_location: Geographic categorization
- site_theme: Secondary tagging system
- site_resource_type: Resource categorization

## Content Management Workflow
1. Create content using custom post types
2. Fill required ACF fields
3. Validate content before publishing
4. Related content automatically suggested
5. Archive pages auto-generated

## Custom Admin Features
- Custom columns for quick overview
- Quick edit for common fields
- Dashboard content overview
- Content audit tools
- Bulk operations available
```

### Step 6: Create Audit Report Template
Create CONTENT-AUDIT-REPORT.md with the template above.

## Deliverables Checklist

- [ ] Content audit completed
- [ ] Dummy content removed
- [ ] Broken links fixed
- [ ] Location inconsistencies resolved
- [ ] Content validation plugin created
- [ ] Validation rules tested
- [ ] Admin enhancements created
- [ ] Custom columns working
- [ ] Quick edit functional
- [ ] Dashboard widget working
- [ ] Content audit admin page working
- [ ] WordPress architecture documented
- [ ] Audit report template created
- [ ] Team trained on new workflow

## Risk Mitigation

### Potential Issues:
1. **Content Validation Too Strict**
   - Mitigation: Allow override with admin notice
   - Fallback: Disable validation temporarily

2. **Audit Performance Issues**
   - Mitigation: Run audits during low-traffic periods
   - Fallback: Limit audit scope

3. **Admin Interface Conflicts**
   - Mitigation: Test with existing plugins
   - Fallback: Remove conflicting features

## Success Criteria
- All dummy content removed
- No broken links remaining
- Location information consistent
- Content validation working effectively
- Admin interfaces improve workflow
- Custom columns display useful information
- Quick edit saves time
- Content audit tool functional
- Architecture documentation complete
- Team comfortable with new workflow

## Next Steps
After completing Day 9, proceed to [Day 10: Testing, Performance & Launch Preparation](DAY-10.md) to conduct comprehensive testing and prepare for launch.