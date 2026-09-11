<?php
/**
 * Template Name: Empowering Women for Employment (revamp)
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
	'root_class'    => 'site-revamp--programme site-revamp--women',
	'kicker'        => 'Our work · Women & inclusion',
	'title'         => 'Empowering Women for Employment',
	'lead'          => 'We promote inclusive employment and economic opportunities for women and marginalized groups — persons with disabilities and refugees — through skills, markets and leadership.',
	'hero_image'    => '/wp-content/uploads/2025/03/1-1-e1742196391213.jpg',
	'hero_image_alt'=> 'Women entrepreneurs at a SITE training session',
	'actions'       => array(
		array( 'label' => 'Partner with us', 'url' => '/contact-us/', 'variant' => 'primary' ),
		array( 'label' => 'Explore our work', 'url' => '/ourwork/', 'variant' => 'ghost' ),
	),
	'intro_head'    => 'Empowering women and marginalized groups',
	'intro_text'    => '<p>SITE recognizes the unique barriers faced by women and marginalized groups — persons with disabilities (PWDs) and refugees. We promote inclusive employment opportunities and best practices that open doors to dignified work and decision-making.</p>',
	'features'      => array(
		array(
			'icon'  => 'fa-female',
			'title' => 'Employment & economic opportunities',
			'text'  => 'Opening practical pathways into work and enterprise for women.',
			'items' => array(
				'Entrepreneurship & financial literacy skills, access to markets and services.',
				'Informal sector ecosystem mapping & interventions to improve access to resources — business licensing, credit, affirmative funds, financial inclusion, safe work spaces and technology.',
				'Encouraging women’s participation in leadership roles and community, business and political decision-making processes.',
			),
		),
		array(
			'icon'  => 'fa-wheelchair',
			'title' => 'Persons with disabilities (PWDs)',
			'text'  => 'Implementing strategies to improve service delivery for persons with disabilities, strengthening disability organizations, and ensuring their inclusion in local decision-making processes.',
		),
		array(
			'icon'  => 'fa-life-ring',
			'title' => 'Refugees support',
			'text'  => 'Equipping refugees with entrepreneurship skills to start and grow businesses, helping them integrate into host communities and contribute to national development initiatives such as the Shirika plan.',
		),
		array(
			'icon'  => 'fa-balance-scale',
			'title' => 'Business rights',
			'text'  => 'Advocating for women’s rights and social protection, addressing gender-based violence and discrimination, and promoting gender equality.',
		),
	),
	'gallery'       => array(
		array( 'url' => '/wp-content/uploads/2025/03/5-1.jpg',   'alt' => 'Women in a soap-making training session' ),
		array( 'url' => '/wp-content/uploads/2025/03/4.png',     'alt' => 'Women entrepreneurs at a market day' ),
		array( 'url' => '/wp-content/uploads/2025/03/3.jpg',     'alt' => 'Community meeting on women’s economic empowerment' ),
		array( 'url' => '/wp-content/uploads/2025/03/2-1.png',   'alt' => 'Women leaders during a savings group meeting' ),
		array( 'url' => '/wp-content/uploads/2025/03/u2.jpg',    'alt' => 'Training session with women producers' ),
	),
	'stories'       => site_child_revamp_stories( 'empowering-women-for-employment', 3 ),
	'stories_url'   => '/stories/',
	'stories_head'  => 'Stories of women’s empowerment',
	'stories_text'  => 'Real journeys of women building income, leadership and resilience.',
	'programmes'    => array(
		array(
			'kicker'  => 'Youth & Skills',
			'title'   => 'Skilling Youth for Employment',
			'summary' => 'Market-led technical, vocational, entrepreneurship, and mentorship support that helps young people transition from training into dignified work.',
			'link'    => '/sample-page-2/',
			'image'   => '/wp-content/uploads/2021/11/Gallery-1-850x567.jpg',
		),
		array(
			'kicker'  => 'Inclusive Markets',
			'title'   => 'Enterprise Development & Value Chains',
			'summary' => 'Business development and value-chain strengthening for entrepreneurs, MSMEs, and producer groups seeking better markets and sustainable growth.',
			'link'    => '/enterprise-development-and-value-chains/',
			'image'   => '/wp-content/uploads/2021/11/journeyofgrowth-850x567.jpg',
		),
		array(
			'kicker'  => 'Resilient Communities',
			'title'   => 'Food Security & Climate Action',
			'summary' => 'Climate-smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
			'link'    => '/climate-actions/',
			'image'   => '/wp-content/uploads/2021/11/harbole-water-850x567.jpg',
		),
	),
	'cta_title'     => 'Support women’s economic empowerment',
	'cta_text'      => 'Partner with us to expand employment, leadership and enterprise opportunities for women and marginalized groups across Kenya.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Start a conversation',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-programme' );

get_footer();