<?php
/**
 * Template Name: Food Security & Climate Action (revamp)
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
	'root_class'    => 'site-revamp--programme site-revamp--climate',
	'kicker'        => 'Our work · Resilient communities',
	'title'         => 'Food Security & Climate Actions towards Resilient Communities',
	'lead'          => 'We promote climate-smart farming, sustainable practices and water security so communities can adapt, thrive and secure their food and household incomes.',
	'hero_image'    => '/wp-content/uploads/2025/03/mai.jpg',
	'hero_image_alt'=> 'Community members at a watering point in a drought-prone area',
	'actions'       => array(
		array( 'label' => 'Partner with us', 'url' => '/contact-us/', 'variant' => 'primary' ),
		array( 'label' => 'Explore our work', 'url' => '/ourwork/', 'variant' => 'ghost' ),
	),
	'intro_head'    => 'Climate and sustainability initiatives',
	'intro_text'    => '<p>Our initiatives help communities adapt to a changing climate through resilient farming, renewable energy technologies and secure water resources.</p>',
	'features'      => array(
		array(
			'icon'  => 'fa-leaf',
			'title' => 'Climate-smart practices',
			'text'  => 'Promoting climate-resilient farming through drought-resistant crops, soil and water conservation techniques, and climate-adapted livestock systems such as camel husbandry. We equip communities with knowledge and practical skills to strengthen livelihoods, food security and household incomes.',
		),
		array(
			'icon'  => 'fa-recycle',
			'title' => 'Sustainable practices',
			'text'  => 'Encouraging environmentally friendly agricultural and livelihood practices, including piloting and scaling solar-powered technologies for water pumping and milk cooling — supporting community-based sustainable initiatives for socioeconomic development.',
		),
		array(
			'icon'  => 'fa-tint',
			'title' => 'Water security',
			'text'  => 'Enhancing the availability and sustainability of water resources by strengthening community water infrastructure, improving water management practices, and ensuring equitable access for domestic and productive uses.',
		),
	),
	'gallery'       => array(
		array( 'url' => '/wp-content/uploads/2025/03/c.jpg',                'alt' => 'Community members at a watering point' ),
		array( 'url' => '/wp-content/uploads/2025/03/story-1a_200X200.jpg', 'alt' => 'Livestock adapting to dryland conditions' ),
		array( 'url' => '/wp-content/uploads/2025/03/story-7a_200X200.jpg', 'alt' => 'Household food production in the drylands' ),
		array( 'url' => '/wp-content/uploads/2025/03/story-11a_200X200.jpg', 'alt' => 'Community members at a water harvesting site' ),
	),
	'stories'       => site_child_revamp_stories( 'food-security-climate-actions-towards-resilient-communities', 3 ),
	'stories_url'   => '/stories/',
	'stories_head'  => 'Stories on food security & climate',
	'stories_text'  => 'Updates from communities adapting and thriving in a changing climate.',
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
			'kicker'  => 'Women & Inclusion',
			'title'   => 'Empowering Women for Employment',
			'summary' => 'Practical pathways for women and marginalized groups to build income, leadership, resilience, and stronger decision-making power.',
			'link'    => '/empowering-women-for-employment/',
			'image'   => '/wp-content/uploads/2021/11/bilatha-850x567.jpg',
		),
	),
	'cta_title'     => 'Help us build climate-resilient communities',
	'cta_text'      => 'Whether you are a community group, donor or technical partner, we would love to work with you on food security and climate action.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Get in touch',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-programme' );

get_footer();