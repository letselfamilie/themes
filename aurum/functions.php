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

add_action('um_after_account_general', 'show_extra_fields', 100);
function show_extra_fields() {

    $id = um_user('ID');
    $output = '';

    $names = array( "receive_notifications", "chat_sound", "push_notifications");

    $fields = array();
    foreach( $names as $name )
        $fields[ $name ] = UM()->builtin()->get_specific_field( $name );
    $id = um_user('ID');
    $fields = apply_filters( 'um_account_secure_fields', $fields, $id );

    foreach( $fields as $key => $data )
        $output .= UM()->fields()->edit_field( $key, $data );

    echo $output;
}

function get_custom_fields( $fields ) {
    global $ultimatemember;
    $array=array();
    foreach ($fields as $field ) {
        if ( isset( UM()->builtin()->saved_fields[$field] ) ) {
            $array[$field] = UM()->builtin()->saved_fields[$field];
        } else if ( isset( UM()->builtin()->predefined_fields[$field] ) ) {
            $array[$field] = UM()->builtin()->predefined_fields[$field];
        }
    }
    return $array;
}