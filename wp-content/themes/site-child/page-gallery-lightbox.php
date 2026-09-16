<?php
/**
 * Template Name: Photo Gallery (revamp)
 * Description: Modernised gallery page — renders real media-library photos
 *              grouped into themed collections with captions, in a modern
 *              lightbox grid (vanilla JS, no library).
 *
 * This page's stored builder content is a SiteOrigin [gallery] shortcode that
 * listed the same photos a second time, so show_content is set to false and
 * the legacy card is no longer printed. The stored content itself is left
 * untouched in the database (nothing is lost in the editor).
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/*
 * Curated photo collections.
 *
 * Every entry points at an existing media-library attachment, and each caption
 * describes what is actually in that file — written from the image itself or
 * taken from the attachment's stored alt text. Similar photos are kept in the
 * same collection so related work reads together.
 *
 * Like the other revamp pages this is a hardcoded array today; moving it to
 * ACF fields / the site_project CPT would be a content migration and needs
 * sign-off before it happens.
 */
$rv_group_source = array(
	array(
		'title'       => 'Camel milk and livestock health',
		'description' => 'Camel milk is a lifeline for pastoralist households. This collection follows the milk from the camel to the market, alongside the animal health services that keep herds productive.',
		'photos'      => array(
			array( 'id' => 7505, 'caption' => 'Working with camels in the arid and semi-arid lands' ),
			array( 'id' => 7507, 'caption' => 'Handling and packing camel milk under hygienic conditions' ),
			array( 'id' => 7506, 'caption' => 'A milk testing kit used to check camel milk quality' ),
			array( 'id' => 7509, 'caption' => 'Checking milk quality with a lactometer at a collection point' ),
			array( 'id' => 7508, 'caption' => 'Children drinking camel milk at a community nutrition outreach' ),
			array( 'id' => 8450, 'caption' => 'Abdia Mohamed at her agrovet shop in Mulanjo, Tana River County' ),
			array( 'id' => 8451, 'caption' => 'Abdi Hassan with his recovered camel herd in Mulanjo, Tana River County' ),
			array( 'id' => 8453, 'caption' => 'Dubey Sirat at the Maalim Aden camel milk market in Garissa' ),
			array( 'id' => 8452, 'caption' => 'Children at the camel milk nutrition outreach in Fafi Bare' ),
			array( 'id' => 8461, 'caption' => 'Abdi Gedi, community animal health worker, working with camels in Sala Ward' ),
		),
	),
	array(
		'title'       => 'Soapstone: from quarry to market',
		'description' => 'Kenyan soapstone carvers turn raw stone into craft sold locally and through fair-trade export markets. This series runs from extraction at the quarry to the finished pieces on display.',
		'photos'      => array(
			array( 'id' => 7500, 'caption' => 'Breaking out soapstone at the quarry' ),
			array( 'id' => 7501, 'caption' => 'Quarry workers shaping blocks of soapstone' ),
			array( 'id' => 7502, 'caption' => 'Members of the KISAC Fair Trade soapstone producer group' ),
			array( 'id' => 7503, 'caption' => 'Hand-carved soapstone plates finished with wildlife motifs' ),
			array( 'id' => 7504, 'caption' => 'Finished soapstone and ceramic ware displayed for sale' ),
		),
	),
	array(
		'title'       => 'Beekeeping and honey',
		'description' => 'Hives hung in acacia trees, honey harvested and sorted by producer groups — beekeeping from the apiary to the refinery floor.',
		'photos'      => array(
			array( 'id' => 7510, 'caption' => 'Beehives hung in acacia trees at a community apiary' ),
			array( 'id' => 7511, 'caption' => 'Honey changing hands after a harvest' ),
			array( 'id' => 7512, 'caption' => 'Sorting and packing honey in a producer group' ),
			array( 'id' => 7513, 'caption' => 'The Linyot Honey Producer Group refinery' ),
		),
	),
	array(
		'title'       => 'Enterprise, markets and value chains',
		'description' => 'On-farm demonstrations, producer groups and bulking centres: the work that links smallholder farmers to better markets.',
		'photos'      => array(
			array( 'id' => 7571, 'caption' => 'An on-farm demonstration with farmers in Meru County' ),
			array( 'id' => 7572, 'caption' => 'A farmer with freshly harvested potatoes in Meru' ),
			array( 'id' => 7573, 'caption' => 'A poultry keeping training session in Machakos County' ),
			array( 'id' => 7574, 'caption' => 'Preparing liquid soap during a business skills demonstration in Meru' ),
			array( 'id' => 7940, 'caption' => 'Produce ready for collection at the Kyusyani Marketing Cluster, Ghala' ),
		),
	),
	array(
		'title'       => 'Water and climate resilience',
		'description' => 'Water points, water pans and climate-smart practices — the work that keeps dryland production going through the dry seasons.',
		'photos'      => array(
			array( 'id' => 7410, 'caption' => 'Water technicians servicing a pump — case study from Fafi Centre, Garissa County' ),
			array( 'id' => 7365, 'caption' => 'Camel herds at the Harbole watering point, Garissa County' ),
			array( 'id' => 7415, 'caption' => 'The Harbole watering point: a convergence of life' ),
			array( 'id' => 8115, 'caption' => 'Camels watering at a water pan in the rangelands' ),
			array( 'id' => 7898, 'caption' => 'Applying climate-smart crop protection practices with farmers' ),
		),
	),
	array(
		'title'       => 'Inclusion: persons with disabilities',
		'description' => 'Working with county governments and organisations of persons with disabilities: assessment, registration and enterprise support.',
		'photos'      => array(
			array( 'id' => 7560, 'caption' => 'Clinicians completing a disability assessment for NCPWD registration' ),
			array( 'id' => 7562, 'caption' => 'Health workers assessing a child at Machakos Level 5 Hospital' ),
			array( 'id' => 7582, 'caption' => 'Registering a person with disability for an NCPWD card in Machakos' ),
			array( 'id' => 7557, 'caption' => 'A business skills training event for persons with disabilities in Machakos County' ),
			array( 'id' => 7568, 'caption' => 'A woman in a wheelchair learning liquid soap making in Machakos' ),
			array( 'id' => 7585, 'caption' => 'The Kanana disability self-help group producing liquid soap' ),
			array( 'id' => 7553, 'caption' => 'Mama Mlemavu serving customers at her food business' ),
			array( 'id' => 7549, 'caption' => 'Lilian Kirimi at her shop — one of the entrepreneurs we support' ),
		),
	),
);

/* Resolve every collection against the media library; skip anything missing. */
$rv_groups      = array();
$rv_photo_total = 0;

foreach ( $rv_group_source as $rv_group ) {
	$rv_group_photos = array();

	foreach ( $rv_group['photos'] as $rv_photo ) {
		$rv_id = (int) $rv_photo['id'];

		$rv_large = wp_get_attachment_image_url( $rv_id, 'large' );
		$rv_grid  = wp_get_attachment_image_url( $rv_id, 'medium_large' );

		if ( ! $rv_grid ) {
			$rv_grid = wp_get_attachment_image_url( $rv_id, 'medium' );
		}
		if ( ! $rv_large ) {
			$rv_large = wp_get_attachment_url( $rv_id );
		}
		if ( ! $rv_grid ) {
			$rv_grid = $rv_large;
		}

		/* A missing file must never produce a broken tile. */
		if ( ! $rv_large ) {
			continue;
		}

		$rv_dimensions = wp_get_attachment_image_src( $rv_id, 'medium_large' );
		if ( ! $rv_dimensions ) {
			$rv_dimensions = wp_get_attachment_image_src( $rv_id, 'medium' );
		}

		$rv_group_photos[] = array(
			'url'     => $rv_large,
			'image'   => $rv_grid,
			'srcset'  => wp_get_attachment_image_srcset( $rv_id, 'medium_large' ),
			'width'   => $rv_dimensions ? absint( $rv_dimensions[1] ) : 640,
			'height'  => $rv_dimensions ? absint( $rv_dimensions[2] ) : 427,
			'caption' => $rv_photo['caption'],
		);
	}

	if ( empty( $rv_group_photos ) ) {
		continue;
	}

	$rv_photo_total += count( $rv_group_photos );

	$rv_groups[] = array(
		'title'       => $rv_group['title'],
		'description' => $rv_group['description'],
		'photos'      => $rv_group_photos,
	);
}

$rv = array(
	'type'          => 'photo-gallery',
	'root_class'    => 'site-revamp--gallery site-revamp--photo',
	'kicker'        => 'Media · Photos',
	'title'         => 'Photo Gallery',
	'lead'          => 'A visual journey through our programmes and the communities we serve across Kenya.',
	'actions'       => array(
		array( 'label' => 'Watch videos', 'url' => '/galleries/gallery-full-width-2/', 'variant' => 'primary' ),
		array( 'label' => 'Browse case studies', 'url' => site_child_resource_hub_url( 'case-studys' ), 'variant' => 'ghost' ),
	),
	'photo_groups'  => $rv_groups,
	/*
	 * The stored page content is a legacy SiteOrigin [gallery] shortcode that
	 * repeated these photos in a plain thumbnail card. It is not printed here,
	 * which removes the duplicate card at the top of the page.
	 */
	'show_content'  => false,
	'photo_note'    => sprintf(
		'%1$s photographs in %2$s collections · select any image to view it enlarged',
		number_format_i18n( $rv_photo_total ),
		number_format_i18n( count( $rv_groups ) )
	),
	'cta_title'     => 'See our work up close',
	'cta_text'      => 'Visit us in the field, partner on a project or arrange a field visit to see our programmes in action.',
	'cta_url'       => '/contact-us/',
	'cta_label'     => 'Arrange a visit',
);

/* Publish config for template-part scope (see revamp-programme.php). */
$GLOBALS['site_child_revamp'] = $rv;

get_template_part( 'template-parts/revamp/revamp-gallery-photo' );

get_footer();