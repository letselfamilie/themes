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
 * WooCommerce Styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/**
 * Page heading visibility
 */
function aurum_page_set_heading_visibility( $visible ) {
	
	// Hide on front page
	if ( is_front_page() ) {
		$visible = false;
	}
	
	// Hide on search page
	if ( is_search() ) {
		$visible = false;
	}
	
	// Hide on archive page
	if ( is_home() || is_post_type_archive() ) {
		$visible = false;
	}
	
	// WooCommerce shop archive/taxonomy
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		$visible = false;
	}
	
	return $visible;
}

add_filter( 'aurum_page_heading_visibility', 'aurum_page_set_heading_visibility' );

/**
 * Add Do-shortcode for text widgets
 */
function aurum_widget_text_do_shortcodes( $text ) {
	return do_shortcode( $text );
}

add_filter( 'widget_text', 'aurum_widget_text_do_shortcodes' );

/**
 * Date Shortcode
 */
function laborator_shortcode_date( $atts = array(), $content = '' ) {
	return date_i18n( get_option( 'date_format' ) );
}

if ( ! shortcode_exists( 'date' ) ) {
	add_shortcode( 'date', 'laborator_shortcode_date' );
}

/**
 * Shortcode for Social Networks [lab_social_networks]
 */
function aurum_shortcode_social_networks( $atts = array(), $content = '' ) {
	$social_order = get_data('social_order');

	$social_order_list = array(
		'fb'  => array('title' => __('Facebook', 'aurum'), 		'icon' => 'fa fa-facebook'),
		'tw'  => array('title' => __('Twitter', 'aurum'), 		'icon' => 'fa fa-twitter'),
		'lin' => array('title' => __('LinkedIn', 'aurum'), 		'icon' => 'fa fa-linkedin'),
		'yt'  => array('title' => __('YouTube', 'aurum'), 		'icon' => 'fa fa-youtube'),
		'vm'  => array('title' => __('Vimeo', 'aurum'), 		'icon' => 'fa fa-vimeo'),
		'drb' => array('title' => __('Dribbble', 'aurum'), 		'icon' => 'fa fa-dribbble'),
		'ig'  => array('title' => __('Instagram', 'aurum'), 	'icon' => 'fa fa-instagram'),
		'pi'  => array('title' => __('Pinterest', 'aurum'), 	'icon' => 'fa fa-pinterest'),
		'gp'  => array('title' => __('Google+', 'aurum'), 		'icon' => 'fa fa-google-plus'),
		'vk'  => array('title' => __('VKontakte', 'aurum'), 	'icon' => 'fa fa-vk'),
		'sc'  => array('title' => __('SoundCloud', 'aurum'), 	'icon' => 'fa fa-soundcloud'),
		'tb'  => array('title' => __('Tumblr', 'aurum'), 		'icon' => 'fa fa-tumblr'),
		'rs'  => array('title' => __('RSS', 'aurum'), 			'icon' => 'fa fa-rss'),
		'sn'  => array('title' => __('Snapchat', 'aurum'), 		'icon' => 'fa fa-snapchat-ghost'),
	);


	$html = '<ul class="social-networks">';

	foreach($social_order['visible'] as $key => $title)
	{
		if($key == 'placebo')
			continue;

		$sn = $social_order_list[$key];


		$html .= '<li>';
			$html .= '<a href="'.get_data("social_network_link_{$key}").'" title="'.$title.'" target="_blank">';
				$html .= '<i class="'.$sn['icon'].'"></i>';
			$html .= '</a>';
		$html .= '</li>';
	}

	$html .= '</ul>';


	return $html;

}

add_shortcode( 'lab_social_networks', 'aurum_shortcode_social_networks' );

/**
 * Excerpt lengths and more text
 */
function laborator_default_excerpt_length() {
	return 75;
}

function laborator_small_excerpt_length() {
	return 35;
}

function laborator_default_excerpt_more() {
	return "&hellip;";
}

add_filter( 'excerpt_length', 'laborator_default_excerpt_length' );
add_filter( 'excerpt_more', 'laborator_default_excerpt_more' );

/**
 * Laborator Theme Options Translate
 */
function laborator_add_menu_classes( $items ) {
	global $submenu;

	foreach ( $submenu as $menu_id => $sub ) {
		if ( $menu_id == 'laborator_options' ) {
			$submenu[$menu_id][0][0] = 'Theme Options';
		}
	}

	return $submenu;
}

add_filter( 'admin_menu', 'laborator_add_menu_classes', 100 );

/**
 * Post Class
 */
function aurum_post_class( $classes ) {
	if ( is_single() && ! get_data( 'blog_single_thumbnails' ) ) {
		$classes[] = 'no-thumbnail';
	} elseif ( ! is_single() && ! get_data( 'blog_thumbnails' ) ) {
		$classes[] = 'no-thumbnail';
	}

	return $classes;
}

add_filter( 'post_class', 'aurum_post_class' );

/**
 * Comments list
 */
function laborator_list_comments_open( $comment, $args, $depth ) {
	global $post, $wpdb, $comment_index;

	$comment_ID = $comment->comment_ID;
	$comment_author = $comment->comment_author;
	$comment_author_url = $comment->comment_author_url;
	$comment_date = $comment->comment_date;
	$comment_parent_ID = $comment->comment_parent;

	$avatar					= preg_replace( "/\s?(height='[0-9]+'|width='[0-9]+')/", "", get_avatar( $comment ) );

	$comment_time 			= strtotime( $comment_date );
	$comment_timespan 		= human_time_diff( $comment_time, time() );

	$link 					= '<a href="' . $comment_author_url . '" target="_blank">';

	$comment_classes = array();

	if ( $depth > 3 ) {
		$comment_classes[] = 'col-md-offset-3';
	} elseif ( $depth > 2 ) {
		$comment_classes[] = 'col-md-offset-2';
	} elseif ( $depth > 1 ) {
		$comment_classes[] = 'col-md-offset-1';
	}

	// In reply to Get
	$parent_comment = null;

	if ( $comment_parent_ID ) {
		$parent_comment = get_comment( $comment_parent_ID );
	}
	?>
	<div <?php comment_class( implode( ' ', $comment_classes ) ); ?> id="comment-<?php echo $comment_ID; ?>"<?php echo $depth > 1 ? " data-replied-to=\"comment-{$comment_parent_ID}\"" : ''; ?>>

		<div class="avatar">
			<?php echo $avatar; ?>
		</div>

		<h4>
			<?php echo $comment_author_url ? ( $link . $comment_author . '</a>' ) : $comment_author; ?>
			<small>
				<?php echo date_i18n( 'F d, Y - H:i', $comment_time ); ?>
				<?php if ( $parent_comment ) : ?>
				<span<?php echo $depth <= 4 ? ' class="visible-xs-inline visible-sm-inline"' : ''; ?>>&ndash; <?php echo sprintf( __( 'In reply to: <span class="replied-to">%s</span>', 'aurum' ), $parent_comment->comment_author ); ?></span>
				<?php endif; ?>
			</small>
		</h4>

		<div class="comment-content">
			<div class="post-formatting"><?php comment_text(); ?></div>
		</div>

		<?php
			// Comment reply link
			comment_reply_link( array_merge( $args, array(
				'reply_text' => __( '<span>Reply</span>', 'aurum' ),
				'depth' => $depth,
				'max_depth' => $args['max_depth'],
				'before' => ''
			) ), $comment, $post );
		?>
	</div>
	<?php
}

function laborator_list_comments_close() {
}

/**
 * Comment fields wrapper
 */
function aurum_comment_form_default_fields_wrap( $args ) {
	
	// Comment fields
	foreach ( $args['fields'] as $field_id => $field ) {
		
		if ( preg_match( "/<label.*?>(?<label_text>.*?)<\/label>.*<input.*?>/", $field, $matches ) ) {
			$label_text = strip_tags( $matches['label_text'] );
			$field_placeholder = 'placeholder="' . esc_attr( $label_text ) . '"';
			$args['fields'][ $field_id ] = str_replace( '<input', "<input $field_placeholder", $field );
		}
	}
	
	// Commenf field
	if ( preg_match( "/<label.*?>(?<label_text>.*?)<\/label>.*<textarea.*?>/", $args['comment_field'], $matches ) ) {
		$label_text = strip_tags( $matches['label_text'] );
		$field_placeholder = 'placeholder="' . esc_attr( $label_text ) . '"';
		$args['comment_field'] = str_replace( '<textarea', "<textarea $field_placeholder", $args['comment_field'] );
	}
	
	return $args;
}

/**
 * Filter to Replace default css class for vc_row shortcode and vc_column
 */
function aurum_css_classes_for_vc( $class_string, $tag ) {
	global $atts_values;

	if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {
		$class_string = str_replace( array( 'wpb_row vc_row-fluid'), array( 'row' ), $class_string );
	} elseif ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
		if ( preg_match( '/vc_span(\d+)/', $class_string, $matches ) ) {
			$span_columns = $matches[1];

			$col_type = $tag == 'vc_column' ? 'sm' : 'md';

			$class_string = str_replace( $matches[0], "col-{$col_type}-{$span_columns}", $class_string );
		}
	}

	return $class_string;
}

add_filter( 'vc_shortcodes_css_class', 'aurum_css_classes_for_vc', 10, 2 );

/**
 * Testimonial Thumbnail
 */
function laborator_testimonial_featured_image_column( $columns ) {
	if ( lab_get( 'post_type' ) == 'testimonial') {
		$columns_new = array(
			'cb' => $columns['cb'],
			'testimonial_featured_image' =>  'Image',
		);

		$columns = array_merge( $columns_new, $columns );
	}

	return $columns;
}

function laborator_testimonial_featured_image_column_content( $column_name, $id ) {
	if ( $column_name === 'testimonial_featured_image' ) {
		if ( has_post_thumbnail() ) {
			echo wp_get_attachment_image( get_post_thumbnail_id(), 'thumbnail', false, array( 'width' => '48' ) );
		} else {
			echo "<small>No Image</small>";
		}
	}
}

add_filter( 'manage_posts_columns', 'laborator_testimonial_featured_image_column', 5 );
add_filter( 'manage_pages_columns', 'laborator_testimonial_featured_image_column', 5 );

add_action( 'manage_posts_custom_column', 'laborator_testimonial_featured_image_column_content', 5, 2 );
add_action( 'manage_pages_custom_column', 'laborator_testimonial_featured_image_column_content', 5, 2 );

/**
 * Body Class
 */
function aurum_body_class( $classes ) {
	
	if ( aurum_get_field( 'remove_top_margin' ) ) {
		$classes[] = 'content-ntm';
	}

	if ( aurum_get_field( 'remove_bottom_margin' ) ) {
		$classes[] = 'content-nbm';
	}

	// Transparent Header
	if ( has_transparent_header() ) {
		$classes[] = 'transparent-header';

		if ( aurum_get_field( 'header_top_links' ) )  {
			$classes[] = 'header-top-menu';
		}
	}
	
	// Disabled Nivo on Mobile
	if ( get_data( 'shop_lightbox_disable_mobile' ) ) {
		$classes[] = 'nivo-disabled-product';
	}

	return $classes;
}

add_filter( 'body_class', 'aurum_body_class' );

/**
 * The Content Filter
 */
function laborator_the_content( $content ) {
	$content = preg_replace( '/\<table\>/', '<table class="table">', $content );
	return $content;
}

add_filter( 'the_content', 'laborator_the_content' );
add_filter( 'comment_text', 'laborator_the_content' );

/**
 * Handle Empty Titles (post)
 */
function laborator_the_title( $title ) {
	if ( trim( $title ) == '' ) {
		return __( '(No title)', 'aurum' );
	}

	return $title;
}

add_filter( 'the_title', 'laborator_the_title' );

/**
 * Fixing "Posts" page highlight in the menu when in search page or 404
 */
function dtbaker_wp_nav_menu_objects( $sorted_menu_items, $args ) {
	// check if the current page is really a blog post.
	global $wp_query;
	if ( ! empty( $wp_query->queried_object_id ) ) {
		$current_page = get_post( $wp_query->queried_object_id );
		if ( $current_page && $current_page->post_type=='post' ) {
			//yes!
		} else {
			$current_page = false;
		}
	} else {
		$current_page = false;
	}

	$home_page_id = (int) get_option( 'page_for_posts' );
	foreach ( $sorted_menu_items as $id => $menu_item ) {
		if ( ! empty( $home_page_id ) && 'post_type' == $menu_item->type && empty( $wp_query->is_page ) && $home_page_id == $menu_item->object_id ){
			if ( ! $current_page ) {
				foreach ( $sorted_menu_items[ $id ]->classes as $classid => $classname ) {
					if ( $classname == 'current_page_parent' ) {
						unset( $sorted_menu_items[ $id ]->classes[ $classid ] );
					}
				}
			}
		}
	}
	
	return $sorted_menu_items;
}

add_filter( 'wp_nav_menu_objects','dtbaker_wp_nav_menu_objects', 10, 2 );

/**
 * Skin Compiler
 */
function laborator_custom_skin_generate( $data ) {
	if ( ! defined( 'DOING_AJAX' ) ) {
		return $data;
	} elseif( $_REQUEST['action'] != 'of_ajax_post_action' ) {
		return $data;
	}
	
	if ( isset( $data['use_custom_skin'] ) && $data['use_custom_skin'] ) {
		update_option( 'aurum_skin_custom_css', '' );
	
		$colors = array();
		
		$custom_skin_bg_color             = $data['custom_skin_bg_color'];
		$custom_skin_link_color           = $data['custom_skin_link_color'];
		$custom_skin_secondary_link_color = $data['custom_skin_secondary_link_color'];
		$custom_skin_footer_bg_color      = $data['custom_skin_footer_bg_color'];
		$custom_skin_border_color         = $data['custom_skin_border_color'];
		$custom_skin_button_color         = $data['custom_skin_button_color'];
		$custom_skin_text_color           = $data['custom_skin_text_color'];
		
		
		$files = array(
			get_template_directory() . "/assets/less/lesshat.less" => "include",
			get_template_directory() . "/assets/less/skin-generator.less"     => "parse",
		);
		
		$vars = array(
			'background'     => $custom_skin_bg_color,
			'link-color'     => $custom_skin_link_color,
			'secondary-link' => $custom_skin_secondary_link_color,
			'footer'         => $custom_skin_footer_bg_color,
			'border-color'   => $custom_skin_border_color,
			'button-color'   => $custom_skin_button_color,
			'text-color'     => $custom_skin_text_color,
		);
		
		$css_stype = aurum_generate_less_style( $files, $vars );
		
		update_option( 'aurum_skin_custom_css', $css_stype );
		
		aurum_generate_custom_skin_file();
	}
	
	return $data;
}

add_filter( 'of_options_before_save', 'laborator_custom_skin_generate' );

/**
 * Remove Plugin Notices
 */
if ( defined( 'LS_PLUGIN_BASE' ) ) {
	function aurum_layerslider_remove_notice() {
		remove_action( 'after_plugin_row_' . LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice', 10, 3 );
	}
	
	add_action( 'admin_init', 'aurum_layerslider_remove_notice', 100 );
}

/**
 * Single Blog Post Content
 */
function aurum_body_class_blog_post_single_lightbox( $classes ) {
	
	if ( is_single() && get_data( 'blog_post_single_lightbox' ) ) {
		$classes[] = 'single-post-lightbox-on';
	}
	
	return $classes;
}

add_filter( 'body_class', 'aurum_body_class_blog_post_single_lightbox' );

/**
 * Hide Purchase Notice
 */
if ( defined( 'LS_PLUGIN_BASE' ) ) {
	remove_action( 'after_plugin_row_' . LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice', 10, 3 );
}

/**
 * File Based Custom Skin
 */
function aurum_use_filebased_custom_skin_filter( $use ) {
	// Generate Skin Hash (Prevent Cache Issues)
	if ( $use ) {
		$skin_colors_vars = array( 'custom_skin_bg_color', 'custom_skin_link_color', 'custom_skin_link_color', 'custom_skin_headings_color', 'custom_skin_paragraph_color', 'custom_skin_footer_bg_color', 'custom_skin_borders_color' );
		$skin_colors_hash = '';
		
		foreach ( $skin_colors_vars as $var ) {
			$skin_colors_hash .= get_data( $var );
		}
		
		$theme = wp_get_theme();
		$skin_colors_hash = md5( $theme->get( 'Version' ) . $skin_colors_hash );
		

		// Eneuque skin		
		$custom_skin_filename = aurum_get_custom_skin_filename();
		
		if ( is_child_theme() ) {
			wp_enqueue_style( 'custom-skin', get_stylesheet_directory_uri() . '/' . $custom_skin_filename, null, $skin_colors_hash );
		} else {
			wp_enqueue_style( 'custom-skin', get_stylesheet_directory_uri() . '/assets/css/' . $custom_skin_filename, null, $skin_colors_hash );
		}
	}
}

add_filter( 'aurum_use_filebased_custom_skin', 'aurum_use_filebased_custom_skin_filter', 10 );

/**
 * Fix Pagination 404 error on Search Page
 */
function aurum_search_page_permalinks_fix( $url ) {
	global $wp_rewrite;
	
	if ( $wp_rewrite->using_permalinks() ) {
		$page = array( 'page' );
		if ( preg_match( "#(" . implode( '|', $page ) . ")\/([0-9]+)/?#" , $url, $paged ) ) {
			$page_num = $paged[2];
			
			$url = preg_replace( '/\??page=[0-9]+/', '', $url );
			
			$url = str_replace( $paged[0], '', $url ) . ( strpos( $url, '?' ) !== false ? '&' : '?' );
			$url .= 'page=' . $page_num;
		}
	}
	
	return $url;
}

/**
 * Disable Kalium Open Graph data generation when Yoast is enabled
 */
if ( defined( 'WPSEO_VERSION' ) ) {
	$social = WPSEO_Options::get_option( 'wpseo_social' );
	
	if ( isset( $social['opengraph'] ) ) {
		add_filter( 'aurum_open_graph_meta', '__return_false' );
	}
}

/**
 * Privacy policy shortcode
 */
function aurum_privacy_policy_shortcode( $atts, $content = '' ) {
	// Shortcode atts
	extract( shortcode_atts( array(
		'title' => '',
		'link' => '',
	), $atts, 'aurum_privacy_policy' ) );
	
	$privacy_policy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
	$link = ! empty( $link ) ? $link : $privacy_policy_url;
	
	return sprintf( '<a href="%s" class="privacy-policy-link" target="_blank">%s</a>', esc_url( $link ), esc_html( $title ) );
}

add_shortcode( 'aurum_privacy_policy', 'aurum_privacy_policy_shortcode' );
