<?php
/**
 * Single Product Sale Flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

/* Note: This file has been altered by Laborator */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

$oos_ribbon_show        = get_data('shop_single_oos_ribbon_show');
$featured_ribbon_show   = get_data('shop_single_featured_ribbon_show');
$sale_ribbon_show       = get_data('shop_single_sale_ribbon_show');
?>

<?php if ( $oos_ribbon_show && $product->is_in_stock() == false && ! ($product->is_type('variable') && $product->get_total_stock() > 0)) : ?>
	
	<div class="onsale oos"><?php esc_html_e( 'Out of stock', 'woocommerce' ); ?></div>

<?php elseif ( $oos_ribbon_show && ! $product->is_in_stock() && ( $product->backorders_allowed() || $product->backorders_require_notification() ) ) : ?>
	
	<div class="onsale oos bo"><?php esc_html_e( 'On backorder', 'woocommerce' ); ?></div>

<?php elseif ( $featured_ribbon_show && $product->is_featured() ) : ?>

	<div class="onsale featured"><?php esc_html_e( 'Featured', 'woocommerce' ); ?></div>

<?php elseif ( $sale_ribbon_show && $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>

<?php endif; ?>