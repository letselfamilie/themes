<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

?>
<div class="coupon-form">
	
	<a href="#" class="icon-button icon-coupon">
		<i></i>
		<span class="title">
			<?php esc_html_e( 'Enter Coupon', 'aurum' ); ?>
			<small><?php esc_html_e( 'To get discounts', 'aurum' ); ?></small>
		</span>
	</a>

	<div class="coupon">

		<?php woocommerce_checkout_coupon_form(); ?>
		
	</div>
	
</div>