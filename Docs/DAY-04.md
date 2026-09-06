# Day 4: "Our Work" Landing Page Rebuild (Priority #2)

## DDIA Focus: Data Model Design - Matching Access Patterns

## Overview
Day 4 rebuilds the "Our Work" landing page, removing broken SiteOrigin components and implementing reusable project components. This follows DDIA data model design principles by creating an architecture that matches actual access patterns - users filter and browse projects by program, location, and theme.

## Objectives
- Remove broken SiteOrigin widget references
- Build reusable project card component
- Implement AJAX filtering system
- Create program cards with featured stories
- Add project filtering with performance optimization
- Ensure page scales as new projects are added

## Files to Create

### 1. `wp-content/themes/site-child/page-our-work.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Template Name: Our Work
 * Description: Landing page for SITE's programs and projects
 */

get_header(); ?>

<div class="our-work-page">
    <?php
    // Page Header
    get_template_part( 'template-parts/page-header' );
    
    // Four Program Cards
    get_template_part( 'template-parts/program-cards' );
    
    // Project Filters and Grid
    get_template_part( 'template-parts/project-filters' );
    get_template_part( 'template-parts/project-grid' );
    ?>
</div>

<?php get_footer(); ?>
```

**Purpose**: Main "Our Work" page template using modular components.

### 2. `wp-content/themes/site-child/template-parts/program-cards.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Four Large Program Cards
 * Each with: Photo, explanation, featured story, CTA
 */

$programs = get_terms( array(
    'taxonomy' => 'site_program',
    'hide_empty' => false,
    'orderby' => 'menu_order',
) );
?>

<section class="program-cards-section">
    <div class="container">
        <div class="section-intro">
            <h1 class="page-title">Our Work</h1>
            <p class="page-subtitle">Transforming livelihoods through skills, enterprise and resilient communities.</p>
        </div>
        
        <div class="program-cards-grid">
            <?php foreach ( $programs as $program ) : 
                $program_data = get_term_meta( $program->term_id, 'program_data', true );
                $featured_story = get_field( 'featured_story', 'site_program_' . $program->term_id );
                $impact_stat = get_field( 'impact_statistic', 'site_program_' . $program->term_id );
            ?>
                <div class="program-card" data-program="<?php echo esc_attr( $program->slug ); ?>">
                    <div class="program-card-image">
                        <img src="<?php echo esc_url( $program_data['image'] ); ?>" 
                             alt="<?php echo esc_attr( $program->name ); ?>"
                             loading="lazy">
                    </div>
                    <div class="program-card-content">
                        <h2 class="program-card-title"><?php echo esc_html( $program->name ); ?></h2>
                        <p class="program-card-description"><?php echo esc_html( $program_data['description'] ); ?></p>
                        
                        <?php if ( $impact_stat ) : ?>
                            <div class="program-impact">
                                <span class="impact-number"><?php echo esc_html( $impact_stat['number'] ); ?></span>
                                <span class="impact-label"><?php echo esc_html( $impact_stat['label'] ); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $featured_story ) : ?>
                            <div class="program-featured-story">
                                <span class="story-label">Featured Story:</span>
                                <a href="<?php echo get_permalink( $featured_story ); ?>" class="story-link">
                                    <?php echo get_the_title( $featured_story ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?php echo get_term_link( $program ); ?>" class="program-card-cta">
                            Explore Our Work
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

**Purpose**: Four large program cards with images, descriptions, impact stats, and featured stories.

### 3. `wp-content/themes/site-child/template-parts/project-filters.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Project Filters
 * AJAX-based filtering: All, Youth, Women, Dairy, Camel Milk, etc.
 */

$filter_categories = array(
    'all' => 'All Projects',
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

<section class="project-filters-section">
    <div class="container">
        <h2 class="section-title">Explore All Projects</h2>
        <div class="filter-buttons">
            <?php foreach ( $filter_categories as $slug => $label ) : ?>
                <button class="filter-button <?php echo $slug === 'all' ? 'active' : ''; ?>" 
                        data-filter="<?php echo esc_attr( $slug ); ?>">
                    <?php echo esc_html( $label ); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

**Purpose**: Filter buttons for AJAX-based project filtering.

### 4. `wp-content/themes/site-child/template-parts/project-grid.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Project Grid
 * Reusable project component with AJAX loading
 */

$args = array(
    'post_type' => 'site_project',
    'posts_per_page' => 12,
    'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
);

$projects = new WP_Query( $args );
?>

<section class="project-grid-section">
    <div class="container">
        <div class="projects-grid" id="projects-grid">
            <?php if ( $projects->have_posts() ) : ?>
                <?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
                    <?php get_template_part( 'template-parts/project-card' ); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="no-projects">No projects found.</p>
            <?php endif; ?>
        </div>
        
        <?php if ( $projects->max_num_pages > 1 ) : ?>
            <div class="pagination">
                <?php 
                echo paginate_links( array(
                    'total' => $projects->max_num_pages,
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                ) );
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>
```

**Purpose**: Project grid with pagination and AJAX loading support.

### 5. `wp-content/themes/site-child/template-parts/project-card.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Single Project Card Component
 * Reusable component for project display
 */

$project_programs = get_the_terms( get_the_ID(), 'site_program' );
$project_location = get_the_terms( get_the_ID(), 'site_location' );
?>

<div class="project-card" data-id="<?php the_ID(); ?>">
    <div class="project-card-image">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium' ); ?>
        </a>
    </div>
    <div class="project-card-content">
        <?php if ( $project_programs ) : ?>
            <span class="project-program"><?php echo esc_html( $project_programs[0]->name ); ?></span>
        <?php endif; ?>
        
        <h3 class="project-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php if ( $project_location ) : ?>
            <span class="project-location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo esc_html( $project_location[0]->name ); ?>
            </span>
        <?php endif; ?>
        
        <p class="project-excerpt"><?php the_excerpt(); ?></p>
        
        <a href="<?php the_permalink(); ?>" class="project-cta">View Project</a>
    </div>
</div>
```

**Purpose**: Reusable project card component for consistent display.

### 6. `wp-content/themes/site-child/assets/css/our-work.css`
**Location**: New file

**Content**:
```css
/* Our Work Page Styles */
.program-cards-section {
    padding: 60px 0;
}

.section-intro {
    text-align: center;
    margin-bottom: 60px;
}

.page-title {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.2rem;
    color: #666;
}

.program-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.program-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.program-card:hover {
    transform: translateY(-5px);
}

.program-card-image {
    height: 200px;
    overflow: hidden;
}

.program-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.program-card-content {
    padding: 20px;
}

.program-card-title {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.program-impact {
    background: #f5f5f5;
    padding: 10px;
    border-radius: 4px;
    margin: 15px 0;
}

.impact-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #9fc612;
}

.project-filters-section {
    background: #f9f9f9;
    padding: 40px 0;
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.filter-button {
    padding: 10px 20px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-button:hover,
.filter-button.active {
    background: #9fc612;
    border-color: #9fc612;
    color: white;
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.project-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.project-card-image {
    height: 200px;
    overflow: hidden;
}

.project-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.project-card-content {
    padding: 20px;
}

.project-program {
    background: #9fc612;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    display: inline-block;
    margin-bottom: 10px;
}

.project-title {
    font-size: 1.2rem;
    margin-bottom: 10px;
}

.project-title a {
    color: #333;
    text-decoration: none;
}

.project-location {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 10px;
}
```

**Purpose**: Styles for program cards, filters, and project grid.

### 7. `wp-content/themes/site-child/assets/js/project-filters.js`
**Location**: New file

**Content**:
```javascript
/**
 * Project AJAX Filtering
 * Efficient filtering without page reloads
 */

document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-button');
    const projectsGrid = document.getElementById('projects-grid');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            // AJAX request
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'filter_projects',
                    filter: filter,
                    nonce: '<?php echo wp_create_nonce('filter_projects'); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    projectsGrid.innerHTML = data.data.html;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
```

**Purpose**: JavaScript for AJAX project filtering.

### 8. `wp-content/mu-plugins/site-ajax-filters.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * AJAX Filter Handler
 * Efficient server-side filtering
 */

add_action('wp_ajax_filter_projects', 'site_filter_projects');
add_action('wp_ajax_nopriv_filter_projects', 'site_filter_projects');

function site_filter_projects() {
    check_ajax_referer('filter_projects', 'nonce');
    
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : 'all';
    
    $args = array(
        'post_type' => 'site_project',
        'posts_per_page' => 12,
    );
    
    if ($filter !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'site_theme',
                'field' => 'slug',
                'terms' => $filter,
            ),
        );
    }
    
    $projects = new WP_Query($args);
    
    ob_start();
    if ($projects->have_posts()) {
        while ($projects->have_posts()) {
            $projects->the_post();
            get_template_part('template-parts/project-card');
        }
    } else {
        echo '<p class="no-projects">No projects found.</p>';
    }
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}
```

**Purpose**: Server-side AJAX handler for project filtering.

## Morning Tasks: Remove Broken Components

### Step 1: Identify Broken SiteOrigin Widgets
1. Go to Pages → Our Work
2. Edit the page
3. Identify SiteOrigin widget references
4. Note any empty headings or unrelated content

### Step 2: Remove Broken Components
1. Switch to text editor mode
2. Remove SiteOrigin widget shortcodes
3. Remove empty headings
4. Remove unrelated content (landscaping references)
5. Clean up HTML structure

### Step 3: Set Page Template
1. Set template to "Our Work"
2. Save page
3. Preview to verify clean structure

## Afternoon Tasks: Build Reusable Components

### Step 1: Create All Template Parts
Create all template part files listed above.

### Step 2: Create CSS and JS Files
Create our-work.css and project-filters.js with the content above.

### Step 3: Enqueue Assets
Add to child theme functions.php:
```php
function site_our_work_assets() {
    if ( is_page_template('page-our-work.php') ) {
        wp_enqueue_style( 'site-our-work', 
            get_stylesheet_directory_uri() . '/assets/css/our-work.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script( 'site-project-filters',
            get_stylesheet_directory_uri() . '/assets/js/project-filters.js',
            array(),
            '1.0.0',
            true
        );
        
        wp_localize_script( 'site-project-filters', 'siteFilters', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('filter_projects')
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'site_our_work_assets' );
```

### Step 4: Create AJAX Handler
Create site-ajax-filters.php in mu-plugins directory.

### Step 5: Add Theme Tags to Projects
1. Go to a few existing projects
2. Add theme tags (youth, women, dairy, etc.)
3. This enables filtering functionality

### Step 6: Test Filtering
1. Load the Our Work page
2. Click different filter buttons
3. Verify AJAX loading works
4. Check that project cards display correctly

### Step 7: Test Responsiveness
1. Test on mobile devices
2. Verify program cards stack correctly
3. Check filter buttons are touch-friendly
4. Ensure project grid adapts to screen size

## Deliverables Checklist

- [ ] Broken SiteOrigin widgets removed
- [ ] Unrelated content cleaned up
- [ ] Our Work page template created
- [ ] Program cards component created
- [ ] Project filters component created
- [ ] Project grid component created
- [ ] Reusable project card component created
- [ ] AJAX filtering implemented
- [ ] CSS styles created
- [ ] JavaScript functionality working
- [ ] AJAX handler created
- [ ] Filtering tested and working
- [ ] Responsive design verified

## Risk Mitigation

### Potential Issues:
1. **AJAX Filtering Not Working**
   - Mitigation: Verify nonce and AJAX endpoint
   - Fallback: Use page reload filtering

2. **Project Cards Not Displaying**
   - Mitigation: Check template part path
   - Fallback: Use inline template code

3. **Performance Issues with Many Projects**
   - Mitigation: Implement pagination and caching
   - Fallback: Limit initial load to 12 projects

## Success Criteria
- Page loads without broken widget references
- Program cards display correctly with all required data
- AJAX filtering works smoothly without page reloads
- Project cards are reusable across the site
- Page scales as new projects are added
- Responsive design works on all devices
- Performance remains acceptable with many projects

## Next Steps
After completing Day 4, proceed to [Day 5: Image Placeholder Replacement & Media Gallery](DAY-05.md) to address image optimization and build the interactive media gallery.