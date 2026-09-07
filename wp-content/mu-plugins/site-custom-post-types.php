<?php
/**
 * Plugin Name: SITE Custom Post Types
 * Description: Registers core CPTs (Projects, Stories, Resources, Partners) for SITE Enterprise Promotion Kenya.
 * Version: 1.0.0
 * Author: SITE Development Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Projects Custom Post Type.
 */
function site_register_projects_cpt() {
	$labels = array(
		'name'               => _x( 'Projects', 'post type general name', 'site-child' ),
		'singular_name'      => _x( 'Project', 'post type singular name', 'site-child' ),
		'menu_name'          => _x( 'Projects', 'admin menu', 'site-child' ),
		'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'site-child' ),
		'add_new'            => _x( 'Add New', 'project', 'site-child' ),
		'add_new_item'       => __( 'Add New Project', 'site-child' ),
		'new_item'           => __( 'New Project', 'site-child' ),
		'edit_item'          => __( 'Edit Project', 'site-child' ),
		'view_item'          => __( 'View Project', 'site-child' ),
		'all_items'          => __( 'All Projects', 'site-child' ),
		'search_items'       => __( 'Search Projects', 'site-child' ),
		'parent_item_colon'  => __( 'Parent Projects:', 'site-child' ),
		'not_found'          => __( 'No projects found.', 'site-child' ),
		'not_found_in_trash' => __( 'No projects found in Trash.', 'site-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'projects',
			'with_front' => false,
		),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-portfolio',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'site_project', $args );
}
add_action( 'init', 'site_register_projects_cpt' );

/**
 * Register Stories Custom Post Type.
 */
function site_register_stories_cpt() {
	$labels = array(
		'name'               => _x( 'Stories', 'post type general name', 'site-child' ),
		'singular_name'      => _x( 'Story', 'post type singular name', 'site-child' ),
		'menu_name'          => _x( 'Stories', 'admin menu', 'site-child' ),
		'name_admin_bar'     => _x( 'Story', 'add new on admin bar', 'site-child' ),
		'add_new'            => _x( 'Add New', 'story', 'site-child' ),
		'add_new_item'       => __( 'Add New Story', 'site-child' ),
		'new_item'           => __( 'New Story', 'site-child' ),
		'edit_item'          => __( 'Edit Story', 'site-child' ),
		'view_item'          => __( 'View Story', 'site-child' ),
		'all_items'          => __( 'All Stories', 'site-child' ),
		'search_items'       => __( 'Search Stories', 'site-child' ),
		'parent_item_colon'  => __( 'Parent Stories:', 'site-child' ),
		'not_found'          => __( 'No stories found.', 'site-child' ),
		'not_found_in_trash' => __( 'No stories found in Trash.', 'site-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'stories',
			'with_front' => false,
		),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 21,
		'menu_icon'          => 'dashicons-book',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'site_story', $args );
}
add_action( 'init', 'site_register_stories_cpt' );

/**
 * Register Resources Custom Post Type.
 */
function site_register_resources_cpt() {
	$labels = array(
		'name'               => _x( 'Resources', 'post type general name', 'site-child' ),
		'singular_name'      => _x( 'Resource', 'post type singular name', 'site-child' ),
		'menu_name'          => _x( 'Resources', 'admin menu', 'site-child' ),
		'name_admin_bar'     => _x( 'Resource', 'add new on admin bar', 'site-child' ),
		'add_new'            => _x( 'Add New', 'resource', 'site-child' ),
		'add_new_item'       => __( 'Add New Resource', 'site-child' ),
		'new_item'           => __( 'New Resource', 'site-child' ),
		'edit_item'          => __( 'Edit Resource', 'site-child' ),
		'view_item'          => __( 'View Resource', 'site-child' ),
		'all_items'          => __( 'All Resources', 'site-child' ),
		'search_items'       => __( 'Search Resources', 'site-child' ),
		'parent_item_colon'  => __( 'Parent Resources:', 'site-child' ),
		'not_found'          => __( 'No resources found.', 'site-child' ),
		'not_found_in_trash' => __( 'No resources found in Trash.', 'site-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'resources',
			'with_front' => false,
		),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 22,
		'menu_icon'          => 'dashicons-media-document',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'site_resource', $args );
}
add_action( 'init', 'site_register_resources_cpt' );

/**
 * Register Partners Custom Post Type.
 */
function site_register_partners_cpt() {
	$labels = array(
		'name'               => _x( 'Partners', 'post type general name', 'site-child' ),
		'singular_name'      => _x( 'Partner', 'post type singular name', 'site-child' ),
		'menu_name'          => _x( 'Partners', 'admin menu', 'site-child' ),
		'name_admin_bar'     => _x( 'Partner', 'add new on admin bar', 'site-child' ),
		'add_new'            => _x( 'Add New', 'partner', 'site-child' ),
		'add_new_item'       => __( 'Add New Partner', 'site-child' ),
		'new_item'           => __( 'New Partner', 'site-child' ),
		'edit_item'          => __( 'Edit Partner', 'site-child' ),
		'view_item'          => __( 'View Partner', 'site-child' ),
		'all_items'          => __( 'All Partners', 'site-child' ),
		'search_items'       => __( 'Search Partners', 'site-child' ),
		'parent_item_colon'  => __( 'Parent Partners:', 'site-child' ),
		'not_found'          => __( 'No partners found.', 'site-child' ),
		'not_found_in_trash' => __( 'No partners found in Trash.', 'site-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'partners',
			'with_front' => false,
		),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 23,
		'menu_icon'          => 'dashicons-groups',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'site_partner', $args );
}
add_action( 'init', 'site_register_partners_cpt' );