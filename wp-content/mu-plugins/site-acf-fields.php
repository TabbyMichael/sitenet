<?php
/**
 * Plugin Name: SITE ACF Field Groups
 * Description: Registers ACF Pro field groups programmatically for Projects, Stories, Resources, and Partners.
 * Version: 1.0.0
 * Author: SITE Development Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'site_register_acf_field_groups' );

function site_register_acf_field_groups() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// 1. Projects Field Group
	acf_add_local_field_group( array(
		'key' => 'group_site_project_fields',
		'title' => 'Project Details',
		'fields' => array(
			array(
				'key' => 'field_project_status',
				'label' => 'Project Status',
				'name' => 'project_status',
				'type' => 'select',
				'required' => 1,
				'choices' => array(
					'planned'   => 'Planned',
					'active'    => 'Active',
					'completed' => 'Completed',
				),
				'default_value' => 'active',
				'return_format' => 'value',
			),
			array(
				'key' => 'field_project_start_date',
				'label' => 'Start Date',
				'name' => 'start_date',
				'type' => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format' => 'Y-m-d',
			),
			array(
				'key' => 'field_project_end_date',
				'label' => 'End Date',
				'name' => 'end_date',
				'type' => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format' => 'Y-m-d',
			),
			array(
				'key' => 'field_project_donor',
				'label' => 'Donor / Funder',
				'name' => 'donor_partner',
				'type' => 'post_object',
				'post_type' => array( 'site_partner' ),
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array(
				'key' => 'field_project_impact_stats',
				'label' => 'Impact Statistics',
				'name' => 'impact_statistics',
				'type' => 'repeater',
				'button_label' => 'Add Statistic',
				'sub_fields' => array(
					array(
						'key' => 'field_impact_stat_number',
						'label' => 'Metric Number',
						'name' => 'number',
						'type' => 'text',
						'placeholder' => 'e.g. 5,000+',
					),
					array(
						'key' => 'field_impact_stat_label',
						'label' => 'Metric Label',
						'name' => 'label',
						'type' => 'text',
						'placeholder' => 'e.g. Farmers Trained',
					),
				),
			),
			array(
				'key' => 'field_project_featured_story',
				'label' => 'Featured Story',
				'name' => 'featured_story',
				'type' => 'post_object',
				'post_type' => array( 'site_story' ),
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array(
				'key' => 'field_project_gallery',
				'label' => 'Project Gallery',
				'name' => 'project_gallery',
				'type' => 'gallery',
				'insert' => 'append',
				'library' => 'all',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'site_project',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
	) );

	// 2. Stories Field Group
	acf_add_local_field_group( array(
		'key' => 'group_site_story_fields',
		'title' => 'Story Details',
		'fields' => array(
			array(
				'key' => 'field_story_hero_image',
				'label' => 'Hero Banner Image',
				'name' => 'hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_story_impact_statement',
				'label' => 'Impact Statement',
				'name' => 'impact_statement',
				'type' => 'textarea',
				'rows' => 3,
			),
			array(
				'key' => 'field_story_year',
				'label' => 'Story Year',
				'name' => 'story_year',
				'type' => 'text',
				'default_value' => date( 'Y' ),
			),
			array(
				'key' => 'field_story_author',
				'label' => 'Story Author',
				'name' => 'story_author',
				'type' => 'text',
			),
			array(
				'key' => 'field_story_impact_stats',
				'label' => 'Impact Statistics',
				'name' => 'impact_statistics',
				'type' => 'repeater',
				'button_label' => 'Add Statistic',
				'sub_fields' => array(
					array(
						'key' => 'field_story_stat_number',
						'label' => 'Metric Number',
						'name' => 'number',
						'type' => 'text',
					),
					array(
						'key' => 'field_story_stat_label',
						'label' => 'Metric Label',
						'name' => 'label',
						'type' => 'text',
					),
				),
			),
			array(
				'key' => 'field_story_gallery',
				'label' => 'Story Gallery',
				'name' => 'story_gallery',
				'type' => 'gallery',
				'insert' => 'append',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'site_story',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
	) );

	// 3. Resources Field Group
	acf_add_local_field_group( array(
		'key' => 'group_site_resource_fields',
		'title' => 'Resource Details',
		'fields' => array(
			array(
				'key' => 'field_resource_pub_date',
				'label' => 'Publication Date',
				'name' => 'publication_date',
				'type' => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format' => 'Y-m-d',
			),
			array(
				'key' => 'field_resource_related_project',
				'label' => 'Related Project',
				'name' => 'related_project',
				'type' => 'post_object',
				'post_type' => array( 'site_project' ),
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array(
				'key' => 'field_resource_file',
				'label' => 'Resource Document File',
				'name' => 'resource_file',
				'type' => 'file',
				'return_format' => 'array',
			),
			array(
				'key' => 'field_resource_external_link',
				'label' => 'External Publication Link',
				'name' => 'external_link',
				'type' => 'url',
			),
			array(
				'key' => 'field_resource_description',
				'label' => 'Resource Summary',
				'name' => 'resource_description',
				'type' => 'textarea',
				'rows' => 4,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'site_resource',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
	) );

	// 4. Partners Field Group

	acf_add_local_field_group( array(
		'key' => 'group_site_partner_fields',
		'title' => 'Partner Details',
		'fields' => array(
			array(
				'key' => 'field_partner_logo',
				'label' => 'Partner Logo',
				'name' => 'partner_logo',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'required' => 1,
			),
			array(
				'key' => 'field_partner_website',
				'label' => 'Partner Website URL',
				'name' => 'partner_website',
				'type' => 'url',
			),
			array(
				'key' => 'field_partner_link_type',
				'label' => 'Partnership Type',
				'name' => 'partner_link_type',
				'type' => 'select',
				'choices' => array(
					'funder'       => 'Funder / Strategic Donor',
					'implementing' => 'Implementing Partner',
					'technical'    => 'Technical / Knowledge Partner',
					'government'   => 'Government / Institutional Partner',
				),
				'default_value' => 'funder',
			),
			array(
				'key' => 'field_partner_period',
				'label' => 'Partnership Period',
				'name' => 'partnership_period',
				'type' => 'text',
				'placeholder' => 'e.g. 2021 - Present',
			),
			array(
				'key' => 'field_partner_focus',
				'label' => 'Partnership Focus',
				'name' => 'partnership_focus',
				'type' => 'textarea',
				'rows' => 3,
			),
			array(
				'key' => 'field_partner_related_projects',
				'label' => 'Related Projects',
				'name' => 'related_projects',
				'type' => 'relationship',
				'post_type' => array( 'site_project' ),
				'return_format' => 'object',
				'ui' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'site_partner',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
	) );

	// 5. Homepage Hero Carousel Field Group
	acf_add_local_field_group( array(
		'key' => 'group_site_homepage_hero',
		'title' => 'Homepage Hero Carousel',
		'fields' => array(
			array(
				'key' => 'field_hero_intro',
				'label' => 'Hero Slides',
				'type' => 'message',
				'message' => 'Add the featured stories shown in the homepage carousel. Drag rows to reorder slides. The first slide is shown first and is the most important.',
			),
			array(
				'key' => 'field_hero_slides',
				'label' => 'Slides',
				'name' => 'hero_slides',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Add Slide',
				'sub_fields' => array(
					array(
						'key' => 'field_hero_slide_image',
						'label' => 'Image',
						'name' => 'image',
						'type' => 'image',
						'required' => 1,
						'return_format' => 'id',
						'preview_size' => 'medium',
						'library' => 'all',
					),
					array(
						'key' => 'field_hero_slide_eyebrow',
						'label' => 'Program / Category Label',
						'name' => 'eyebrow',
						'type' => 'text',
						'maxlength' => 40,
						'placeholder' => 'e.g. Youth & Skills',
					),
					array(
						'key' => 'field_hero_slide_title',
						'label' => 'Headline',
						'name' => 'title',
						'type' => 'text',
						'required' => 1,
						'maxlength' => 90,
						'placeholder' => 'A strong, short headline',
					),
					array(
						'key' => 'field_hero_slide_description',
						'label' => 'Short Description',
						'name' => 'description',
						'type' => 'textarea',
						'rows' => 3,
						'maxlength' => 220,
					),
					array(
						'key' => 'field_hero_slide_cta_text',
						'label' => 'Button Text',
						'name' => 'cta_text',
						'type' => 'text',
						'placeholder' => 'e.g. Explore Our Work',
					),
					array(
						'key' => 'field_hero_slide_cta_url',
						'label' => 'Button URL',
						'name' => 'cta_url',
						'type' => 'url',
					),
					array(
						'key' => 'field_hero_slide_cta2_text',
						'label' => 'Secondary Link Text (optional)',
						'name' => 'secondary_cta_text',
						'type' => 'text',
					),
					array(
						'key' => 'field_hero_slide_cta2_url',
						'label' => 'Secondary Link URL (optional)',
						'name' => 'secondary_cta_url',
						'type' => 'url',
					),
					array(
						'key' => 'field_hero_slide_image_position',
						'label' => 'Image Focus Position',
						'name' => 'image_position',
						'type' => 'select',
						'choices' => array(
							'center' => 'Center (default)',
							'top' => 'Top',
							'bottom' => 'Bottom',
							'left' => 'Left',
							'right' => 'Right',
						),
						'default_value' => 'center',
						'return_format' => 'value',
						'instructions' => 'Keeps the important part of the photo in view when it is cropped. If faces are near the top of the photo, choose Top.',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'default',
	) );
}
