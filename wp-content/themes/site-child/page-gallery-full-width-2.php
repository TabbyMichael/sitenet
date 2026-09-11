<?php
/**
 * Template Name: Video Gallery (revamp)
 * Description: Modernised gallery page — bypasses the legacy SiteOrigin
 *              data for this page and presents the page's real YouTube
 *              videos as a clean, responsive grid. Videos open on YouTube
 *              (no third-party embeds or thumbnail requests).
 *
 * NOTE for editors: titles below come from YouTube's public oEmbed API.
 * A third legacy URL (https://youtu.be/_paqJ_hXhjk) returns 404 publicly
 * and is left out until the upload is made public again.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$rv = array(
	'type'          => 'video-gallery',
	'root_class'    => 'site-revamp--gallery site-revamp--video',
	'kicker'        => 'Media · Video',
	'title'         => 'Video Gallery',
	'lead'          => 'Short films and highlights from SITE programmes — trainings, graduations and community moments, watchable on YouTube.',
	'actions'       => array(
		array( 'label' => 'View photos', 'url' => '/galleries/gallery-lightbox/', 'variant' => 'primary' ),
		array( 'label' => 'Browse papers', 'url' => '/resources/papers/', 'variant' => 'ghost' ),
	),
	'videos'        => array(
		array(
			'title' => 'Camili Documentary: A story of families and their camels thriving in drought-prone lands',
			'url'   => 'https://www.youtube.com/watch?v=bjAVuIYPdI0',
			'meta'  => 'SITE Enterprise Promotion · YouTube',
		),
		array(
			'title' => 'Graduation ceremony organized by SITE and ILO at Garissa Vocational Training Center',
			'url'   => 'https://www.youtube.com/watch?v=EAi9jcKILbI',
			'meta'  => 'SITE Enterprise Promotion · YouTube',
		),
	),
	'cta_title'     => 'Have a story to share?',
	'cta_text'      => 'We regularly document our work on video — suggestions and collaborations are welcome.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Get in touch',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-gallery-video' );

get_footer();