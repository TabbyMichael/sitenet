<?php
/**
 * Template Name: Press Releases (revamp)
 * Description: Modernised resources page — bypasses the legacy SiteOrigin
 *              data for this page. Preserves the original release text as
 *              the featured item and lists real, recent story posts as
 *              updates. (No press-release post type exists yet — editors
 *              can add releases as posts and they will surface here.)
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Legacy "Latest updates" section removed — stories live on /stories/. */

$rv = array(
	'type'          => 'press-releases',
	'root_class'    => 'site-revamp--resource site-revamp--press-releases',
	'kicker'        => 'Resources · Press releases',
	'title'         => 'Press Releases',
	'lead'          => 'Official statements, announcements and updates from SITE Enterprise Promotion and its partners.',
	'actions'       => array(
		array( 'label' => 'Browse case studies', 'url' => site_child_resource_hub_url( 'case-studys' ), 'variant' => 'primary' ),
		array( 'label' => 'Browse papers', 'url' => site_child_resource_hub_url( 'papers' ), 'variant' => 'ghost' ),
	),
	/* Preserved from the legacy page content — a real SITE statement. */
	'featured'      => array(
		'date'  => '',
		'title' => 'Business community in Mathare calls for low-cost working spaces',
		'text'  => '<p>Current and prospective business owners in Mathare struggle to remain profitable and start new businesses due to the high costs of doing business within Mathare. A survey of the business community identified the lack of appropriate working spaces and related infrastructural services as the top barriers to conducting business in Mathare. Participants also highlighted the need for greater dialogue between the business community and government around investment and planning.</p><p>The provision of services to the business community is important for the development of a robust enterprise ecosystem in Mathare. The business community calls upon government, civil society and the private sector to take action. One of the most effective first steps to address these concerns is to provide low cost, appropriate working spaces in unutilized areas around the community, centralizing service delivery for local enterprises.</p><p>This policy brief discusses how the provision of working spaces and integration of infrastructure in Mathare promotes economic and social development in the community. The brief is derived from a mapping of the enterprise ecosystem and how it interacts with the business community that was carried out by SITE in Kiamaiko and Mlango Kubwa wards in Mathare sub-county between April and May 2018.</p>',
	),
	'contact_cards' => array(
		array(
			'icon'  => 'fa-phone',
			'title' => 'Request a Call Back',
			'text'  => 'Fill in the request form and we\'ll contact you by phone shortly.',
			'url'   => '/contact-us/',
		),
		array(
			'icon'  => 'fa-calendar',
			'title' => 'Make An Appointment',
			'text'  => 'To schedule an appointment and discuss your project with us.',
			'url'   => '/contact-us/',
		),
	),
	'resource_links'=> array(
		array(
			'icon'  => 'fa-book',
			'title' => 'Case studies',
			'desc'  => 'In-depth looks at how our projects create measurable change.',
			'url'   => site_child_resource_hub_url( 'case-studys' ),
		),
		array(
			'icon'  => 'fa-file-pdf-o',
			'title' => 'Papers',
			'desc'  => 'Research, surveys, policy briefs and reports as free PDFs.',
			'url'   => site_child_resource_hub_url( 'papers' ),
		),
		array(
			'icon'  => 'fa-video-camera',
			'title' => 'Video gallery',
			'desc'  => 'Short films and highlights from SITE programmes.',
			'url'   => '/galleries/gallery-full-width-2/',
		),
	),
	'cta_title'     => 'Media enquiries welcome',
	'cta_text'      => 'Journalists and partners are welcome to reach out for comment, interviews and background information.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Contact the team',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-press-releases' );

get_footer();