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

/**
 * WooCommerce Init
 */
function aurum_woocommerce_init() {
	
	// Additional variation images plugin does not supports product images layout of Aurum
	if ( aurum_is_plugin_active( 'woocommerce-additional-variation-images/woocommerce-additional-variation-images.php' ) ) {
		add_filter( 'aurum_woocommerce_use_custom_product_image_gallery_layout', '__return_false' );
	}

	// Replace product image gallery
	if ( aurum_woocommerce_use_custom_product_image_gallery_layout() ) {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		add_action( 'woocommerce_before_single_product_summary', 'aurum_woocommerce_show_product_images', 25 );
		add_filter( 'woocommerce_available_variation', 'aurum_woocommerce_variation_image_handler', 10, 3 );
	}
	
	// Product rating
	if ( ! get_data( 'shop_single_rating' ) ) {
		add_filter( 'pre_option_woocommerce_enable_review_rating', aurum_hook_return_value( 'no' ) );
	}
	
	// Related products
	if ( '0' === get_data( 'shop_related_products_per_page' ) ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}
	
	// Remove add to cart on "Catalog mode"
	if ( aurum_is_catalog_mode() ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		
		if ( get_data( 'shop_catalog_mode_hide_prices' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
		}
	}
	
	// Disable WooCommerce_Quantity_Increment style
	if ( class_exists( 'WooCommerce_Quantity_Increment' ) ) {
		add_action( 'wp_enqueue_scripts', 'aurum_woocommerce_wqi_remove_style' );
	}
	
	// Share product
	if ( get_data( 'shop_share_product' ) ) {
		add_action( 'woocommerce_share', 'aurum_woocommerce_share' );
	}
	
	// Remove product meta
	if ( get_data( 'shop_single_meta_show' ) == false ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	}

	// Shop Category Count
	if ( ! get_data( 'shop_category_count' ) ) {
		add_filter( 'woocommerce_subcategory_count_html', aurum_hook_return_value( '' ) );
	}
}

add_action( 'woocommerce_init', 'aurum_woocommerce_init' );

/**
 * Breadcrumb is moved under page-heading element
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Remove certain WooCommerce actions
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );

/**
 * Remove default empty cart message
 */
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

/**
 * Change sidebar placement
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Move Price Below description
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );

/**
 * Related Products args
 */
add_filter( 'woocommerce_output_related_products_args', 'laborator_woocommerce_related_products_args' );

/**
 * Remove WooCommerce Quantity Increment style
 */
function aurum_woocommerce_wqi_remove_style() {
	wp_dequeue_style( 'wcqi-css' );
}

/**
 * Get shop sidebar position, if hidden "false" will be returned
 */
function aurum_woocommerce_get_sidebar_position() {
	$sidebar = get_data( 'shop_sidebar' );
	
	if ( in_array( $sidebar, array( 'left', 'right' ) ) ) {
		return $sidebar;
	}
	
	return false;
}

/**
 * Get shop sidebar position, if hidden "false" will be returned
 */
function aurum_woocommerce_single_product_get_sidebar_position() {
	$sidebar = get_data( 'shop_single_sidebar' );
	
	if ( in_array( $sidebar, array( 'left', 'right' ) ) ) {
		return $sidebar;
	}
	
	return false;
}

/**
 *  Archive wrapper before
 */
function aurum_woocommerce_archive_wrapper_start() {
	
	// Show on archive and product taxonomy page
	if ( ! ( is_shop() || is_product_taxonomy() ) ) {
		return;
	}
	
	$shop_sidebar = aurum_woocommerce_get_sidebar_position();
	
	$products_archive_classes = array( 'products-archive' );
	
	// Shop sidebar
	if ( in_array( $shop_sidebar, array( 'left', 'right' ) ) ) {
		$products_archive_classes[] = 'products-archive--has-sidebar';
		
		if ( 'left' == $shop_sidebar ) {
			$products_archive_classes[] = 'products-archive--sidebar-left';
		}
	}
	
	// Masonry layout
	if ( get_data( 'shop_loop_masonry' ) ) {
		// Enqueue isotope
		wp_enqueue_script( 'isotope' );
		
		// Set container classes
		$layout_mode = get_data( 'shop_loop_masonry_layout_mode' );
		$products_archive_classes[] = 'products-archive--masonry';
		
		if ( 'fitRows' == $layout_mode ) {
			$products_archive_classes[] = 'products-archive--fitrows';
		}
	}
	
	?>
	<div class="<?php echo implode( ' ', $products_archive_classes ); ?>">
		
		<div class="products-archive--products">
	<?php
}
	
/**
 *  Archive wrapper after
 */
function aurum_woocommerce_archive_wrapper_end() {
		
	// Show on archive and product taxonomy page
	if ( ! ( is_shop() || is_product_taxonomy() ) ) {
		return;
	}
	
	?>
		</div>
		
		<?php if ( aurum_woocommerce_get_sidebar_position() ) : ?>
		
			<?php get_sidebar( 'shop' ); ?>
			
		<?php endif; ?>
	
	</div>
		
	<?php
}

add_action( 'woocommerce_before_main_content', 'aurum_woocommerce_archive_wrapper_start', 20 );
add_action( 'woocommerce_after_main_content', 'aurum_woocommerce_archive_wrapper_end', 5 );

/**
 * Archive header
 */
function aurum_woocommerce_before_main_content_heading() {
	
	if ( false == is_product() && apply_filters( 'woocommerce_show_page_title', true ) ) {
		get_template_part( 'tpls/woocommerce-heading-title' );
		
		// Archive description
		do_action( 'woocommerce_archive_description' );
	}
}

add_action( 'woocommerce_before_main_content', 'aurum_woocommerce_before_main_content_heading', 10 );

/**
 * Shop categories
 */
function aurum_woocommerce_maybe_show_product_categories() {
	wc_set_loop_prop( 'loop', 0 );
	
	$categories = woocommerce_maybe_show_product_subcategories( '' );
	
	if ( trim( $categories ) ) {
		$classes = array( 'products', 'shop-categories' );
		$classes[] = 'columns-' . aurum_woocommerce_get_category_columns();
		
		printf( '<ul class="%s">%s</ul>', aurum_show_classes( $classes ), $categories );
	}
	
}

remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
add_filter( 'woocommerce_before_shop_loop', 'aurum_woocommerce_maybe_show_product_categories' );

/**
 * Get number of category columns
 */
function aurum_woocommerce_get_category_columns() {
	$category_columns = get_data( 'shop_category_columns' );
	return apply_filters( 'aurum_woocommerce_get_category_columns', $category_columns );
}

/**
 * Is catalog mode
 */
function aurum_is_catalog_mode() {
	return get_data( 'shop_catalog_mode' );
}

/**
 * Share Product Item
 */
function aurum_woocommerce_share() {
	global $product;

	$as_icons = get_data( 'shop_share_product_icons' );
	?>
	<div class="share-post <?php echo $as_icons ? ' share-post-icons' : ''; ?>">
		<h3><?php _e( 'Share this item:', 'aurum' ); ?></h3>
		<div class="share-product share-post-links list-unstyled list-inline">
		<?php
		$share_product_networks = get_data( 'shop_share_product_networks' );
		
		if ( $as_icons ) {
			add_filter( 'aurum_shop_product_single_share', '__return_true', 100 );
		}

		if ( is_array( $share_product_networks ) ) :

			foreach ( $share_product_networks['visible'] as $network_id => $network ) :

				if ( $network_id == 'placebo' ) {
					continue;
				}

				share_story_network_link( $network_id, $product->get_id(), false );

			endforeach;

		endif;
		?>
		</div>
	</div>
	<?php
}

/**
 * Related Product Counts
 */
function laborator_woocommerce_related_products_args( $args ) {
	$args['posts_per_page'] = get_data( 'shop_related_products_per_page' );
	return $args;
}

/**
 * Related Product Columns
 */
function aurum_woocommerce_related_products_columns( $columns ) {
	
	if ( $columns_set = aurum_get_number_from_word( get_data( 'shop_related_products_columns' ) ) ) {
		$columns = $columns_set;
	}
		
	return $columns;
}

add_filter( 'woocommerce_related_products_columns', 'aurum_woocommerce_related_products_columns' );
add_filter( 'woocommerce_upsells_columns', 'aurum_woocommerce_related_products_columns' );

/**
 * Payment Method Title
 */
function laborator_woocommerce_review_order_before_payment() {
	?><div class="vspacer"></div>
	<h2 id="payment_method_heading"><?php esc_html_e( 'Payment Method', 'aurum' ); ?></h2><?php
}

add_action( 'woocommerce_review_order_before_payment', 'laborator_woocommerce_review_order_before_payment' );

/**
 * Shop Item Thumbnail
 */
if ( ! function_exists( 'aurum_shop_loop_item_thumbnail' ) ) {
	
	function aurum_shop_loop_item_thumbnail() {
		?>
		<div class="item-image">
			<?php get_template_part( 'tpls/woocommerce-item-thumbnail' ); ?>
	
			<?php if ( 'none' != get_data( 'shop_item_preview_type' ) ) : ?>
			<div class="bounce-loader">
				<div class="loading loading-0"></div>
				<div class="loading loading-1"></div>
				<div class="loading loading-2"></div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}
}

add_action( 'woocommerce_before_shop_loop_item', 'aurum_shop_loop_item_thumbnail' );

/**
 * Remove Item Link
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/**
 * Show Product Title (Loop)
 */
if ( ! function_exists( 'aurum_shop_loop_item_title' ) ) {
	
	function aurum_shop_loop_item_title() {
		global $product;
		
		$id = $product->get_ID();
		?>
		<div class="item-info">
			<?php do_action( 'aurum_before_shop_loop_item_title' ); ?>
			
			<h3<?php echo ! get_data('shop_add_to_cart_listing') ? ' class="no-right-margin"' : ''; ?>>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
	
			<?php if(get_data('shop_product_category_listing')): ?>
			<span class="product-terms">
				<?php the_terms($id, 'product_cat'); ?>
			</span>
			<?php endif; ?>
			
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>	
		<?php
	}
}

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'aurum_shop_loop_item_title', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 20 );

/**
 * Remove price and rating below the product
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

/**
 * Shop Category Item
 */
function aurum_shop_loop_category_thumbnail( $category ) {
	
	$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
	$thumbnail_url = wc_placeholder_img_src();

	if ( $thumbnail_id ) {
		$thumbnail_url_custom = wp_get_attachment_image( $thumbnail_id, apply_filters( 'laborator_wc_category_thumbnail_size', 'shop-category-thumb' ) );

		if ( $thumbnail_url_custom ) {
			$thumbnail_url = $thumbnail_url_custom[0];
		}

		echo aurum_image_placeholder_wrap_element( $thumbnail_url_custom );
	} else {
		echo aurum_woocommerce_get_product_placeholder_image();
	}
}

add_action( 'woocommerce_before_subcategory_title', 'aurum_shop_loop_category_thumbnail' );

/**
 * Account Navigation
 */
if ( ! function_exists( 'aurum_woocommerce_before_account_navigation' ) ) {
	
	function aurum_woocommerce_before_account_navigation() {
		global $current_user;
		
		$account_page_id = wc_get_page_id( 'myaccount' );
		$account_url = get_permalink( $account_page_id );
		$logout_url = wp_logout_url( $account_url );
		
		?>
		<div class="woocommerce-MyAccount-links">
			
			<div class="user-profile">
				<a class="image">
					<?php echo get_avatar( $current_user->ID, 128 ); ?>
				</a>
				<div class="user-info">
					<a class="name" href="<?php echo the_author_meta( 'user_url', $current_user->ID ); ?>"><?php echo $current_user->display_name; ?></a>
					<a class="logout" href="<?php echo $logout_url; ?>"><?php _e( 'Logout', 'aurum' ); ?></a>
				</div>
			</div>
		<?php
	}
}

if ( ! function_exists( 'aurum_woocommerce_after_account_navigation' ) ) {
	
	function aurum_woocommerce_after_account_navigation() {
		?></div><?php
	}
}

add_action( 'woocommerce_before_account_navigation', 'aurum_woocommerce_before_account_navigation' );
add_action( 'woocommerce_after_account_navigation', 'aurum_woocommerce_after_account_navigation' );

/**
 * Aurum-styled Minicart Contents
 */
if ( ! function_exists( 'laborator_woocommerce_get_mini_cart_contents' ) ) {
	
	function laborator_woocommerce_get_mini_cart_contents() {
		ob_start();
		get_template_part( 'tpls/woocommerce-mini-cart' );
		return ob_get_clean();
	}
}

/**
 * Mini Cart
 */
function aurum_woocommerce_mini_cart_fragments( $fragments ) {
	$fragments['aurumMinicart']     = laborator_woocommerce_get_mini_cart_contents();
	$fragments['aurumCartItems']    = WC()->cart->get_cart_contents_count();
	$fragments['aurumCartSubtotal'] = WC()->cart->get_cart_subtotal();
	$fragments['aurumCartTotal'] = WC()->cart->get_cart_total();
	
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'aurum_woocommerce_mini_cart_fragments' );

/**
 * Hide title
 */
add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );

/**
 * Display Product ID on Product Meta
 */
function aurum_woocommerce_display_product_id_on_product_meta() {
	?>
	<span>
		<?php _e( 'Product ID', 'aurum' ); ?>: <strong><?php the_ID(); ?></strong>
	</span>
	<?php
}

add_action( 'woocommerce_product_meta_start', 'aurum_woocommerce_display_product_id_on_product_meta' );

/**
 * Shop section title
 */
if ( ! function_exists( 'aurum_woocommerce_section_title' ) ) {
	
	function aurum_woocommerce_section_title( $title, $subtitle = '', $return = false ) {
		
		ob_start();
		?>
		<div class="page-heading woocommerce-page-title">
			<div class="col">
				<h1>
					<?php echo esc_html( $title ); ?>
					
					<?php if ( trim( $subtitle ) ) : ?>
					<small><?php echo $subtitle; ?></small>
					<?php endif; ?>
				</h1>
			</div>
		</div>
		<?php
		$html = ob_get_clean();
		
		if ( $return ) {
			return $html;
		}
		
		echo $html;
	}
}

/**
 * Cart page contents
 */
if ( ! function_exists( 'aurum_woocommerce_show_cart_title' ) ) {
	
	function aurum_woocommerce_show_cart_title() {
		$cart_items_count = WC()->cart->get_cart_contents_count();
		
		?>
		<div class="page-heading woocommerce-page-title<?php echo WC()->cart->coupons_enabled() ? ' columns-2' : ''; ?>">
			
			<div class="col">
				<h1>
					<?php the_title(); ?>
					<small><?php echo sprintf( _n( "You've got one item in the cart", "You've got %d items in the cart", $cart_items_count, 'aurum' ), $cart_items_count ); ?></small>
				</h1>
			</div>
	
			<?php
			if ( WC()->cart->coupons_enabled() ) {
				?>
				<div class="col content-right">
					
					<?php get_template_part( 'tpls/woocommerce-coupon-form' ); ?>
					
				</div>
				<?php
			} ?>
		</div>
		<?php
	}
}

add_action( 'woocommerce_before_cart_table', 'aurum_woocommerce_show_cart_title' );

/**
 * Checkout page title
 */
if ( ! function_exists( 'aurum_woocommerce_before_checkout_form_title' ) ) {
	
	function aurum_woocommerce_before_checkout_form_title() {
		$has_login_form = ! ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) );
		$coupons_enabled = WC()->cart->coupons_enabled();
		
		$two_columns = $has_login_form || $coupons_enabled;
		?>
		<div class="page-heading woocommerce-page-title<?php echo $two_columns ? ' columns-2' : ''; ?>">
			
			<div class="col">
				
				<h1>
					<?php the_title(); ?>
					<small><?php esc_html_e( 'Personal information and payment', 'aurum' ); ?></small>
				</h1>
				
			</div>
			
			<?php if ( $two_columns ) : ?>
			<div class="col content-right">
				
				<?php
					// Login trigger
					if ( $has_login_form ) {
						echo sprintf( '<div class="login-form"><a class="icon-button show-login-form" href="#"><i></i><span class="title">%s<small>%s</small></span></a></div>', __( 'Login Here', 'aurum' ), __( 'Returning Customers', 'aurum' ) );
					}
					
					// Coupons
					if ( $coupons_enabled ) {
						get_template_part( 'tpls/woocommerce-coupon-form' ); 
					}
				?>
				
			</div>
			<?php endif; ?>
			
		</div>
		<?php
		
		// Login form
		woocommerce_checkout_login_form();
	}
}

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

add_action( 'woocommerce_before_checkout_form', 'aurum_woocommerce_before_checkout_form_title' );

/**
 * Loop cart item title wrap
 */
function aurum_woocommerce_cart_item_name( $name ) {
	return '<span class="name">' . $name . '</span>';
}

add_action( 'woocommerce_cart_item_name', 'aurum_woocommerce_cart_item_name' );

/**
 * Cart item subtotal
 */
function aurum_woocommerce_cart_item_subtotal( $price ) {
	return '<div class="price">' . $price . '</div>';
}

add_action( 'woocommerce_cart_item_subtotal', 'aurum_woocommerce_cart_item_subtotal', 10, 3 );

/**
 * YITH Wishlist Feature
 */
function aurum_woocommerce_yith_wcwl_add_to_wishlist() {
	
	if ( get_data( 'shop_wishlist_catalog_show' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
		global $product;
		
		$id = $product->get_id();
		$type = $product->get_type();

		echo do_shortcode( "<div class=\"yith-add-to-wishlist\">[yith_wcwl_add_to_wishlist product_id='{$id}' product_type='{$type}' label='' browse_wishlist_text='' already_in_wishslist_text='']</div>" );
	}
}

add_action( 'woocommerce_before_shop_loop_item', 'aurum_woocommerce_yith_wcwl_add_to_wishlist' );

/**
 * Default Form Fields Args
 */
function aurum_woocommerce_form_field_args( $field ) {
	$field['class'][] = 'form-group';
	$field['input_class'] = array();
	$field['placeholder'] = ( isset( $field['label'] ) ? $field['label'] : '' ) . ( isset( $field['required'] ) && $field['required'] ? ' *' : '' );
	$field['label_class'] = 'hidden';
	
	if ( 'order_comments' == $field['id'] ) {
		$field['label_class'] = '';
		$field['placeholder'] = __( 'Include custom requirements for this order here', 'aurum' );
		$field['input_class'] = array( 'form-control autogrow' );
	}
	
	return $field;
}

add_filter( 'woocommerce_form_field_args', 'aurum_woocommerce_form_field_args' );

/**
 * Empty cart page
 */
function aurum_woocommerce_empty_cart_page( $classes ) {
	if ( function_exists( 'WC' ) && is_cart() && WC()->cart->is_empty() ) {
		$classes[] = 'wc-cart-empty';
	}
	
	return $classes;
}

add_filter( 'body_class', 'aurum_woocommerce_empty_cart_page' );

/**
 * Product Rating
 */
if ( ! function_exists( 'aurum_woocommerce_product_get_rating_html' ) ) {
	
	function aurum_woocommerce_product_get_rating_html( $html, $average, $count ) {
		
		ob_start();
		?>	
		<div class="star-rating-icons" class="tooltip" data-toggle="tooltip" data-placement="<?php echo ! is_rtl() ? 'right' : 'left'; ?>" title="<?php echo esc_html( $average ); ?> <?php _e( 'out of 5', 'aurum' ); ?>">
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
			<i class="entypo-star<?php echo $average >= $i || ( $average > 0 && intval( $average ) == $i - 1 && ( $average - intval( $average ) > 0.49 ) ) ? ' filled' : ''; ?>"></i>
			<?php endfor; ?>
		</div>
		<?php
			
		return ob_get_clean();
	}
}

add_filter( 'woocommerce_product_get_rating_html', 'aurum_woocommerce_product_get_rating_html', 10, 3 );

/**
 * Products per row on mobile
 */
function aurum_woocommerce_products_per_row_on_mobile() {
	$columns_mobile = aurum_get_number_from_word( get_data( 'shop_products_mobile_two_per_row' ) );
	$cols = 2 == $columns_mobile ? 2 : 1;
	
	return apply_filters( 'aurum_woocommerce_products_per_row_on_mobile', $cols );
}

/**
 * Product columns
 */
function aurum_woocommerce_catalog_loop_product_classes( $classes ) {
	global $woocommerce_loop;
	
	// Use default number of columns for product
	wc_get_loop_class();
	
	// Only when is AJAX request
	if ( is_ajax() ) {
		$classes[] = 'product';
	}
	
	// Preview type
	switch ( get_data( 'shop_item_preview_type' ) ) {
		case 'fade':
			$classes[] = 'hover-effect-1';
			break;
	
		case 'slide':
			$classes[] = 'hover-effect-1 image-slide';
			break;
	
		case 'zoom-over':
			$classes[] = 'hover-effect-zoom-over';
			break;
	
		case 'gallery':
			$classes[] = 'hover-effect-2 image-slide';
			break;
	}
	
	// Product rows in mobile
	if ( 2 == aurum_woocommerce_products_per_row_on_mobile() ) {
		$classes[] = 'columns-xs-2';
	}
	
	return $classes;
}

add_filter( 'aurum_woocommerce_catalog_loop_product_classes', 'aurum_woocommerce_catalog_loop_product_classes' );

/**
 * Product category classes
 */
function aurum_woocommerce_product_cat_class( $classes ) {
	if ( 'two' == get_data( 'shop_categories_mobile_per_row' ) ) {
		$classes[] = 'columns-xs-2';
	}
	return $classes;
}

add_filter( 'product_cat_class', 'aurum_woocommerce_product_cat_class' );

// Filter catalog orderby options
function aurum_woocommerce_catalog_orderby_filter( $catalog_orderby ) {
	if ( get_data( 'shop_catalog_mode' ) && get_data( 'shop_catalog_mode_hide_prices' ) ) {
		unset( $catalog_orderby['price'] );
		unset( $catalog_orderby['price-desc'] );
	}

	return $catalog_orderby;
}

add_filter( 'woocommerce_catalog_orderby', 'aurum_woocommerce_catalog_orderby_filter', 100 );

// Cart link replacement
if ( ! function_exists( 'aurum_woocommerce_loop_add_to_cart_link' ) ) {
	
	function aurum_woocommerce_loop_add_to_cart_link( $html, $product, $args = array() ) {
		global $wp_query;
		
		// Catalog mode
		if ( aurum_is_catalog_mode() || ! get_data( 'shop_add_to_cart_listing' ) ) {
			return '';
		}
		
		$is_textual = get_data( 'shop_add_to_cart_textual' );
		
		$classes = $add_to_cart_link_attrs = array( ' ' );
		
		if ( in_array( 'wishlist-action', array_keys( $wp_query->query ) ) || ( is_ajax() && isset( $_REQUEST['remove_from_wishlist'] ) ) ) {
			$is_textual = true;
		}
		
		if ( $is_textual ) {
			$classes[] = 'is-textual';
		} else {
			$add_to_cart_link_attrs[] = 'data-toggle="tooltip"';
			$add_to_cart_link_attrs[] = 'data-placement="' . ( is_rtl() ? 'right' : 'left' ) . '"';
			$add_to_cart_link_attrs[] = 'data-title="' . $product->add_to_cart_text() . '"';
			$add_to_cart_link_attrs[] = 'data-title-loaded="' . __( 'Product added to cart!', 'aurum' ) . '"';
		}
		
		$classes[] = 'product-type-' . $product->get_type();
		
		if ( preg_match( '#<a(?<attributes>[^>]+)>(?<content>.*?)<\/a>#', $html, $matches ) ) {
			$attributes = $matches['attributes'];
			$content = $is_textual ? $matches['content'] : '';
			
			// Add clasees
			$attributes = preg_replace( '#class="(.*?)"#', 'class="${1}' . implode( ' ', $classes ) . '"', $attributes );
			
			// Add other attributes
			$attributes .= implode( '', $add_to_cart_link_attrs );
			
			return "<a {$attributes}>{$content}</a>";
		}
		
		return $html;
	}
}

add_filter( 'woocommerce_loop_add_to_cart_link', 'aurum_woocommerce_loop_add_to_cart_link', 10, 3 );

/**
 * Product thumbnail image size
 */
function aurum_woocommerce_get_thumbnail_image_size() {
	$deprecated_size_filter = apply_filters( 'single_product_small_thumbnail_size', 'woocommerce_thumbnail' );
	return apply_filters( 'single_product_archive_thumbnail_size', $deprecated_size_filter );
}

/**
 * Product single image size
 */
function aurum_woocommerce_get_single_image_size() {
	$deprecated_size_filter = apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' );
	return apply_filters( 'single_product_archive_thumbnail_size', $deprecated_size_filter );
}

/**
 * Product gallery image size
 */
function aurum_woocommerce_get_product_gallery_image_size() {
	return apply_filters( 'woocommerce_gallery_thumbnail_size', 'woocommerce_gallery_thumbnail' );
}

/**
 * Get single product thumbnails direction
 */
function aurum_woocommerce_get_single_product_thumbnails_placement() {
	return 'horizontal' == get_data( 'shop_product_thumbnails_placing' ) ? 'horizontal' : 'vertical';
}

/**
 * Product next and previous links in single product page
 */
if ( ! function_exists( 'aurum_woocommerce_single_product_prev_next' ) ) {
	
	function aurum_woocommerce_single_product_prev_next() {
		
		if ( is_product() ) {
			get_template_part( 'tpls/woocommerce-product-next-prev' );
		}
	}
}

add_action( 'woocommerce_after_main_content', 'aurum_woocommerce_single_product_prev_next', 25 );

/**
 * Single product wrapper start
 */
if ( ! function_exists( 'aurum_woocommerce_single_product_wrapper_start' ) ) {
	
	function aurum_woocommerce_single_product_wrapper_start() {
		
		$classes = array( 'single-product' );
		$sidebar = aurum_woocommerce_single_product_get_sidebar_position();
		
		if ( $sidebar ) {
			$classes[] = 'single-product--has-sidebar';
			
			if ( 'left' == $sidebar ) {
				$classes[] = 'single-product--sidebar-left';
			}
			
			if ( get_data( 'shop_single_sidebar_before_products_mobile' ) ) {
				$classes[] = 'single-product--sidebar-first';
			}
		}
		
		?>
		<div <?php aurum_class_attr( $classes ); ?>>
			
			<div class="single-product--product-details">
		<?php
	}
}

add_action( 'woocommerce_before_single_product', 'aurum_woocommerce_single_product_wrapper_start', 1 );

/**
 * Single product wrapper end
 */
if ( ! function_exists( 'aurum_woocommerce_single_product_wrapper_end' ) ) {
	
	function aurum_woocommerce_single_product_wrapper_end() {
		
		?>
			</div>
			
			<?php
				// Sidebar
				if ( aurum_woocommerce_single_product_get_sidebar_position() ) :
				
				?>
				
				<div class="single-product--sidebar">
					
					<?php
						// Show widgets
						$sidebar = is_active_sidebar( 'shop_sidebar_single' ) ? 'shop_sidebar_single' : 'shop_sidebar';
						
						aurum_get_widgets( $sidebar, 'single-product--widgets' );
					?>
					
				</div>
				
				<?php
				endif;
			?>
			
		</div>
		<?php
	}
}

add_action( 'woocommerce_after_single_product', 'aurum_woocommerce_single_product_wrapper_end', 1000 );

/**
 * Use Aurum's default product gallery layout
 */
function aurum_woocommerce_use_custom_product_image_gallery_layout() {
	return apply_filters( 'aurum_woocommerce_use_custom_product_image_gallery_layout', true );
}

/**
 * Single product image wrapper
 */
function aurum_woocommerce_before_single_product_summary_before() {
	
	$classes = array( 'product-images-container' );
	
	if ( aurum_woocommerce_use_custom_product_image_gallery_layout() ) {
		$classes[] = 'thumbnails-' . aurum_woocommerce_get_single_product_thumbnails_placement();
		
		if ( get_data( 'shop_magnifier' ) ) {
			$classes[] = 'zoom-enabled';
		}
		
		$auto_rotate = get_data( 'shop_single_auto_rotate_image' );
		$auto_rotate = is_numeric( $auto_rotate ) ? $auto_rotate : 5;
		
		if ( $auto_rotate ) {
			$classes[] = 'auto-rotate';
		}
		
		echo sprintf( '<div %s data-autorotate="%d">', aurum_class_attr( $classes, false ), esc_attr( $auto_rotate ) );
	} else {
		echo sprintf( '<div %s>', aurum_class_attr( $classes, false ) );
	}
	
}

function aurum_woocommerce_before_single_product_summary_after() {
	echo '</div>';
}

add_action( 'woocommerce_before_single_product_summary', 'aurum_woocommerce_before_single_product_summary_before', 1 );
add_action( 'woocommerce_before_single_product_summary', 'aurum_woocommerce_before_single_product_summary_after', 10000 );

/**
 * Aurum's built in image gallery
 */
function aurum_woocommerce_show_product_images() {
	get_template_part( 'tpls/woocommerce-product-images' );
}

/**
 * Get product image
 */
if ( ! function_exists( 'aurum_woocommerce_get_gallery_image' ) ) {

	function aurum_woocommerce_get_gallery_image( $attachment_id, $main_image = false ) {
		
		$thumbnail_size = $main_image ? aurum_woocommerce_get_single_image_size() : aurum_woocommerce_get_product_gallery_image_size();
		
		// Image size
		$image_size = apply_filters( 'woocommerce_gallery_image_size', $thumbnail_size );
		
		// Fullsize image
		$full_size = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$full_src = wp_get_attachment_image_src( $attachment_id, $full_size );
		
		$image = aurum_get_attachment_image( $attachment_id, $image_size, false, array(
			'title'                   => get_post_field( 'post_title', $attachment_id ),
			'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
			'class'                   => 'wp-post-image',
			'data-large_image_width'  => $full_src[1],
			'data-large_image_height' => $full_src[2],
		) );
		
		// Lightbox trigger
		$lightbox_trigger = '';
		
		if ( $main_image && get_data( 'shop_single_lightbox' ) ) {
			$lightbox_trigger = aurum_woocommerce_get_lightbox_trigger_button();
		}
		
		return sprintf( '<div class="woocommerce-product-gallery__image"><a href="%2$s">%1$s</a>%3$s</div>', $image, $full_src[0], $lightbox_trigger );
	}
}

/**
 *  Add Aurum style images for variations
 */
if ( ! function_exists( 'aurum_woocommerce_variation_image_handler' ) ) {
	
	function aurum_woocommerce_variation_image_handler( $variation_arr, $variable_product, $variation ) {
		$attachment_id = $variation->get_image_id();
		
		$variation_arr['aurum_image'] = array();
		
		// Product main and thumbmail image
		if ( $attachment_id ) {
			$variation_arr['aurum_image']['main'] = aurum_woocommerce_get_gallery_image( $attachment_id, true );
			$variation_arr['aurum_image']['thumb'] = aurum_woocommerce_get_gallery_image( $attachment_id );
		}
		
		return $variation_arr;
	}
}

/**
 * Trigger lightbox button
 */
if ( ! function_exists( 'aurum_woocommerce_get_lightbox_trigger_button' ) ) {
	
	function aurum_woocommerce_get_lightbox_trigger_button() {
		return '<button class="product-gallery-lightbox-trigger" title="' . esc_attr__( 'View full size', 'aurum' ) . '">+</button>';
	}
}

/**
 * Update cart, checkout link and shipping calculator
 */
function aurum_woocommerce_cart_collaterals_after() {
	?>
	<div class="cart-buttons">
		<div class="col">
			<button type="button" class="button button-block button-large button-secondary" id="update-cart-secondary"><?php _e( 'Update Cart', 'aurum' ); ?></button>
		</div>

		<div class="col">
			<a class="button button-block button-large" href="<?php echo esc_url( wc_get_checkout_url() ); ?>" id="proceed-to-checkout"><?php esc_html_e( 'Checkout', 'aurum' ); ?></a>
		</div>
	</div>
	<?php
	
	// Shipping calculator
	woocommerce_shipping_calculator();
}

remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
add_action( 'woocommerce_cart_collaterals', 'aurum_woocommerce_cart_collaterals_after', 20 );

/**
 * Yith Infinite Scrolling selectors and containers
 */
if ( defined( 'YITH_INFS' ) ) {
	
	function aurum_yith_infinite_scroll_selectors_config( $opts ) {	
		$opts['yith-infs-navselector'] = '.woocommerce-pagination';
		$opts['yith-infs-nextselector'] = '.woocommerce-pagination .next';
		$opts['yith-infs-itemselector'] = '.type-product.shop-item';
		$opts['yith-infs-contentselector'] = '#main';
	
		return $opts;
	}
	
	add_filter( 'pre_option_yit_infs_options', 'aurum_yith_infinite_scroll_selectors_config', 100 );
}

/**
 * Login form title in customer page
 */
function aurum_woocommerce_before_customer_login_form_title() {
	
	aurum_woocommerce_section_title( __( 'Login', 'woocommerce' ), __( 'Manage your account and see your orders', 'aurum' ) );
}

add_action( 'woocommerce_before_customer_login_form', 'aurum_woocommerce_before_customer_login_form_title' );

/**
 * Login form title
 */
function aurum_woocommerce_login_form_start_title() {
	echo '<h2>' . esc_html__( 'Login', 'woocommerce' ) . '</h2>';
}

add_action( 'woocommerce_login_form_start', 'aurum_woocommerce_login_form_start_title', 10 );

/**
 * Register form title
 */
function aurum_woocommerce_register_form_start_title() {
	echo '<h2>' . esc_html__( 'Register', 'woocommerce' ) . '</h2>';
}

add_action( 'woocommerce_register_form_start', 'aurum_woocommerce_register_form_start_title', 10 );

/**
 * Login form wrapper
 */
function aurum_woocommerce_login_form_start() {
	echo '<div class="bordered-block">';
}

function aurum_woocommerce_login_form_end() {
	echo '</div>';
}

add_action( 'woocommerce_login_form_start', 'aurum_woocommerce_login_form_start', 5 );
add_action( 'woocommerce_login_form_end', 'aurum_woocommerce_login_form_end' );

add_action( 'woocommerce_register_form_start', 'aurum_woocommerce_login_form_start', 5 );
add_action( 'woocommerce_register_form_end', 'aurum_woocommerce_login_form_end' );

add_action( 'woocommerce_order_details_before_order_table', 'aurum_woocommerce_login_form_start', 5 );
add_action( 'woocommerce_order_details_after_order_table', 'aurum_woocommerce_login_form_end' );

/**
 * Cart item image size
 */
function aurum_woocommerce_get_cart_image_size() {
	return apply_filters( 'lab_wc_cart_image_size', 'shop-thumb-2' );
}

/**
 * Cart item image
 */
function aurum_woocommerce_cart_item_thumbnail( $html, $cart_item, $cart_item_key ) {
	
	if ( ! is_cart() ) {
		return $html;
	}
	
	$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	return aurum_image_placeholder_wrap_element( $_product->get_image( aurum_woocommerce_get_cart_image_size() ) );
}

add_action( 'woocommerce_cart_item_thumbnail', 'aurum_woocommerce_cart_item_thumbnail', 10, 3 );

/**
 * Orders page title
 */
function aurum_woocommerce_before_account_orders_title() {
	aurum_woocommerce_section_title( __( 'Orders', 'woocommerce' ), __( 'My recent orders', 'aurum' ) );
}

add_action( 'woocommerce_before_account_orders', 'aurum_woocommerce_before_account_orders_title' );

/**
 * My downloads title
 */
function aurum_woocommerce_before_available_downloads_title() {
	aurum_woocommerce_section_title( __( 'Downloads', 'woocommerce' ), __( 'Available downloads for your account', 'aurum' ) );
}

add_action( 'woocommerce_before_available_downloads', 'aurum_woocommerce_before_available_downloads_title' );

/**
 * Wishlist title
 */
function aurum_yith_wcwl_wishlist_title( $title ) {
	if ( preg_match( '#<h2>(.*?)<\/h2>#', $title, $matches ) ) {
		
		return aurum_woocommerce_section_title( $matches[1], __( 'Your favorite list of products', 'aurum' ), true );
	}
	
	return $title;
}

add_action( 'yith_wcwl_wishlist_title', 'aurum_yith_wcwl_wishlist_title' );

/**
 * Wishlist wrapper
 */
function aurum_yith_wcwl_before_wishlist_wrapper() {
	echo '<div class="woocommerce-cart-form">';
}

function aurum_yith_wcwl_after_wishlist_wrapper() {
	echo '</div>';
}

add_action( 'yith_wcwl_before_wishlist', 'aurum_yith_wcwl_before_wishlist_wrapper' );
add_action( 'yith_wcwl_after_wishlist', 'aurum_yith_wcwl_after_wishlist_wrapper' );

/**
 * Yith Wishlist product name wrap
 */
add_action( 'woocommerce_in_cartproduct_obj_title', 'aurum_woocommerce_cart_item_name' );

/**
 * Yith Wishlist - display price under product name
 */
function aurum_yith_wcwl_table_after_product_name( $item ) {
	global $product;
	
	if ( 'yes' == get_option( 'yith_wcwl_price_show' ) ) {
		$base_product = $product->is_type( 'variable' ) ? $product->get_variation_regular_price( 'max' ) : $product->get_price();
		echo '<span class="price">';
	    echo $base_product ? $product->get_price_html() : apply_filters( 'yith_free_text', __( 'Free!', 'yith-woocommerce-wishlist' ) ); 
		echo '</span>';
	}
}

add_action( 'yith_wcwl_table_after_product_name', 'aurum_yith_wcwl_table_after_product_name' );

/**
 * Edit account form title
 */
function aurum_woocommerce_before_edit_account_form_title() {
	aurum_woocommerce_section_title( __( 'Edit Account', 'aurum' ), __( 'Change your account details', 'aurum' ) );
}

add_action( 'woocommerce_before_edit_account_form', 'aurum_woocommerce_before_edit_account_form_title' );

/**
 * Hide Single Product Description Tab heading
 */
add_filter( 'woocommerce_product_description_heading', aurum_hook_return_value( '' ) );

/**
 * Get translated strings to use inside WooCommerce template folder
 */
function aurum_woocmmerce_get_translated_string( $str, $echo = false ) {
	$strings = array(
		'Get the order details and notes' => __( 'Get the order details and notes', 'aurum' ),
		'Edit address information' => __( 'Edit address information', 'aurum' ),
		'Forgot Password' => __( 'Forgot Password', 'aurum' ),
		'My Addresses' => __( 'My Addresses', 'aurum' ),
		'My Address' => __( 'My Address', 'aurum' ),
		'Reset Password' => __( 'Reset Password', 'aurum' ),
		'Pay Order' => __( 'Pay Order', 'aurum' ),
		'You are making payment for order:' => __( 'You are making payment for order:', 'aurum' ),
		'&laquo; Go back' => __( '&laquo; Go back', 'aurum' ),
		'Cart Empty' => __( 'Cart Empty', 'aurum' ),
		'Browse our products &amp; fill the cart!' => __( 'Browse our products &amp; fill the cart!', 'aurum' ),
		'&laquo; Previous' => __( '&laquo; Previous', 'aurum' ),
		'Next &raquo;' => __( 'Next &raquo;', 'aurum' ),
	);
	
	if ( isset( $strings[ $str ] ) ) {
		if ( ! $echo ) {
			return $strings[ $str ];
		}
		echo $strings[ $str ];
	}
	
	return '{null}';
}

/**
 * Product prices handler
 */
function aurum_woocommerce_get_price_html( $price ) {
	$hide_prices = false == get_data( 'shop_product_price_listing' );
	
	// Price is visible on product single page only
	if ( $hide_prices && is_product() && did_action( 'woocommerce_before_single_product_summary' ) && ! did_action( 'woocommerce_after_single_product_summary' ) ) {
		$hide_prices = false;
	}
	
	if ( ( aurum_is_catalog_mode() && get_data( 'shop_catalog_mode_hide_prices' ) ) || $hide_prices ) {
		return '';
	}
	
	return $price;
}

add_filter( 'woocommerce_get_price_html', 'aurum_woocommerce_get_price_html', 100 );

/**
 * Product placeholder image
 */
if ( ! function_exists( 'aurum_woocommerce_get_product_placeholder_image' ) ) {
	
	function aurum_woocommerce_get_product_placeholder_image() {
		$placeholder_image = wc_placeholder_img_src();
		
		if ( strpos( $placeholder_image, site_url() ) === 0 ) {
			$image_path = str_replace( site_url( '/' ), ABSPATH, $placeholder_image );
			
			if ( file_exists( $image_path ) ) {
				$size = @getimagesize( $image_path );
				
				if ( $size ) {
					$image = sprintf( '<img src="%s" alt="" width="%d" height="%d" class="wp-post-image product-placeholder-image">', $placeholder_image, $size[0], $size[1] );
					return aurum_image_placeholder_wrap_element( $image );
				}
			}
		}
		
		return sprintf( '<span class="product-placeholder-image"><img src="%s" alt=""></span>', $placeholder_image );
	}
}

/**
 * Item price in cart
 */
if ( ! function_exists( 'aurum_woocommerce_cart_product_price_display' ) ) {
	
	function aurum_woocommerce_cart_product_price_display( $cart_item, $cart_item_key ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		
		echo '<span class="price">';
		echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
		echo '</span>';
	}
}

add_action( 'woocommerce_after_cart_item_name', 'aurum_woocommerce_cart_product_price_display', 10, 2 );

/**
 * Assign "form-control" class to variation select field
 */
if ( ! function_exists( 'aurum_woocommerce_dropdown_variation_attribute_options_args' ) ) {
	
	function aurum_woocommerce_dropdown_variation_attribute_options_args( $args ) {
		if ( empty( $args['class'] ) ) {
			$args['class'] = '';
		}
		
		$args['class'] .= ' form-control';
		
		return $args;
	}
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'aurum_woocommerce_dropdown_variation_attribute_options_args', 10 );
