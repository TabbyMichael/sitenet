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

$rv = array(
	'type'          => 'programme',
	'root_class'    => 'site-revamp--programme site-revamp--value-chains',
	'kicker'        => 'Our work · Inclusive markets',
	'title'         => 'Value Chains for Enterprise Development',
	'lead'          => 'We strengthen producers, MSMEs and cooperatives to improve productivity, add value and connect small enterprises to bigger markets — from camel milk and dairy to crops, beekeeping and soapstone.',
	'hero_image'    => '/wp-content/uploads/2025/03/kyusyani-marketing-cluster-e1741789378810.jpg',
	'hero_image_alt'=> 'Members of a marketing cluster meeting in Kyusyani',
	'actions'       => array(
		array( 'label' => 'Partner with us', 'url' => '/contact-us/', 'variant' => 'primary' ),
		array( 'label' => 'Explore our work', 'url' => '/ourwork/', 'variant' => 'ghost' ),
	),
	'intro_head'    => 'Building stronger, fairer value chains',
	'intro_text'    => '<p>SITE supports group management and self-regulation, strengthens cooperatives for product aggregation, and facilitates access to business information through blended-learning and mobile-based digital platforms.</p>',
	'features'      => array(
		array(
			'icon'  => 'fa-line-chart',
			'title' => 'Entrepreneurship skills & financial literacy',
			'text'  => 'Supporting group management and self-regulation, strengthening cooperatives for product aggregation, and facilitating access to business information through blended-learning and mobile-based digital platforms.',
		),
		array(
			'icon'  => 'fa-truck',
			'title' => 'Value chains development',
			'text'  => 'Facilitating access to quality inputs, increasing productivity, supporting value addition and processing by small enterprises, and facilitating market linkages between producers and big buyers.',
			'items' => array(
				'Camel milk value chain',
				'Dairy milk value chain',
				'Crops value chain',
				'Beekeeping value chain',
				'Soapstone value chain',
			),
		),
		array(
			'icon'  => 'fa-mobile',
			'title' => 'Technology & post-harvest solutions',
			'text'  => 'Piloting and testing digital tools — such as the HerVenture business application with women in the informal sector — leveraging AI tools for business growth, and adopting appropriate technologies to improve post-harvest handling.',
		),
		array(
			'icon'  => 'fa-bank',
			'title' => 'Influencing policy & regulatory frameworks',
			'text'  => 'Collaborating with SMEs, stakeholders and government to push for sustainable business practices and the inclusion of MSMEs and persons with disabilities.',
		),
	),
	'gallery'       => array(
		array( 'url' => '/wp-content/uploads/2025/03/3-1.jpg',   'alt' => 'Artisans working on soapstone products' ),
		array( 'url' => '/wp-content/uploads/2025/03/2.jpg',     'alt' => 'Produce being prepared for market' ),
		array( 'url' => '/wp-content/uploads/2025/03/1-2.jpg',   'alt' => 'Cooperative members sorting harvest' ),
		array( 'url' => '/wp-content/uploads/2025/03/DSCN2570.jpg', 'alt' => 'Field visit with value chain partners' ),
	),
	'stories'       => site_child_revamp_stories( 'enterprise-development-and-value-chains', 3 ),
	'stories_url'   => '/stories/',
	'stories_head'  => 'Stories from the value chains',
	'stories_text'  => 'Real updates from producers, cooperatives and markets we work with.',
	'programmes'    => array(
		array(
			'kicker'  => 'Youth & Skills',
			'title'   => 'Skilling Youth for Employment',
			'summary' => 'Market-led technical, vocational, entrepreneurship, and mentorship support that helps young people transition from training into dignified work.',
			'link'    => '/sample-page-2/',
			'image'   => '/wp-content/uploads/2021/11/Gallery-1-850x567.jpg',
		),
		array(
			'kicker'  => 'Women & Inclusion',
			'title'   => 'Empowering Women for Employment',
			'summary' => 'Practical pathways for women and marginalized groups to build income, leadership, resilience, and stronger decision-making power.',
			'link'    => '/empowering-women-for-employment/',
			'image'   => '/wp-content/uploads/2021/11/bilatha-850x567.jpg',
		),
		array(
			'kicker'  => 'Resilient Communities',
			'title'   => 'Food Security & Climate Action',
			'summary' => 'Climate-smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
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