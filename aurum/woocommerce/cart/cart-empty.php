<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

?>

<div class="shop-empty-cart-page cart-empty">

	<div class="container">
		<div class="col-sm-12 text-center">

			<div class="cart-bag-env">
				<div class="cart-smiley">
					<i></i>
					<i class="cart-smiley-hands"></i>
				</div>
				<div class="cart-bag"></div>
			</div>

			<div class="cart-empty-title">
				<h1><?php aurum_woocmmerce_get_translated_string( 'Cart Empty', true ); ?></h1>
				
				<?php				
				
				/**
				 * @hooked wc_empty_cart_message - 10
				 */
				do_action( 'woocommerce_cart_is_empty' );
				
				?>

				<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
				<p class="return-to-shop">
					<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
						<?php aurum_woocmmerce_get_translated_string( 'Browse our products &amp; fill the cart!', true ); ?>
					</a>
				</p>
				<?php endif; ?>
			</div>

			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					$( '.cart-smiley' ).addClass( 'shown' );
				} );
			</script>

		</div>
	</div>

</div>