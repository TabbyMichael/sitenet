<?php
/**
 * TEMPORARY: one-time seeder for homepage hero slides.
 * Seeds real media-library images into the hero_slides repeater on the
 * static front page (ID 7149), guarded by an option so it runs once.
 * DELETE THIS FILE after the seed has run.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_loaded', 'site_seed_hero_slides_once' );

function site_seed_hero_slides_once() {
	if ( get_option( 'site_hero_seeded' ) ) {
		return;
	}
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$slides = array(
		array(
			'image'              => 7519,
			'eyebrow'            => 'Since 1996',
			'title'              => 'Transforming Livelihoods Through Enterprise, Skills & Resilient Communities',
			'description'        => 'For nearly three decades, SITE has walked with enterprising Kenyans — youth, women and smallholder producers — turning skills and opportunity into lasting incomes.',
			'cta_text'           => 'Explore Our Work',
			'cta_url'            => home_url( '/ourwork/' ),
			'secondary_cta_text' => 'Partner With Us',
			'secondary_cta_url'  => home_url( '/contact-us/' ),
			'image_position'     => 'center',
		),
		array(
			'image'              => 8009,
			'eyebrow'            => 'Youth & Skills',
			'title'              => 'Equipping Young Kenyans With Market-Ready Skills',
			'description'        => 'Technical training, apprenticeships and mentorship that move young people from learning to earning.',
			'cta_text'           => 'See Youth Programs',
			'cta_url'            => home_url( '/ourwork/' ),
			'secondary_cta_text' => '',
			'secondary_cta_url'  => '',
			'image_position'     => 'center',
		),
		array(
			'image'              => 7898,
			'eyebrow'            => 'Climate-Smart Agriculture',
			'title'              => 'Resilient Value Chains for a Changing Climate',
			'description'        => 'Working hand-in-hand with smallholder farmers to build drought-resilient value chains — from seed to market.',
			'cta_text'           => 'Our Approach',
			'cta_url'            => home_url( '/about-us/' ),
			'secondary_cta_text' => '',
			'secondary_cta_url'  => '',
			'image_position'     => 'center',
		),
		array(
			'image'              => 7810,
			'eyebrow'            => 'Women\'s Economic Empowerment',
			'title'              => 'Women Building Enterprises That Lift Whole Communities',
			'description'        => 'Village savings, cooperative enterprise and market linkages that put income decisions in women\'s hands.',
			'cta_text'           => 'Meet the Women',
			'cta_url'            => home_url( '/blog/' ),
			'secondary_cta_text' => '',
			'secondary_cta_url'  => '',
			'image_position'     => 'top',
		),
		array(
			'image'              => 7710,
			'eyebrow'            => 'Livestock & Dairy Value Chains',
			'title'              => 'From Local Herds to Growing Markets',
			'description'        => 'Supporting producers to meet quality standards and reach urban markets across Kenya.',
			'cta_text'           => 'Explore Value Chains',
			'cta_url'            => home_url( '/ourwork/' ),
			'secondary_cta_text' => '',
			'secondary_cta_url'  => '',
			'image_position'     => 'center',
		),
	);

	update_field( 'hero_slides', $slides, 7149 );
	update_option( 'site_hero_seeded', 1 );
}
