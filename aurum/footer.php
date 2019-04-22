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

global $theme_version;

if ( ! defined( 'LAB_FOOTERLESS' ) ) {
	get_template_part( 'tpls/footer' );
}

wp_footer(); 
?>
	
	<!-- <?php echo 'ET: ', microtime( true ) - TS, 's ', $theme_version, ( is_child_theme() ? 'ch' : '' ); ?> -->

</body>
</html>
