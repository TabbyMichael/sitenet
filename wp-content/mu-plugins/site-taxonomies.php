<?php
/**
 * Plugin Name: SITE Custom Taxonomies
 * Description: Registers core taxonomies (Programs, Locations, Themes, Resource Types) for SITE Enterprise Promotion Kenya.
 * Version: 1.0.0
 * Author: SITE Development Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Programs Taxonomy (for Projects, Stories, Resources).
 */
function site_register_programs_taxonomy() {
	$labels = array(
		'name'              => _x( 'Programs', 'taxonomy general name', 'site-child' ),
		'singular_name'     => _x( 'Program', 'taxonomy singular name', 'site-child' ),
		'search_items'      => __( 'Search Programs', 'site-child' ),
		'all_items'         => __( 'All Programs', 'site-child' ),
		'parent_item'       => __( 'Parent Program', 'site-child' ),
		'parent_item_colon' => __( 'Parent Program:', 'site-child' ),
		'edit_item'         => __( 'Edit Program', 'site-child' ),
		'update_item'       => __( 'Update Program', 'site-child' ),
		'add_new_item'      => __( 'Add New Program', 'site-child' ),
		'new_item_name'     => __( 'New Program Name', 'site-child' ),
		'menu_name'         => __( 'Programs', 'site-child' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'       => 'program',
			'with_front' => false,
		),
	);

	register_taxonomy( 'site_program', array( 'site_project', 'site_story', 'site_resource' ), $args );
}
add_action( 'init', 'site_register_programs_taxonomy' );

/**
 * Locations Taxonomy (for Projects, Stories, Resources).
 */
function site_register_locations_taxonomy() {
	$labels = array(
		'name'              => _x( 'Locations', 'taxonomy general name', 'site-child' ),
		'singular_name'     => _x( 'Location', 'taxonomy singular name', 'site-child' ),
		'search_items'      => __( 'Search Locations', 'site-child' ),
		'all_items'         => __( 'All Locations', 'site-child' ),
		'parent_item'       => __( 'Parent Location', 'site-child' ),
		'parent_item_colon' => __( 'Parent Location:', 'site-child' ),
		'edit_item'         => __( 'Edit Location', 'site-child' ),
		'update_item'       => __( 'Update Location', 'site-child' ),
		'add_new_item'      => __( 'Add New Location', 'site-child' ),
		'new_item_name'     => __( 'New Location Name', 'site-child' ),
		'menu_name'         => __( 'Locations', 'site-child' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'       => 'location',
			'with_front' => false,
		),
	);

	register_taxonomy( 'site_location', array( 'site_project', 'site_story', 'site_resource' ), $args );
}
add_action( 'init', 'site_register_locations_taxonomy' );

/**
 * Themes/Tags Taxonomy (non-hierarchical for Projects, Stories).
 */
function site_register_themes_taxonomy() {
	$labels = array(
		'name'              => _x( 'Themes', 'taxonomy general name', 'site-child' ),
		'singular_name'     => _x( 'Theme', 'taxonomy singular name', 'site-child' ),
		'search_items'      => __( 'Search Themes', 'site-child' ),
		'all_items'         => __( 'All Themes', 'site-child' ),
		'edit_item'         => __( 'Edit Theme', 'site-child' ),
		'update_item'       => __( 'Update Theme', 'site-child' ),
		'add_new_item'      => __( 'Add New Theme', 'site-child' ),
		'new_item_name'     => __( 'New Theme Name', 'site-child' ),
		'menu_name'         => __( 'Themes', 'site-child' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'       => 'theme',
			'with_front' => false,
		),
	);

	register_taxonomy( 'site_theme', array( 'site_project', 'site_story' ), $args );
}
add_action( 'init', 'site_register_themes_taxonomy' );

/**
 * Resource Types Taxonomy (hierarchical for Resources).
 */
function site_register_resource_types_taxonomy() {
	$labels = array(
		'name'              => _x( 'Resource Types', 'taxonomy general name', 'site-child' ),
		'singular_name'     => _x( 'Resource Type', 'taxonomy singular name', 'site-child' ),
		'search_items'      => __( 'Search Resource Types', 'site-child' ),
		'all_items'         => __( 'All Resource Types', 'site-child' ),
		'parent_item'       => __( 'Parent Resource Type', 'site-child' ),
		'parent_item_colon' => __( 'Parent Resource Type:', 'site-child' ),
		'edit_item'         => __( 'Edit Resource Type', 'site-child' ),
		'update_item'       => __( 'Update Resource Type', 'site-child' ),
		'add_new_item'      => __( 'Add New Resource Type', 'site-child' ),
		'new_item_name'     => __( 'New Resource Type Name', 'site-child' ),
		'menu_name'         => __( 'Resource Types', 'site-child' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'       => 'resource-type',
			'with_front' => false,
		),
	);

	register_taxonomy( 'site_resource_type', array( 'site_resource' ), $args );
}
add_action( 'init', 'site_register_resource_types_taxonomy' );