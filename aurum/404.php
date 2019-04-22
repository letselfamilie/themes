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

get_header();

?>
<div class="not-found">
	<div class="error-404"></div>
	<h1><?php _e( 'Error 404', 'aurum' ); ?></h1>
	<h2><?php _e( 'Page not found!', 'aurum' ); ?></h2>
</div>
<?php

get_footer();