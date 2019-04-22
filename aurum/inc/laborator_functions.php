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
 * SMOF data
 */
function get_data( $var = '' ) {
	global $smof_data;

	if ( ! function_exists( 'of_get_options' ) ) {
		return null;
	}

	if ( ! empty( $var ) && isset( $smof_data[ $var ] ) ) {
		return apply_filters( "get_data_{$var}", $smof_data[ $var ] );
	}

	return null;
}

$smof_data = of_get_options();

/**
 * Get element from array by key (fail safe)
 */
function get_array_key( $arr, $key ) {
	if ( ! is_array( $arr ) ) {
		return null;
	}
	
	return isset( $arr[ $key ] ) ? $arr[ $key ] : null;
}

/**
 * GET/POST/COOKIE getter
 */
function lab_get( $var ) {
	return isset( $_GET[ $var ] ) ? $_GET[ $var ] : ( isset( $_REQUEST[ $var ] ) ? $_REQUEST[ $var ] : '' );
}

function post( $var ) {
	return isset ( $_POST[ $var ] ) ? $_POST[ $var ] : null;
}

function cookie( $var ) {
	return isset( $_COOKIE[ $var ] ) ? $_COOKIE[ $var ] : null;
}

/**
 * Generate From-To numbers borders
 */
function generate_from_to( $from, $to, $current_page, $max_num_pages, $numbers_to_show = 5 ) {
	if ( $numbers_to_show > $max_num_pages) {
		$numbers_to_show = $max_num_pages;
	}

	$add_sub_1 = round( $numbers_to_show / 2 );
	$add_sub_2 = round( $numbers_to_show - $add_sub_1 );

	$from = $current_page - $add_sub_1;
	$to = $current_page + $add_sub_2;

	$limits_exceeded_l = FALSE;
	$limits_exceeded_r = FALSE;

	if ( $from < 1 ) {
		$from = 1;
		$limits_exceeded_l = TRUE;
	}

	if ( $to > $max_num_pages ) {
		$to = $max_num_pages;
		$limits_exceeded_r = TRUE;
	}

	if ( $limits_exceeded_l ) {
		$from = 1;
		$to = $numbers_to_show;
	} else if ( $limits_exceeded_r ) {
		$from = $max_num_pages - $numbers_to_show + 1;
		$to = $max_num_pages;
	} else {
		$from += 1;
	}

	if ( $from < 1 ) {
		$from = 1;
	}

	if ( $to > $max_num_pages ) {
		$to = $max_num_pages;
	}

	return array( $from, $to );
}

/**
 * Laborator Pagination
 */
function laborator_show_pagination( $current_page, $max_num_pages, $from, $to, $pagination_position = 'full', $numbers_to_show = 5 ) {
	$current_page = $current_page ? $current_page : 1;

	?>
	<div class="clear"></div>

	<!-- pagination -->
	<ul class="pagination<?php echo $pagination_position ? " pagination-{$pagination_position}" : ''; ?>">

	<?php if($current_page > 1): ?>
		<li class="first_page"><a href="<?php echo get_pagenum_link( 1 ); ?>"><?php _e( '&laquo; First', 'aurum' ); ?></a></li>
	<?php endif; ?>

	<?php if ( $current_page > 2 ) : ?>
		<li class="first_page"><a href="<?php echo get_pagenum_link( $current_page - 1 ); ?>"><?php _e( 'Previous', 'aurum' ); ?></a></li>
	<?php endif; ?>

	<?php

	if ( $from > floor( $numbers_to_show / 2 ) ) {
		?>
		<li><a href="<?php echo get_pagenum_link( 1 ); ?>"><?php echo 1; ?></a></li>
		<li class="dots"><span>...</span></li>
		<?php
	}

	for ( $i = $from; $i <= $to; $i++ ) :

		$link_to_page = get_pagenum_link( $i );
		$is_active = $current_page == $i;

	?>
		<li<?php echo $is_active ? ' class="active"' : ''; ?>><a href="<?php echo $link_to_page; ?>"><?php echo $i; ?></a></li>
	<?php
	endfor;


	if ( $max_num_pages > $to ) {
		if ( $max_num_pages != $i ) :
		?>
			<li class="dots"><span>...</span></li>
		<?php
		endif;

		?>
		<li><a href="<?php echo get_pagenum_link( $max_num_pages ); ?>"><?php echo $max_num_pages; ?></a></li>
		<?php
	}
	?>

	<?php if ( $current_page + 1 <= $max_num_pages ) : ?>
		<li class="last_page"><a href="<?php echo get_pagenum_link($current_page + 1); ?>"><?php _e('Next', 'aurum'); ?></a></li>
	<?php endif; ?>

	<?php if($current_page < $max_num_pages): ?>
		<li class="last_page"><a href="<?php echo get_pagenum_link($max_num_pages); ?>"><?php _e('Last &raquo;', 'aurum'); ?></a></li>
	<?php endif; ?>
	</ul>
	<!-- end: pagination -->
	<?php
}

/**
 * Compress Text Function
 */
function compress_text( $buffer ) {
	/* remove comments */
	$buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );
	/* remove tabs, spaces, newlines, etc. */
	$buffer = str_replace( array( "\r\n", "\r", "\n", "\t", '	', '	', '	' ), '', $buffer );
	return $buffer;
}

/**
 * Load Font Style
 */
function laborator_load_font_style() {
	global $custom_css;

	$api_url           = 'https://fonts.googleapis.com/css?family=';

	$font_variants 	   = '300italic,400italic,700italic,300,400,700';

	$primary_font      = 'Roboto:' . $font_variants;
	$secondary_font    = 'Roboto+Condensed:' . $font_variants;

	// Custom Font
	$_font_primary      = get_data( 'font_primary' );
	$_font_secondary    = get_data( 'font_secondary' );

	$primary_font_replaced = $secondary_font_replaced = 0;

	if ( $_font_primary && $_font_primary != 'none' && $_font_primary != 'Use default' ) {
		$primary_font_replaced = 1;
		$primary_font = $_font_primary . ':' . $font_variants . '';
	}

	if ( $_font_secondary && $_font_secondary != 'none' && $_font_secondary != 'Use default' ) {
		$secondary_font_replaced = 1;
		$secondary_font = $_font_secondary . ':' . $font_variants;
	}

	$custom_primary_font_url   = get_data( 'custom_primary_font_url' );
	$custom_primary_font_name  = get_data( 'custom_primary_font_name' );

	$custom_heading_font_url   = get_data( 'custom_heading_font_url' );
	$custom_heading_font_name  = get_data( 'custom_heading_font_name' );

	if ( $custom_primary_font_url && $custom_primary_font_name ) {
		$primary_font_replaced    = 2;
		$primary_font             = $custom_primary_font_url;
		$_font_primary            = $custom_primary_font_name;
	}

	if ( $custom_heading_font_url && $custom_heading_font_name ) {
		$secondary_font_replaced    = 2;
		$secondary_font             = $custom_heading_font_url;
		$_font_secondary            = $custom_heading_font_name;
	}
	
	$primary_subset = apply_filters( 'aurum_primary_google_font_subset', 'latin' );
	$secondary_subset = apply_filters( 'aurum_secondary_google_font_subset', 'latin' );
	
	if ( strpos( $primary_font, 'fonts.googleapis.com' ) ) {
		$primary_font .= '&subset=' . $primary_subset;
	}
	
	if ( strpos( $secondary_subset, 'fonts.googleapis.com' ) ) {
		$secondary_subset .= '&subset=' . $secondary_subset;
	}

	if ( apply_filters( 'aurum_primary_font_include', true ) ) {
		wp_enqueue_style( 'primary-font', strstr( $primary_font, '://' ) ? $primary_font : ( $api_url . $primary_font ) );
	}
	
	if ( apply_filters( 'aurum_heading_font_include', true ) ) {
		wp_enqueue_style( 'heading-font', strstr($secondary_font, '://') ? $secondary_font : ( $api_url . $secondary_font ) );
	}

	ob_start();

	if ( $primary_font_replaced ) :
	?>
	.primary-font, body, div, div *, p {
		font-family: <?php echo $primary_font_replaced == 1 ? "'{$_font_primary}', sans-serif" : $_font_primary; ?>;
	}
	<?php
	endif;

	if ( $secondary_font_replaced ) :
	?>
	.heading-font,
	header.site-header,
	header.site-header .logo.text-logo a,
	header.mobile-menu .mobile-logo .logo.text-logo a,
	.top-menu,
	footer.site-footer,
	footer.site-footer .footer-widgets .sidebar.widget_search #searchsubmit.btn-bordered,
	.contact-page .contact-form label,
	.breadcrumb,
	.woocommerce-breadcrumb,
	section.blog .post .comments .comment + .comment-respond #cancel-comment-reply-link,
	section.blog .post .comments .comment-respond label,
	section.blog .post .comments .comment-respond #submit.btn-bordered,
	section.blog .post-password-form label,
	section.blog .post-password-form input[type="submit"].btn-bordered,
	.woocommerce .woocommerce-MyAccount-links,
	.woocommerce .woocommerce-orders-table th,
	.woocommerce .woocommerce-orders-table td,
	.woocommerce .woocommerce-shop-header--title .woocommerce-result-count,
	.woocommerce .button,
	.woocommerce .quantity.buttons_added input.input-text,
	.woocommerce .icon-button .title,
	.woocommerce #order_review .shop_table tr td,
	.woocommerce .cart_totals .shop_table tr td,
	.woocommerce #order_review .shop_table tr th,
	.woocommerce .cart_totals .shop_table tr th,
	.woocommerce-notice,
	.woocommerce .products .product .item-info span,
	.woocommerce .summary .price,
	.woocommerce .summary form.cart .variations .label,
	.woocommerce .summary form.cart .variations div.variation-select,
	.woocommerce .summary .product_meta > span,
	.woocommerce .summary .product_meta .wcml_currency_switcher,
	.woocommerce .summary .group_table .woocommerce-grouped-product-list-item__price,
	.woocommerce .summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist.btn-bordered,
	.woocommerce .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a.btn-bordered,
	.woocommerce .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a.btn-bordered,
	.woocommerce .order-info,
	.woocommerce .cross-sells .product-item .product-details .price,
	.woocommerce .woocommerce-cart-form .shop_table th,
	.woocommerce .woocommerce-cart-form .shop_table td,
	.woocommerce .woocommerce-cart-form .shop_table td > .price,
	.woocommerce .woocommerce-cart-form table.wishlist_table thead tr th,
	#yith-wcwl-popup-message,
	.woocommerce .woocommerce-checkout .order-totals-column .lost-password,
	.woocommerce-order-pay #order_review .lost-password,
	.header-menu .lab-mini-cart .total,
	.sidebar .sidebar-entry,
	.sidebar .sidebar-entry select,
	.sidebar .sidebar-entry.widget_search #searchsubmit.btn-bordered,
	.sidebar .sidebar-entry.widget_product_search #searchsubmit.btn-bordered,
	.sidebar .sidebar-entry .woocommerce-product-search [type="submit"].btn-bordered,
	.sidebar .sidebar-entry.widget_wysija .wysija-submit.btn-bordered,
	.sidebar .sidebar-entry.widget_shopping_cart .total,
	.sidebar .sidebar-entry.widget_shopping_cart .buttons .button.btn-bordered,
	.sidebar .sidebar-entry .price_slider_wrapper .price_slider_amount .button.btn-bordered,
	.sidebar .sidebar-list li,
	.bordered-block .lost-password,
	.page-heading small p,
	h1,
	h2,
	h3,
	h4,
	h5,
	h6,
	.btn.btn-bordered,
	.dropdown-menu,
	.nav-tabs > li > a,
	.alert,
	.form-control,
	.banner .button_outer .button_inner .banner-content strong,
	.table > thead > tr > th,
	.tooltip-inner,
	.search .search-header,
	.page-container .vc_tta-tabs.vc_tta-style-theme-styled .vc_tta-tabs-list .vc_tta-tab a,
	.page-container .wpb_content_element.wpb_tabs .ui-tabs .wpb_tabs_nav li a,
	.page-container .wpb_content_element.wpb_tour .wpb_tabs_nav li a,
	.page-container .wpb_content_element.lab_wpb_image_banner .banner-text-content,
	.page-container .wpb_content_element.alert p,
	.page-container .wpb_content_element.lab_wpb_products_carousel .products-loading,
	.page-container .wpb_content_element.lab_wpb_testimonials .testimonials-inner .testimonial-entry .testimonial-blockquote,
	.page-container .feature-tab .title,
	.page-container .vc_progress_bar .vc_single_bar .vc_label,
	.pagination > a,
	.pagination > span,
	.woocommerce .commentlist .comment_container .comment-text .meta,
	.woocommerce #review_form_wrapper .comment-form-rating label,
	.woocommerce #review_form_wrapper .form-submit [type="submit"].btn-bordered,
	.woocommerce .shop_attributes th,
	.woocommerce .shop_attributes td,
	.woocommerce dl.variation dt,
	.woocommerce dl.variation dd,
	.woocommerce .order-details-list li,
	.woocommerce .bacs_details li,
	.woocommerce .digital-downloads li .count,
	.woocommerce legend,
	.shop-empty-cart-page .cart-empty-title p a,
	.woocommerce-info,
	.woocommerce-message,
	.woocommerce-error {
		font-family: <?php echo $secondary_font_replaced == 1 ? "'{$_font_secondary}', sans-serif" : $_font_secondary; ?>;
	}
	<?php
	endif;
	$custom_css = ob_get_clean();

	if ( $custom_css ) {
		$custom_css = compress_text( "<style id=\"theme-fonts-css\">{$custom_css}</style>" );
		add_action( 'wp_head', 'aurum_wp_print_scripts_custom_fonts_css' );
	}
}

/**
 * Custom fonts CSS
 */
function aurum_wp_print_scripts_custom_fonts_css() {
	global $custom_css;
	echo $custom_css;
}

/**
 * Share Network Story
 */
function share_story_network_link( $network, $id, $simptips = true ) {
	global $post;
	
	$title     = urlencode( get_the_title() );
	$excerpt   = urlencode( aurum_clean_excerpt( get_the_excerpt(), true ) );
	$permalink = urlencode( get_permalink() );
	$url       = urlencode( get_permalink() );


	$networks = array(
		'fb' => array(
			'url'		=> 'https://www.facebook.com/sharer.php?u=' . $permalink,
			'tooltip'	=> __( 'Facebook', 'aurum' ),
			'icon'		=> 'fa-facebook'
		),

		'tw' => array(
			'url'		=> 'https://twitter.com/home?status=' . $title . ' – ' . $permalink,
			'tooltip'	=> __( 'Twitter', 'aurum' ),
			'icon'		 => 'fa-twitter'
		),

		'gp' => array(
			'url'		=> 'https://plus.google.com/share?url=' . $permalink,
			'tooltip'	=> __( 'Google+', 'aurum' ),
			'icon'		 => 'fa-google-plus'
		),

		'tlr' => array(
			'url'		=> 'http://www.tumblr.com/share/link?url=' . $permalink . '&name=' . $title . '&description=' . $excerpt,
			'tooltip'	=> __( 'Tumblr', 'aurum' ),
			'icon'		 => 'fa-tumblr'
		),

		'lin' => array(
			'url'		=> 'https://linkedin.com/shareArticle?mini=true&url=' . $permalink . '&title=' . $title,
			'tooltip'	=> __( 'LinkedIn', 'aurum'),
			'icon'		 => 'fa-linkedin'
		),

		'pi' => array(
			'url'		=> 'https://pinterest.com/pin/create/button/?url=' . $permalink . '&description=' . urlencode( get_the_title() ) . '&' . ($id ? ('media=' . wp_get_attachment_url( get_post_thumbnail_id($id) )) : ''),
			'tooltip'	=> __( 'Pinterest', 'aurum' ),
			'icon'	 	 => 'fa-pinterest'
		),

		'vk' => array(
			'url'		=> 'https://vkontakte.ru/share.php?url=' . $permalink,
			'tooltip'	=> __( 'VKontakte', 'aurum' ),
			'icon'	 	 => 'fa-vk'
		),

		'em' => array(
			'url'		=> 'mailto:?subject=' . rawurlencode( get_the_title() ) . '&amp;body=' . get_permalink(),
			'tooltip'	=> __( 'Email', 'aurum' ),
			'icon'		 => 'fa-envelope'
		),
	);

	$network_entry = $networks[ $network ];
	$icon_class = ( strpos( $network_entry['icon'], 'fa-' ) === 0 ? 'fa ' : 'entypo entypo-' ) .  $network_entry['icon'];
	?>
	<a class="<?php echo str_replace( 'fa-', '', $network_entry['icon'] ); ?>" href="<?php echo $network_entry['url']; ?>" target="_blank">
		<?php if ( apply_filters( 'aurum_shop_product_single_share', false ) ) : ?>
			<i class="<?php echo $icon_class; ?>"></i>
		<?php else: ?>
			<?php echo $network_entry['tooltip']; ?>
		<?php endif; ?>
	</a>
	<?php
}

/**
 * Page Path
 */
function laborator_page_path( $post ) {
	$page_path = array( __( 'Home', 'aurum' ) );

	$page_hierarchy = array( $post->post_title );

	if ( $post->post_parent ) {
		laborator_page_path_recursive( $post, $page_hierarchy );
	}

	$page_hierarchy = array_reverse( $page_hierarchy );
	$page_path = array_merge( $page_path, $page_hierarchy );

	return implode( ' &raquo ', $page_path );
}

function laborator_page_path_recursive( $post, & $hierarchy ) {
	$parent = get_post( $post->post_parent );

	array_push( $hierarchy, $parent->post_title );

	if ( $parent->post_parent ) {
		laborator_page_path_recursive( $parent, $hierarchy );
	}
}

/**
 * Get field from ACF (with fallback)
 */
function aurum_get_field( $field_id, $post_id = null, $format_value = true ) {
	global $post;
	
	if ( function_exists( 'get_field' ) || aurum_is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
		return get_field( $field_id, $post_id, $format_value );
	}
	
	if ( is_numeric( $post_id ) ) {
		$post = get_post( $post_id );
	}
	
	if ( $post instanceof WP_Post ) {
		return $post->$field_id;
	}
	
	return null;
}

/**
 * Has transparent header
 */
function has_transparent_header() {
	return aurum_get_field( 'enable_transparent_header' );
}

/**
 * Get SVG
 */
function lab_get_svg( $svg_path, $id = null, $size = array( 24, 24 ), $is_asset = true ) {
	if ( $is_asset ) {
		$svg_path = get_template_directory() . '/assets/' .  $svg_path;
	}

	if ( ! $id ) {
		$id = sanitize_title( basename( $svg_path ) );
	}

	if ( is_numeric( $size ) ) {
		$size = array( $size, $size );
	}

	$svg = preg_replace(
		array(
			'/^.*<svg/s',
			'/id=".*?"/i',
			'/width=".*?"/',
			'/height=".*?"/'
		),
		array(
			'<svg', 'id="' . $id . '"',
			'width="' . $size[0] . 'px"',
			'height="' . $size[0] . 'px"'
		),
		@file_get_contents( $svg_path )
	);

	return $svg;
}

/**
 * Check if page is fullwidth
 */
function is_fullwidth_page() {
	return defined( 'IS_FULLWIDTH_PAGE' );
}

/**
 * Less Style Generator
 */
function aurum_generate_less_style( $files = array(), $vars = array() ) {
	if ( ! class_exists( 'Less_Parser' ) ) {
		include_once get_template_directory() . '/inc/lib/lessphp/Less.php';
	}
	
	// Compile Less
	$less_options = array(
		'compress' => true
	);
	
	$css = '';
	
	try {
		
		$less = new Less_Parser( $less_options );
		
		foreach ( $files as $file => $type ) {
			if ( $type == 'parse' ) {
				$css_contents = file_get_contents( $file );
				
				// Replace Vars
				foreach ( $vars as $var => $value ) {
					if ( trim( $value ) ) {
						$css_contents = preg_replace( "/(@{$var}):\s*.*?;/", '$1: ' . $value . ';', $css_contents );
					}
				}
				
				$less->parse( $css_contents );
			} else {
				$less->parseFile( $file );
			}
		}
		
		$css = $less->getCss();
	} 
	catch ( Exception $e ) {}
	
	return $css;
}

/**
 * Get SVG file dimensions
 */
function laborator_svg_get_size( $image_path ) {
	
	// SVG Support
	$pathinfo_image = pathinfo( $image_path );
	$extension = pathinfo( $image_path, PATHINFO_EXTENSION );
	
	$image_size = array( 1, 1 );
	
	if ( $extension == 'svg' && function_exists( 'simplexml_load_file' ) ) {
		$svgfile = simplexml_load_file( $image_path );
		
		if ( isset( $svgfile->rect ) ) {
			$width = reset( $svgfile->rect['width'] );
			$height = reset( $svgfile->rect['height'] );
			
			$image_size = array( $width, $height );
		} else {
			$svg_attrs = $svgfile->attributes();
			$image_size = array( intval( $svg_attrs['width'] ), intval( $svg_attrs['height'] ) );
		}
	}
	
	return $image_size;
}

/**
 * Immediate Return Function (Deprecated)
 */
function laborator_immediate_return_fn( $value ) {
	/**
	 * New PHP 7.2 compatible anonymous function
	 */
	return aurum_hook_return_value( $value );
}

/**
 * Show Cart 
 */
function aurum_show_header_cart_icon( $size = array( 24, 24 ) ) {
	
	if ( get_data( 'header_cart_info' ) && function_exists( 'WC' ) && ! aurum_is_catalog_mode() ) :

		$cart_items_count = WC()->cart->get_cart_contents_count();
		$cart_icon = get_data( 'header_cart_info_icon' );

		if ( ! $cart_icon ) {
			$cart_icon = 1;
		}
		
		?>
		<section class="cart-info">
			<a class="cart-counter cart-zero" href="<?php echo wc_get_cart_url(); ?>">
				<i class="cart-icon"><?php echo lab_get_svg( "images/cart_{$cart_icon}.svg", 'cart-info-icon', $size ); ?></i>
				<strong><?php _e( 'Cart', 'aurum' ); ?></strong>
				<span class="badge items-count">0</span>
			</a>
		</section>
		<?php
			
	endif;
}

/**
 * Default Value Set for Visual Composer Loop Parameter Type
 */
function aurum_vc_loop_param_set_default_value( & $query, $field, $value = '' ) {
	
	if ( ! preg_match( '/(\|?)' . preg_quote( $field ) . ':/', $query ) ) {
		$query .= "|{$field}:{$value}";
	}
	
	return ltrim( '|', $query );
}

/**
 * Laborator Excerpt Clean
 */
function aurum_clean_excerpt( $content, $strip_tags = false ) {
	$content = preg_replace( '#<style.*?>(.*?)</style>#i', '', $content );
	$content = preg_replace( '#<script.*?>(.*?)</script>#i', '', $content );
	return $strip_tags ? strip_tags( $content ) : $content;
}

/**
 * Check if WPML is active
 */
function aurum_is_wpml_active() {
	return function_exists( 'icl_object_id' );
}

/**
 * Show Hidden Input for WPML current language
 */
function aurum_wpml_current_language_hidden_input() {
	if ( aurum_is_wpml_active() ) {
		global $sitepress_settings;
		
		if ( $sitepress_settings['language_negotiation_type'] == '3' ) {
			?><input type="hidden" name="lang" value="<?php echo esc_attr( ICL_LANGUAGE_CODE ); ?>" /><?php
		}
	}
}

/**
 * Get Custom Skin File Name
 */
function aurum_get_custom_skin_filename() {
	if ( is_multisite() ) {
		return apply_filters( 'aurum_multisite_custom_skin_name', 'custom-skin-' . get_current_blog_id() . '.css', get_current_blog_id() );
	}
	
	return apply_filters( 'aurum_custom_skin_name', 'custom-skin.css' );
}

/**
 * File Based Custom Skin
 */
function aurum_use_filebased_custom_skin() {
	$custom_skin_filename = aurum_get_custom_skin_filename();
	$custom_skin_path_full = get_stylesheet_directory() . '/assets/css/' . $custom_skin_filename;
	
	if ( is_child_theme() ) {
		$custom_skin_path_full = get_stylesheet_directory() . '/' . $custom_skin_filename;
	}
	
	// Create skin file in case it does not exists
	if ( file_exists( $custom_skin_path_full ) === false ) {
		@touch( $custom_skin_path_full );
	}
	
	if ( is_writable( $custom_skin_path_full ) === true ) {
		
		if ( ! trim( @file_get_contents( $custom_skin_path_full ) ) ) {
			return aurum_generate_custom_skin_file();
		}
		
		return true;
	}
	
	return false;
}

/**
 * Generate Custom Skin File
 */
function aurum_generate_custom_skin_file() {
	$custom_skin_filename = aurum_get_custom_skin_filename();
	$custom_skin_path = get_stylesheet_directory() . '/assets/css/' . $custom_skin_filename;
	
	if ( is_child_theme() ) {
		$custom_skin_path = get_stylesheet_directory() . '/' . $custom_skin_filename;
	}
	
	if ( is_writable( $custom_skin_path ) ) {
		$aurum_skin_custom_css = get_option( 'aurum_skin_custom_css' );
		
		$fp = @fopen( $custom_skin_path , 'w' );
		@fwrite( $fp, $aurum_skin_custom_css );
		@fclose( $fp );
		
		return true;
	}
	
	return false;
}

/**
 * Search Tabs functions
 */
function laborator_get_search_tabs() {
	global $wp_query;
	
	$tabs = array();
	
	if ( is_search() ) {
		
		$search_link = get_search_link();
		
		$all = array(
			'title'  => __( 'All', 'aurum' ),
			'link'   => $search_link,
			'active' => true,
			'count'  => $wp_query->found_posts
		);
		
		$tabs[] = & $all;
		
		// All results
		$args = $wp_query->query;
		
		$args['posts_per_page'] = -1;
		$args['post_type']      = 'any';
		
		add_filter( 'posts_fields', 'laborator_search_page_posts_fields', 100, 2 );
		add_filter( 'posts_groupby', 'laborator_search_page_group_by_post_type', 100, 2 );
		
		$query = new WP_Query( $args );
		$count_all = 0;
		
		foreach ( $query->posts as $post ) {
			$post_type       = $post->post_type;
			$count           = $post->posts_count;
			$post_type_title = get_post_type_object( $post_type )->labels->singular_name;
			$is_active       = lab_get( 'type' ) == $post_type;
			
			if ( $is_active ) {
				$all['active'] = false;
			}
			
			$tabs[] = array(
				'title'  => $post_type_title,
				'link'   => sprintf( '%s?type=%s', $search_link, $post_type ),
				'active' => $is_active,
				'count'  => $count
			);
			
			$count_all += $count;
		}
		
		if ( $count_all ) {
			$all['count'] = $count_all;
		}
		
		remove_filter( 'posts_fields', 'laborator_search_page_posts_fields', 100, 2 );
		remove_filter( 'posts_groupby', 'laborator_search_page_group_by_post_type', 100, 2 );
	}
	
	return $tabs;
}

function laborator_search_page_group_by_post_type( $group_by, $query ) {
	global $wpdb;
	
	$group_by = "{$wpdb->posts}.post_type";

	return $group_by;
}

function laborator_search_page_posts_fields( $fields, $query ) {
	$fields .= ", COUNT(*) posts_count";
	return $fields;
}

/**
 * Check if plugin is active
 */
function aurum_is_plugin_active( $plugin ) {
	$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
	$active_sitewide_plugins = apply_filters( 'active_sitewide_plugins', get_site_option( 'active_sitewide_plugins', array() ) );
	$plugins = array_merge( $active_plugins, $active_sitewide_plugins );
	
	return in_array( $plugin, $plugins ) || isset( $plugins[ $plugin ] );
}

/**
 * Convert an english word to number
 */
function aurum_get_number_from_word( $word ) {
	
	if ( is_numeric( $word ) ) {
		return $word;
	}
	
	switch ( $word ) {
		case 'ten' 	 : return 10; break;
		case 'nine'  : return 9; break;
		case 'eight' : return 8; break;
		case 'seven' : return 7; break;
		case 'six' 	 : return 6; break;
		case 'five'  : return 5; break;
		case 'four'	 : return 4; break;
		case 'three' : return 3; break;
		case 'two' 	 : return 2; break;
		case 'one'	 : return 1; break;
	}
	
	return 0;
}

/**
 * Enqueue Slick Slider
 */
function aurum_enqueue_slick_carousel() {
	wp_enqueue_script( 'slick' );
	wp_enqueue_style( 'slick-theme' );
}

/**
 * Show class attribute
 */
function aurum_show_classes( $classes, $echo = false ) {
	if ( ! is_array( $classes ) ) {
		$classes = array( $classes );
	}
	
	$classes = implode( ' ', array_map( 'esc_attr', $classes ) );
	
	if ( $echo ) {
		echo $classes;
	}
	
	return $classes;
}

/**
 * Image placeholder
 */
function aurum_get_attachment_image( $attachment_id, $size = 'original', $icon = null, $attr = array(), $placeholder_attr = array() ) {
	$image = $image_el = $image_placeholder = $image_placeholder_style = $image_url = $width = $height = '';
	
	// Image
	if ( ! $image = wp_get_attachment_image( $attachment_id, $size, $icon, $attr ) ) {
		return '';
	}
	
	// Style attribute
	$style = array();
	
	// Image element
	$image_el = array();
	
	// Parse atts
	$image_atts = wp_parse_args( shortcode_parse_atts( $image ), array(
		'width' => 1,
		'height' => 1,
	) );
	
	// Lazy loading
	if ( apply_filters( 'aurum_get_attachment_image_lazy_load', true ) ) {
		$image_atts['data-src'] = $image_atts['src'];
		$image_atts['class'] .= ' lazyload';
		unset( $image_atts['src'] );
	}
	
	// Padding bottom
	$aspect_ratio = number_format( $image_atts['height'] / $image_atts['width'] * 100, 6 );
	$style[] = sprintf( 'padding-bottom:%s%%', $aspect_ratio );
	
	// Build image elememnt
	foreach ( $image_atts as $key => $value ) {
		
		if ( is_numeric( $key ) ) {
			unset( $image_atts[ $key ] );
		} else {
			$image_el[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}
	
	// Open and closing tag
	array_unshift( $image_el, '<img' );
	$image_el[] = '/>';
	
	$image_el = implode( ' ', $image_el );
	
	// Placeholder element
	$placeholder_atts = array(
		'class' => 'image-placeholder',
		'style' => implode( ';', $style ),
	);
	
	// Add placeholder attributes
	$placeholder_attr = is_array( $placeholder_attr ) ? $placeholder_attr : array( $placeholder_attr );
	
	foreach ( $placeholder_attr as $key => $value ) {
		// Placeholder class
		if ( 'class' == $key ) {
			$placeholder_atts['class'] .= ' ' . $value;
		}
		
		// Placeholder style
		else if ( 'style' == $key ) {
			$placeholder_atts['style'] .= ';' . $value;
		}
		
		// Any other attribute
		else {
			$placeholder_atts[ $key ] = $value;
		}
	}
	
	
	// Build placeholder element
	$placeholder_el = array();
	
	foreach ( $placeholder_atts as $key => $value ) {
		
		if ( is_numeric( $key ) ) {
			unset( $placeholder_atts[ $key ] );
		} else {
			$placeholder_el[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}
	
	// Open and closing tag
	array_unshift( $placeholder_el, '<span' );
	$placeholder_el[] = '>';
	
	$placeholder_el = implode( ' ', $placeholder_el );
	
	return $placeholder_el . $image_el . '</span>';
}

/**
 * Aspect Ratio Calculator
 */
function aurum_calculate_aspect_ratio( $width, $height ) {
	return number_format( $height / $width * 100, 8 );
}

/**
 * Wrap image with image placeholder element
 */
function aurum_image_placeholder_wrap_element( $image ) {
	$ratio = '';
	
	// If its not an image, do not process
	if ( false === strpos( $image, '<img' ) ) {
		return $image;
	}
	
	// Generate aspect ratio
	if ( preg_match_all( '#(width|height)=(\'|")?(?<dimensions>[0-9]+)(\'|")?#i', $image, $image_dimensions ) && 2 == count( $image_dimensions['dimensions'] ) ) {
		$ratio = 'padding-bottom:' . aurum_calculate_aspect_ratio( $image_dimensions['dimensions'][0], $image_dimensions['dimensions'][1] ) . '%';
	}
	
	// Lazy loading
	if ( apply_filters( 'aurum_get_attachment_image_lazy_load', true ) ) {
		if ( preg_match( '(class=(\'|")[^"]+)', $image, $class_attr ) ) {
			$image = str_replace( $class_attr[0], $class_attr[0] . ' lazyload', $image );
		}
	}
	
	return sprintf( '<span class="image-placeholder" style="%2$s">%1$s</span>', $image, $ratio );
}

/**
 * Return single value in WP Hook
 */
function aurum_hook_return_value( $value ) {
	$returnable = new Aurum_WP_Hook_Value( $value );
	return array( $returnable, 'returnValue' );
}

/**
 * Merge array value in WP Hook
 */
function aurum_hook_merge_array_value( $value, $key = '' ) {
	$returnable = new Aurum_WP_Hook_Value();
	$returnable->array_value = $value;
	$returnable->array_key = $key;
	
	return array( $returnable, 'mergeArrayValue' );
}

/**
 * Call user function in WP Hook
 */
function aurum_hook_call_user_function( $function_name ) {
	
	// Function arguments
	$function_args = func_get_args();
	
	// Remove the function name argument
	array_shift( $function_args );
	
	$returnable = new Aurum_WP_Hook_Value();
	$returnable->function_name = $function_name;
	$returnable->function_args = $function_args;
	
	return array( $returnable, 'callUserFunction' );
}

/**
 * Get theme assets URL
 */
function aurum_assets_url( $name = '' ) {
	$url = get_template_directory_uri() . '/assets';
	
	if ( $name ) {
		$url .= '/' . $name;
	}
	
	return $url;
}

/**
 * Get widgets of specific sidebar
 */
if ( ! function_exists( 'aurum_get_widgets' ) ) {
	
	function aurum_get_widgets( $sidebar_id, $class = '' ) {
		$classes = array( 'sidebar', 'widget-area' );
		
		if ( is_array( $class ) ) {
			$classes = array_merge( $classes, $class );
		} else if ( ! empty( $class ) ) {
			$classes[] = $class;
		}
		
		?>
		<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', apply_filters( 'aurum_widget_area_classes', $classes, $sidebar_id ) ) ); ?>" role="complementary">
			
			<?php
				// Show sidebar widgets
				dynamic_sidebar( $sidebar_id );
			?>
			
		</div>
		<?php
	}
}

/**
 * Show classes attribute array
 */
if ( ! function_exists( 'aurum_class_attr' ) ) {

	function aurum_class_attr( $classes, $echo = true ) {
		
		if ( ! is_array( $classes ) ) {
			$classes = array( $classes );
		}
		
		$class = sprintf( 'class="%s"', implode( ' ', array_map( 'esc_attr', $classes ) ) );
		
		if ( $echo ) {
			echo $class;
			return '';
		}
		
		return $class;
	}
}

/**
 * Aurum breakcrumb
 */
if ( ! function_exists( 'aurum_breadcrumb' ) ) {
	
	function aurum_breadcrumb( $return = false ) {
		$breadcrumb = '';
		
		// NavXT Breadcrumb
		if ( function_exists( 'bcn_display' ) ) {
			$breadcrumb = sprintf( '<div class="breadcrumb">%s</div>', bcn_display( true ) );
		}
		// WooCommerce Breadcrumb
		else if ( function_exists( 'woocommerce_breadcrumb' ) ) {
			ob_start();
			$args = array(
				'delimiter' => '<span class="sep">&raquo;</span>'
			);
			
			woocommerce_breadcrumb( $args );
			$breadcrumb = ob_get_clean();
		}
		
		if ( $return ) {
			return $breadcrumb;
		}
		
		echo $breadcrumb;
	}
}

/**
 * Display page heading
 */
if ( ! function_exists( 'aurum_page_heading' ) ) {
	
	function aurum_page_heading( $first_column = '', $second_column = '' ) {
		
		if ( is_string( $first_column ) && function_exists( $first_column ) ) {
			$first_column = call_user_func( $first_column );
		} else if ( is_array( $first_column ) ) {
			$first_column = aurum_page_title_from_array( $first_column );
		}
		
		if ( is_string( $second_column ) && function_exists( $second_column ) ) {
			$second_column = call_user_func( $second_column );
		} else if ( is_array( $second_column ) ) {
			$second_column = aurum_page_title_from_array( $second_column );
		}
		
		// Show page heading
		$show_page_heading = apply_filters( 'aurum_page_heading_visibility', aurum_get_field( 'show_page_title', get_queried_object_id() ) );
		
		if ( $show_page_heading && ( $first_column || $second_column ) ) {
			$two_columns = $first_column && $second_column;
			
			?>
			<div class="container page-heading-container">
				
				<div class="page-heading<?php echo $two_columns ? ' columns-2' : ''; ?>">
					
					<?php if ( $first_column ) : ?>
					<div class="col">
						<?php echo $first_column; ?>
					</div>
					<?php endif; ?>
					
					<?php if ( $second_column ) : ?>
					<div class="col">
						<?php echo $second_column; ?>
					</div>
					<?php endif; ?>
					
				</div>
				
			</div>
			<?php
		}
	}
}

/**
 * Display page title from array
 */
if ( ! function_exists( 'aurum_page_title_from_array' ) ) {
	
	function aurum_page_title_from_array( $elements ) {
		
		$title = '';
		$has_small = ! empty( $elements['small'] );
		
		if ( $has_small ) {
			$small_content = $elements['small'];
			unset( $elements['small'] );
		}
		
		foreach ( $elements as $tag_name => $content ) {
			if ( $has_small ) {
				$content .= "<small>{$small_content}</small>";
			}
			
			// Append element
			if ( ! empty( $content ) ) {
				$title .= sprintf( '<%1$s>%2$s</%1$s>', $tag_name, $content );
			}
		}
		
		return $title;
	}
}

/**
 * Display page heading title
 */
if ( ! function_exists( 'aurum_page_heading_title' ) ) {
	
	function aurum_page_heading_title() {
		$post_id = get_queried_object_id();
		
		$main_title = aurum_get_field( 'main_title', $post_id );
		$small_description = aurum_get_field( 'small_description', $post_id );
		
		$title = $main_title;
		
		if ( $small_description ) {
			$title .= "<small>{$small_description}</small>";
		}
		
		if ( $title ) {
			return "<h1>{$title}</h1>";
		}
		
		return '';
	}
}

/**
 * Display page heading breadcrumb
 */
if ( ! function_exists( 'aurum_page_heading_breadcrumb' ) ) {
	
	function aurum_page_heading_breadcrumb() {
		$post_id = get_queried_object_id();
		
		if ( ! aurum_get_field( 'show_breadcrumb', $post_id ) ) {
			return '';
		}
		
		$breadcrumb = aurum_breadcrumb( true );
		
		if ( $breadcrumb ) {
			return "<div class=\"right-aligned\">{$breadcrumb}</div>";
		}
		
		return '';
	}
}

/**
 * Show top menu widget
 */
if ( ! function_exists( 'aurum_show_top_menu_widget' ) ) {
	
	function aurum_show_top_menu_widget( $widget_id, $is_text_widget = false ) {
		global $current_user;
		
		$widget = '';
		$widget_as_id = 'custom';
		$is_wc_installed = function_exists( 'WC' );
		$is_wpml_installed = function_exists( 'icl_object_id' );
		$is_wcml_installed = defined( 'WCML_VERSION' );
		
		// Text widget
		if ( $is_text_widget ) {
			$widget_as_id = 'text';
			$widget = do_shortcode( $widget_id );
		} 
		// Date
		else if ( 'laborator_current_date' == $widget_id ) {
			$widget_as_id = 'current-date';
			$widget = date_i18n( get_option( 'date_format' ) );
		}
		// Social networks
		else if ( 'laborator_social_networks' == $widget_id ) {
			$widget_as_id = 'social-networks';
			$widget = do_shortcode( '[lab_social_networks]' );
		}
		// Account links and date
		else if ( 'laborator_account_links_and_date' == $widget_id && $is_wc_installed ) {
			$widget_as_id = 'account-links-and-date';
			$myaccount_link = wc_get_page_permalink( 'myaccount' );
			
			if ( 0 == $current_user->ID ) {
				$no_registration = is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' );
				
				if ( $no_registration ) {
					$widget = sprintf( __( '<a href="%1$s" class="top-menu--link">Customer Login</a>', 'aurum' ), $myaccount_link );
				} else {
					$widget = sprintf( '<a href="%1$s" class="top-menu--link">%2$s</a> %3$s <a href="%1$s" class="top-menu--link">%4$s</a>', $myaccount_link, __( 'Login', 'aurum' ), __( 'or', 'aurum' ), __( 'Register', 'aurum' ) );
				}
			} else {
				$widget = sprintf( '<a href="%1$s" class="top-menu--link">%2$s</a>', $myaccount_link, __( 'My Account Details', 'aurum' ) );
			}
			
			// Separator
			$widget .= '<span class="top-menu--separator">|</span>';
			
			// Date
			$widget .= '<span class="top-menu--date">' . date_i18n( get_option( 'date_format' ) ) . '</span>';
			
		}
		// Cart totals
		else if ( 'laborator_cart_totals' == $widget_id && $is_wc_installed ) {
			$widget_as_id = 'cart-totals';
			
			$widget = sprintf( '<a href="%1$s" class="top-menu--cart-totals">%2$s <span class="top-menu--number">%3$s</span></a>', wc_get_cart_url(), __( 'Cart totals:', 'aurum' ), WC()->cart->get_cart_total() );
		}
		// Breadcrumbs
		else if ( 'laborator_breadcrubms' == $widget_id || 'navxt_breadcrubms' == $widget_id ) {
			$widget_as_id = 'breadcrumbs';
			
			$widget = aurum_breadcrumb( true );
		}
		// WooCommerce Currency Switcher
		else if ( 'wc_currency_switcher' == $widget_id ) {
			$widget_as_id = 'woocommerce-currency-switcher';
			
			$widget = do_shortcode( apply_filters( 'aurum_top_menu_wc_currency_switcher_shortcode', '[woocs width="100%" show_flags="0"]' ) );
		}
		// WPML language switcher
		else if ( 'wpml_lang_switcher' == $widget_id && $is_wpml_installed ) {
			$widget_as_id = 'wpml-language-switcher';
			
			ob_start();
			do_action( 'wpml_add_language_selector' );
			$widget = ob_get_clean();
		}
		// WPML Currency Switcher
		else if ( 'wpml_currency_switcher' == $widget_id && $is_wcml_installed ) {
			$widget_as_id = 'wpml-currency-switcher';
			
			$widget = do_shortcode( apply_filters( 'aurum_top_menu_wpml_currency_switcher_shortcode', '[currency_switcher]' ) );
		}
		// WPML Language and Currency Switcher
		else if ( 'wpml_lang_currency_switcher' == $widget_id && $is_wpml_installed && $is_wcml_installed ) {
			aurum_show_top_menu_widget( 'wpml_lang_switcher' );
			aurum_show_top_menu_widget( 'wpml_currency_switcher' );
			return;
		}
		// Check for WP Menus
		else if ( preg_match( '#menu-([0-9]+)#', $widget_id, $matches ) ) {
			$widget_as_id = 'menu';
			
			$menu_id = $matches[1];

			$widget = wp_nav_menu(
				array(
					'menu' => $menu_id,
					'echo' => false
				)
			);
		}
		
		echo apply_filters( 'aurum_show_top_menu_widget', sprintf( '<div class="top-menu--widget top-menu--widget-%2$s">%1$s</div>', $widget, $widget_as_id ), $widget_id, $is_text_widget );
	}
}
