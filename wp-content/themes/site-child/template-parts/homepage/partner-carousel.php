<?php
/**
 * Our Partners & Donors — Swiper.js carousels.
 *
 * Both carousels use the auto-detected logo images from
 * assets/images/partners/ (drop in new logo files and they appear
 * automatically). The pool is split in half: first half → donors,
 * second half → partners. Empty carousels are skipped entirely.
 *
 * Assets (Swiper 11 CDN + theme CSS/JS) are enqueued in functions.php.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a single carousel slide (card).
 *
 * @param array $item      Slide item.
 * @param bool  $duplicate True when rendering the loop-duplicate set.
 * @return string
 */
function site_pd_render_slide( $item, $duplicate = false ) {
	$aria  = $duplicate ? ' aria-hidden="true"' : '';
	$inner = '';

	if ( 'image' === $item['type'] ) {
		if ( ! empty( $item['id'] ) ) {
			$inner = wp_get_attachment_image(
				$item['id'],
				'medium',
				false,
				array(
					'alt'     => $item['title'],
					'loading' => 'lazy',
				)
			);
		} else {
			$inner = '<img src="' . esc_url( $item['src'] ) . '" alt="' . esc_attr( $item['alt'] ) . '" loading="lazy">';
		}

		if ( ! empty( $item['link'] ) ) {
			$inner = '<a href="' . esc_url( $item['link'] ) . '" title="' . esc_attr( $item['title'] ) . '"' . ( $duplicate ? ' tabindex="-1"' : '' ) . '>' . $inner . '</a>';
		}
	} else {
		$inner = '<span class="pd-logo-text ' . esc_attr( $item['class'] ) . '">' . esc_html( $item['label'] ) . '</span>';
	}

	return '<div class="swiper-slide"' . $aria . '><div class="pd-card">' . $inner . '</div></div>';
}

// ── Auto-detected partner / donor logos ─────────────────────────────────────
// Images live in assets/images/partners/. Drop in new logo files and they
// appear here automatically. These replace the old text-based "black cards".
$site_pd_image_dir = get_stylesheet_directory() . '/assets/images/partners';
$site_pd_images    = array();

if ( is_dir( $site_pd_image_dir ) ) {
	$site_pd_allowed = array( 'jpg', 'jpeg', 'png', 'webp', 'gif', 'svg' );
	foreach ( glob( $site_pd_image_dir . '/*' ) as $site_pd_file ) {
		if ( ! is_file( $site_pd_file ) ) {
			continue;
		}
		$site_pd_ext = strtolower( pathinfo( $site_pd_file, PATHINFO_EXTENSION ) );
		if ( ! in_array( $site_pd_ext, $site_pd_allowed, true ) ) {
			continue;
		}

		$site_pd_basename = basename( $site_pd_file );
		$site_pd_url      = get_stylesheet_directory_uri() . '/assets/images/partners/' . rawurlencode( $site_pd_basename );

		// Derive a readable title from the filename.
		$site_pd_label = pathinfo( $site_pd_basename, PATHINFO_FILENAME );
		$site_pd_label = preg_replace( '/[-_\d]+/', ' ', $site_pd_label );
		$site_pd_label = ucwords( trim( $site_pd_label ) );

		$site_pd_images[] = array(
			'type'  => 'image',
			'src'   => $site_pd_url,
			'alt'   => $site_pd_label,
			'title' => $site_pd_label,
		);
	}
}

// Split the logo pool: first half → donors, second half → partners.
$site_pd_split    = ceil( count( $site_pd_images ) / 2 );
$site_pd_donors   = array_slice( $site_pd_images, 0, $site_pd_split );
$site_pd_partners = array_slice( $site_pd_images, $site_pd_split );

// Fall back to text labels only when no logo images exist at all.
if ( empty( $site_pd_images ) ) {
	$site_pd_donors = array(
		array( 'type' => 'text', 'label' => 'APT', 'class' => 'lg-apt' ),
		array( 'type' => 'text', 'label' => 'CIPE', 'class' => 'lg-cipe' ),
		array( 'type' => 'text', 'label' => 'KMT', 'class' => 'lg-kmt' ),
		array( 'type' => 'text', 'label' => 'MESPT', 'class' => 'lg-mespt' ),
		array( 'type' => 'text', 'label' => 'MCI Relief', 'class' => 'lg-mci' ),
		array(
			'type' => 'image',
			'src'  => content_url( 'uploads/2025/03/ilo-scaled-1.jpg' ),
			'alt'  => 'International Labour Organization',
		),
		array(
			'type' => 'image',
			'src'  => content_url( 'uploads/2021/11/eu.jpg' ),
			'alt'  => 'European Union',
		),
	);

	$site_pd_partners = array(
		array( 'type' => 'text', 'label' => 'County Government', 'class' => 'lg-county' ),
		array( 'type' => 'text', 'label' => 'NGO Partners', 'class' => 'lg-ngo' ),
		array( 'type' => 'text', 'label' => 'Private Sector', 'class' => 'lg-private' ),
	);
}

// Both carousels use the same auto-detected folder images
// (assets/images/partners/) - drop logo files into that folder and they
// appear automatically, split between "Our Donors" and "Our Partners".

$site_pd_groups = array(
	array( 'label' => 'Our Donors', 'items' => $site_pd_donors, 'noun' => 'donors' ),
	array( 'label' => 'Our Partners', 'items' => $site_pd_partners, 'noun' => 'partners' ),
);
?>

<section class="pd-section" aria-label="Our partners and donors">
	<div class="pd-header">
		<h2 class="pd-title"><?php echo esc_html( 'Our Partners & Donors' ); ?></h2>
		<p class="pd-lede">We extend our deepest appreciation to our dedicated donors and strategic partners. Your continued collaboration empowers our mission and creates <strong>sustainable community impact</strong>.</p>
	</div>

	<?php foreach ( $site_pd_groups as $site_pd_index => $site_pd_group ) : ?>
		<?php if ( empty( $site_pd_group['items'] ) ) continue; ?>
		
		<h3 class="pd-sub"<?php echo 0 === $site_pd_index ? '' : ' style="margin-top:56px"'; ?>><?php echo esc_html( $site_pd_group['label'] ); ?></h3>

		<div class="swiper pd-swiper" data-pd-carousel>
			<div class="swiper-wrapper">
				<?php
				// Set A — the real slides.
				foreach ( $site_pd_group['items'] as $site_pd_item ) {
					echo site_pd_render_slide( $site_pd_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in function.
				}

				// Set B — duplicated slides so Swiper's infinite loop stays seamless.
				foreach ( $site_pd_group['items'] as $site_pd_item ) {
					echo site_pd_render_slide( $site_pd_item, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in function.
				}
				?>
			</div>

			<button class="pd-nav pd-prev" type="button" aria-label="Previous <?php echo esc_attr( $site_pd_group['noun'] ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
			</button>
			<button class="pd-nav pd-next" type="button" aria-label="Next <?php echo esc_attr( $site_pd_group['noun'] ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
			</button>
			<div class="swiper-pagination"></div>
		</div>
	<?php endforeach; ?>
</section>
