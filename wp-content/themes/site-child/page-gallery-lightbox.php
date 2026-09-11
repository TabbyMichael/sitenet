<?php
/**
 * Template Name: Photo Gallery (revamp)
 * Description: Modernised gallery page — bypasses the legacy SiteOrigin
 *              data for this page and renders the 25 real media-library
 *              photos that the legacy gallery referenced, in a modern
 *              lightbox grid (vanilla JS, no library).
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* The exact media-library attachments the legacy gallery referenced. */
$rv_photo_ids = array(
	7513, 7512, 7511, 7510, 7509, 7508, 7507, 7506, 7505, 7504, 7503, 7502,
	7560, 7562, 7567, 7568, 7571, 7572, 7573, 7574, 7577, 7578, 7579, 7582, 7583,
);

$rv_photo_posts = get_posts(
	array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'post__in'       => $rv_photo_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => 50,
		'no_found_rows'  => true,
	)
);

$rv_photos = array();
foreach ( $rv_photo_posts as $rv_photo_post ) {
	$rv_photo_large = wp_get_attachment_image_url( $rv_photo_post->ID, 'large' );
	$rv_photo_thumb = wp_get_attachment_image_url( $rv_photo_post->ID, 'medium' );

	if ( ! $rv_photo_large ) {
		$rv_photo_large = wp_get_attachment_url( $rv_photo_post->ID );
	}
	if ( ! $rv_photo_thumb ) {
		$rv_photo_thumb = $rv_photo_large;
	}

	$rv_photos[] = array(
		'url'     => $rv_photo_large,
		'image'   => $rv_photo_thumb,
		'caption' => get_the_title( $rv_photo_post ),
	);
}

$rv = array(
	'type'          => 'photo-gallery',
	'root_class'    => 'site-revamp--gallery site-revamp--photo',
	'kicker'        => 'Media · Photos',
	'title'         => 'Photo Gallery',
	'lead'          => 'A visual journey through our programmes and the communities we serve across Kenya.',
	'actions'       => array(
		array( 'label' => 'Watch videos', 'url' => '/galleries/gallery-full-width-2/', 'variant' => 'primary' ),
		array( 'label' => 'Browse case studies', 'url' => '/resources/case-studys/', 'variant' => 'ghost' ),
	),
	'photos'        => $rv_photos,
	'photo_note'    => count( $rv_photos ) . ' photos · select any image to view it enlarged',
	'cta_title'     => 'See our work up close',
	'cta_text'      => 'Visit us in the field, partner on a project or arrange a field visit to see our programmes in action.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Arrange a visit',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-gallery-photo' );

get_footer();