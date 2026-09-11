<?php
/**
 * Stories (blog) index - premium editorial layout.
 *
 * Used for the "Stories" posts page (/blog/). Replaces the parent theme's
 * index.php list/grid layout with a modern editorial showcase: hero, featured
 * story, story grid and a closing CTA - in the SITE brand with full light/dark
 * mode support.
 *
 * Locked boundaries: header (get_header) and footer (get_footer) are untouched.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$st_index    = 0;
$st_featured = null;
$st_posts    = array();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();

        $excerpt = get_the_excerpt();
        if ( empty( $excerpt ) ) {
            $excerpt = wp_trim_words( wp_strip_all_tags( get_the_content() ), 40 );
        }

        $words   = str_word_count( wp_strip_all_tags( get_the_content() ) );
        $minutes = max( 1, (int) ceil( $words / 200 ) );

        $item = array(
            'id'             => get_the_ID(),
            'title'          => get_the_title(),
            'permalink'      => get_permalink(),
            'excerpt'        => $excerpt,
            'date'           => get_the_date( 'F j, Y' ),
            'reading_time'   => $minutes . ' min read',
            'thumbnail_html' => get_the_post_thumbnail(
                get_the_ID(),
                'large',
                array(
                    'class'    => 'st-card-image',
                    'alt'      => the_title_attribute( array( 'echo' => false ) ),
                    'sizes'    => '(max-width: 767px) 100vw, (max-width: 1023px) 50vw, 33vw',
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                )
            ),
        );

        if ( 0 === $st_index ) {
            $st_featured = $item;
        } else {
            $st_posts[] = $item;
        }

        $st_index++;
    }
}

?>
<main id="primary" class="site-stories">

    <!-- HERO -->
    <section class="st-hero" aria-labelledby="st-hero-title">
        <div class="st-container">
            <span class="st-eyebrow">Stories</span>
            <h1 id="st-hero-title" class="st-hero__title">Real people. Real change.</h1>
            <p class="st-hero__lead">First-person stories of the entrepreneurs, women, youth and communities transforming their livelihoods with SITE.</p>
        </div>
    </section>

    <?php if ( $st_featured || ! empty( $st_posts ) ) : ?>

        <?php if ( $st_featured ) : ?>
            <!-- FEATURED STORY -->
            <section class="st-featured" aria-label="Featured story">
                <div class="st-container">
                    <article class="st-card st-card--featured" data-index="0">
                        <a href="<?php echo esc_url( $st_featured['permalink'] ); ?>" class="st-card__image-link" tabindex="-1" aria-hidden="true">
                            <div class="st-card__media st-card__media--featured">
                                <?php if ( $st_featured['thumbnail_html'] ) : ?>
                                    <?php echo $st_featured['thumbnail_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP generated markup. ?>
                                <?php else : ?>
                                    <span class="st-card__fallback" aria-hidden="true"><i class="fa fa-quote-left"></i></span>
                                <?php endif; ?>
                                <span class="st-card__overlay" aria-hidden="true"></span>
                            </div>
                        </a>
                        <div class="st-card__content st-card__content--featured">
                            <span class="st-card__badge">Featured story</span>
                            <h2 class="st-card__title"><a href="<?php echo esc_url( $st_featured['permalink'] ); ?>"><?php echo esc_html( $st_featured['title'] ); ?></a></h2>
                            <p class="st-card__excerpt"><?php echo esc_html( wp_trim_words( $st_featured['excerpt'], 40 ) ); ?></p>
                            <span class="st-card__meta"><?php echo esc_html( $st_featured['date'] ); ?> &middot; <?php echo esc_html( $st_featured['reading_time'] ); ?></span>
                            <a href="<?php echo esc_url( $st_featured['permalink'] ); ?>" class="st-card__cta">Read story <span class="st-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
                        </div>
                    </article>
                </div>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $st_posts ) ) : ?>
            <!-- STORY GRID -->
            <section class="st-workspace" aria-label="More stories">
                <div class="st-container">
                    <div class="st-grid">
                        <?php foreach ( $st_posts as $index => $item ) : ?>
                            <article class="st-card st-grid__item" data-index="<?php echo esc_attr( $index + 1 ); ?>">
                                <a href="<?php echo esc_url( $item['permalink'] ); ?>" class="st-card__image-link" tabindex="-1" aria-hidden="true">
                                    <div class="st-card__media">
                                        <?php if ( $item['thumbnail_html'] ) : ?>
                                            <?php echo $item['thumbnail_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP generated markup. ?>
                                        <?php else : ?>
                                            <span class="st-card__fallback" aria-hidden="true"><i class="fa fa-quote-left"></i></span>
                                        <?php endif; ?>
                                        <span class="st-card__overlay" aria-hidden="true"></span>
                                    </div>
                                </a>
                                <div class="st-card__content">
                                    <span class="st-card__badge">Story</span>
                                    <h3 class="st-card__title"><a href="<?php echo esc_url( $item['permalink'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
                                    <p class="st-card__excerpt"><?php echo esc_html( wp_trim_words( $item['excerpt'], 22 ) ); ?></p>
                                    <span class="st-card__meta"><?php echo esc_html( $item['date'] ); ?> &middot; <?php echo esc_html( $item['reading_time'] ); ?></span>
                                    <a href="<?php echo esc_url( $item['permalink'] ); ?>" class="st-card__cta">Read story <span class="st-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php
                    if ( $wp_query->max_num_pages > 1 ) {
                        the_posts_pagination(
                            array(
                                'mid_size'  => 1,
                                'prev_text' => '&larr; Newer',
                                'next_text' => 'Older &rarr;',
                            )
                        );
                    }
                    ?>
                </div>
            </section>
        <?php endif; ?>

    <?php else : ?>
        <section class="st-workspace">
            <div class="st-container">
                <div class="st-empty">
                    <div class="st-empty__icon" aria-hidden="true"><i class="fa fa-book"></i></div>
                    <h2 class="st-empty__title">No stories yet.</h2>
                    <p class="st-empty__text">We're preparing new stories from the field. Check back soon.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- CLOSING CTA -->
    <section class="st-contact-cta" aria-labelledby="st-cta-title">
        <div class="st-container">
            <div class="st-contact-cta__inner">
                <div class="st-contact-cta__text">
                    <span class="st-eyebrow st-eyebrow--cta">Get in touch</span>
                    <h2 id="st-cta-title" class="st-contact-cta__title">Have a story to share?</h2>
                    <p class="st-contact-cta__lead">We'd love to hear how SITE's work has shaped your journey - or explore a partnership.</p>
                </div>
                <a class="st-contact-cta__button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Start a conversation <span class="st-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
