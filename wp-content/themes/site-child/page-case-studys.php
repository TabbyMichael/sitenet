<?php
/**
 * Template Name: Case Studies (revamp)
 * Note: the slug "case-studys" (missing "ie") matches the existing page slug
 * in the database. Do not rename this file without also updating the slug
 * mapping in site_child_revamp_page_template() in functions.php.
  * Description: Modernised case-studies page — bypasses the legacy SiteOrigin
 *              data and renders four real SITE intervention case studies,
 *              each pairing a field photo with a downloadable PDF report.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* The four case-study reports on this page: each pairs a field photo with a
   downloadable PDF report. Recovered from the original SiteOrigin panels data
   on this page (post 673) so the real SITE interventions are shown instead of
   the generic blog feed. */
$rv_cases = array(
	array(
		'title'   => 'Water Resources Management',
		'link'    => '/wp-content/uploads/2021/11/Participation_in_the_Water_Resources_Management2.pdf',
		'date'    => '',
		'excerpt' => 'Community participation in water resources management — how local groups took ownership of their water catchment for sustainable supply.',
		'type'    => 'Case study',
		'image'   => '/wp-content/uploads/2021/11/wrm-720x400.png',
		'alt'     => 'Community water resource management session',
		'is_pdf'  => true,
	),
	array(
		'title'   => 'A journey of Growth',
		'link'    => '/wp-content/uploads/2021/11/Aspirations_and_gains_of_-small-scale_camel_owner1.pdf',
		'date'    => '',
		'excerpt' => 'The journey of small-scale camel owners from subsistence herding to a thriving value chain enterprise.',
		'type'    => 'Case study',
		'image'   => '/wp-content/uploads/2021/11/journeyofgrowth2-720x400.png',
		'alt'     => 'Enterprise development session with community members',
		'is_pdf'  => true,
	),
	array(
		'title'   => 'Harbole Watering Point',
		'link'    => '/wp-content/uploads/2021/11/Harbole_watering_point_The_convergence_of_Life1.pdf',
		'date'    => '',
		'excerpt' => 'How a single watering point became a convergence of life for people, livestock, and enterprise in Harbole.',
		'type'    => 'Case study',
		'image'   => '/wp-content/uploads/2021/11/habole2-720x400.png',
		'alt'     => 'Harbole watering point serving families and livestock',
		'is_pdf'  => true,
	),
	array(
		'title'   => 'Camel milk Value chain',
		'link'    => '/wp-content/uploads/2021/11/A_successful_trader_in_the_camel_milk_Value_chain1.pdf',
		'date'    => '',
		'excerpt' => 'From small-scale producer to growing wholesale business — a trader\'s journey through the camel milk value chain.',
		'type'    => 'Case study',
		'image'   => '/wp-content/uploads/2021/11/bilatha-1-720x400.jpg',
		'alt'     => 'Women-led enterprise group members together',
		'is_pdf'  => true,
	),
);

$rv = array(
	'type'          => 'case-studies',
	'root_class'    => 'site-revamp--resource site-revamp--case-studies',
	'kicker'        => 'Resources · Case studies',
	'title'         => 'Case Studies',
	'lead'          => 'In-depth looks at how our projects and partnerships create measurable change in the lives of communities and enterprises.',
	'actions'       => array(
		array( 'label' => 'Browse papers', 'url' => '/resources/papers/', 'variant' => 'primary' ),
		array( 'label' => 'View all stories', 'url' => '/stories/', 'variant' => 'ghost' ),
	),
	'intro_head'    => 'Stories of change',
	'intro_text'    => '<p>Each case study documents a real SITE intervention — the challenge, the approach and the difference it made for the people involved.</p>',
	'cases'         => $rv_cases,
		/* Showcase strip removed — its four images now serve as the case-study
	   card images above, so a second strip would be redundant. */
	'showcases'     => array(),
	'resource_links'=> array(
		array(
			'icon'  => 'fa-file-pdf-o',
			'title' => 'Papers',
			'desc'  => 'Research, surveys, policy briefs and reports as free PDFs.',
			'url'   => '/resources/papers/',
		),
		array(
			'icon'  => 'fa-bullhorn',
			'title' => 'Press releases',
			'desc'  => 'Official statements, announcements and updates from SITE.',
			'url'   => '/resources/irrigation-and-drainage/',
		),
		array(
			'icon'  => 'fa-camera',
			'title' => 'Photo gallery',
			'desc'  => 'A visual journey through our programmes across Kenya.',
			'url'   => '/galleries/gallery-lightbox/',
		),
	),
	'cta_title'     => 'Want to know more about our impact?',
	'cta_text'      => 'Our team can share results, publications and lessons from across our programmes.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Talk to our team',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-case-studies' );

get_footer();