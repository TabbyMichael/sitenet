<?php
/**
 * Template Name: Papers (revamp)
 * Description: Modernised resources page — bypasses the legacy SiteOrigin
 *              data for this page and lists the real PDF publications that
 *              exist in the media library.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$rv = array(
	'type'          => 'papers',
	'root_class'    => 'site-revamp--resource site-revamp--papers',
	'kicker'        => 'Resources · Papers & research',
	'title'         => 'Papers',
	'lead'          => 'Research, surveys, policy briefs and reports from SITE and its partners — available as free downloads.',
	'actions'       => array(
		array( 'label' => 'Browse case studies', 'url' => '/resources/case-studys/', 'variant' => 'primary' ),
		array( 'label' => 'View all stories', 'url' => '/stories/', 'variant' => 'ghost' ),
	),
	'intro_head'    => 'Publications',
	'intro_text'    => '<p>Every document below is a real SITE publication, stored in our media library and available for free download.</p>',
	'papers'        => array(
		array(
			'type'  => 'Research paper',
			'title' => 'Aspirations and gains of small-scale camel owners',
			'url'   => '/wp-content/uploads/2021/11/Aspirations_and_gains_of_-small-scale_camel_owner1.pdf',
		),
		array(
			'type'  => 'Case study',
			'title' => 'A successful trader in the camel milk value chain',
			'url'   => '/wp-content/uploads/2021/11/A_successful_trader_in_the_camel_milk_Value_chain1.pdf',
		),
		array(
			'type'  => 'Research paper',
			'title' => 'Harbole watering point: the convergence of life',
			'url'   => '/wp-content/uploads/2021/11/Harbole_watering_point_The_convergence_of_Life1.pdf',
		),
		array(
			'type'  => 'Research paper',
			'title' => 'Participation in the Water Resources Management',
			'url'   => '/wp-content/uploads/2021/11/Participation_in_the_Water_Resources_Management2.pdf',
		),
		array(
			'type'  => 'Policy brief',
			'title' => 'Policy brief: working spaces in Mathare',
			'url'   => '/wp-content/uploads/2021/11/PolicyBrief_Working_Spaces_in_Mathare1.pdf',
		),
		array(
			'type'  => 'Brochure',
			'title' => 'SITE brochure — 20th anniversary',
			'url'   => '/wp-content/uploads/2021/11/SITE_BROCHURE-20th_Anniversary3.pdf',
		),
		array(
			'type'  => 'Research paper',
			'title' => 'Social capital as a coping mechanism for women small scale traders in the informal economy in Nairobi, Kenya',
			'url'   => '/wp-content/uploads/2021/11/Social_Capital_as_a_Coping_Mechanism_for_Women1.pdf',
		),
		array(
			'type'  => 'Research paper',
			'title' => 'Women small scale traders businesses in the informal economy in Nairobi, Kenya: is profit-making a primary goal?',
			'url'   => '/wp-content/uploads/2021/11/Women_small_scale_traders..is_profit_making_a_primary_goal1.pdf',
		),
	),
	'resource_links'=> array(
		array(
			'icon'  => 'fa-book',
			'title' => 'Case studies',
			'desc'  => 'In-depth looks at how our projects create measurable change.',
			'url'   => '/resources/case-studys/',
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
	'cta_title'     => 'Can’t find what you’re looking for?',
	'cta_text'      => 'Our team can point you to related research, reports and programme results.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Ask our team',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-papers' );

get_footer();