<?php
/**
 * Client Testimonials — success stories shown two at a time.
 *
 * Reusable component: call
 * `get_template_part( 'template-parts/homepage/testimonials' )` anywhere and it
 * renders this section. To supply your own stories, define the
 * `$site_testimonials` variable before including it, or override the defaults
 * via the `site_child_testimonials` filter. The background image is overridable
 * via the `site_child_testimonials_background` filter.
 *
 * Items are grouped into pairs (two cards per slide); the slider advances one
 * pair at a time and the grid wraps gracefully if an odd count is supplied.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_tm_items = apply_filters(
	'site_child_testimonials',
	array(
		array(
			'quote'    => 'Thanks to SITE, in the last one month I have not received any spoilt milk back or negative feedback that the milk I sold to my customers was spoilt.',
			'name'     => 'Saadia Muhamud',
			'location' => 'Municipal market, Garissa town',
			'stars'    => 5,
		),
		array(
			'quote'    => '“What changed us was during the training, the weather specialists told us, ‘It won’t rain before this date, go and see whether what we say is true,’” she recalls. “And what they said happened. Rain stopped that first week.”',
			'name'     => 'The Tote’s',
			'location' => 'Kithambioni village, Mwingi West',
			'stars'    => 5,
		),
		array(
			'quote'    => 'I see this to be sustainable as opposed to previous initiatives that give us handouts with no skills, which left us poorer than we were before.',
			'name'     => 'Catherine Wangui',
			'location' => 'Kawangware, Kenya',
			'stars'    => 5,
		),
		array(
			'quote'    => 'What I saw unique and good about the training is that we were trained on the farms. They used to tell us when they were coming, we’d call farmers and we met on the farm. We used to plant all the crops together, they taught us how to do pure stands, how to trap and harvest water.',
			'name'     => 'Nicholas Musyoka Kasa',
			'location' => 'Kithambioni village, Mwingi West',
			'stars'    => 5,
		),
	)
);

// Allow the caller to supply their own list by setting this variable first.
if ( ! empty( $site_testimonials ) && is_array( $site_testimonials ) ) {
	$site_tm_items = $site_testimonials;
}

// Group into pairs — each slide shows two cards side by side.
$site_tm_pairs = array_chunk( $site_tm_items, 2 );

$site_tm_bg = apply_filters(
	'site_child_testimonials_background',
	get_stylesheet_directory_uri() . '/assets/images/hero/WWD-in-Machakos-during-soap-making-training-1536x1024.jpg'
);
?>
<section
	id="site-testimonials"
	class="site-testimonials"
	aria-label="Client testimonials"
	style="--site-tm-bg: url('<?php echo esc_url( $site_tm_bg ); ?>');"
>
	<div class="site-testimonials__inner container">
		<header class="site-testimonials__head">
			<span class="site-testimonials__kicker">Success Stories</span>
			<h2 class="site-testimonials__title">What Our Clients Say</h2>
			<p class="site-testimonials__lede">Real voices from the communities and enterprises SITE works with across Kenya.</p>
		</header>

		<div class="site-testimonials__slider" data-tm-slider>
			<div class="site-testimonials__viewport">
				<div class="site-testimonials__track">
					<?php foreach ( $site_tm_pairs as $site_tm_pair_index => $site_tm_pair ) : ?>
						<div class="site-testimonials__slide" data-tm-slide aria-hidden="<?php echo 0 === $site_tm_pair_index ? 'false' : 'true'; ?>">
							<?php foreach ( $site_tm_pair as $site_tm_item ) : ?>
								<figure class="site-testimonial-card" aria-label="Testimonial from <?php echo esc_attr( $site_tm_item['name'] ); ?>">
									<blockquote class="site-testimonial-card__quote">
										<span class="site-testimonial-card__quote-mark" aria-hidden="true">&ldquo;</span>
										<p><?php echo wp_kses_post( $site_tm_item['quote'] ); ?></p>
									</blockquote>

									<?php if ( ! empty( $site_tm_item['stars'] ) ) : ?>
										<div class="site-testimonial-card__stars" aria-label="<?php echo esc_attr( $site_tm_item['stars'] ); ?> out of 5 stars">
											<?php for ( $site_tm_i = 0; $site_tm_i < (int) $site_tm_item['stars']; $site_tm_i++ ) : ?>
												<span aria-hidden="true">&#9733;</span>
											<?php endfor; ?>
										</div>
									<?php endif; ?>

									<figcaption class="site-testimonial-card__author">
										<span class="site-testimonial-card__avatar" aria-hidden="true">
											<?php echo esc_html( mb_substr( trim( $site_tm_item['name'] ), 0, 1 ) ); ?>
										</span>
										<span class="site-testimonial-card__author-meta">
											<strong class="site-testimonial-card__name"><?php echo esc_html( $site_tm_item['name'] ); ?></strong>
											<span class="site-testimonial-card__location"><?php echo esc_html( $site_tm_item['location'] ); ?></span>
										</span>
									</figcaption>
								</figure>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="site-testimonials__controls">
				<button class="site-testimonials__arrow site-testimonials__arrow--prev" type="button" data-tm-prev aria-label="Previous testimonial pair">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
				</button>
				<button class="site-testimonials__arrow site-testimonials__arrow--next" type="button" data-tm-next aria-label="Next testimonial pair">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
				</button>
			</div>

			<div class="site-testimonials__pagination" data-tm-pagination aria-label="Testimonial pagination"></div>
		</div>
	</div>
</section>
<noscript>
	<style>
		.site-testimonials__slider { opacity: 1 !important; transform: none !important; }
	</style>
</noscript>
