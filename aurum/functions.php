<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

// Theme Content Width
$GLOBALS['content_width'] = isset( $GLOBALS['content_width'] ) ? $GLOBALS['content_width'] : 1170;

// Core Files
require 'inc/lib/smof/smof.php';
require 'inc/laborator_classes.php';
require 'inc/laborator_actions.php';
require 'inc/laborator_filters.php';
require 'inc/laborator_functions.php';

if ( aurum_is_plugin_active( 'woocommerce/woocommerce.php' ) || class_exists( 'WooCommerce' ) ) {
	require 'inc/laborator_woocommerce.php';
}

// ACF Fields
require 'inc/acf-fields.php';

// Libraries
require 'inc/lib/laborator/laborator_gallerybox.php';
require 'inc/lib/laborator/laborator_custom_css.php';
require 'inc/lib/class-tgm-plugin-activation.php';

if ( is_admin() ) {
	require 'inc/lib/laborator/laborator-demo-content-importer/laborator_demo_content_importer.php';
}

// Blog thumbnail
$blog_thumbnail_height = get_data( 'blog_thumbnail_height' );
$blog_thumbnail_height = is_numeric( $blog_thumbnail_height ) && $blog_thumbnail_height > 100 ? $blog_thumbnail_height : 640;

add_image_size( 'post-thumb-big', 1140, $blog_thumbnail_height, true );

// Shop thumbs
add_image_size( 'shop-thumb-2', 70, 90, true );
add_image_size( 'shop-category-thumb', 320, 256, true );
