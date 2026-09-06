# Day 6: Media Metadata Standards (Priority #5)

## DDIA Focus: Maintainability - Operability & Documentation

## Overview
Day 6 establishes comprehensive media metadata standards to create a useful digital asset library rather than just an image folder. This follows DDIA maintainability principles by implementing operability through clear documentation, automated tools, and standardized processes that make the system easy to work with over time.

## Objectives
- Define naming convention: `site-program-location-year.format`
- Create WordPress media upload guidelines
- Implement automatic metadata validation
- Develop custom media library enhancement plugin
- Implement bulk metadata update tools
- Document standards and procedures
- Train content team on new standards

## Files to Create

### 1. `wp-content/mu-plugins/site-media-standards.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Media Metadata Standards Enforcement
 * Automatic validation and standardization
 */

// Enforce naming convention on upload
function site_enforce_media_naming($file) {
    $path_info = pathinfo($file['name']);
    $extension = strtolower($path_info['extension']);
    
    // Extract information from filename if possible
    $new_name = site_generate_standard_name($file['name'], $extension);
    
    $file['name'] = $new_name;
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'site_enforce_media_naming');

function site_generate_standard_name($original_name, $extension) {
    // Pattern: site-program-location-year.format
    // If filename doesn't match pattern, generate timestamp-based name
    $pattern = '/^site-[a-z-]+-[a-z-]+-\d{4}\.' . $extension . '$/i';
    
    if (!preg_match($pattern, $original_name)) {
        $timestamp = current_time('Y-m-d-His');
        return "site-upload-{$timestamp}.{$extension}";
    }
    
    return $original_name;
}

// Require metadata before publishing
function site_require_media_metadata($post_ID) {
    if (get_post_type($post_ID) !== 'attachment') return;
    
    $required_fields = array(
        'image_program',
        'image_location', 
        'image_year',
        'alt_text'
    );
    
    foreach ($required_fields as $field) {
        $value = get_field($field, $post_ID);
        if (empty($value)) {
            // Add admin notice
            add_action('admin_notices', function() use ($field) {
                echo '<div class="notice notice-warning"><p>Missing required metadata: ' . esc_html($field) . '</p></div>';
            });
        }
    }
}
add_action('add_attachment', 'site_require_media_metadata');

// Auto-generate alt text from title if missing
function site_auto_generate_alt_text($post_ID) {
    $alt_text = get_post_meta($post_ID, '_wp_attachment_image_alt', true);
    
    if (empty($alt_text)) {
        $post = get_post($post_ID);
        $title = $post->post_title;
        
        // Generate descriptive alt text from title
        $alt_text = site_generate_descriptive_alt($title);
        update_post_meta($post_ID, '_wp_attachment_image_alt', $alt_text);
    }
}
add_action('add_attachment', 'site_auto_generate_alt_text');

function site_generate_descriptive_alt($title) {
    // Convert title to descriptive alt text
    // Example: "Youth vocational training – Mwingi, Kitui County – 2022"
    // becomes: "Youth participating in vocational training in Mwingi, Kitui County"
    
    $alt = preg_replace('/–\s*\d{4}$/', '', $title); // Remove year
    $alt = str_replace('–', 'in', $alt); // Replace dash with 'in'
    $alt = preg_replace('/\s+/', ' ', $alt); // Normalize spaces
    
    return $alt;
}

// Validate metadata on media library save
function site_validate_media_metadata($post_ID) {
    if (get_post_type($post_ID) !== 'attachment') return;
    
    $validation_errors = array();
    
    // Check title format
    $post = get_post($post_ID);
    if (!preg_match('/^site-[a-z-]+-[a-z-]+-\d{4}$/', $post->post_title)) {
        $validation_errors[] = 'Title does not follow naming convention';
    }
    
    // Check required ACF fields
    $required_acf = array('image_program', 'image_location', 'image_year');
    foreach ($required_acf as $field) {
        if (empty(get_field($field, $post_ID))) {
            $validation_errors[] = "Missing required field: {$field}";
        }
    }
    
    if (!empty($validation_errors)) {
        // Prevent saving or add warning
        add_filter('redirect_post_location', function($location) use ($validation_errors) {
            return add_query_arg('media_validation_errors', implode(',', $validation_errors), $location);
        });
    }
}
add_action('edit_attachment', 'site_validate_media_metadata');
```

**Purpose**: Enforces media naming conventions and metadata requirements.

### 2. `wp-content/mu-plugins/site-media-bulk-tools.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Bulk Media Metadata Tools
 * Import/export and bulk update functionality
 */

// Add bulk action to media library
function site_add_bulk_media_actions($bulk_actions) {
    $bulk_actions['standardize_metadata'] = 'Standardize Metadata';
    $bulk_actions['generate_alt_text'] = 'Generate Alt Text';
    $bulk_actions['export_metadata'] = 'Export Metadata';
    return $bulk_actions;
}
add_filter('bulk_actions-upload', 'site_add_bulk_media_actions');

// Handle bulk actions
function site_handle_bulk_media_actions($redirect_to, $action, $post_ids) {
    if ($action === 'standardize_metadata') {
        foreach ($post_ids as $post_id) {
            site_standardize_single_media($post_id);
        }
        $redirect_to = add_query_arg('bulk_standardized', count($post_ids), $redirect_to);
    }
    
    if ($action === 'generate_alt_text') {
        foreach ($post_ids as $post_id) {
            site_auto_generate_alt_text($post_id);
        }
        $redirect_to = add_query_arg('bulk_alt_generated', count($post_ids), $redirect_to);
    }
    
    if ($action === 'export_metadata') {
        $export_data = site_export_media_metadata($post_ids);
        site_download_metadata_csv($export_data);
        return $redirect_to;
    }
    
    return $redirect_to;
}
add_filter('handle_bulk_actions-upload', 'site_handle_bulk_media_actions', 10, 3);

function site_standardize_single_media($post_id) {
    $post = get_post($post_id);
    
    // Standardize title
    $new_title = site_generate_standard_name($post->post_title, 'jpg');
    wp_update_post(array(
        'ID' => $post_id,
        'post_title' => $new_title
    ));
    
    // Ensure alt text exists
    site_auto_generate_alt_text($post_id);
    
    // Set default year if missing
    if (empty(get_field('image_year', $post_id))) {
        $upload_date = get_post_time('Y', false, $post_id);
        update_field('image_year', $upload_date, $post_id);
    }
}

function site_export_media_metadata($post_ids) {
    $export_data = array();
    
    foreach ($post_ids as $post_id) {
        $post = get_post($post_id);
        $export_data[] = array(
            'ID' => $post_id,
            'title' => $post->post_title,
            'alt_text' => get_post_meta($post_id, '_wp_attachment_image_alt', true),
            'program' => get_field('image_program', $post_id),
            'location' => get_field('image_location', $post_id),
            'year' => get_field('image_year', $post_id),
            'project' => get_field('related_project', $post_id),
            'caption' => $post->post_excerpt,
        );
    }
    
    return $export_data;
}

function site_download_metadata_csv($data) {
    $filename = 'site-media-metadata-' . date('Y-m-d') . '.csv';
    $filepath = sys_get_temp_dir() . '/' . $filename;
    
    $file = fopen($filepath, 'w');
    if (!empty($data)) {
        fputcsv($file, array_keys($data[0]));
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
    }
    fclose($file);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filepath);
    unlink($filepath);
    exit;
}

// Add admin notice for bulk actions
function site_bulk_media_admin_notices() {
    if (isset($_GET['bulk_standardized'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Standardized metadata for ' . intval($_GET['bulk_standardized']) . ' media items.</p></div>';
    }
    
    if (isset($_GET['bulk_alt_generated'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Generated alt text for ' . intval($_GET['bulk_alt_generated']) . ' media items.</p></div>';
    }
}
add_action('admin_notices', 'site_bulk_media_admin_notices');
```

**Purpose**: Provides bulk tools for media metadata management.

### 3. `wp-content/themes/site-child/admin/media-library-enhancements.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Media Library Admin Enhancements
 * Custom fields and validation UI
 */

// Add custom columns to media library
function site_media_library_columns($columns) {
    $columns['program'] = 'Program';
    $columns['location'] = 'Location';
    $columns['year'] = 'Year';
    $columns['validation'] = 'Validation';
    return $columns;
}
add_filter('manage_upload_columns', 'site_media_library_columns');

// Populate custom columns
function site_media_library_custom_column($column_name, $post_id) {
    switch ($column_name) {
        case 'program':
            $program = get_field('image_program', $post_id);
            echo $program ? esc_html($program) : '<span style="color:red">Missing</span>';
            break;
            
        case 'location':
            $location = get_field('image_location', $post_id);
            echo $location ? esc_html($location) : '<span style="color:red">Missing</span>';
            break;
            
        case 'year':
            $year = get_field('image_year', $post_id);
            echo $year ? esc_html($year) : '<span style="color:red">Missing</span>';
            break;
            
        case 'validation':
            $is_valid = site_validate_media_item($post_id);
            echo $is_valid ? '<span style="color:green">✓ Valid</span>' : '<span style="color:red">✗ Invalid</span>';
            break;
    }
}
add_action('manage_media_custom_column', 'site_media_library_custom_column', 10, 2);

function site_validate_media_item($post_id) {
    $post = get_post($post_id);
    
    // Check naming convention
    if (!preg_match('/^site-[a-z-]+-[a-z-]+-\d{4}$/', $post->post_title)) {
        return false;
    }
    
    // Check required fields
    $required = array('image_program', 'image_location', 'image_year');
    foreach ($required as $field) {
        if (empty(get_field($field, $post_id))) {
            return false;
        }
    }
    
    // Check alt text
    if (empty(get_post_meta($post_id, '_wp_attachment_image_alt', true))) {
        return false;
    }
    
    return true;
}

// Add media library filtering
function site_media_library_filter_links() {
    $screen = get_current_screen();
    if ($screen->id !== 'upload') return;
    
    $programs = get_terms(array('taxonomy' => 'site_program', 'hide_empty' => false));
    
    if (!empty($programs)) {
        echo '<select name="program_filter" id="program_filter">';
        echo '<option value="">All Programs</option>';
        foreach ($programs as $program) {
            echo '<option value="' . esc_attr($program->slug) . '">' . esc_html($program->name) . '</option>';
        }
        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'site_media_library_filter_links');

// Filter media library query
function site_filter_media_library_query($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    
    $screen = get_current_screen();
    if ($screen->id !== 'upload') return;
    
    if (isset($_GET['program_filter']) && !empty($_GET['program_filter'])) {
        $query->set('meta_query', array(
            array(
                'key' => 'image_program',
                'value' => sanitize_text_field($_GET['program_filter']),
                'compare' => '='
            )
        ));
    }
}
add_action('pre_get_posts', 'site_filter_media_library_query');
```

**Purpose**: Enhances media library with custom columns and filtering.

### 4. `Docs/MEDIA-STANDARDS.md`
**Location**: New file

**Content**:
```markdown
# SITE Media Standards Documentation

## Naming Convention
All media files must follow this pattern: `site-program-location-year.format`

### Examples:
- `site-women-enterprise-training-meru-2024.jpg`
- `site-youth-vocational-training-mwingi-2022.webp`
- `site-dairy-value-chain-garissa-2023.jpg`

## Required Metadata
Every image must include:

### WordPress Fields:
- **Title**: Descriptive title following naming convention
- **Caption**: Brief description of the image content
- **Alt Text**: Descriptive text for accessibility

### Custom ACF Fields:
- **Program**: Primary program category
- **Location**: Geographic location where image was taken
- **Year**: Year the image was created
- **Related Project**: Associated project (if applicable)

## Alt Text Guidelines
Alt text should be descriptive and contextual:
- **Good**: "Women participating in SITE business training in Meru County"
- **Bad**: "Image" or "Training photo"

## Image Specifications
- **Format**: Prefer WebP, fallback to JPG/PNG
- **Compression**: 85% quality for WebP, 80% for JPG
- **Dimensions**: Optimize for intended use (thumbnail, medium, large)
- **File Size**: Maximum 500KB for web use

## Bulk Operations
Use the bulk actions in Media Library to:
- Standardize metadata for multiple images
- Generate alt text automatically
- Export metadata for review

## Validation
Images will be automatically validated for:
- Naming convention compliance
- Required metadata completeness
- Alt text presence
- File format optimization
```

**Purpose**: Comprehensive documentation for media standards.

## Morning Tasks: Define Standards

### Step 1: Define Naming Convention
Establish the pattern: `site-program-location-year.format`
- Program: Use program slug (youth, women, dairy, etc.)
- Location: Use location slug (meru, mwingi, garissa, etc.)
- Year: 4-digit year
- Format: jpg, png, webp (prefer webp)

### Step 2: Create ACF Fields for Media
1. Go to Custom Fields → Add New
2. Create field group "Media Metadata"
3. Add fields:
   - Image Program (text field)
   - Image Location (text field)
   - Image Year (number field)
   - Related Project (relationship field)
4. Set location to "Attachment"

### Step 3: Create Media Standards Plugin
Create site-media-standards.php in mu-plugins directory.

### Step 4: Test Naming Convention
1. Upload a test image with proper name
2. Verify it passes validation
3. Upload an image with improper name
4. Verify it gets renamed automatically

## Afternoon Tasks: Tools & Documentation

### Step 1: Create Bulk Tools Plugin
Create site-media-bulk-tools.php in mu-plugins directory.

### Step 2: Create Admin Enhancements
Create media-library-enhancements.php in child theme admin directory.

### Step 3: Create Documentation
Create MEDIA-STANDARDS.md in Docs directory.

### Step 4: Test Bulk Operations
1. Go to Media Library
2. Select multiple images
3. Test "Standardize Metadata" bulk action
4. Test "Generate Alt Text" bulk action
5. Test "Export Metadata" bulk action

### Step 5: Test Media Library Enhancements
1. Check that custom columns appear
2. Test filtering by program
3. Verify validation status shows correctly
4. Test that invalid items are marked

### Step 6: Create Training Materials
Create simple guide for content team:
```markdown
# Media Upload Guide for SITE Team

## Step 1: Prepare Your Image
- Rename image to follow convention: site-program-location-year.jpg
- Example: site-women-training-meru-2024.jpg

## Step 2: Upload Image
1. Go to Media Library → Add New
2. Upload your prepared image
3. If name doesn't match convention, it will be auto-renamed

## Step 3: Add Metadata
1. Click on the uploaded image
2. Fill in required fields:
   - Program: Select from dropdown
   - Location: Enter location
   - Year: Enter year
   - Related Project: Select if applicable
3. Add descriptive caption
4. Review auto-generated alt text

## Step 4: Validate
1. Check that validation shows "✓ Valid"
2. If invalid, fix missing fields
3. Save changes

## Tips
- Use WebP format when possible
- Keep file size under 500KB
- Add descriptive alt text for accessibility
- Use consistent location names
```

## Deliverables Checklist

- [ ] Naming convention defined
- [ ] ACF fields created for media metadata
- [ ] Media standards plugin created
- [ ] Naming convention enforcement tested
- [ ] Bulk tools plugin created
- [ ] Bulk operations tested
- [ ] Admin enhancements created
- [ ] Custom columns working
- [ ] Filtering functionality tested
- [ ] Validation system working
- [ ] Documentation created
- [ ] Training materials created
- [ ] Team trained on new standards

## Risk Mitigation

### Potential Issues:
1. **Naming Convention Too Restrictive**
   - Mitigation: Provide auto-renaming for non-compliant names
   - Fallback: Allow manual override with admin notice

2. **Bulk Operations Timeout**
   - Mitigation: Process in batches of 50 images
   - Fallback: Use WP-CLI for large batches

3. **ACF Fields Not Saving**
   - Mitigation: Test field group configuration
   - Fallback: Use WordPress custom fields

## Success Criteria
- Naming convention enforced automatically
- Required metadata validation working
- Bulk operations functional
- Media library enhanced with custom columns
- Filtering by program working
- Documentation comprehensive
- Team trained and comfortable with new process

## Next Steps
After completing Day 6, proceed to [Day 7: Partners, Stories & Resources](DAY-07.md) to implement the partner carousel, story templates, and knowledge hub.