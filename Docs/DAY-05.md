# Day 5: Image Placeholder Replacement & Media Gallery (Priority #3 & #4)

## DDIA Focus: Scalability - Performance & Resource Management

## Overview
Day 5 addresses image placeholder replacement and builds an interactive media gallery. This follows DDIA scalability principles by implementing performance optimizations like WebP conversion, lazy loading, and efficient resource management to handle the large image library (1,647+ images) effectively.

## Objectives
- Run site-wide placeholder audit
- Replace placeholders with relevant images or remove components
- Implement image optimization pipeline (WebP/AVIF)
- Build filterable gallery with masonry layout
- Implement lightbox with metadata display
- Add keyboard navigation and mobile swipe support
- Ensure gallery scales with hundreds of photos

## Files to Create

### 1. `wp-content/mu-plugins/site-placeholder-replacement.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Placeholder Replacement Script
 * Site-wide audit and replacement of 850×450 placeholders
 */

function site_audit_placeholders() {
    global $wpdb;
    
    // Find all posts with placeholder images
    $placeholder_urls = array(
        'woocommerce-placeholder-850x479.png',
        'woocommerce-placeholder-850x567.png',
        'woocommerce-placeholder.png',
    );
    
    foreach ($placeholder_urls as $placeholder) {
        $posts_with_placeholder = $wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} 
            WHERE post_content LIKE %s 
            OR post_content LIKE %s",
            '%' . $placeholder . '%',
            '%' . str_replace('.png', '', $placeholder) . '%'
        ));
        
        foreach ($posts_with_placeholder as $post_id) {
            $post = get_post($post_id);
            $content = $post->post_content;
            
            // Log each placeholder found
            error_log("Placeholder found in post {$post_id}: {$post->post_title}");
            
            // Determine replacement strategy based on post type
            $replacement = site_get_replacement_image($post_id);
            
            if ($replacement) {
                $content = str_replace($placeholder, $replacement, $content);
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_content' => $content
                ));
            }
        }
    }
    
    return array(
        'found' => count($posts_with_placeholder),
        'replaced' => count($posts_with_placeholder) // Simplified for demo
    );
}

function site_get_replacement_image($post_id) {
    $post_type = get_post_type($post_id);
    
    // Return appropriate replacement based on context
    switch ($post_type) {
        case 'site_project':
            return get_field('project_featured_image', $post_id) ?: '';
        case 'site_story':
            return get_field('story_hero_image', $post_id) ?: '';
        default:
            return ''; // Remove placeholder if no replacement
    }
}

// Run audit and return results
function site_placeholder_audit() {
    return site_audit_placeholders();
}
```

**Purpose**: Automated placeholder detection and replacement system.

### 2. `wp-content/mu-plugins/site-image-optimization.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Image Optimization Pipeline
 * WebP/AVIF conversion, lazy loading, compression
 */

// Add WebP support
function site_add_webp_support($mime_types) {
    $mime_types['webp'] = 'image/webp';
    $mime_types['avif'] = 'image/avif';
    return $mime_types;
}
add_filter('upload_mimes', 'site_add_webp_support');

// Auto-convert uploaded images to WebP
function site_convert_to_webp($metadata) {
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['basedir'] . '/' . $metadata['file'];
    
    // Convert to WebP
    if (function_exists('imagewebp')) {
        $image_info = getimagesize($file_path);
        if ($image_info) {
            $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file_path);
            
            switch ($image_info[2]) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($file_path);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($file_path);
                    break;
                default:
                    return $metadata;
            }
            
            if ($image) {
                imagewebp($image, $webp_path, 85); // 85% quality
                imagedestroy($image);
                
                // Add WebP version to metadata
                $metadata['sizes']['webp'] = array(
                    'file' => basename($webp_path),
                    'width' => $image_info[0],
                    'height' => $image_info[1],
                    'mime-type' => 'image/webp'
                );
            }
        }
    }
    
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'site_convert_to_webp');

// Add lazy loading to all images
function site_add_lazy_loading($content) {
    // Add loading="lazy" to all images
    $content = preg_replace('/<img([^>]+)src=/i', '<img$1loading="lazy" src=', $content);
    return $content;
}
add_filter('the_content', 'site_add_lazy_loading');

// Serve WebP images when supported
function site_serve_webp($url, $attachment_id) {
    if (!isset($_SERVER['HTTP_ACCEPT']) || strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') === false) {
        return $url;
    }
    
    $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $url);
    $webp_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $webp_url);
    
    if (file_exists($webp_path)) {
        return $webp_url;
    }
    
    return $url;
}
add_filter('wp_get_attachment_url', 'site_serve_webp', 10, 2);
```

**Purpose**: Automatic image optimization pipeline for performance.

### 3. `wp-content/themes/site-child/template-parts/media-gallery.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Interactive Media Gallery
 * Filterable gallery with lightbox and metadata
 */

$gallery_filters = array(
    'all' => 'All',
    'youth' => 'Youth',
    'women' => 'Women',
    'dairy' => 'Dairy',
    'camel-milk' => 'Camel Milk',
    'beekeeping' => 'Beekeeping',
    'soapstone' => 'Soapstone',
    'agriculture' => 'Agriculture',
    'climate' => 'Climate',
    'informal-economy' => 'Informal Economy',
);
?>

<section class="media-gallery-section">
    <div class="container">
        <h1 class="gallery-title">SITE Media Gallery</h1>
        
        <div class="gallery-filters">
            <?php foreach ($gallery_filters as $slug => $label) : ?>
                <button class="gallery-filter-btn <?php echo $slug === 'all' ? 'active' : ''; ?>" 
                        data-filter="<?php echo esc_attr($slug); ?>">
                    <?php echo esc_html($label); ?>
                </button>
            <?php endforeach; ?>
        </div>
        
        <div class="gallery-grid masonry-layout">
            <?php
            $gallery_images = get_posts(array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'posts_per_page' => 50,
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            
            foreach ($gallery_images as $image) :
                $image_meta = wp_get_attachment_metadata($image->ID);
                $image_programs = get_the_terms($image->ID, 'site_program');
                $image_location = get_field('image_location', $image->ID);
                $image_year = get_field('image_year', $image->ID);
                $image_project = get_field('related_project', $image->ID);
            ?>
                <div class="gallery-item" 
                     data-program="<?php echo $image_programs ? esc_attr($image_programs[0]->slug) : 'all'; ?>"
                     data-id="<?php echo esc_attr($image->ID); ?>">
                    <img src="<?php echo esc_url($image->guid); ?>" 
                         alt="<?php echo esc_attr($image->post_title); ?>"
                         loading="lazy">
                    <div class="gallery-item-overlay">
                        <span class="gallery-item-title"><?php echo esc_html($image->post_title); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="gallery-load-more">
            <button class="load-more-btn">Load More Images</button>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="gallery-lightbox" id="gallery-lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close">&times;</button>
        <button class="lightbox-prev">❮</button>
        <button class="lightbox-next">❯</button>
        
        <div class="lightbox-image-container">
            <img src="" alt="" class="lightbox-image">
        </div>
        
        <div class="lightbox-metadata">
            <h3 class="lightbox-title"></h3>
            <div class="lightbox-details">
                <span class="lightbox-program"></span>
                <span class="lightbox-location"></span>
                <span class="lightbox-year"></span>
                <span class="lightbox-project"></span>
            </div>
            <p class="lightbox-caption"></p>
        </div>
    </div>
</div>
```

**Purpose**: Interactive media gallery with filtering and lightbox functionality.

### 4. `wp-content/themes/site-child/assets/css/gallery.css`
**Location**: New file

**Content**:
```css
/* Gallery Performance Optimizations */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 30px;
}

.masonry-layout {
    grid-template-rows: masonry;
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    aspect-ratio: 4/3;
    cursor: pointer;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.gallery-item-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    padding: 20px;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-item-overlay {
    opacity: 1;
}

/* Lightbox Styles */
.gallery-lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.95);
    z-index: 10000;
    justify-content: center;
    align-items: center;
}

.gallery-lightbox.active {
    display: flex;
}

.lightbox-content {
    max-width: 90%;
    max-height: 90%;
    display: flex;
    flex-direction: column;
}

.lightbox-image-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.lightbox-image {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
}

.lightbox-metadata {
    background: white;
    padding: 20px;
    margin-top: 20px;
    border-radius: 8px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
    
    .lightbox-image {
        max-height: 50vh;
    }
}
```

**Purpose**: Gallery styles with performance optimizations and responsive design.

### 5. `wp-content/themes/site-child/assets/js/gallery.js`
**Location**: New file

**Content**:
```javascript
/**
 * Gallery Interactions
 * Lightbox, filtering, lazy loading, keyboard navigation
 */

document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImage = lightbox.querySelector('.lightbox-image');
    const lightboxTitle = lightbox.querySelector('.lightbox-title');
    const lightboxCaption = lightbox.querySelector('.lightbox-caption');
    const lightboxProgram = lightbox.querySelector('.lightbox-program');
    const lightboxLocation = lightbox.querySelector('.lightbox-location');
    const lightboxYear = lightbox.querySelector('.lightbox-year');
    const lightboxProject = lightbox.querySelector('.lightbox-project');
    
    let currentIndex = 0;
    let filteredItems = Array.from(galleryItems);
    
    // Open lightbox
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            currentIndex = filteredItems.indexOf(item);
            openLightbox(item);
        });
    });
    
    function openLightbox(item) {
        const img = item.querySelector('img');
        const title = item.querySelector('.gallery-item-title').textContent;
        
        // Get metadata from data attributes or AJAX
        const metadata = {
            program: item.dataset.program,
            location: item.dataset.location,
            year: item.dataset.year,
            project: item.dataset.project,
            caption: item.dataset.caption
        };
        
        lightboxImage.src = img.src;
        lightboxTitle.textContent = title;
        lightboxCaption.textContent = metadata.caption || '';
        lightboxProgram.textContent = metadata.program || '';
        lightboxLocation.textContent = metadata.location || '';
        lightboxYear.textContent = metadata.year || '';
        lightboxProject.textContent = metadata.project || '';
        
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Close lightbox
    lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
    
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Navigation
    lightbox.querySelector('.lightbox-prev').addEventListener('click', function() {
        currentIndex = (currentIndex - 1 + filteredItems.length) % filteredItems.length;
        openLightbox(filteredItems[currentIndex]);
    });
    
    lightbox.querySelector('.lightbox-next').addEventListener('click', function() {
        currentIndex = (currentIndex + 1) % filteredItems.length;
        openLightbox(filteredItems[currentIndex]);
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') lightbox.querySelector('.lightbox-prev').click();
        if (e.key === 'ArrowRight') lightbox.querySelector('.lightbox-next').click();
    });
    
    // Mobile swipe
    let touchStartX = 0;
    let touchEndX = 0;
    
    lightbox.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    lightbox.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        if (touchEndX < touchStartX - 50) lightbox.querySelector('.lightbox-next').click();
        if (touchEndX > touchStartX + 50) lightbox.querySelector('.lightbox-prev').click();
    }
    
    // Gallery filtering
    const filterButtons = document.querySelectorAll('.gallery-filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            filteredItems = Array.from(galleryItems).filter(item => {
                if (filter === 'all') return true;
                return item.dataset.program === filter;
            });
            
            galleryItems.forEach(item => {
                if (filteredItems.includes(item)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
```

**Purpose**: Gallery JavaScript with full interaction support.

## Morning Tasks: Placeholder Audit & Replacement

### Step 1: Run Placeholder Audit
Create a simple script to audit placeholders:
```php
// Add to functions.php temporarily
add_action('admin_init', function() {
    if (isset($_GET['run_placeholder_audit'])) {
        $results = site_audit_placeholders();
        echo '<pre>' . print_r($results, true) . '</pre>';
        exit;
    }
});
```

Visit: `/wp-admin/?run_placeholder_audit=1`

### Step 2: Analyze Results
- Count how many placeholders found
- Identify which pages/posts contain them
- Determine replacement strategy for each

### Step 3: Replace Placeholders
For each placeholder found:
- If relevant SITE image exists, use it
- If no relevant image, remove the component
- If component is essential, use branded graphic

### Step 4: Verify Changes
- Check each page where placeholders were found
- Ensure layout still works without placeholders
- Test responsive design

## Afternoon Tasks: Gallery Implementation

### Step 1: Create Image Optimization Plugin
Create site-image-optimization.php in mu-plugins directory.

### Step 2: Test WebP Conversion
1. Upload a test image
2. Verify WebP version is created
3. Check that WebP is served to supported browsers

### Step 3: Create Gallery Template
Create media-gallery.php template part.

### Step 4: Create Gallery Page
1. Create new page "Media Gallery"
2. Add gallery template part to page
3. Set up page template if needed

### Step 5: Create Gallery Assets
Create gallery.css and gallery.js with the content above.

### Step 6: Enqueue Gallery Assets
Add to child theme functions.php:
```php
function site_gallery_assets() {
    if (is_page('media-gallery')) {
        wp_enqueue_style('site-gallery', 
            get_stylesheet_directory_uri() . '/assets/css/gallery.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script('site-gallery',
            get_stylesheet_directory_uri() . '/assets/js/gallery.js',
            array(),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'site_gallery_assets');
```

### Step 7: Add Metadata to Images
1. Go to Media Library
2. Add program, location, year metadata to sample images
3. This enables filtering and metadata display

### Step 8: Test Gallery Functionality
1. Load gallery page
2. Test filtering by program
3. Click images to open lightbox
4. Test keyboard navigation
5. Test mobile swipe
6. Verify metadata displays correctly

## Deliverables Checklist

- [ ] Placeholder audit completed
- [ ] All placeholders replaced or removed
- [ ] Image optimization plugin created
- [ ] WebP conversion tested
- [ ] Gallery template created
- [ ] Gallery page created
- [ ] Gallery CSS created
- [ ] Gallery JavaScript created
- [ ] Lightbox functionality working
- [ ] Filtering system working
- [ ] Keyboard navigation tested
- [ ] Mobile swipe tested
- [ ] Metadata display working
- [ ] Performance optimized with lazy loading

## Risk Mitigation

### Potential Issues:
1. **WebP Conversion Fails**
   - Mitigation: Check PHP GD library support
   - Fallback: Serve original images

2. **Gallery Performance with Many Images**
   - Mitigation: Implement pagination and lazy loading
   - Fallback: Limit initial load to 50 images

3. **Lightbox Not Working**
   - Mitigation: Check JavaScript console for errors
   - Fallback: Use simple image viewer

## Success Criteria
- All placeholder images replaced or removed
- WebP conversion working for new uploads
- Gallery loads quickly with lazy loading
- Filtering works smoothly
- Lightbox displays correctly with metadata
- Keyboard navigation functional
- Mobile swipe gestures working
- Gallery scales with large image libraries

## Next Steps
After completing Day 5, proceed to [Day 6: Media Metadata Standards](DAY-06.md) to establish comprehensive media naming and metadata standards.