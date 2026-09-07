# Day 2 Parent Theme Audit: The Landscaper

## Theme Overview
* **Theme Name**: The Landscaper
* **Version**: 2.6.1
* **Author**: QreativeThemes
* **Text Domain**: `the-landscaper-wp`
* **Parent Directory**: `wp-content/themes/the-landscaper`

## Enqueued Styles & Scripts
* **Styles**:
  * Font Awesome (v5.15 or v4.7.0)
  * Bootstrap (v3.4.1)
  * Main Theme Stylesheet (`style.css`)
  * WooCommerce Stylesheet (`woocommerce.css`)
  * Admin Stylesheet (`assets/css/admin.css`)
  * Block Editor Stylesheet (`assets/css/gutenberg-editor.css`)
* **Scripts**:
  * Modernizr Custom (`assets/js/modernizr-custom.js`)
  * Respimage (`assets/js/respimage.min.js`)
  * Google Maps API (conditional on API key)
  * Bootstrap (`assets/js/bootstrap.min.js`)
  * Theme Main Script (`assets/js/main.min.js`)

## Theme Features & Supports
* `title-tag`
* `automatic-feed-links`
* `woocommerce` (with `wc-product-gallery-zoom`, `wc-product-gallery-lightbox`, `wc-product-gallery-slider`)
* `align-wide`, `align-full`
* `wp-block-styles`
* `post-thumbnails` (Default: 848x480)
* `custom-background`
* Excerpt support added for `page` post type

## Registered Navigation Menus
* `primary`: Primary Menu
* `services-menu`: Services Menu
* `footer-menu`: Footer Menu

## Custom Image Sizes
* `thelandscaper-home-slider-l`: 1920x730 (hard crop)
* `thelandscaper-home-slider-m`: 960x320 (hard crop)
* `thelandscaper-home-slider-s`: 480x160 (hard crop)
* `thelandscaper-featured-thumb`: 360x240 (hard crop)
* `thelandscaper-featured-large`: 850x567 (hard crop)
* `thelandscaper-news-large`: 850x479 (hard crop)
* `thelandscaper-news-small`: 360x203 (hard crop)
* `thelandscaper-project-images`: 653x375 (hard crop)
* `thelandscaper-project-images-l`: 1170x650 (hard crop)
* `thelandscaper-project-images-m`: 720x400 (hard crop)
* `thelandscaper-project-images-s`: 480x265 (hard crop)

## Post Types and Taxonomies in Theme
* **Custom Post Types**: None registered directly in theme files (CPTs are managed via plugins / MU plugins).
* **Taxonomies**: None registered directly in theme files.

## Page Builder & Extension Integrations
* **SiteOrigin Panels**: Template support and custom widget definitions included in `inc/`.
* **Elementor**: Compatible through standard page template system.
* **WooCommerce**: Custom WooCommerce templates and styling included in `woocommerce/` and `inc/woocommerce.php`.

## Maintainability & Separation Strategy
* Parent theme `the-landscaper` will remain untouched.
* Presentation modifications will be placed in `wp-content/themes/site-child/`.
* Custom Post Types and Taxonomies reside in `wp-content/mu-plugins/` for framework independence.
