# Day 3: Homepage Redesign (Priority #1)

## DDIA Focus: Scalability - Performance & Load Handling

## Overview
Day 3 implements the homepage redesign focusing on making SITE understandable within seconds. This follows DDIA scalability principles by implementing performance optimizations like lazy loading, efficient CSS/JS delivery, and database-driven content management to handle load effectively.

## Objectives
- Design hero section with clear value proposition
- Implement four focus areas with featured stories
- Create impact figures section with database-driven metrics
- Build partners carousel
- Implement lazy loading for images
- Optimize CSS/JS delivery (critical path rendering)
- Set up ACF fields for homepage content management

## Files to Create

### 1. `wp-content/themes/site-child/front-page.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Template Name: SITE Homepage
 * Description: Custom homepage for SITE Enterprise Promotion Kenya
 */

get_header(); ?>

<div class="site-homepage">
    <?php
    // Hero Section
    get_template_part( 'template-parts/hero-section' );
    
    // Organizational Introduction
    get_template_part( 'template-parts/intro-section' );
    
    // Four Focus Areas
    get_template_part( 'template-parts/focus-areas' );
    
    // Impact Figures
    get_template_part( 'template-parts/impact-figures' );
    
    // Featured Stories (one from each program)
    get_template_part( 'template-parts/featured-stories' );
    
    // Selected Programs/Projects
    get_template_part( 'template-parts/featured-projects' );
    
    // Partners and Donors Carousel
    get_template_part( 'template-parts/partners-carousel' );
    
    // Testimonials
    get_template_part( 'template-parts/testimonials' );
    
    // Contact/Partnership CTA
    get_template_part( 'template-parts/contact-cta' );
    ?>
</div>

<?php get_footer(); ?>
```

**Purpose**: Main homepage template using modular components for maintainability.

### 2. `wp-content/themes/site-child/template-parts/hero-section.php`
**Location**: New file in template-parts directory

**Content**:
```php
<?php
/**
 * Hero Section
 * Who SITE is, what SITE does, clear value proposition
 */

$hero_image = get_field( 'hero_background_image', 'option' );
$hero_title = get_field( 'hero_title', 'option' );
$hero_subtitle = get_field( 'hero_subtitle', 'option' );
$hero_cta_text = get_field( 'hero_cta_text', 'option' );
$hero_cta_link = get_field( 'hero_cta_link', 'option' );
?>

<section class="hero-section" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');">
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
                <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
                <a href="<?php echo esc_url( $hero_cta_link ); ?>" class="hero-cta">
                    <?php echo esc_html( $hero_cta_text ); ?>
                </a>
            </div>
        </div>
    </div>
</section>
```

**Purpose**: Hero section with configurable background, title, subtitle, and call-to-action.

### 3. `wp-content/themes/site-child/template-parts/focus-areas.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Four Focus Areas
 * Skilling Youth, Empowering Women, Enterprise Development, Food Security
 */

$programs = get_terms( array(
    'taxonomy' => 'site_program',
    'hide_empty' => false,
    'orderby' => 'menu_order',
) );
?>

<section class="focus-areas-section">
    <div class="container">
        <h2 class="section-title">Our Focus Areas</h2>
        <div class="focus-areas-grid">
            <?php foreach ( $programs as $program ) : 
                $program_image = get_field( 'program_image', 'site_program_' . $program->term_id );
                $program_description = get_field( 'program_description', 'site_program_' . $program->term_id );
                $featured_story = get_field( 'featured_story', 'site_program_' . $program->term_id );
            ?>
                <div class="focus-area-card">
                    <div class="focus-area-image">
                        <img src="<?php echo esc_url( $program_image ); ?>" alt="<?php echo esc_attr( $program->name ); ?>">
                    </div>
                    <h3 class="focus-area-title"><?php echo esc_html( $program->name ); ?></h3>
                    <p class="focus-area-description"><?php echo esc_html( $program_description ); ?></p>
                    <?php if ( $featured_story ) : ?>
                        <div class="focus-area-story">
                            <span class="story-label">Featured Story:</span>
                            <a href="<?php echo get_permalink( $featured_story ); ?>" class="story-link">
                                <?php echo get_the_title( $featured_story ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <a href="<?php echo get_term_link( $program ); ?>" class="focus-area-cta">
                        Explore Our Work
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

**Purpose**: Four program cards with images, descriptions, and featured stories.

### 4. `wp-content/themes/site-child/template-parts/impact-figures.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Impact Figures
 * Database-driven metrics showing SITE's impact
 */

$impact_stats = get_field( 'impact_statistics', 'option' );
?>

<section class="impact-figures-section">
    <div class="container">
        <h2 class="section-title">Our Impact</h2>
        <div class="impact-stats-grid">
            <?php if ( $impact_stats ) : ?>
                <?php foreach ( $impact_stats as $stat ) : ?>
                    <div class="impact-stat-card">
                        <div class="stat-number"><?php echo esc_html( $stat['number'] ); ?></div>
                        <div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                        <div class="stat-description"><?php echo esc_html( $stat['description'] ); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
```

**Purpose**: Impact statistics section with database-driven metrics.

### 5. `wp-content/themes/site-child/template-parts/featured-stories.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Featured Stories
 * One story from each major program category
 */

$programs = get_terms( array(
    'taxonomy' => 'site_program',
    'hide_empty' => false,
) );
?>

<section class="featured-stories-section">
    <div class="container">
        <h2 class="section-title">Featured Stories</h2>
        <div class="stories-grid">
            <?php foreach ( $programs as $program ) : 
                $args = array(
                    'post_type' => 'site_story',
                    'posts_per_page' => 1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'site_program',
                            'field' => 'term_id',
                            'terms' => $program->term_id,
                        ),
                    ),
                );
                $stories = new WP_Query( $args );
                
                if ( $stories->have_posts() ) : 
                    while ( $stories->have_posts() ) : $stories->the_post(); ?>
                        <div class="story-card">
                            <div class="story-image">
                                <?php the_post_thumbnail( 'medium' ); ?>
                            </div>
                            <div class="story-content">
                                <span class="story-program"><?php echo esc_html( $program->name ); ?></span>
                                <h3 class="story-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="story-excerpt"><?php the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="story-read-more">Read Story</a>
                            </div>
                        </div>
                    <?php endwhile; 
                    wp_reset_postdata();
                endif;
            endforeach; ?>
        </div>
    </div>
</section>
```

**Purpose**: Featured stories grid showing one story from each program.

### 6. `wp-content/themes/site-child/template-parts/partners-carousel.php`
**Location**: New file

**Content**:
```php
<?php
/**
 * Partners and Donors Carousel
 * Interactive logo carousel with external/partner page options
 */

$partners = get_posts( array(
    'post_type' => 'site_partner',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
) );
?>

<section class="partners-carousel-section">
    <div class="container">
        <h2 class="section-title">Our Partners & Funders</h2>
        <div class="partners-carousel">
            <?php foreach ( $partners as $partner ) : 
                $partner_logo = get_field( 'partner_logo', $partner->ID );
                $partner_website = get_field( 'partner_website', $partner->ID );
                $partner_link_type = get_field( 'partner_link_type', $partner->ID );
            ?>
                <div class="partner-slide">
                    <a href="<?php echo esc_url( $partner_link_type === 'external' ? $partner_website : get_permalink( $partner->ID ) ); ?>" 
                       class="partner-link" 
                       target="<?php echo $partner_link_type === 'external' ? '_blank' : '_self'; ?>">
                        <img src="<?php echo esc_url( $partner_logo ); ?>" alt="<?php echo esc_attr( $partner->post_title ); ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

**Purpose**: Partners carousel with logo slides and external/relationship links.

### 7. `wp-content/themes/site-child/assets/css/homepage.css`
**Location**: New file

**Content**:
```css
/* Homepage Performance Optimizations */
.hero-section {
    background-size: cover;
    background-position: center;
    min-height: 80vh;
    position: relative;
}

.hero-overlay {
    background: rgba(0, 0, 0, 0.6);
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.hero-content {
    position: relative;
    z-index: 1;
    color: white;
    padding: 100px 0;
}

.hero-title {
    font-size: 3rem;
    line-height: 1.2;
    margin-bottom: 20px;
}

.hero-subtitle {
    font-size: 1.5rem;
    margin-bottom: 30px;
}

.hero-cta {
    display: inline-block;
    background: #9fc612;
    color: white;
    padding: 15px 30px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.focus-areas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.focus-area-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.focus-area-card:hover {
    transform: translateY(-5px);
}

.impact-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.partners-carousel {
    display: flex;
    overflow-x: auto;
    gap: 30px;
    padding: 20px 0;
    scroll-snap-type: x mandatory;
}

.partner-slide {
    flex: 0 0 auto;
    scroll-snap-align: start;
    min-width: 150px;
}

.partner-link img {
    max-height: 80px;
    width: auto;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
    }
    
    .focus-areas-grid {
        grid-template-columns: 1fr;
    }
}
```

**Purpose**: Homepage styles with performance optimizations and responsive design.

### 8. `wp-content/themes/site-child/assets/js/homepage.js`
**Location**: New file

**Content**:
```javascript
/**
 * Homepage Interactions
 * Lazy loading, carousel functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Partners Carousel Auto-scroll
    const carousel = document.querySelector('.partners-carousel');
    if (carousel) {
        let scrollAmount = 0;
        const scrollStep = 1;
        
        function autoScroll() {
            carousel.scrollLeft += scrollStep;
            if (carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth) {
                carousel.scrollLeft = 0;
            }
            requestAnimationFrame(autoScroll);
        }
        
        // Pause on hover
        carousel.addEventListener('mouseenter', () => {
            // Stop auto-scroll
        });
        
        carousel.addEventListener('mouseleave', () => {
            autoScroll();
        });
        
        autoScroll();
    }
    
    // Lazy load images below fold
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
});
```

**Purpose**: Homepage JavaScript for carousel and lazy loading functionality.

## Morning Tasks: Homepage Architecture

### Step 1: Design Hero Section ACF Fields
1. Go to Custom Fields → Add New
2. Create field group "Homepage Options"
3. Add fields:
   - Hero Background Image (image field)
   - Hero Title (text field)
   - Hero Subtitle (textarea field)
   - Hero CTA Text (text field)
   - Hero CTA Link (URL field)
4. Set location to "Options Page" or create custom options page

### Step 2: Create Program Taxonomy Terms
1. Go to Programs taxonomy
2. Create four program terms:
   - Skilling Youth for Employment
   - Empowering Women for Employment
   - Enterprise Development & Value Chains
   - Food Security & Climate Action
3. Add images and descriptions to each program term

### Step 3: Set Up Impact Statistics
1. In Homepage Options field group
2. Add "Impact Statistics" repeater field
3. Sub-fields:
   - Number (text field)
   - Label (text field)
   - Description (textarea field)
4. Add sample impact statistics

### Step 4: Create Template Parts Directory
```bash
mkdir -p wp-content/themes/site-child/template-parts
```

### Step 5: Create All Template Part Files
Create all the template part files listed above.

## Afternoon Tasks: Implementation & Optimization

### Step 1: Create Assets Directory
```bash
mkdir -p wp-content/themes/site-child/assets/css
mkdir -p wp-content/themes/site-child/assets/js
```

### Step 2: Create CSS and JS Files
Create homepage.css and homepage.js with the content above.

### Step 3: Enqueue Homepage Assets
Add to child theme functions.php:
```php
function site_homepage_assets() {
    if ( is_front_page() ) {
        wp_enqueue_style( 'site-homepage', 
            get_stylesheet_directory_uri() . '/assets/css/homepage.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script( 'site-homepage',
            get_stylesheet_directory_uri() . '/assets/js/homepage.js',
            array(),
            '1.0.0',
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'site_homepage_assets' );
```

### Step 4: Set Homepage Template
1. Go to Pages → Homepage
2. Set template to "SITE Homepage"
3. Publish page

### Step 5: Implement Lazy Loading
Add loading="lazy" attribute to below-fold images in template parts.

### Step 6: Optimize CSS Delivery
- Critical CSS inline for above-fold content
- Non-critical CSS loaded asynchronously
- Minify CSS files

### Step 7: Test Performance
1. Run Lighthouse audit
2. Check Core Web Vitals
3. Verify lazy loading works
4. Test carousel functionality

## Deliverables Checklist

- [ ] Homepage template created
- [ ] All template parts created
- [ ] Hero section ACF fields configured
- [ ] Program taxonomy terms created
- [ ] Impact statistics configured
- [ ] Partners carousel implemented
- [ ] Homepage CSS created
- [ ] Homepage JavaScript created
- [ ] Assets properly enqueued
- [ ] Lazy loading implemented
- [ ] Performance benchmarks achieved
- [ ] Content management interface functional

## Risk Mitigation

### Potential Issues:
1. **Homepage Template Not Recognized**
   - Mitigation: Verify template name matches exactly
   - Fallback: Use default page template with custom query

2. **ACF Fields Not Saving**
   - Mitigation: Test with simple text field first
   - Fallback: Use WordPress custom fields

3. **Performance Degradation**
   - Mitigation: Optimize images, minify assets
   - Fallback: Remove non-essential features

## Success Criteria
- Homepage loads in < 3 seconds
- All sections display correctly
- Hero section responsive on all devices
- Partners carousel functions properly
- Lazy loading reduces initial load time
- Content manageable via ACF fields
- Performance benchmarks met

## Next Steps
After completing Day 3, proceed to [Day 4: "Our Work" Landing Page Rebuild](DAY-04.md) to rebuild the program landing page with reusable components.