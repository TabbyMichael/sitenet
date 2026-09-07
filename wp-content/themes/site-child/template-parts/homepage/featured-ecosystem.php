<?php
/**
 * Homepage Featured Ecosystem — Our Work Stories
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = array(
	'post_type'      => array( 'site_story', 'post' ),
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$featured_query = new WP_Query( $args );

$fallback_items = array(
	array(
		'title'        => 'A young small scale trader, with big dreams',
		'image'        => content_url( 'uploads/2021/11/wrm-848x450.png' ),
		'image_srcset' => '',
		'image_w'      => 848,
		'image_h'      => 450,
		'excerpt'      => 'Catherine Wangui, 28 years dropped out of school at form 2 due to lack of school fees...',
		'link'         => home_url( '/stories/' ),
	),
	array(
		'title'        => 'From Struggle to Strength: Flora&#8217;s Journey of Hope',
		'image'        => content_url( 'uploads/2021/11/harbole-water-850x567.jpg' ),
		'image_srcset' => '',
		'image_w'      => 850,
		'image_h'      => 567,
		'excerpt'      => 'Flora Gacheri, a 40-year-old woman living with a physical disability in Meru County, spent years fighting an uphill battle...',
		'link'         => home_url( '/stories/' ),
	),
	array(
		'title'        => 'Empowerment of Women with Disability &#8212; Case of Kanana Disability Group',
		'image'        => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ),
		'image_srcset' => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ) . ' 850w, ' . content_url( 'uploads/2021/11/Gallery-1-1536x1025.jpg' ) . ' 1536w',
		'image_w'      => 850,
		'image_h'      => 567,
		'excerpt'      => 'In rural Meru County, 203 people — 88% of them women living with disabilities — found themselves pushed even further into poverty by the COVID-19 pandemic...',
		'link'         => home_url( '/stories/' ),
	),
);
?>

<section class="site-featured-ecosystem-section">
	<div class="container">

		<div class="site-section-title site-modern-section-title site-section-title-row">
			<div>
				<span class="site-section-kicker">Stories From The Field</span>
				<h2>Our Work</h2>
				<p class="section-lead">
					See how SITE&#8217;s work translates into practical change for entrepreneurs, households, and communities across Kenya.
				</p>
			</div>
			<a class="site-section-button" href="<?php echo esc_url( home_url( '/stories/' ) ); ?>">
				More News <i class="fa fa-angle-right" aria-hidden="true"></i>
			</a>
		</div>

		<div class="site-work-grid">
			<?php
			$card_index = 0;
			if ( $featured_query->have_posts() ) :
				while ( $featured_query->have_posts() ) :
					$featured_query->the_post();
					$excerpt = get_the_excerpt();
					if ( empty( $excerpt ) ) {
						$excerpt = wp_trim_words( get_the_content(), 22 );
					}
					?>
					<article class="ecosystem-card" data-index="<?php echo esc_attr( $card_index ); ?>" aria-label="<?php the_title_attribute(); ?>">

						<a href="<?php the_permalink(); ?>" class="ecosystem-card-image-link" tabindex="-1" aria-hidden="true">
							<div class="ecosystem-card-thumb">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php
									echo wp_get_attachment_image(
										get_post_thumbnail_id(),
										'large',
										false,
										array(
											'class'    => 'ecosystem-thumb-img',
											'alt'      => the_title_attribute( array( 'echo' => false ) ),
											'sizes'    => '(max-width: 599px) 100vw, (max-width: 1023px) 50vw, 33vw',
											'loading'  => 'lazy',
											'decoding' => 'async',
										)
									);
									?>
								<?php else : ?>
									<img src="<?php echo esc_url( content_url( 'uploads/2021/11/wrm-848x450.png' ) ); ?>"
										sizes="(max-width: 599px) 100vw, (max-width: 1023px) 50vw, 33vw"
										width="848" height="450"
										alt="<?php the_title_attribute(); ?>"
										class="ecosystem-thumb-img" loading="lazy" decoding="async" />
								<?php endif; ?>
								<div class="ecosystem-card-overlay" aria-hidden="true"></div>
							</div>
						</a>

						<div class="ecosystem-card-content">
							<span class="ecosystem-card-meta">Impact Story</span>
							<h3 class="ecosystem-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<p class="ecosystem-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
							<a href="<?php the_permalink(); ?>" class="ecosystem-read-more">
								Read Story
								<span class="ecosystem-read-more__arrow" aria-hidden="true">
									<i class="fa fa-angle-right"></i>
								</span>
							</a>
						</div>

					</article>
					<?php
					$card_index++;
				endwhile;
				wp_reset_postdata();
			else :
				foreach ( $fallback_items as $i => $item ) :
					?>
					<article class="ecosystem-card" data-index="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( $item['title'] ); ?>">

						<a href="<?php echo esc_url( $item['link'] ); ?>" class="ecosystem-card-image-link" tabindex="-1" aria-hidden="true">
							<div class="ecosystem-card-thumb">
								<img src="<?php echo esc_url( $item['image'] ); ?>"
									<?php if ( ! empty( $item['image_srcset'] ) ) : ?>
										srcset="<?php echo esc_attr( $item['image_srcset'] ); ?>"
									<?php endif; ?>
									sizes="(max-width: 599px) 100vw, (max-width: 1023px) 50vw, 33vw"
									width="<?php echo esc_attr( $item['image_w'] ?? 850 ); ?>"
									height="<?php echo esc_attr( $item['image_h'] ?? 567 ); ?>"
									alt="<?php echo esc_attr( wp_strip_all_tags( $item['title'] ) ); ?>"
									class="ecosystem-thumb-img" loading="lazy" decoding="async" />
								<div class="ecosystem-card-overlay" aria-hidden="true"></div>
							</div>
						</a>

						<div class="ecosystem-card-content">
							<span class="ecosystem-card-meta">Impact Story</span>
							<h3 class="ecosystem-card-title">
								<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo wp_kses_post( $item['title'] ); ?></a>
							</h3>
							<p class="ecosystem-card-excerpt"><?php echo esc_html( $item['excerpt'] ); ?></p>
							<a href="<?php echo esc_url( $item['link'] ); ?>" class="ecosystem-read-more">
								Read Story
								<span class="ecosystem-read-more__arrow" aria-hidden="true">
									<i class="fa fa-angle-right"></i>
								</span>
							</a>
						</div>

					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div><!-- .site-work-grid -->

	</div><!-- .container -->
</section>

<script>
/* Our Work — staggered scroll-reveal animation (IntersectionObserver).
   Mirrors the Focus Areas pattern. Gracefully degrades without JS.   */
(function () {
	'use strict';

	function initWorkCardAnimations() {
		var cards = document.querySelectorAll('.site-featured-ecosystem-section .ecosystem-card');
		if ( ! cards.length ) return;

		/* No IntersectionObserver? Show all immediately. */
		if ( ! ('IntersectionObserver' in window) ) {
			cards.forEach( function (c) { c.classList.add('is-visible'); } );
			return;
		}

		var observer = new IntersectionObserver( function (entries) {
			entries.forEach( function (entry) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add('is-visible');
					observer.unobserve( entry.target );
				}
			} );
		}, {
			threshold: 0.10,
			rootMargin: '0px 0px -48px 0px'
		} );

		cards.forEach( function (c) { observer.observe(c); } );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initWorkCardAnimations );
	} else {
		initWorkCardAnimations();
	}
}());
</script>
