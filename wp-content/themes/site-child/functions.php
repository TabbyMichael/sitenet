<?php
/**
 * SITE Child Theme Functions
 */

// Enqueue child theme styles
function site_child_enqueue_styles() {
    wp_enqueue_style( 'site-child-style', 
        get_stylesheet_directory_uri() . '/style.css',
        array( 'thelandscaper-main' ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_styles' );

// Remove parent theme unnecessary features
function site_child_cleanup() {
    // Remove landscaping-specific features that don't apply to NGO
    remove_theme_support( 'woocommerce' ); // Will be re-added if needed
}
add_action( 'after_setup_theme', 'site_child_cleanup', 10 );

// Add SITE-specific image sizes
function site_add_image_sizes() {
    add_image_size( 'site-hero', 1920, 730, true );
    add_image_size( 'site-program-card', 600, 400, true );
    add_image_size( 'site-story-thumb', 400, 300, true );
}
add_action( 'after_setup_theme', 'site_add_image_sizes' );