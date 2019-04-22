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

// Enqueue comment reply
if ( comments_open() ) {
	wp_enqueue_script( 'comment-reply' );
}

// Enqueue lightbox
wp_enqueue_script( 'nivo-lightbox' );
wp_enqueue_style( 'nivo-lightbox-default' );

// View post
get_template_part( 'archive' );