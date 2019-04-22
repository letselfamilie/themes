<?php
/*
	Template Name: Fullwidth Page
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

get_header();

echo '<div class="page-container standalone-container">';

the_content();

echo '</div>';

get_footer();