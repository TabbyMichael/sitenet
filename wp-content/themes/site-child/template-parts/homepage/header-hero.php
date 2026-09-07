<?php
/**
 * Homepage Hero Carousel — image-first.
 *
 * Data source (priority order):
 *   1. ACF repeater "hero_slides" (field group: Homepage Hero Carousel,
 *      registered in wp-content/mu-plugins/site-acf-fields.php).
 *   2. Fallback — images in assets/images/hero/ (auto-detected), so the
 *      carousel works out of the box without any backend setup.
 *
 * Behaviour JS:  assets/js/carousel.js  (vanilla, no dependencies)
 * Styling:       assets/css/carousel.css
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── 1. Decide the source ────────────────────────────────────────────────────
// Priority: folder images (explicitly requested) unless ACF slides exist AND
// the site has opted into ACF via the site_hero_prefer_acf filter.
$site_source       = '';
$site_prefer_acf   = apply_filters( 'site_hero_prefer_acf', false );
$site_acf_slides   = array();

if ( function_exists( 'get_field' ) ) {
	$site_acf = get_field( 'hero_slides' );
	if ( is_array( $site_acf ) && ! empty( $site_acf ) ) {
		foreach ( $site_acf as $site_slide ) {
			$site_image_id = isset( $site_slide['image'] ) ? absint( $site_slide['image'] ) : 0;
			if ( ! $site_image_id || 'attachment' !== get_post_type( $site_image_id ) ) {
				continue;
			}
			$site_acf_slides[] = array(
				'type'        => 'attachment',
				'image_id'    => $site_image_id,
				'eyebrow'     => isset( $site_slide['eyebrow'] ) ? sanitize_text_field( $site_slide['eyebrow'] ) : '',
				'title'       => isset( $site_slide['title'] ) ? sanitize_text_field( $site_slide['title'] ) : '',
				'description' => isset( $site_slide['description'] ) ? wp_kses_post( $site_slide['description'] ) : '',
				'cta_url'     => isset( $site_slide['cta_url'] ) ? esc_url_raw( $site_slide['cta_url'] ) : '',
				'cta_text'    => isset( $site_slide['cta_text'] ) ? sanitize_text_field( $site_slide['cta_text'] ) : '',
				'position'    => ( isset( $site_slide['image_position'] ) && in_array( $site_slide['image_position'], array( 'center', 'top', 'bottom', 'left', 'right' ), true ) ) ? $site_slide['image_position'] : 'center',
			);
		}
	}
}

if ( $site_prefer_acf && ! empty( $site_acf_slides ) ) {
	$site_slides  = $site_acf_slides;
	$site_source = 'acf';
} else {
	// ── 2. Folder images (default) ────────────────────────────────────────────
	$site_hero_dir = get_stylesheet_directory() . '/assets/images/hero';

	/**
	 * Detailed banner content per image (keyed by filename without extension).
	 * Each slide gets a unique title, description and CTA — edit these to match
	 * what each photo actually shows.
	 */
	$site_hero_content = array(
		'Capture8'       => array(
			'title'       => 'Enterprise Skills for Young Entrepreneurs',
			'description' => 'Hands-on business training equipping youth and women with the tools to launch, manage and grow sustainable micro-enterprises across Kenyan communities.',
			'cta_text'    => 'Our Programs',
			'cta_url'     => '/our-work/',
		),
		'5-1'            => array(
			'title'       => 'Building Resilient Livelihoods',
			'description' => 'Community-led development that strengthens household incomes, improves market access and creates lasting economic opportunity for families.',
			'cta_text'    => 'Learn More',
			'cta_url'     => '/about-us/',
		),
		'camelmmilk'     => array(
			'title'       => 'Camel Milk Value Chain Development',
			'description' => 'Supporting pastoralist communities to add value, improve hygiene and connect camel milk products to profitable regional and urban markets.',
			'cta_text'    => 'See Our Impact',
			'cta_url'     => '/impact/',
		),
		'Capture'        => array(
			'title'       => 'Transforming Lives Since 1996',
			'description' => 'For over 25 years SITE has championed inclusive enterprise development, reaching thousands of entrepreneurs, women, youth and marginalized groups.',
			'cta_text'    => 'About SITE',
			'cta_url'     => '/about-us/',
		),
		'Masinga-group-training' => array(
			'title'       => 'Group Training in Masinga',
			'description' => 'Participatory enterprise and financial-literacy training sessions that give community groups the confidence to pool resources, start ventures and grow together.',
			'cta_text'    => 'Explore Training',
			'cta_url'     => '/our-work/',
		),
		'PWD-leaders'    => array(
			'title'       => 'Persons with Disabilities Leading Change',
			'description' => 'PWD leaders driving inclusive entrepreneurship — proving that with the right skills, mentorship and market linkabilities, disability is no barrier to business success.',
			'cta_text'    => 'Inclusion Work',
			'cta_url'     => '/focus-areas/',
		),
		'WWD-in-Machakos-during-soap-making-training' => array(
			'title'       => 'Soap-Making Training in Machakos',
			'description' => 'Women gaining practical soap-manufacturing skills to produce quality hygiene products, generate income and meet growing local demand in Machakos County.',
			'cta_text'    => 'View Programs',
			'cta_url'     => '/our-work/',
		),
		'WWDs-learning-how-to-make-liquid-soap-in-Machakos' => array(
			'title'       => 'Liquid Soap Production Skills',
			'description' => 'Step-by-step liquid-soap training that enables women-led groups to create market-ready products, reduce household costs and build small enterprises.',
			'cta_text'    => 'Get Involved',
			'cta_url'     => '/partnership/',
		),
	);

	if ( is_dir( $site_hero_dir ) ) {
		$site_allowed_ext  = array( 'jpg', 'jpeg', 'png', 'webp', 'gif' );
		$site_folder_files = glob( $site_hero_dir . '/*' );

		if ( ! empty( $site_folder_files ) ) {
			$site_source = 'folder';

			// Index available files by their label (filename minus extension
			// and dimension suffix) so we can pull them in content-array order.
			$site_file_index = array();
			foreach ( $site_folder_files as $site_file ) {
				if ( ! is_file( $site_file ) ) {
					continue;
				}
				$site_ext = strtolower( pathinfo( $site_file, PATHINFO_EXTENSION ) );
				if ( ! in_array( $site_ext, $site_allowed_ext, true ) ) {
					continue;
				}
				$site_basename = basename( $site_file );
				$site_label    = preg_replace( '/-\d+x\d+$/', '', pathinfo( $site_basename, PATHINFO_FILENAME ) );
				$site_file_index[ $site_label ] = array(
					'basename' => $site_basename,
					'url'      => get_stylesheet_directory_uri() . '/assets/images/hero/' . rawurlencode( $site_basename ),
				);
			}

			// 1. Slides that have explicit content — in content-array order.
			foreach ( $site_hero_content as $site_key => $site_content ) {
				if ( ! isset( $site_file_index[ $site_key ] ) ) {
					continue;
				}
				$site_label = ucwords( trim( preg_replace( '/\s+/', ' ', preg_replace( '/[-_]+/', ' ', $site_key ) ) ) );
				$site_slides[] = array(
					'type'        => 'file',
					'url'         => $site_file_index[ $site_key ]['url'],
					'alt'         => $site_label,
					'title'       => ! empty( $site_content['title'] ) ? $site_content['title'] : $site_label,
					'description' => isset( $site_content['description'] ) ? $site_content['description'] : '',
					'cta_url'     => isset( $site_content['cta_url'] ) ? $site_content['cta_url'] : '',
					'cta_text'    => isset( $site_content['cta_text'] ) ? $site_content['cta_text'] : '',
					'position'    => 'center',
				);
				unset( $site_file_index[ $site_key ] );
			}

			// 2. Any remaining files not in the content array — alphabetical.
			ksort( $site_file_index );
			foreach ( $site_file_index as $site_key => $site_file ) {
				$site_label = ucwords( trim( preg_replace( '/\s+/', ' ', preg_replace( '/[-_]+/', ' ', $site_key ) ) ) );
				$site_slides[] = array(
					'type'        => 'file',
					'url'         => $site_file['url'],
					'alt'         => $site_label,
					'title'       => $site_label,
					'description' => '',
					'cta_url'     => '',
					'cta_text'    => '',
					'position'    => 'center',
				);
			}
		}
	}

	// ACF slides only win if the folder is empty AND nothing is forced.
	if ( empty( $site_slides ) && ! empty( $site_acf_slides ) ) {
		$site_slides  = $site_acf_slides;
		$site_source = 'acf';
	}
}

// Nothing to show? Graceful hide.
if ( empty( $site_slides ) ) {
	return;
}

$site_total = count( $site_slides );
?>
<section
	class="site-hero-carousel"
	aria-roledescription="carousel"
	aria-label="<?php esc_attr_e( 'Featured stories', 'site-child' ); ?>"
	data-autoplay-delay="6000"
>
	<div class="site-carousel-frame">
		<div class="site-carousel-track" data-carousel-track>

			<?php $site_index = 0; ?>
		<?php foreach ( $site_slides as $site_slide ) : ?>
			<?php
			$site_is_first = ( 0 === $site_index );

			if ( 'attachment' === $site_slide['type'] ) {
				// ACF slide — render via WP attachment (responsive srcset).
				$site_image_id = $site_slide['image_id'];

				// Meaningful alt: attachment alt, falling back to the slide title.
				$site_alt = (string) get_post_meta( $site_image_id, '_wp_attachment_image_alt', true );
				if ( '' === trim( $site_alt ) && ! empty( $site_slide['title'] ) ) {
					$site_alt = $site_slide['title'];
				}

				$site_image_attrs = array(
					'class' => 'site-carousel-image',
					'alt'   => $site_alt,
					'style' => 'object-position:' . esc_attr( $site_slide['position'] ) . ';',
					'sizes' => '(max-width: 767px) 100vw, (max-width: 1199px) 100vw, 98vw',
				);

				if ( $site_is_first ) {
					// LCP candidate: load immediately, do not lazy-load.
					$site_image_attrs['loading']       = 'eager';
					$site_image_attrs['fetchpriority'] = 'high';
					$site_image_attrs['decoding']      = 'async';
				} else {
					$site_image_attrs['loading']  = 'lazy';
					$site_image_attrs['decoding'] = 'async';
				}

				$site_image_markup = wp_get_attachment_image( $site_image_id, 'site-hero', false, $site_image_attrs );
			} else {
				// Folder fallback — render a plain responsive <img>.
				$site_alt = $site_slide['alt'];

				$site_image_attrs = array(
					'class'    => 'site-carousel-image',
					'alt'      => $site_alt,
					'style'    => 'object-position:' . esc_attr( $site_slide['position'] ) . ';',
					'sizes'    => '(max-width: 767px) 100vw, (max-width: 1199px) 100vw, 98vw',
					'loading'  => $site_is_first ? 'eager' : 'lazy',
					'decoding' => 'async',
				);
				if ( $site_is_first ) {
					$site_image_attrs['fetchpriority'] = 'high';
				}

				$site_attr_string = '';
				foreach ( $site_image_attrs as $site_attr_key => $site_attr_val ) {
					$site_attr_string .= ' ' . esc_attr( $site_attr_key ) . '="' . esc_attr( $site_attr_val ) . '"';
				}

				$site_image_markup = '<img src="' . esc_url( $site_slide['url'] ) . '"' . $site_attr_string . ' width="1920" height="730" />';
			}

			$site_eyebrow  = $site_slide['eyebrow'];
			$site_title    = $site_slide['title'];
			$site_desc     = $site_slide['description'];
			$site_cta_url  = $site_slide['cta_url'];
			$site_cta_text = ( $site_cta_url && $site_slide['cta_text'] ) ? $site_slide['cta_text'] : '';

			$site_cta2_url  = isset( $site_slide['secondary_cta_url'] ) ? $site_slide['secondary_cta_url'] : '';
			$site_cta2_text = ( $site_cta2_url && isset( $site_slide['secondary_cta_text'] ) ) ? $site_slide['secondary_cta_text'] : '';
			?>
			<article
				class="site-carousel-slide<?php echo $site_is_first ? ' is-active' : ''; ?>"
				role="group"
				aria-roledescription="slide"
				aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total slides */ __( '%1$d of %2$d', 'site-child' ), $site_index + 1, $site_total ) ); ?>"
				<?php echo $site_is_first ? '' : 'aria-hidden="true"'; ?>
			>
				<figure class="site-carousel-media">
					<?php echo $site_image_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per-source above. ?>
				</figure>

					<?php if ( $site_eyebrow || $site_title || $site_desc || $site_cta_text ) : ?>
						<div class="site-carousel-panel">
							<?php if ( $site_eyebrow ) : ?>
								<p class="site-carousel-eyebrow"><?php echo esc_html( $site_eyebrow ); ?></p>
							<?php endif; ?>

							<?php if ( $site_title ) : ?>
								<?php if ( $site_is_first ) : ?>
									<h1 class="site-carousel-title"><?php echo esc_html( $site_title ); ?></h1>
								<?php else : ?>
									<h2 class="site-carousel-title"><?php echo esc_html( $site_title ); ?></h2>
								<?php endif; ?>
							<?php endif; ?>

							<?php if ( $site_desc ) : ?>
								<p class="site-carousel-description"><?php echo wp_kses_post( $site_desc ); ?></p>
							<?php endif; ?>

							<?php if ( $site_cta_text || $site_cta2_text ) : ?>
								<div class="site-carousel-actions">
									<?php if ( $site_cta_text ) : ?>
										<a class="site-carousel-cta" href="<?php echo esc_url( $site_cta_url ); ?>"><?php echo esc_html( $site_cta_text ); ?></a>
									<?php endif; ?>
									<?php if ( $site_cta2_text ) : ?>
										<a class="site-carousel-cta-secondary" href="<?php echo esc_url( $site_cta2_url ); ?>"><?php echo esc_html( $site_cta2_text ); ?> <span aria-hidden="true">&rarr;</span></a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</article>
				<?php
				$site_index++;
			endforeach;
			?>
		</div>
		<?php if ( $site_total > 1 ) : ?>
			<button
				type="button"
				class="site-carousel-arrow site-carousel-arrow--prev"
				data-carousel-prev
				aria-label="<?php esc_attr_e( 'Previous slide', 'site-child' ); ?>"
			>
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
			<button
				type="button"
				class="site-carousel-arrow site-carousel-arrow--next"
				data-carousel-next
				aria-label="<?php esc_attr_e( 'Next slide', 'site-child' ); ?>"
			>
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>

			<div class="site-carousel-footer">
				<div class="site-carousel-counter" aria-hidden="true">
					<span class="site-carousel-counter-current" data-carousel-current>01</span>
					<span class="site-carousel-counter-sep">/</span>
					<span class="site-carousel-counter-total"><?php echo esc_html( str_pad( (string) $site_total, 2, '0', STR_PAD_LEFT ) ); ?></span>
				</div>

				<button
					type="button"
					class="site-carousel-toggle"
					data-carousel-toggle
					aria-pressed="false"
					aria-label="<?php esc_attr_e( 'Pause automatic sliding', 'site-child' ); ?>"
				>
					<svg class="site-carousel-toggle-icon-pause" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
					<svg class="site-carousel-toggle-icon-play" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M7 4.5v15l13-7.5-13-7.5z"/></svg>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>
