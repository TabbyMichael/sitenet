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
$rv_groups     = isset( $rv['photo_groups'] ) ? $rv['photo_groups'] : array();

/*
 * Render blocks: themed collections when the shell supplies them, otherwise
 * the flat photo list, so this part still works as a simple gallery.
 */
$rv_blocks = array();

foreach ( $rv_groups as $rv_group ) {
	if ( empty( $rv_group['photos'] ) ) {
		continue;
	}

	$rv_blocks[] = array(
		'title'       => isset( $rv_group['title'] ) ? $rv_group['title'] : '',
		'description' => isset( $rv_group['description'] ) ? $rv_group['description'] : '',
		'photos'      => $rv_group['photos'],
	);
}

if ( empty( $rv_blocks ) && ! empty( $rv_photos ) ) {
	$rv_blocks[] = array(
		'title'       => '',
		'description' => '',
		'photos'      => $rv_photos,
	);
}
?>

<main id="primary" class="site-main site-revamp <?php echo esc_attr( $rv_root_class ); ?>">

	<?php
	get_template_part( 'template-parts/revamp/revamp-hero' );
	?>

	<?php
	/*
	 * Legacy builder content is optional. The Photo Gallery shell sets
	 * show_content => false because this page's stored content is a SiteOrigin
	 * [gallery] shortcode holding the same photos as the grid below — printing
	 * both showed the collection twice. The stored content is untouched in the
	 * database; only this page's output changes.
	 */
	?>
	<?php if ( ! empty( $rv['show_content'] ) && get_the_content() ) : ?>
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

			<?php if ( ! empty( $rv_blocks ) ) : ?>
				<?php
				foreach ( $rv_blocks as $rv_block_index => $rv_block ) :
					$rv_block_title = $rv_block['title'];
					$rv_block_desc  = $rv_block['description'];
					$rv_block_count = count( $rv_block['photos'] );
					$rv_block_id    = 'rv-gallery-group-' . ( $rv_block_index + 1 );
					?>
					<section class="rv-gallery-group"<?php echo $rv_block_title ? ' aria-labelledby="' . esc_attr( $rv_block_id ) . '"' : ''; ?>>
						<?php if ( $rv_block_title ) : ?>
							<header class="rv-gallery-group__head">
								<h3 id="<?php echo esc_attr( $rv_block_id ); ?>" class="rv-gallery-group__title">
									<?php echo esc_html( $rv_block_title ); ?>
								</h3>

								<?php if ( $rv_block_desc ) : ?>
									<p class="rv-gallery-group__desc"><?php echo esc_html( $rv_block_desc ); ?></p>
								<?php endif; ?>

								<span class="rv-gallery-group__count">
									<?php
									echo esc_html(
										sprintf(
											/* translators: %s: number of photographs in this collection. */
											_n( '%s photo', '%s photos', $rv_block_count, 'site-child' ),
											number_format_i18n( $rv_block_count )
										)
									);
									?>
								</span>
							</header>
						<?php endif; ?>

						<div class="rv-photo-grid" data-rv-lightbox-grid>
							<?php foreach ( $rv_block['photos'] as $rv_photo ) : ?>
								<?php $rv_caption = isset( $rv_photo['caption'] ) ? $rv_photo['caption'] : ''; ?>
								<button type="button"
									class="rv-photo-grid__item"
									data-photo-url="<?php echo esc_url( $rv_photo['url'] ); ?>"
									data-photo-caption="<?php echo esc_attr( $rv_caption ); ?>"
									aria-label="<?php echo esc_attr( sprintf( 'View photo: %s', $rv_caption ) ); ?>">
									<img class="rv-photo-grid__img"
										src="<?php echo esc_url( $rv_photo['image'] ); ?>"
										<?php if ( ! empty( $rv_photo['srcset'] ) ) : ?>
											srcset="<?php echo esc_attr( $rv_photo['srcset'] ); ?>"
											sizes="(max-width: 767px) 92vw, (max-width: 1199px) 46vw, 300px"
										<?php endif; ?>
										alt="<?php echo esc_attr( $rv_caption ); ?>"
										loading="lazy" decoding="async"
										width="<?php echo esc_attr( isset( $rv_photo['width'] ) ? $rv_photo['width'] : 640 ); ?>"
										height="<?php echo esc_attr( isset( $rv_photo['height'] ) ? $rv_photo['height'] : 427 ); ?>">
									<?php if ( $rv_caption ) : ?>
										<span class="rv-photo-grid__caption"><?php echo esc_html( $rv_caption ); ?></span>
									<?php endif; ?>
								</button>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach; ?>
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