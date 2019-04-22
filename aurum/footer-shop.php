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

if ( get_data( 'shop_sidebar_footer' ) ) :

	?>
	<div class="container">
		
		<div class="row sidebar shop-footer-sidebar<?php echo get_data( 'sidebar_borders' ) ? '' : ' borderless'; ?>">
			<?php aurum_get_widgets( 'shop_footer_sidebar' ); ?>
		</div>
		
	</div>
	<?php

endif;

get_footer();