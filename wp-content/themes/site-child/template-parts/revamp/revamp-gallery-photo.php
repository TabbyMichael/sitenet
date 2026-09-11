<?php
/**
 * Modernised Photo Gallery page renderer.
 *
 * Expects the global $rv config (set by page-gallery-lightbox.php):
 *   type, root_class, kicker, title, lead, actions[],
 *   photos[] (id|url|alt) — built from real media-library attachments,
 *   photo_count.
 *
 * The lightbox itself is handled by assets/js/revamp.js (no jQuery).
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Scope: shells publish $rv on $GLOBALS (see revamp-programme.php). */
$rv = isset( $GLOBALS['site_child_revamp'] ) && is_array( $GLOBALS['site_child_revamp'] )
	? $GLOBALS['site_child_revamp']
	: array();

$rv_root_class = isset( $rv['root_class'] ) ? $rv['root_class'] : 'site-revamp--gallery';
$rv_photos     = isset( $rv['photos'] )     ? $rv['photos']     : array();
?>

<main id="primary" class="site-main site-revamp <?php echo esc_attr( $rv_root_class ); ?>">

	<?php
	get_template_part( 'template-parts/revamp/revamp-hero' );
	?>

	<?php if ( get_the_content() ) : ?>
		<section class="rv-section rv-content" aria-label="Page content">
			<div class="rv-container">
				<div class="rv-content__inner">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="rv-section rv-photo-wrap" aria-labelledby="rv-gallery-title">
		<div class="rv-container">
			<div class="rv-section-head">
				<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Photo gallery</p>
				<h2 id="rv-gallery-title" class="rv-section-title">Our work, in pictures</h2>
				<p class="rv-section-sub"><?php echo esc_html( isset( $rv['photo_note'] ) ? $rv['photo_note'] : 'Select any photo to view it enlarged.' ); ?></p>
			</div>

			<?php if ( ! empty( $rv_photos ) ) : ?>
				<div class="rv-photo-grid" data-rv-lightbox-grid>
					<?php foreach ( $rv_photos as $rv_photo ) : ?>
						<button type="button"
							class="rv-photo-grid__item"
							data-photo-url="<?php echo esc_url( $rv_photo['url'] ); ?>"
							data-photo-caption="<?php echo esc_attr( $rv_photo['caption'] ); ?>"
							aria-label="<?php echo esc_attr( sprintf( 'View photo: %s', $rv_photo['caption'] ) ); ?>">
							<img class="rv-photo-grid__img"
								src="<?php echo esc_url( $rv_photo['image'] ); ?>"
								alt="<?php echo esc_attr( $rv_photo['caption'] ); ?>"
								loading="lazy" decoding="async" width="640" height="427">
						</button>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="rv-empty" role="status">
					<div class="rv-empty__icon" aria-hidden="true"><i class="fa fa-camera"></i></div>
					<h3 class="rv-empty__title">Photos are coming soon.</h3>
					<p class="rv-empty__text">We are gathering recent photos from the field. Check back shortly.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/revamp/revamp-cta' );
	?>

</main>