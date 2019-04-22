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

$sidebar_id = 'shop_sidebar';

if ( is_product() && is_active_sidebar( 'shop_single_sidebar' ) ) {
	$sidebar_id = 'shop_single_sidebar';
}

$sidebar_classes = array( 'sidebar' );

if ( ! get_data( 'sidebar_borders' ) ) {
	$sidebar_classes[] = 'borderless';
}

?>
<div class="products-archive--sidebar">

	<div class="<?php echo aurum_show_classes( $sidebar_classes ); ?>">
		
		<?php dynamic_sidebar( $sidebar_id ); ?>
		
	</div>

</div>