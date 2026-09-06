<?php
/**
 * SITE Custom Taxonomies
 * Must-use plugin for reliability - always active
 */

// Programs Taxonomy (for Projects, Stories, Resources)
function site_register_programs_taxonomy() {
    $labels = array(
        'name' => 'Programs',
        'singular_name' => 'Program',
        'search_items' => 'Search Programs',
        'all_items' => 'All Programs',
        'parent_item' => 'Parent Program',
        'parent_item_colon' => 'Parent Program:',
        'edit_item' => 'Edit Program',
        'update_item' => 'Update Program',
        'add_new_item' => 'Add New Program',
        'new_item_name' => 'New Program Name',
        'menu_name' => 'Programs',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'program' ),
    );

    register_taxonomy( 'site_program', array( 'site_project', 'site_story', 'site_resource' ), $args );
}
add_action( 'init', 'site_register_programs_taxonomy' );

// Locations Taxonomy
function site_register_locations_taxonomy() {
    $labels = array(
        'name' => 'Locations',
        'singular_name' => 'Location',
        'search_items' => 'Search Locations',
        'all_items' => 'All Locations',
        'edit_item' => 'Edit Location',
        'update_item' => 'Update Location',
        'add_new_item' => 'Add New Location',
        'new_item_name' => 'New Location Name',
        'menu_name' => 'Locations',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'location' ),
    );

    register_taxonomy( 'site_location', array( 'site_project', 'site_story', 'site_resource' ), $args );
}
add_action( 'init', 'site_register_locations_taxonomy' );

// Themes/Tags Taxonomy (for secondary categorization)
function site_register_themes_taxonomy() {
    $labels = array(
        'name' => 'Themes',
        'singular_name' => 'Theme',
        'search_items' => 'Search Themes',
        'all_items' => 'All Themes',
        'edit_item' => 'Edit Theme',
        'update_item' => 'Update Theme',
        'add_new_item' => 'Add New Theme',
        'new_item_name' => 'New Theme Name',
        'menu_name' => 'Themes',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'theme' ),
    );

    register_taxonomy( 'site_theme', array( 'site_project', 'site_story' ), $args );
}
add_action( 'init', 'site_register_themes_taxonomy' );

// Resource Types Taxonomy
function site_register_resource_types_taxonomy() {
    $labels = array(
        'name' => 'Resource Types',
        'singular_name' => 'Resource Type',
        'search_items' => 'Search Resource Types',
        'all_items' => 'All Resource Types',
        'edit_item' => 'Edit Resource Type',
        'update_item' => 'Update Resource Type',
        'add_new_item' => 'Add New Resource Type',
        'new_item_name' => 'New Resource Type Name',
        'menu_name' => 'Resource Types',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'resource-type' ),
    );

    register_taxonomy( 'site_resource_type', array( 'site_resource' ), $args );
}
add_action( 'init', 'site_register_resource_types_taxonomy' );