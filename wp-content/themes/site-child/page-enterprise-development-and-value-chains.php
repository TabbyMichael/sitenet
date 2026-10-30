<?php
/**
 * Template Name: Value Chains for Enterprise Development (revamp)
 * Description: Modernised programme page — bypasses the legacy Elementor
 *              data for this page and renders a designed layout that pulls
 *              real programme content, local imagery and live story posts.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Gallery imagery for this programme lives in the child theme so it travels
// with the code and stays version-controlled (matches the Focus Areas pattern).
$rv_enterprise_uri = get_stylesheet_directory_uri() . '/assets/images/enterprise/';

$rv = array(
	'type'          => 'programme',
	'root_class'    => 'site-revamp--programme site-revamp--value-chains',
	// The legacy Elementor body for this page duplicates the designed Features
	// section below and carries outdated photos (produce sacks, cloth/soap
	// slider) that live in post meta and cannot be edited from PHP. Suppress it
	// so only the designed sections render — including the new gallery imagery.
	'hide_legacy_content' => true,
	'kicker'        => 'Our work · Inclusive markets',
	'title'         => 'Value Chains for Enterprise Development',
	'lead'          => 'We strengthen producers, MSMEs and cooperatives to improve productivity, add value and connect small enterprises to bigger markets from camel milk and dairy to crops, beekeeping and soapstone.',
	'hero_image'    => $rv_enterprise_uri . 'soapstone-crafts.jpg',
	'hero_image_alt'=> 'Finished soapstone carvings and tableware displayed on a market table',
	'actions'       => array(
		array( 'label' => 'Partner with us', 'url' => '/contact-us/', 'variant' => 'primary' ),
		array( 'label' => 'Explore our work', 'url' => '/ourwork/', 'variant' => 'ghost' ),
	),
	'intro_head'    => 'Building stronger, fairer value chains',
	'intro_text'    => '<p>SITE supports mobilization and management of business groups. Self regulation and organizing groups to meet required standards. Strengthening producer groups.</p>',
	'features'      => array(
		array(
			'icon'  => 'fa-line-chart',
			'title' => 'Entrepreneurship skills & financial literacy',
			'text'  => 'Supporting group management and self regulation, strengthening cooperatives for product aggregation, and facilitating access to business information through blended learning and mobile based digital platforms.',
		),
		array(
			'icon'  => 'fa-truck',
			'title' => 'Value chains development',
			'text'  => 'Facilitating access to quality inputs, increasing productivity, supporting value addition and processing by small enterprises, and facilitating market linkages between producers and big buyers.',
			'items' => array(
				'Gourd value chain',
				'Self selection',
				'Self regulation & organizing groups to meet standards',
				'Strengthening producer groups',
			),
		),
		array(
			'icon'  => 'fa-mobile',
			'title' => 'Technology & post harvest solutions',
			'text'  => 'Piloting and testing digital tools such as the HerVenture business application with women in the informal sector leveraging AI tools for business growth, and adopting appropriate technologies to improve post harvest handling.',
		),
		array(
			'icon'  => 'fa-bank',
			'title' => 'Influencing policy & regulatory frameworks',
			'text'  => 'Collaborating with SMEs, stakeholders and government to push for sustainable business practices and the inclusion of MSMEs and persons with disabilities.',
		),
	),
	'gallery'       => array(
		array( 'url' => $rv_enterprise_uri . 'quarry-collage.jpg',    'alt' => 'Collage of a sunset over a stone quarry and artisans splitting soapstone blocks by hand' ),
		array( 'url' => $rv_enterprise_uri . 'kisac-fair-trade.jpg',  'alt' => 'KISAC Fair Trade soapstone producers posing behind their banner in a forest' ),
		array( 'url' => $rv_enterprise_uri . 'soapstone-crafts.jpg',  'alt' => 'Finished soapstone carvings and tableware displayed on a market table' ),
		array( 'url' => $rv_enterprise_uri . 'soapstone-carving.jpg', 'alt' => 'A smiling soapstone artisan carving a figure with a hammer and chisel' ),
	),
	'stories'       => site_child_revamp_stories( 'enterprise-development', 3 ),
	'stories_url'   => '/stories/',
	'stories_head'  => 'Stories from the value chains',
	'stories_text'  => 'Real updates from producers, cooperatives and markets we work with.',
	'programmes'    => array(
		array(
			'kicker'  => 'Youth & Skills',
			'title'   => 'Skilling Youth for Employment',
			'summary' => 'Market led technical, vocational, entrepreneurship, and mentorship support that helps young people transition from training into dignified work.',
			'link'    => '/sample-page-2/',
			'image'   => '/wp-content/uploads/2021/11/Gallery-1-850x567.jpg',
		),
		array(
			'kicker'  => 'Women & Inclusion',
			'title'   => 'Empowering Women for Employment',
			'summary' => 'Practical pathways for women and marginalized groups to build income, leadership, resilience, and stronger decision making power.',
			'link'    => '/empowering-women-for-employment/',
			'image'   => '/wp-content/uploads/2021/11/bilatha-850x567.jpg',
		),
		array(
			'kicker'  => 'Resilient Communities',
			'title'   => 'Food Security & Climate Action',
			'summary' => 'Climate smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
			'link'    => '/climate-actions/',
			'image'   => '/wp-content/uploads/2021/11/harbole-water-850x567.jpg',
		),
	),
	'cta_title'     => 'Interested in inclusive enterprise development?',
	'cta_text'      => 'Whether you are an entrepreneur, a partner or a funder, we would love to explore how value chains can grow together.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Talk to our team',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-programme' );

get_footer();