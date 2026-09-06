<?php
/**
 * SITE Custom Post Types
 * Must-use plugin for reliability - always active
 */

// Register Projects Post Type
function site_register_projects() {
    $labels = array(
        'name' => 'Projects',
        'singular_name' => 'Project',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Project',
        'edit_item' => 'Edit Project',
        'new_item' => 'New Project',
        'view_item' => 'View Project',
        'search_items' => 'Search Projects',
        'not_found' => 'No projects found',
        'not_found_in_trash' => 'No projects found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'projects' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_project', $args );
}
add_action( 'init', 'site_register_projects' );

// Register Stories Post Type
function site_register_stories() {
    $labels = array(
        'name' => 'Stories',
        'singular_name' => 'Story',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Story',
        'edit_item' => 'Edit Story',
        'new_item' => 'New Story',
        'view_item' => 'View Story',
        'search_items' => 'Search Stories',
        'not_found' => 'No stories found',
        'not_found_in_trash' => 'No stories found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-book',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'stories' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_story', $args );
}
add_action( 'init', 'site_register_stories' );

// Register Resources Post Type
function site_register_resources() {
    $labels = array(
        'name' => 'Resources',
        'singular_name' => 'Resource',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Resource',
        'edit_item' => 'Edit Resource',
        'new_item' => 'New Resource',
        'view_item' => 'View Resource',
        'search_items' => 'Search Resources',
        'not_found' => 'No resources found',
        'not_found_in_trash' => 'No resources found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'resources' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_resource', $args );
}
add_action( 'init', 'site_register_resources' );

// Register Partners Post Type
function site_register_partners() {
    $labels = array(
        'name' => 'Partners',
        'singular_name' => 'Partner',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Partner',
        'edit_item' => 'Edit Partner',
        'new_item' => 'New Partner',
        'view_item' => 'View Partner',
        'search_items' => 'Search Partners',
        'not_found' => 'No partners found',
        'not_found_in_trash' => 'No partners found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
        'rewrite' => array( 'slug' => 'partners' ),
        'show_in_rest' => true,
    );

    register_post_type( 'site_partner', $args );
}
add_action( 'init', 'site_register_partners' );

// Flush rewrite rules on activation
function site_rewrite_flush() {
    site_register_projects();
    site_register_stories();
    site_register_resources();
    site_register_partners();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'site_rewrite_flush' );