<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

$shop_cart_show_on_hover = get_data('shop_cart_show_on_hover');

if ( ! get_data( 'header_links' ) ) {
	return;
}
?>
<div class="header-links">

	<ul class="header-widgets">
		<?php if ( get_data( 'header_links_search_form' ) ) : ?>
		<li>

			<form action="<?php echo home_url(); ?>" method="get" class="search-form<?php echo get_search_query() ? ' input-visible' : ''; ?>" enctype="application/x-www-form-urlencoded">

				<div class="search-input-env<?php echo trim( lab_get( 's' ) ) ? ' visible' : ''; ?>">
					<input type="text" class="form-control search-input" name="s" placeholder="<?php _e( 'Search...', 'aurum' ); ?>" value="<?php echo get_search_query( true ); ?>">
				</div>
				
				<?php aurum_wpml_current_language_hidden_input(); ?>

				<a href="#" class="search-btn">
					<?php echo lab_get_svg( 'images/search.svg' ); ?>
					<span class="sr-only"><?php _e( 'Search', 'aurum' ); ?></span>
				</a>

			</form>

		</li>
		<?php endif; ?>

		<?php 
		if ( get_data( 'header_cart_info' ) && function_exists( 'WC' ) && ! aurum_is_catalog_mode() ) :
			
			
			// Cart icon
			$cart_icon = get_data( 'header_cart_info_icon' );

			if ( ! $cart_icon ) {
				$cart_icon = 1;
			}
			
			// Classes
			$classes = array( 'cart-counter' );
			
			// Hover activated
			if ( $shop_cart_show_on_hover ) {
				$classes[] = 'hover-activated';
			}
			
			// Direct link
			if ( apply_filters( 'aurum_mini_cart_direct_link', false ) ) {
				$classes[] = 'direct-link';
			}

		?>
		<li>
			<a <?php aurum_class_attr( $classes ); ?> href="<?php echo wc_get_cart_url(); ?>">
				<?php if ( get_data( 'shop_cart_show_counter' ) ) : ?>
					<span class="badge items-count">0</span>
				<?php endif; ?>
				
				<?php echo lab_get_svg( "images/cart_{$cart_icon}.svg" ); ?>
			</a>

			<div class="woocommerce lab-mini-cart">
				<div class="cart-is-loading"><?php _e( 'Loading cart contents...', 'aurum' ); ?></div>
			</div>
		</li>
		<?php 
		endif; 
		?>
	</ul>

</div>