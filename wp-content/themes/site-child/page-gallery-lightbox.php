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
			array( 'file' => '2025/03/camel-1.jpg', 'caption' => 'Camels gathered at a community water point' ),
			array( 'id' => 7507, 'caption' => 'Handling and packing camel milk under hygienic conditions' ),
			array( 'id' => 7506, 'caption' => 'A milk testing kit used to check camel milk quality' ),
			array( 'id' => 7509, 'caption' => 'Checking milk quality with a lactometer at a collection point' ),
			array( 'id' => 7508, 'caption' => 'Children drinking camel milk at a community nutrition outreach' ),
			array( 'id' => 8450, 'file' => '2026/09/abdia-hero.jpg', 'caption' => 'Abdia Mohamed at her agrovet shop in Mulanjo, Tana River County' ),
			array( 'id' => 8451, 'file' => '2026/09/gedi-hero.jpg', 'caption' => 'Herders and community animal health workers examine a camel during hands-on training' ),
			array( 'id' => 8453, 'file' => '2026/09/abdi-herd-hero.jpg', 'caption' => 'A camel is examined and treated during a practical session in Mulanjo' ),
			array( 'id' => 8452, 'file' => '2026/09/cup-of-health-hero.jpg', 'caption' => 'Children at the camel milk nutrition outreach in Fafi Bare' ),
			array( 'id' => 8458, 'file' => '2026/09/abdi-herd-02.jpg', 'caption' => 'A camel receiving follow-up treatment at Mulanjo, Tana River County' ),
			array( 'id' => 8459, 'file' => '2026/09/abdi-herd-03.jpg', 'caption' => 'A veterinary officer treating a camel for trypanosomiasis' ),
			array( 'id' => 8454, 'file' => '2026/09/dubey-sirat-hero.jpg', 'caption' => 'Packaged camel milk on display at a Garissa shop' ),
			array( 'id' => 8456, 'file' => '2026/09/cup-of-health-outreach.jpg', 'caption' => 'Community health promoters demonstrate MUAC measurement to mothers' ),
			array( 'id' => 8457, 'file' => '2026/09/abdi-herd-01.jpg', 'caption' => 'A camel is examined for a wound before treatment' ),
			array( 'id' => 7611, 'caption' => 'Camels drinking at a community water point' ),
			array( 'id' => 7612, 'caption' => 'Pastoral livestock gathering at a shared water point' ),
			array( 'id' => 7613, 'caption' => 'Camel milk being poured for collection' ),
			array( 'id' => 7614, 'caption' => 'Camels grazing with small stock in the drylands' ),
			array( 'id' => 7615, 'caption' => 'A herder milking a camel by hand' ),
			array( 'id' => 7616, 'caption' => 'Camels and goats gathered across the rangeland' ),
			array( 'id' => 7617, 'caption' => 'Fresh camel milk prepared for handling' ),
			array( 'id' => 7619, 'caption' => 'Close view of camels in a pastoral herd' ),
			array( 'id' => 7620, 'caption' => 'Camel and small-stock herds moving through the rangelands' ),
			array( 'id' => 7621, 'caption' => 'A pastoralist milking a camel' ),
			array( 'id' => 7622, 'caption' => 'Camels at a livestock watering area' ),
			array( 'id' => 7623, 'caption' => 'A woman sharing her experience in the camel milk value chain' ),
			array( 'id' => 7631, 'caption' => 'Camel milk being prepared for chilling and transport' ),
			array( 'id' => 7636, 'caption' => 'Bottled camel milk packed with ice for delivery' ),
			array( 'id' => 7637, 'caption' => 'Camel milk being packed at a processing facility' ),
			array( 'id' => 7639, 'caption' => 'A water pan and surrounding rangeland in the drylands' ),
			array( 'id' => 7792, 'caption' => 'Children tasting camel milk during a community outreach' ),
			array( 'id' => 8470, 'file' => '2026/09/camels.jpeg', 'caption' => 'A camel herd in the pastoral rangelands' ),
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
		'description' => 'Hives hung in acacia trees, honey harvested and sorted by producer groups, beekeeping from the apiary to the refinery floor.',
		'photos'      => array(
			array( 'id' => 7510, 'caption' => 'Beehives hung in acacia trees at a community apiary' ),
			array( 'id' => 7511, 'caption' => 'Honey changing hands after a harvest' ),
			array( 'id' => 7512, 'caption' => 'Sorting and packing honey in a producer group' ),
			array( 'id' => 7513, 'caption' => 'The Linyot Honey Producer Group refinery' ),
			array( 'id' => 7899, 'caption' => 'Beehives set beneath acacia trees at a community apiary' ),
			array( 'id' => 7901, 'caption' => 'A beehive installed in a dryland apiary' ),
		),
	),
	array(
		'title'       => 'Enterprise, markets and value chains',
		'description' => 'On farm demonstrations, producer groups and bulking centres: the work that links smallholder farmers to better markets.',
		'photos'      => array(
			array( 'id' => 7571, 'caption' => 'An on farm demonstration with farmers in Meru County' ),
			array( 'id' => 7572, 'caption' => 'A farmer with freshly harvested potatoes in Meru' ),
			array( 'id' => 7573, 'caption' => 'A poultry keeping training session in Machakos County' ),
			array( 'id' => 7574, 'caption' => 'Preparing liquid soap during a business skills demonstration in Meru' ),
			array( 'id' => 7940, 'caption' => 'Produce ready for collection at the Kyusyani Marketing Cluster, Ghala' ),
			array( 'id' => 7912, 'caption' => 'A food preparation demonstration for local entrepreneurs' ),
			array( 'id' => 7947, 'caption' => 'A young mechanic working on a motorcycle engine' ),
			array( 'id' => 8002, 'caption' => 'SITE staff and trainees at a skills programme' ),
			array( 'id' => 8481, 'file' => '2025/05/Catherine-Wangui-848x450-1.png', 'caption' => 'Catherine Wangui at her small business' ),
		),
	),
	array(
		'title'       => 'Water and climate resilience',
		'description' => 'Water points, water pans and climate smart practices, the work that keeps dryland production going through the dry seasons.',
		'photos'      => array(
			array( 'id' => 7410, 'caption' => 'Water technicians servicing a pump, case study from Fafi Centre, Garissa County' ),
			array( 'id' => 7365, 'caption' => 'Camel herds at the Harbole watering point, Garissa County' ),
			array( 'id' => 7415, 'caption' => 'The Harbole watering point: a convergence of life' ),
			array( 'id' => 8115, 'caption' => 'Camels watering at a water pan in the rangelands' ),
			array( 'id' => 7898, 'caption' => 'Applying climate smart crop protection practices with farmers' ),
			array( 'id' => 8460, 'file' => '2026/09/abdi-herd-04.jpg', 'caption' => 'Community members assess Hambress Water Pan before rehabilitation works' ),
			array( 'id' => 8461, 'file' => '2026/09/abdi-herd-05.jpg', 'caption' => 'Hambress Water Pan after rehabilitation, holding rainwater for the community' ),
			array( 'id' => 8462, 'file' => '2026/09/abdi-herd-06.jpg', 'caption' => 'Camels gathered at Hambress Water Pan after rehabilitation' ),
			array( 'id' => 7618, 'caption' => 'SITE and project partners visiting a community water site' ),
			array( 'id' => 7624, 'caption' => 'An aerial view of a pastoral settlement and its water points' ),
			array( 'id' => 7625, 'caption' => 'A rehabilitated water pan holding rainwater' ),
			array( 'id' => 7626, 'caption' => 'A dryland water pan and surrounding rangeland' ),
			array( 'id' => 7627, 'caption' => 'Livestock and residents share a community watering point' ),
			array( 'id' => 7628, 'caption' => 'A camel herd gathered near a community water tank' ),
			array( 'id' => 7629, 'caption' => 'Camels and other livestock using a water trough' ),
			array( 'id' => 7630, 'caption' => 'Livestock drinking at a shared water point' ),
			array( 'id' => 7632, 'caption' => 'Water pan serving the surrounding dryland community' ),
			array( 'id' => 7633, 'caption' => 'The Kasha Camel Milk Service Centre signboard' ),
			array( 'id' => 7634, 'caption' => 'Community members collecting water from shared taps' ),
			array( 'id' => 7635, 'caption' => 'Aerial view of water storage and livestock around a community water point' ),
			array( 'id' => 7638, 'caption' => 'Camel milk being poured during processing' ),
			array( 'id' => 7640, 'caption' => 'The Garissa water pans supporting pastoral communities' ),
			array( 'id' => 7900, 'caption' => 'Camels drinking from a water trough in the drylands' ),
			array( 'id' => 7902, 'caption' => 'Excavation work at a community water pan' ),
			array( 'id' => 7904, 'caption' => 'Camels resting beside a community water pan' ),
			array( 'id' => 7905, 'caption' => 'A completed water pan holding water for the community' ),
			array( 'id' => 8496, 'file' => '2026/11/water-2-1.jpg', 'caption' => 'Residents and officials gather around the Bula Asli water point' ),
			array( 'id' => 8497, 'file' => '2026/11/case-studies-1.jpeg', 'caption' => 'Officials unveil the Bula Asli water project signboard' ),
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
			array( 'id' => 7585, 'caption' => 'The Kanana disability self help group producing liquid soap' ),
			array( 'id' => 7553, 'caption' => 'Mama Mlemavu serving customers at her food business' ),
			array( 'id' => 7549, 'caption' => 'Lilian Kirimi at her shop, one of the entrepreneurs we support' ),
			array( 'id' => 7555, 'caption' => 'Persons with disabilities at a sensitization forum in Machakos' ),
			array( 'id' => 7556, 'caption' => 'Participants at a disability inclusion training in Kathiani' ),
			array( 'id' => 7561, 'caption' => 'County leaders at an NCPWD registration launch' ),
			array( 'id' => 7563, 'caption' => 'A community group taking part in a training session in Masinga' ),
			array( 'id' => 7564, 'caption' => 'Masinga Sub-County officials address participants at a disability forum' ),
			array( 'id' => 7565, 'caption' => 'Participants at a disability inclusion training in Kathiani' ),
			array( 'id' => 7566, 'caption' => 'Persons with disabilities attend a sensitization forum in Machakos' ),
			array( 'id' => 7567, 'caption' => 'A woman with a disability takes part in soap-making training' ),
			array( 'id' => 7580, 'caption' => 'Duty bearers meet in Meru County' ),
			array( 'id' => 7583, 'caption' => 'Leaders from organizations of persons with disabilities' ),
		),
	),
);

/* Resolve every collection against the media library; skip anything missing. */
$rv_groups      = array();
$rv_photo_total = 0;

foreach ( $rv_group_source as $rv_group ) {
	$rv_group_photos = array();

	foreach ( $rv_group['photos'] as $rv_photo ) {
		$rv_id     = isset( $rv_photo['id'] ) ? (int) $rv_photo['id'] : 0;
		$rv_srcset = '';
		$rv_width  = 640;
		$rv_height = 427;

		$rv_large      = $rv_id ? wp_get_attachment_image_url( $rv_id, 'large' ) : false;
		$rv_grid       = $rv_id ? wp_get_attachment_image_url( $rv_id, 'medium_large' ) : false;
		$rv_dimensions = $rv_id ? wp_get_attachment_image_src( $rv_id, 'medium_large' ) : false;

		if ( $rv_id && ! $rv_grid ) {
			$rv_grid = wp_get_attachment_image_url( $rv_id, 'medium' );
		}
		if ( $rv_id && ! $rv_large ) {
			$rv_large = wp_get_attachment_url( $rv_id );
		}
		if ( $rv_id && ! $rv_dimensions ) {
			$rv_dimensions = wp_get_attachment_image_src( $rv_id, 'medium' );
		}

		if ( $rv_dimensions ) {
			$rv_width  = absint( $rv_dimensions[1] );
			$rv_height = absint( $rv_dimensions[2] );
			$rv_srcset = wp_get_attachment_image_srcset( $rv_id, 'medium_large' );
		}

		if ( ! $rv_large && ! empty( $rv_photo['file'] ) ) {
			$rv_uploads = wp_get_upload_dir();
			$rv_file    = trailingslashit( $rv_uploads['basedir'] ) . ltrim( $rv_photo['file'], '/\\' );

			if ( is_file( $rv_file ) ) {
				$rv_file_dimensions = wp_getimagesize( $rv_file );

				if ( $rv_file_dimensions ) {
					$rv_large = trailingslashit( $rv_uploads['baseurl'] ) . ltrim( $rv_photo['file'], '/\\' );
					$rv_grid  = $rv_large;
					$rv_width = absint( $rv_file_dimensions[0] );
					$rv_height = absint( $rv_file_dimensions[1] );
				}
			}
		}

		if ( ! $rv_large ) {
			continue;
		}

		if ( ! $rv_grid ) {
			$rv_grid = $rv_large;
		}

		$rv_group_photos[] = array(
			'url'     => $rv_large,
			'image'   => $rv_grid,
			'srcset'  => $rv_srcset,
			'width'   => $rv_width,
			'height'  => $rv_height,
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