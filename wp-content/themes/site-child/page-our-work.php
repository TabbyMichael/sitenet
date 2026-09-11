<?php
/**
 * Template Name: Our Work
 * Description: Impact hub for SITE Enterprise Promotion - hero, impact stats,
 *              focus areas, a filterable showcase of case studies, papers, news,
 *              press releases and stories, plus a closing CTA.
 *
 * Queries real WordPress content (posts + the Projects/Stories/Resources custom
 * post types when they exist) and links to the live category landing pages.
 * Locked boundaries: header (get_header) and footer (get_footer) are untouched.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

/* ---------------------------------------------------------------------------
 * 1. Curated, real data - impact stats, focus areas and category collections.
 * ------------------------------------------------------------------------- */
$ow_stats = array(
    array( 'value' => '35,000+',  'label' => 'Men reached' ),
    array( 'value' => '100,000+', 'label' => 'Women & girls' ),
    array( 'value' => '51,000+',  'label' => 'Persons with disabilities' ),
    array( 'value' => '460+',     'label' => 'Refugees' ),
    array( 'value' => '20,000+',  'label' => 'Youth' ),
);

$ow_focus = array(
    array(
        'kicker'  => 'Youth & Skills',
        'title'   => 'Skilling Youth for Employment',
        'icon'    => 'fa-graduation-cap',
        'image'   => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ),
        'summary' => 'Market-led technical, vocational, entrepreneurship, and mentorship support that helps young people transition from training into dignified work.',
        'link'    => home_url( '/sample-page-2/' ),
    ),
    array(
        'kicker'  => 'Inclusive Markets',
        'title'   => 'Enterprise Development & Value Chains',
        'icon'    => 'fa-line-chart',
        'image'   => content_url( 'uploads/2021/11/journeyofgrowth-850x567.jpg' ),
        'summary' => 'Business development and value-chain strengthening for entrepreneurs, MSMEs, and producer groups seeking better markets and sustainable growth.',
        'link'    => home_url( '/enterprise-development-and-value-chains/' ),
    ),
    array(
        'kicker'  => 'Women & Inclusion',
        'title'   => 'Empowering Women for Employment',
        'icon'    => 'fa-female',
        'image'   => content_url( 'uploads/2021/11/bilatha-850x567.jpg' ),
        'summary' => 'Practical pathways for women and marginalized groups to build income, leadership, resilience, and stronger decision-making power.',
        'link'    => home_url( '/empowering-women-for-employment/' ),
    ),
    array(
        'kicker'  => 'Resilient Communities',
        'title'   => 'Food Security & Climate Action',
        'icon'    => 'fa-leaf',
        'image'   => content_url( 'uploads/2021/11/harbole-water-850x567.jpg' ),
        'summary' => 'Climate-smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
        'link'    => home_url( '/climate-actions/' ),
    ),
);

/* Live category landing pages. */
$ow_collections = array(
    array(
        'title'  => 'Case Studies',
        'desc'   => 'In-depth looks at how our projects and partnerships create measurable change.',
        'icon'   => 'fa-book',
        'link'   => home_url( '/resources/case-studys/' ),
        'filter' => 'case-studies',
    ),
    array(
        'title'  => 'Papers',
        'desc'   => 'Research, surveys and reports from our work in enterprise development.',
        'icon'   => 'fa-file-text-o',
        'link'   => home_url( '/resources/papers/' ),
        'filter' => 'papers',
    ),
    array(
        'title'  => 'News',
        'desc'   => 'Updates and announcements from across our programs and communities.',
        'icon'   => 'fa-newspaper-o',
        'link'   => home_url( '/resources/stone-and-hardscaping/' ),
        'filter' => 'news',
    ),
    array(
        'title'  => 'Press Releases',
        'desc'   => 'Official statements and media releases from SITE Enterprise Promotion.',
        'icon'   => 'fa-bullhorn',
        'link'   => home_url( '/resources/irrigation-and-drainage/' ),
        'filter' => 'press-releases',
    ),
);

/* ---------------------------------------------------------------------------
 * 2. Query real content.
 * ------------------------------------------------------------------------- */
$ow_type_meta = array(
    'post'          => array( 'badge' => 'Story',      'cta' => 'Read story',      'icon' => 'fa-quote-left',  'filter' => 'stories' ),
    'site_project'  => array( 'badge' => 'Case Study', 'cta' => 'View case study', 'icon' => 'fa-book',        'filter' => 'case-studies' ),
    'site_story'    => array( 'badge' => 'Story',      'cta' => 'Read story',      'icon' => 'fa-quote-left',  'filter' => 'stories' ),
    'site_resource' => array( 'badge' => 'Paper',      'cta' => 'Read paper',      'icon' => 'fa-file-text-o', 'filter' => 'papers' ),
);

$ow_query = new WP_Query(
    array(
        'post_type'      => array( 'post', 'site_project', 'site_story', 'site_resource' ),
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    )
);

$ow_content_items = array();

if ( $ow_query->have_posts() ) {
    while ( $ow_query->have_posts() ) {
        $ow_query->the_post();
        $pt   = get_post_type();
        $meta = isset( $ow_type_meta[ $pt ] ) ? $ow_type_meta[ $pt ] : $ow_type_meta['post'];

        $excerpt = get_the_excerpt();
        if ( empty( $excerpt ) ) {
            $excerpt = wp_trim_words( wp_strip_all_tags( get_the_content() ), 40 );
        }

        $ow_content_items[] = array(
            'id'             => get_the_ID(),
            'title'          => get_the_title(),
            'permalink'      => get_permalink(),
            'excerpt'        => $excerpt,
            'date'           => get_the_date( 'F Y' ),
            'filter'         => $meta['filter'],
            'badge'          => $meta['badge'],
            'cta'            => $meta['cta'],
            'icon'           => $meta['icon'],
            'thumbnail_html' => get_the_post_thumbnail(
                get_the_ID(),
                'large',
                array(
                    'class'    => 'ow-card-image',
                    'alt'      => the_title_attribute( array( 'echo' => false ) ),
                    'sizes'    => '(max-width: 767px) 100vw, (max-width: 1023px) 50vw, 33vw',
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                )
            ),
        );
    }
    wp_reset_postdata();
}

/* Featured = most recent story. Grid = category collections first, then the rest. */
$ow_featured = null;
$ow_grid     = array();

foreach ( $ow_collections as $collection ) {
    $collection['kind'] = 'collection';
    $ow_grid[]          = $collection;
}

foreach ( $ow_content_items as $item ) {
    if ( null === $ow_featured && 'stories' === $item['filter'] ) {
        $ow_featured = $item;
    } else {
        $ow_grid[] = $item;
    }
}

/* ---------------------------------------------------------------------------
 * 3. Filters + helpers.
 * ------------------------------------------------------------------------- */
$ow_filters = array(
    'all'            => 'All Work',
    'stories'        => 'Stories',
    'case-studies'   => 'Case Studies',
    'papers'         => 'Papers',
    'news'           => 'News',
    'press-releases' => 'Press Releases',
);

$active_filter = isset( $_GET['filter'] ) ? sanitize_key( $_GET['filter'] ) : 'all'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! array_key_exists( $active_filter, $ow_filters ) ) {
    $active_filter = 'all';
}

$ow_filter_urls = array();
foreach ( $ow_filters as $key => $label ) {
    $ow_filter_urls[ $key ] = ( 'all' === $key )
        ? remove_query_arg( 'filter' )
        : add_query_arg( 'filter', $key );
}

?>
<main id="primary" class="site-our-work">

    <!-- HERO -->
    <section class="ow-hero" aria-labelledby="ow-hero-title">
        <div class="ow-container">
            <span class="ow-eyebrow">Our Work</span>
            <h1 id="ow-hero-title" class="ow-hero__title">Ideas into impact.</h1>
            <p class="ow-hero__lead">Explore the projects, research, stories and partnerships that turn skills and opportunity into lasting livelihoods across Kenya.</p>
            <div class="ow-hero__actions">
                <a class="ow-btn ow-btn--primary" href="#ow-work">Explore our work</a>
                <a class="ow-btn ow-btn--ghost" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Partner with us</a>
            </div>
        </div>
    </section>

    <!-- IMPACT STATS -->
    <section class="ow-stats" aria-label="Our impact in numbers">
        <div class="ow-container">
            <ul class="ow-stats__grid">
                <?php foreach ( $ow_stats as $stat ) : ?>
                    <li class="ow-stat">
                        <span class="ow-stat__value"><?php echo esc_html( $stat['value'] ); ?></span>
                        <span class="ow-stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- FOCUS AREAS -->
    <section class="ow-programs" aria-labelledby="ow-programs-title">
        <div class="ow-container">
            <div class="ow-section-head">
                <span class="ow-eyebrow">What we do</span>
                <h2 id="ow-programs-title" class="ow-section-head__title">Focus areas</h2>
                <p class="ow-section-head__lead">Our programs connect skills, enterprise growth, gender inclusion and climate resilience so communities can build sustainable incomes.</p>
            </div>
            <div class="ow-programs__grid">
                <?php foreach ( $ow_focus as $area ) : ?>
                    <article class="ow-program-card" aria-label="<?php echo esc_attr( $area['title'] ); ?>">
                        <a class="ow-program-card__media" href="<?php echo esc_url( $area['link'] ); ?>" tabindex="-1" aria-hidden="true">
                            <img src="<?php echo esc_url( $area['image'] ); ?>" alt="<?php echo esc_attr( $area['title'] ); ?>" loading="lazy" decoding="async" />
                            <span class="ow-program-card__icon" aria-hidden="true"><i class="fa <?php echo esc_attr( $area['icon'] ); ?>"></i></span>
                        </a>
                        <div class="ow-program-card__body">
                            <span class="ow-program-card__kicker"><?php echo esc_html( $area['kicker'] ); ?></span>
                            <h3 class="ow-program-card__title"><a href="<?php echo esc_url( $area['link'] ); ?>"><?php echo esc_html( $area['title'] ); ?></a></h3>
                            <p class="ow-program-card__text"><?php echo esc_html( $area['summary'] ); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FILTERABLE SHOWCASE -->
    <section class="ow-workspace" id="ow-work" aria-label="Selected work">
        <div class="ow-container">
            <div class="ow-section-head ow-section-head--row">
                <div>
                    <span class="ow-eyebrow">Explore our work</span>
                    <h2 class="ow-section-head__title">Stories &amp; resources</h2>
                </div>
                <a class="ow-section-head__link" href="<?php echo esc_url( home_url( '/stories/' ) ); ?>">View all stories <i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div>

            <nav class="ow-filters" aria-label="Filter work by category">
                <ul class="ow-filter__list" role="tablist" aria-label="Work categories">
                    <?php foreach ( $ow_filters as $key => $label ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $ow_filter_urls[ $key ] ); ?>" class="ow-filter__btn<?php echo ( $key === $active_filter ) ? ' is-active' : ''; ?>" data-filter="<?php echo esc_attr( $key ); ?>" role="tab" aria-selected="<?php echo ( $key === $active_filter ) ? 'true' : 'false'; ?>"><?php echo esc_html( $label ); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <?php if ( $ow_featured ) : ?>
                <article class="ow-featured ow-card ow-card--featured" data-type="<?php echo esc_attr( $ow_featured['filter'] ); ?>" data-index="0">
                    <a href="<?php echo esc_url( $ow_featured['permalink'] ); ?>" class="ow-card__image-link" tabindex="-1" aria-hidden="true">
                        <div class="ow-card__media ow-card__media--featured">
                            <?php if ( $ow_featured['thumbnail_html'] ) : ?>
                                <?php echo $ow_featured['thumbnail_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP generated markup. ?>
                            <?php else : ?>
                                <?php echo site_child_ow_get_fallback_image( $ow_featured ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <?php endif; ?>
                            <span class="ow-card__overlay" aria-hidden="true"></span>
                            <span class="ow-card__hover-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        </div>
                    </a>
                    <div class="ow-card__content ow-card__content--featured">
                        <span class="ow-card__badge"><?php echo esc_html( $ow_featured['badge'] ); ?></span>
                        <h2 class="ow-card__title"><a href="<?php echo esc_url( $ow_featured['permalink'] ); ?>"><?php echo esc_html( $ow_featured['title'] ); ?></a></h2>
                        <p class="ow-card__excerpt"><?php echo esc_html( wp_trim_words( $ow_featured['excerpt'], 38 ) ); ?></p>
                        <span class="ow-card__meta"><?php echo esc_html( $ow_featured['date'] ); ?></span>
                        <a href="<?php echo esc_url( $ow_featured['permalink'] ); ?>" class="ow-card__cta">
                            <?php echo esc_html( $ow_featured['cta'] ); ?>
                            <span class="ow-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        </a>
                    </div>
                </article>
            <?php endif; ?>

            <?php if ( ! empty( $ow_grid ) ) : ?>
                <div class="ow-grid">
                    <?php foreach ( $ow_grid as $index => $item ) : ?>
                        <?php
                        $is_collection = ( isset( $item['kind'] ) && 'collection' === $item['kind'] );
                        $item_url      = $is_collection ? $item['link'] : $item['permalink'];
                        $item_blurb    = $is_collection ? $item['desc'] : wp_trim_words( $item['excerpt'], 22 );
                        $item_badge    = $is_collection ? 'Collection' : $item['badge'];
                        $item_cta      = $is_collection ? 'Browse' : $item['cta'];
                        ?>
                        <article class="ow-card ow-grid__item<?php echo $is_collection ? ' ow-card--collection' : ''; ?>" data-type="<?php echo esc_attr( $item['filter'] ); ?>" data-index="<?php echo esc_attr( $index + 1 ); ?>">
                            <a href="<?php echo esc_url( $item_url ); ?>" class="ow-card__image-link" tabindex="-1" aria-hidden="true">
                                <div class="ow-card__media">
                                    <?php if ( $is_collection ) : ?>
                                        <span class="ow-collection__media" aria-hidden="true">
                                            <span class="ow-collection__icon"><i class="fa <?php echo esc_attr( $item['icon'] ); ?>"></i></span>
                                        </span>
                                    <?php elseif ( $item['thumbnail_html'] ) : ?>
                                        <?php echo $item['thumbnail_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <?php else : ?>
                                        <?php echo site_child_ow_get_fallback_image( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <?php endif; ?>
                                    <span class="ow-card__overlay" aria-hidden="true"></span>
                                    <span class="ow-card__hover-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                </div>
                            </a>
                            <div class="ow-card__content">
                                <span class="ow-card__badge"><?php echo esc_html( $item_badge ); ?></span>
                                <h3 class="ow-card__title"><a href="<?php echo esc_url( $item_url ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
                                <p class="ow-card__excerpt"><?php echo esc_html( $item_blurb ); ?></p>
                                <?php if ( ! $is_collection && ! empty( $item['date'] ) ) : ?>
                                    <span class="ow-card__meta"><?php echo esc_html( $item['date'] ); ?></span>
                                <?php endif; ?>
                                <a href="<?php echo esc_url( $item_url ); ?>" class="ow-card__cta">
                                    <?php echo esc_html( $item_cta ); ?>
                                    <span class="ow-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="ow-filter-empty" role="status" aria-live="polite">
                    <div class="ow-empty__icon" aria-hidden="true"><i class="fa fa-folder-open"></i></div>
                    <h2 class="ow-empty__title">Nothing in this category yet.</h2>
                    <p class="ow-empty__text">We're preparing new work here. Try another category or check back soon.</p>
                </div>
            <?php else : ?>
                <div class="ow-empty">
                    <div class="ow-empty__icon" aria-hidden="true"><i class="fa fa-folder-open"></i></div>
                    <h2 class="ow-empty__title">Nothing here yet.</h2>
                    <p class="ow-empty__text">We're preparing new work and research. Check back soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CLOSING CTA -->
    <section class="ow-contact-cta" aria-labelledby="ow-cta-title">
        <div class="ow-container">
            <div class="ow-contact-cta__inner">
                <div class="ow-contact-cta__text">
                    <span class="ow-eyebrow ow-eyebrow--cta">Get in touch</span>
                    <h2 id="ow-cta-title" class="ow-contact-cta__title">Have a question about our work?</h2>
                    <p class="ow-contact-cta__lead">Whether you're interested in our research, projects, or potential collaboration, we'd love to hear from you.</p>
                </div>
                <a class="ow-contact-cta__button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
                    Start a conversation
                    <span class="ow-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                </a>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
