<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

wc_print_notices(); ?>

<?php // start: modified by Arlind ?>
<div class="row">
	<div class="col-sm-6">
		<div class="bordered-block woocommerce-ResetPassword-wrapper">
			<h2><?php aurum_woocmmerce_get_translated_string( 'Reset Password', true ); ?></h2>
<?php // end: modified by Arlind ?>

			<form method="post" class="woocommerce-ResetPassword lost_reset_password">
			
				<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
			
				<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" placeholder="<?php esc_html_e( 'New password', 'woocommerce' ); ?> *" autocomplete="new-password" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" placeholder="<?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>" autocomplete="new-password" />
				</p>
			
				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />
			
				<div class="clear"></div>
			
				<?php do_action( 'woocommerce_resetpassword_form' ); ?>
			
				<p class="woocommerce-form-row form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
				</p>
			
				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
			
			</form>

<?php // start: modified by Arlind ?>
		</div>
	</div>
</div>
<?php // end: modified by Arlind ?>
