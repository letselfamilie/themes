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

global $s;

// Empty Search S
$keyword = esc_html( $s );

if ( empty( $keyword ) ) {
	wp_redirect( home_url() );
} //else {
//    echo $keyword;
//}

get_header();

get_template_part( 'tpls/search-results' );

get_footer();