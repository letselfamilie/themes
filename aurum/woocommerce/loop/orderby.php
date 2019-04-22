<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// start: modified by Arlind
$selected = '';

$dropdown = '<ul class="dropdown-menu" role="menu">';

foreach ( $catalog_orderby_options as $id => $name ) {
	if ( $id == $orderby ) {
		$selected = $name;
	}
	$dropdown .= sprintf( '<li%3$s role="presentation"><a role="menuitem" tabindex="-1" href="#%1$s">%2$s</a>', esc_attr( $id ), esc_html( $name ), $orderby == $id ? ' class="active"' : '' );
}

$dropdown .= '</ul>';
// end: modified by Arlind
?>
<form class="woocommerce-ordering" method="get">
	
	<?php // start: modified by Arlind ?>
	<div class="form-group sort pull-right-md">
		
		<div class="dropdown">
			
			<button class="btn btn-block btn-bordered dropdown-toggle" type="button" data-toggle="dropdown">
				<?php echo esc_html( $selected ); ?>
				<span class="caret"></span>
			</button>
			
			<?php echo $dropdown; ?>
			
		</div>
		
	</div>
	<?php // end: modified by Arlind ?>
	
	<select name="orderby" class="orderby">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="paged" value="1" />
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>
