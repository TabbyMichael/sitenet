# Day 7: Partners, Stories & Resources (Priority #6, #7, #8)

## DDIA Focus: Evolvability - Modular Design

## Overview
Day 7 implements the partner carousel system, story page templates, and knowledge hub interface. This follows DDIA evolvability principles by creating modular, reusable components that can be easily extended and modified as SITE's content needs grow over time.

## Objectives
- Design responsive logo carousel with external/partner page options
- Implement partner relationship database schema
- Redesign story pages with hero, impact statement, program tags
- Build knowledge hub with filterable resources
- Implement resource download system
- Add related content recommendations

## Files to Create

### 1. `wp-content/themes/site-child/single-site_partner.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Single Partner Template
 * Option A: External link or Option B: SITE relationship page
 */

get_header(); ?>

<div class="single-partner">
    <?php while (have_posts()) : the_post(); ?>
        
        <?php
        $partner_logo = get_field('partner_logo');
        $partner_website = get_field('partner_website');
        $partner_link_type = get_field('partner_link_type');
        $partner_projects = get_field('related_projects');
        $partner_period = get_field('partnership_period');
        $partner_focus = get_field('partnership_focus');
        ?>
        
        <article class="partner-article">
            <header class="partner-header">
                <div class="partner-logo">
                    <img src="<?php echo esc_url($partner_logo); ?>" alt="<?php the_title(); ?>">
                </div>
                <h1 class="partner-title"><?php the_title(); ?></h1>
                
                <?php if ($partner_link_type === 'external' && $partner_website) : ?>
                    <a href="<?php echo esc_url($partner_website); ?>" class="partner-external-link" target="_blank">
                        Visit Website <i class="fas fa-external-link-alt"></i>
                    </a>
                <?php endif; ?>
            </header>
            
            <div class="partner-content">
                <?php the_content(); ?>
                
                <?php if ($partner_link_type === 'relationship') : ?>
                    <div class="partner-relationship-details">
                        <h2>Partnership Details</h2>
                        
                        <?php if ($partner_period) : ?>
                            <div class="partner-detail">
                                <span class="detail-label">Period:</span>
                                <span class="detail-value"><?php echo esc_html($partner_period); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($partner_focus) : ?>
                            <div class="partner-detail">
                                <span class="detail-label">Focus:</span>
                                <span class="detail-value"><?php echo esc_html($partner_focus); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($partner_projects) : ?>
                            <div class="partner-projects">
                                <h3>Projects Worked On</h3>
                                <ul class="projects-list">
                                    <?php foreach ($partner_projects as $project) : ?>
                                        <li>
                                            <a href="<?php echo get_permalink($project->ID); ?>">
                                                <?php echo get_the_title($project->ID); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>
        
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
```

**Purpose**: Single partner template with external link or relationship page options.

### 2. `wp-content/themes/site-child/single-site_story.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Single Story Template
 * Hero photograph, impact statement, program tags, readable typography
 */

get_header(); ?>

<div class="single-story">
    <?php while (have_posts()) : the_post(); ?>
        
        <?php
        $story_hero = get_field('hero_image');
        $story_impact = get_field('impact_statement');
        $story_program = get_field('primary_program');
        $story_location = get_field('story_location');
        $story_year = get_field('story_year');
        $story_author = get_field('story_author');
        ?>
        
        <article class="story-article">
            <!-- Hero Section -->
            <header class="story-hero" style="background-image: url('<?php echo esc_url($story_hero); ?>');">
                <div class="hero-overlay">
                    <div class="container">
                        <div class="story-hero-content">
                            <h1 class="story-title"><?php the_title(); ?></h1>
                            
                            <?php if ($story_impact) : ?>
                                <p class="story-impact"><?php echo esc_html($story_impact); ?></p>
                            <?php endif; ?>
                            
                            <div class="story-meta">
                                <?php if ($story_program) : ?>
                                    <span class="story-program"><?php echo esc_html($story_program->name); ?></span>
                                <?php endif; ?>
                                
                                <?php if ($story_location) : ?>
                                    <span class="story-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo esc_html($story_location); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($story_year) : ?>
                                    <span class="story-year"><?php echo esc_html($story_year); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Story Content -->
            <div class="story-content-wrapper">
                <div class="container">
                    <div class="story-main-content">
                        <?php if ($story_author) : ?>
                            <div class="story-author">
                                <span class="author-label">By:</span>
                                <span class="author-name"><?php echo esc_html($story_author); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="story-body">
                            <?php
                            the_content();
                            
                            // Supporting images from gallery
                            $story_gallery = get_field('story_gallery');
                            if ($story_gallery) : ?>
                                <div class="story-gallery">
                                    <?php foreach ($story_gallery as $image) : ?>
                                        <figure class="story-gallery-item">
                                            <img src="<?php echo esc_url($image['url']); ?>" 
                                                 alt="<?php echo esc_attr($image['alt']); ?>"
                                                 loading="lazy">
                                            <?php if ($image['caption']) : ?>
                                                <figcaption><?php echo esc_html($image['caption']); ?></figcaption>
                                            <?php endif; ?>
                                        </figure>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Impact Stat Boxes -->
                        <?php
                        $impact_stats = get_field('impact_statistics');
                        if ($impact_stats) : ?>
                            <div class="story-impact-stats">
                                <h3>Impact</h3>
                                <div class="impact-stats-grid">
                                    <?php foreach ($impact_stats as $stat) : ?>
                                        <div class="impact-stat-box">
                                            <div class="stat-number"><?php echo esc_html($stat['number']); ?></div>
                                            <div class="stat-label"><?php echo esc_html($stat['label']); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Share Buttons -->
                        <div class="story-share">
                            <h3>Share This Story</h3>
                            <?php echo do_shortcode('[share_buttons]'); ?>
                        </div>
                        
                        <!-- Partnership CTA -->
                        <div class="story-cta">
                            <h3>Support SITE's Work</h3>
                            <p>Help us continue transforming livelihoods across Kenya.</p>
                            <a href="/contact/" class="cta-button">Partner with SITE</a>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="story-sidebar">
                        <?php
                        // Related stories from same program
                        $related_stories = get_posts(array(
                            'post_type' => 'site_story',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'site_program',
                                    'field' => 'term_id',
                                    'terms' => $story_program->term_id,
                                ),
                            ),
                        ));
                        
                        if ($related_stories) : ?>
                            <div class="related-stories">
                                <h3>More Stories from <?php echo esc_html($story_program->name); ?></h3>
                                <ul class="related-stories-list">
                                    <?php foreach ($related_stories as $story) : ?>
                                        <li>
                                            <a href="<?php echo get_permalink($story->ID); ?>">
                                                <?php echo get_the_title($story->ID); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </aside>
                </div>
            </div>
        </article>
        
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
```

**Purpose**: Enhanced story template with hero, impact statement, and readable typography.

### 3. `wp-content/themes/site-child/single-site_resource.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Single Resource Template
 * Download/view functionality with metadata
 */

get_header(); ?>

<div class="single-resource">
    <?php while (have_posts()) : the_post(); ?>
        
        <?php
        $resource_type = get_field('resource_type');
        $resource_date = get_field('publication_date');
        $related_project = get_field('related_project');
        $resource_program = get_field('primary_program');
        $resource_file = get_field('resource_file');
        $external_link = get_field('external_link');
        ?>
        
        <article class="resource-article">
            <header class="resource-header">
                <div class="resource-meta">
                    <?php if ($resource_type) : ?>
                        <span class="resource-type"><?php echo esc_html($resource_type); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($resource_date) : ?>
                        <span class="resource-date"><?php echo esc_html($resource_date); ?></span>
                    <?php endif; ?>
                </div>
                
                <h1 class="resource-title"><?php the_title(); ?></h1>
                
                <div class="resource-details">
                    <?php if ($resource_program) : ?>
                        <span class="resource-program"><?php echo esc_html($resource_program->name); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($related_project) : ?>
                        <span class="resource-project">
                            Project: <?php echo esc_html(get_the_title($related_project->ID)); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>
            
            <div class="resource-content">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="resource-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="resource-description">
                    <?php the_content(); ?>
                </div>
                
                <div class="resource-actions">
                    <?php if ($resource_file) : ?>
                        <a href="<?php echo esc_url($resource_file); ?>" class="btn-download" download>
                            Download PDF
                        </a>
                    <?php elseif ($external_link) : ?>
                        <a href="<?php echo esc_url($external_link); ?>" class="btn-download" target="_blank">
                            View External Resource
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
```

**Purpose**: Single resource template with download functionality.

### 4. `wp-content/themes/site-child/archive-site_resource.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Resources Archive - Knowledge Hub
 * Filterable resource library with cards
 */

get_header(); ?>

<div class="resources-archive">
    <div class="container">
        <header class="archive-header">
            <h1 class="archive-title">Knowledge & Resources</h1>
            <p class="archive-subtitle">Research, case studies, and insights from SITE's work</p>
        </header>
        
        <!-- Resource Filters -->
        <div class="resource-filters">
            <?php
            $resource_types = get_terms(array(
                'taxonomy' => 'site_resource_type',
                'hide_empty' => false,
            ));
            
            $programs = get_terms(array(
                'taxonomy' => 'site_program',
                'hide_empty' => false,
            ));
            ?>
            
            <div class="filter-group">
                <label>Resource Type:</label>
                <select id="resource-type-filter">
                    <option value="">All Types</option>
                    <?php foreach ($resource_types as $type) : ?>
                        <option value="<?php echo esc_attr($type->slug); ?>">
                            <?php echo esc_html($type->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Program:</label>
                <select id="program-filter">
                    <option value="">All Programs</option>
                    <?php foreach ($programs as $program) : ?>
                        <option value="<?php echo esc_attr($program->slug); ?>">
                            <?php echo esc_html($program->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Search:</label>
                <input type="text" id="resource-search" placeholder="Search resources...">
            </div>
        </div>
        
        <!-- Resource Grid -->
        <div class="resources-grid" id="resources-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/resource-card'); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="no-resources">No resources found.</p>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php the_posts_pagination(); ?>
    </div>
</div>

<?php get_footer(); ?>
```

**Purpose**: Resources archive with filtering capabilities.

### 5. `wp-content/themes/site-child/template-parts/resource-card.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Resource Card Component
 * Thumbnail, title, date, type, project, program, description, buttons
 */

$resource_type = get_field('resource_type');
$resource_date = get_field('publication_date');
$related_project = get_field('related_project');
$resource_program = get_field('primary_program');
$resource_file = get_field('resource_file');
$external_link = get_field('external_link');
?>

<div class="resource-card" data-id="<?php the_ID(); ?>">
    <div class="resource-thumbnail">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        <?php else : ?>
            <div class="resource-placeholder">
                <i class="fas fa-file-alt"></i>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="resource-content">
        <div class="resource-meta">
            <?php if ($resource_type) : ?>
                <span class="resource-type"><?php echo esc_html($resource_type); ?></span>
            <?php endif; ?>
            
            <?php if ($resource_date) : ?>
                <span class="resource-date"><?php echo esc_html($resource_date); ?></span>
            <?php endif; ?>
        </div>
        
        <h3 class="resource-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <div class="resource-details">
            <?php if ($resource_program) : ?>
                <span class="resource-program"><?php echo esc_html($resource_program->name); ?></span>
            <?php endif; ?>
            
            <?php if ($related_project) : ?>
                <span class="resource-project">
                    Project: <?php echo esc_html(get_the_title($related_project->ID)); ?>
                </span>
            <?php endif; ?>
        </div>
        
        <p class="resource-description"><?php the_excerpt(); ?></p>
        
        <div class="resource-actions">
            <a href="<?php the_permalink(); ?>" class="btn-view">View Details</a>
            
            <?php if ($resource_file) : ?>
                <a href="<?php echo esc_url($resource_file); ?>" class="btn-download" download>
                    Download PDF
                </a>
            <?php elseif ($external_link) : ?>
                <a href="<?php echo esc_url($external_link); ?>" class="btn-download" target="_blank">
                    View External
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
```

**Purpose**: Reusable resource card component.

### 6. `wp-content/themes/site-child/assets/css/stories.css`
**Location**: New file

**Content**:
```css
/* Story Typography - Readability Focus */
.story-article {
    max-width: 1200px;
    margin: 0 auto;
}

.story-hero {
    min-height: 60vh;
    background-size: cover;
    background-position: center;
    position: relative;
    color: white;
}

.story-hero-content {
    padding: 100px 0;
}

.story-title {
    font-size: 3rem;
    line-height: 1.2;
    margin-bottom: 20px;
}

.story-impact {
    font-size: 1.5rem;
    font-style: italic;
    margin-bottom: 30px;
}

.story-content-wrapper {
    padding: 60px 0;
}

.story-main-content {
    max-width: 800px;
    margin: 0 auto;
}

.story-body {
    font-size: 1.125rem;
    line-height: 1.8;
    color: #333;
}

.story-body p {
    margin-bottom: 1.5rem;
    max-width: 70ch; /* Optimal reading length */
}

.story-body h2 {
    font-size: 2rem;
    margin: 2rem 0 1rem;
}

.story-body h3 {
    font-size: 1.5rem;
    margin: 1.5rem 0 0.75rem;
}

.story-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 2rem 0;
}

.story-gallery-item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.impact-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin: 2rem 0;
}

.impact-stat-box {
    background: #f5f5f5;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #9fc612;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

.story-cta {
    background: #9fc612;
    color: white;
    padding: 40px;
    border-radius: 8px;
    text-align: center;
    margin: 40px 0;
}

.cta-button {
    display: inline-block;
    background: white;
    color: #9fc612;
    padding: 15px 30px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    margin-top: 20px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .story-title {
        font-size: 2rem;
    }
    
    .story-impact {
        font-size: 1.2rem;
    }
    
    .story-body {
        font-size: 1rem;
    }
    
    .story-gallery {
        grid-template-columns: 1fr;
    }
}
```

**Purpose**: Story styles focusing on readability and accessibility.

### 7. `wp-content/themes/site-child/assets/css/resources.css`
**Location**: New file

**Content**:
```css
/* Knowledge Hub Styles */
.resources-archive {
    padding: 40px 0;
}

.archive-header {
    text-align: center;
    margin-bottom: 40px;
}

.archive-title {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 10px;
}

.archive-subtitle {
    font-size: 1.2rem;
    color: #666;
}

.resource-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 40px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.resource-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.resource-card:hover {
    transform: translateY(-5px);
}

.resource-thumbnail {
    height: 200px;
    overflow: hidden;
}

.resource-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.resource-placeholder {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    color: #999;
    font-size: 3rem;
}

.resource-content {
    padding: 20px;
}

.resource-meta {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.resource-type {
    background: #9fc612;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.resource-date {
    color: #666;
    font-size: 0.8rem;
}

.resource-title {
    font-size: 1.2rem;
    margin-bottom: 10px;
}

.resource-title a {
    color: #333;
    text-decoration: none;
}

.resource-title a:hover {
    color: #9fc612;
}

.resource-details {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: #666;
}

.resource-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.resource-actions {
    display: flex;
    gap: 10px;
}

.btn-view,
.btn-download {
    flex: 1;
    padding: 10px;
    text-align: center;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
}

.btn-view {
    background: #f5f5f5;
    color: #333;
}

.btn-download {
    background: #9fc612;
    color: white;
}

.btn-view:hover,
.btn-download:hover {
    opacity: 0.9;
}
```

**Purpose**: Resources archive and card styles.

## Morning Tasks: Partner Carousel

### Step 1: Create Partner Template
Create single-site_partner.php with the content above.

### Step 2: Add Partner ACF Fields
Ensure partner ACF fields are configured (from Day 2):
- Partner Logo
- Partner Website
- Partner Link Type (External/Relationship)
- Partnership Period
- Partnership Focus
- Related Projects

### Step 3: Create Sample Partners
1. Go to Partners → Add New
2. Create 2-3 sample partners
3. Add logos and metadata
4. Test both external and relationship link types

### Step 4: Integrate Carousel
Add the partners carousel from Day 3 to homepage if not already done.

## Afternoon Tasks: Stories & Resources

### Step 1: Create Story Template
Create single-site_story.php with enhanced typography and features.

### Step 2: Create Story CSS
Create stories.css with readability focus.

### Step 3: Enqueue Story Assets
Add to child theme functions.php:
```php
function site_story_assets() {
    if (is_singular('site_story')) {
        wp_enqueue_style('site-stories', 
            get_stylesheet_directory_uri() . '/assets/css/stories.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'site_story_assets');
```

### Step 4: Create Resource Templates
Create single-site_resource.php and archive-site_resource.php.

### Step 5: Create Resource Card
Create template-parts/resource-card.php.

### Step 6: Create Resource CSS
Create resources.css.

### Step 7: Enqueue Resource Assets
Add to child theme functions.php:
```php
function site_resource_assets() {
    if (is_post_type_archive('site_resource') || is_singular('site_resource')) {
        wp_enqueue_style('site-resources', 
            get_stylesheet_directory_uri() . '/assets/css/resources.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'site_resource_assets');
```

### Step 8: Test All Templates
1. Create sample story with all metadata
2. Create sample resource with file
3. Test story page display
4. Test resource page display
5. Test resources archive filtering
6. Verify related content recommendations

## Deliverables Checklist

- [ ] Partner single template created
- [ ] Partner carousel integrated
- [ ] External and relationship links tested
- [ ] Story single template created
- [ ] Story CSS created with readability focus
- [ ] Story assets enqueued
- [ ] Resource single template created
- [ ] Resource archive template created
- [ ] Resource card component created
- [ ] Resource CSS created
- [ ] Resource assets enqueued
- [ ] All templates tested
- [ ] Related content recommendations working

## Risk Mitigation

### Potential Issues:
1. **Partner Link Type Not Working**
   - Mitigation: Test both external and relationship options
   - Fallback: Default to external link

2. **Story Typography Issues**
   - Mitigation: Test on various screen sizes
   - Fallback: Use parent theme typography

3. **Resource Download Failing**
   - Mitigation: Verify file permissions and paths
   - Fallback: Use external link option

## Success Criteria
- Partner carousel displays correctly
- Both partner link types work properly
- Story pages have enhanced readability
- Story hero sections display correctly
- Related stories appear in sidebar
- Resource archive filters work
- Resource cards display properly
- Download functionality works
- All templates responsive on mobile

## Next Steps
After completing Day 7, proceed to [Day 8: Taxonomy, Branding & SEO](DAY-08.md) to clean up taxonomy, implement brand identity, and configure SEO optimization.