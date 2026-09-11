<?php
/**
 * Single site_story template - premium editorial story layout.
 *
 * Renders one beneficiary story from the site_story CPT using its six ACF
 * fields (hero_image, impact_statement, story_year, story_author,
 * impact_statistics repeater, story_gallery) plus the site_program /
 * site_theme / site_location taxonomies. Related stories come from the same
 * primary program.
 *
 * Locked boundaries: header (get_header) and footer (get_footer) are untouched.
 * All styling lives in assets/css/story-single.css scoped to .site-story-single.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ss_hero_id = get_field( 'hero_image' );
if ( is_array( $ss_hero_id ) ) {
	$ss_hero_id = $ss_hero_id['ID'] ?? 0;
}
$ss_hero_id = (int) $ss_hero_id;

$ss_impact  = get_field( 'impact_statement' );
$ss_author  = get_field( 'story_author' );
$ss_stats   = get_field( 'impact_statistics' );
$ss_gallery = array_map(
	static function ( $ss_item ) {
		return is_array( $ss_item ) ? ( $ss_item['ID'] ?? 0 ) : (int) $ss_item;
	},
	(array) get_field( 'story_gallery' )
);
$ss_gallery = array_filter( $ss_gallery );

$ss_programs  = get_the_terms( get_the_ID(), 'site_program' );
$ss_themes    = get_the_terms( get_the_ID(), 'site_theme' );
$ss_locations = get_the_terms( get_the_ID(), 'site_location' );


$ss_share_url   = rawurlencode( get_permalink() );
$ss_share_title = rawurlencode( wp_strip_all_tags( get_the_title() ) );
?>

<section class="site-story-single">

	<?php if ( $ss_hero_id ) : ?>
		<figure class="ss-hero">
			<?php
			echo wp_get_attachment_image(
				(int) $ss_hero_id,
				'full',
				false,
				array(
					'class'    => 'ss-hero-image',
					'alt'      => get_post_meta( (int) $ss_hero_id, '_wp_attachment_image_alt', true ),
					'decoding' => 'async',
				)
			);
			?>
		</figure>
	<?php endif; ?>

	<div class="ss-container">
		<article class="ss-article">

			<?php if ( $ss_programs && ! is_wp_error( $ss_programs ) ) : ?>
				<p class="ss-kicker"><?php echo esc_html( $ss_programs[0]->name ); ?></p>
			<?php endif; ?>

			<h1 class="ss-title"><?php the_title(); ?></h1>

			<?php if ( $ss_impact ) : ?>
				<p class="ss-impact"><?php echo esc_html( $ss_impact ); ?></p>
			<?php endif; ?>

			<p class="ss-meta">
				<?php
				$ss_meta_bits = array();

				if ( $ss_locations && ! is_wp_error( $ss_locations ) ) {
					$ss_meta_bits[] = esc_html( implode( ' · ', wp_list_pluck( $ss_locations, 'name' ) ) );
				}
				if ( $ss_author ) {
					$ss_meta_bits[] = esc_html( $ss_author );
				}

				echo implode( ' <span class="ss-meta-divider" aria-hidden="true">|</span> ', $ss_meta_bits ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- parts pre-escaped
				?>
			</p>

			<?php if ( $ss_stats ) : ?>
				<ul class="ss-stats">
					<?php foreach ( $ss_stats as $ss_stat ) : ?>
						<li class="ss-stat">
							<span class="ss-stat-number"><?php echo esc_html( $ss_stat['number'] ); ?></span>
							<span class="ss-stat-label"><?php echo esc_html( $ss_stat['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<div class="ss-body">
				<?php the_content(); ?>
			</div>

			<?php if ( $ss_themes && ! is_wp_error( $ss_themes ) ) : ?>
				<p class="ss-themes">
					<?php foreach ( $ss_themes as $ss_theme ) : ?>
						<span class="ss-theme-tag"><?php echo esc_html( $ss_theme->name ); ?></span>
					<?php endforeach; ?>
				</p>
			<?php endif; ?>

			<?php if ( $ss_gallery ) : ?>
				<h2 class="ss-gallery-title"><?php esc_html_e( 'Photo gallery', 'site-child' ); ?></h2>
				<div class="ss-gallery">
					<?php foreach ( $ss_gallery as $ss_image_id ) : ?>
						<figure class="ss-gallery-item">
							<?php
							echo wp_get_attachment_image(
								(int) $ss_image_id,
								'large',
								false,
								array(
									'class'    => 'ss-gallery-image',
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							$ss_caption = wp_get_attachment_caption( (int) $ss_image_id );
							if ( $ss_caption ) :
								?>
								<figcaption class="ss-gallery-caption"><?php echo esc_html( $ss_caption ); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="ss-share">
				<span class="ss-share-label"><?php esc_html_e( 'Share this story:', 'site-child' ); ?></span>
				<a class="ss-share-link" href="<?php echo esc_url( 'https://wa.me/?text=' . $ss_share_title . '%20' . $ss_share_url ); ?>" rel="noopener nofollow" target="_blank"><?php esc_html_e( 'WhatsApp', 'site-child' ); ?></a>
				<a class="ss-share-link" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . $ss_share_url . '&text=' . $ss_share_title ); ?>" rel="noopener nofollow" target="_blank"><?php esc_html_e( 'X', 'site-child' ); ?></a>
				<a class="ss-share-link" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $ss_share_url ); ?>" rel="noopener nofollow" target="_blank"><?php esc_html_e( 'Facebook', 'site-child' ); ?></a>
			</div>

			<div class="ss-cta">
				<p class="ss-cta-title"><?php esc_html_e( 'Partner with SITE Enterprise Promotion', 'site-child' ); ?></p>
				<p class="ss-cta-text"><?php esc_html_e( 'Stories like this are only possible through partnerships. Support pastoral livelihoods across Garissa and Tana River counties.', 'site-child' ); ?></p>
				<a class="ss-cta-button" href="/contact/"><?php esc_html_e( 'Get in touch', 'site-child' ); ?></a>
			</div>

		</article>

		<?php
		$ss_related = new WP_Query(
			array(
				'post_type'      => 'site_story',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
				'post__not_in'   => array( get_the_ID() ),
				'no_found_rows'  => true,
				'tax_query'      => ( $ss_programs && ! is_wp_error( $ss_programs ) ) ? array(
					array(
						'taxonomy' => 'site_program',
						'field'    => 'slug',
						'terms'    => wp_list_pluck( $ss_programs, 'slug' ),
					),
				) : array(),
			)
		);

		if ( $ss_related->have_posts() ) :
			?>
			<aside class="ss-related">
				<h2 class="ss-related-title"><?php esc_html_e( 'More stories from this program', 'site-child' ); ?></h2>
				<div class="ss-related-grid">
					<?php
					while ( $ss_related->have_posts() ) :
						$ss_related->the_post();
						$ss_rel_hero   = get_field( 'hero_image' );
						$ss_rel_hero   = is_array( $ss_rel_hero ) ? ( $ss_rel_hero['ID'] ?? 0 ) : (int) $ss_rel_hero;
						$ss_rel_impact = get_field( 'impact_statement' );
						?>
						<a class="ss-related-card" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php if ( $ss_rel_hero ) : ?>
								<span class="ss-related-image">
									<?php
									echo wp_get_attachment_image(
										(int) $ss_rel_hero,
										'medium',
										false,
										array(
											'class'    => 'ss-related-img',
											'loading'  => 'lazy',
											'decoding' => 'async',
										)
									);
									?>
								</span>
							<?php endif; ?>
							<span class="ss-related-name"><?php the_title(); ?></span>
							<?php if ( $ss_rel_impact ) : ?>
								<span class="ss-related-impact"><?php echo esc_html( $ss_rel_impact ); ?></span>
							<?php endif; ?>
						</a>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</aside>
		<?php endif; ?>
	</div>

</section>

<?php get_footer(); ?>
