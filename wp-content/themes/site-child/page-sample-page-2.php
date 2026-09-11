<?php
/**
 * Template Name: Skilling Youth for Employment (revamp)
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
	'root_class'    => 'site-revamp--programme site-revamp--youth',
	'kicker'        => 'Our work · Youth & skills',
	'title'         => 'Skilling Youth for Employment',
	'lead'          => 'SITE is dedicated to providing youth with the tools they need to succeed and build resilience in competitive job markets.',
	'hero_image'    => '/wp-content/uploads/2025/03/1.jpg',
	'hero_image_alt'=> 'Young people at a SITE skills training session',
	'actions'       => array(
		array( 'label' => 'Partner with us', 'url' => '/contact-us/', 'variant' => 'primary' ),
		array( 'label' => 'Explore our work', 'url' => '/ourwork/', 'variant' => 'ghost' ),
	),
	'intro_head'    => 'Preparing youth for dignified work',
	'intro_text'    => '<p>Through market-led training, mentorship and hands-on experience, we help young people turn skills into incomes, enterprises and careers.</p>',
	'features'      => array(
		array(
			'icon'  => 'fa-graduation-cap',
			'title' => 'Youth entrepreneurship & financial literacy',
			'text'  => 'SITE empowers young people with the knowledge and tools to start, manage and grow sustainable businesses, enhancing their economic independence and resilience.',
		),
		array(
			'icon'  => 'fa-wrench',
			'title' => 'Vocational & life skills development',
			'text'  => 'We design market-oriented training programmes that equip youth with practical vocational skills and essential life skills — such as communication, self-awareness and problem-solving — to provide solutions to community needs and thrive in the job market.',
		),
		array(
			'icon'  => 'fa-laptop',
			'title' => 'Digital literacy & access to opportunities',
			'text'  => 'Through mobile devices and access to digital platforms, youth access critical resources including market information, financial services and employment opportunities — bridging the digital divide in underserved areas.',
		),
		array(
			'icon'  => 'fa-handshake-o',
			'title' => 'Internships, mentorship & hands-on training',
			'text'  => 'In partnership with county departments and private sector actors, SITE facilitates real-world experience through internships and host-training, preparing youth for meaningful employment and leadership roles.',
		),
	),
	'gallery'       => array(
		array( 'url' => '/wp-content/uploads/2025/03/1.jpg',       'alt' => 'Youth in a vocational skills class' ),
		array( 'url' => '/wp-content/uploads/2025/03/Untitled-1.jpg', 'alt' => 'Young trainees at a graduation ceremony' ),
		array( 'url' => '/wp-content/uploads/2021/11/Gallery-1-850x567.jpg', 'alt' => 'Youth entrepreneurship training session' ),
	),
	'stories'       => site_child_revamp_stories( 'skilling-youth-for-employment', 3 ),
	'stories_url'   => '/stories/',
	'stories_head'  => 'Stories from youth skills',
	'stories_text'  => 'Real journeys of young people moving from training into employment and enterprise.',
	'programmes'    => array(
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
		array(
			'kicker'  => 'Resilient Communities',
			'title'   => 'Food Security & Climate Action',
			'summary' => 'Climate-smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
			'link'    => '/climate-actions/',
			'image'   => '/wp-content/uploads/2021/11/harbole-water-850x567.jpg',
		),
	),
	'cta_title'     => 'Help young people build their futures',
	'cta_text'      => 'Whether you are an employer, a training partner or a funder, we would love to connect youth with opportunities that work.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Get in touch',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-programme' );

get_footer();