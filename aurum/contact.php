<?php
/*
	Template Name: Contact Page
*/

/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

wp_enqueue_script( 'aurum-contact' );

get_header();

get_template_part( 'tpls/contact' );

get_footer();