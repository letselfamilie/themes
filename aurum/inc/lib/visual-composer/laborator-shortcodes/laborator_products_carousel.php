<?php
/**
 *	Products Carousel Shortcode for Visual Composer
 *
 *	Laborator.co
 *	www.laborator.co
 */

class WPBakeryShortCode_laborator_products_carousel extends WPBakeryShortCode {
	
	/**
	 * Ids to exclude in products query
	 */
	private $exclude_ids = array();
	
	/**
	 * Tax query
	 */
	private $tax_query = array();
	
	/**
	 * Shortcode content
	 */
	public function content( $atts, $content = null ) {
		global $products_columns;

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
		}
		
		aurum_vc_loop_param_set_default_value( $atts['products_query'], 'post_type', 'product' );
		
		extract( shortcode_atts( array(
			'products_query' => '',
			'product_types_to_show' => '',
			'columns' => '',
			'auto_rotate' => '',
			'el_class' => '',
			'css' => '',
		), $atts ) );


		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_wpb_products_carousel laborator-woocommerce woocommerce wpb_content_element products-hidden ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

		if ( $columns == 1 ) {
			$css_class .= ' single-column';
		}
		
		// Generate query using WC_Shortcode_Products class
		$query_args = aurum_vc_query_builder( $products_query );
		
		$atts = array(
			'columns' => $columns
		);
		
		$type = 'products';
		
		// Items per page
		if ( ! empty( $query_args['posts_per_page'] ) ) {
			$atts['limit'] = $query_args['posts_per_page'];
		}
		
		// Order column
		if ( ! empty( $query_args['orderby'] ) ) {
			$atts['orderby'] = $query_args['orderby'];
		}
		
		// Order direction
		if ( ! empty( $query_args['order'] ) ) {
			$atts['order'] = $query_args['order'];
		}
		
		// Tax Query
		if ( ! empty( $query_args['tax_query'] ) ) {
			$tax_query = $categories = array();
			
			foreach ( $query_args['tax_query'] as $i => $tax ) {
				
				if ( is_numeric( $i ) && ! empty( $tax['taxonomy'] ) ) {
					// Product Categories
					if ( 'product_cat' == $tax['taxonomy'] ) {
						if ( 'NOT IN' == strtoupper( $tax['operator'] ) ) {
							$tax_query[] = $tax;
						} else {
							foreach ( $tax['terms'] as $term_id ) {
								if ( $term = get_term( $term_id, 'product_cat' ) ) {
									$categories[] = $term->slug;
								}
							}
						}
					} 
					// Other terms
					else {
						$tax_query[] = $tax;
					}
				}
			}
			
			// Categories
			$atts['category'] = implode( ',', $categories );
			
			// Add tax query to products query
			if ( count( $tax_query ) ) {
				$this->tax_query = $tax_query;
				add_filter( 'woocommerce_shortcode_products_query', array( $this, 'addTaxQuery' ), 100, 3 );
			}
		}
		
		// Include post ids
		if ( ! empty( $query_args['post__in'] ) ) {
			$atts['ids'] = implode( ',', $query_args['post__in'] );
		}
		
		// Exclude post ids
		if ( ! empty( $query_args['post__not_in'] ) ) {
			$this->exclude_ids = $query_args['post__not_in'];
			add_filter( 'woocommerce_shortcode_products_query', array( $this, 'excludeIds' ), 100, 3 );
		}
		
		// Featured items only
		if ( 'only_featured' == $product_types_to_show ) {
			$atts['visibility'] = 'featured';
			$type = 'featured_products';
		}
		
		// On sale products
		if ( 'only_on_sale' == $product_types_to_show ) {
			$type = 'sale_products';
		}
		
		// Get products
		$shortcode = new WC_Shortcode_Products( $atts, $type );
		
		// Enqueue Slick Carousel
		aurum_enqueue_slick_carousel();

		// Element id
		$rand_id = "el_" . time() . mt_rand( 10000, 99999 );

		ob_start();

		$products_columns = -1;

		?>
		<div class="<?php echo $css_class; ?>" id="<?php echo $rand_id; ?>">

			<div class="products-loading">
				<?php _e( 'Loading products...', 'aurum' ); ?>
			</div>

			<div class="row">
					
				<?php
					
					/**
					 * Show products
					 */
					echo $shortcode->get_content();
					
				?>
			
			</div>

		</div>

		<script type="text/javascript">
			jQuery( document ).ready(function( $ ) {
				var $carouselContainer = $( '#<?php echo $rand_id; ?>' ),
					$products = $carouselContainer.find( '.products' );
				
				// Set products visible
				$carouselContainer.removeClass( 'products-hidden' );
				
				// Setup carousel
				$products.slick( {
					slidesToShow : <?php echo $columns; ?>,
					swipeToSlide : true,
					rtl : isRTL(),
					slide : '.shop-item',
					autoplay: <?php echo $auto_rotate > 0 ? 'true' : 'false'; ?>,
					autoplaySpeed: <?php echo intval( $auto_rotate ) * 1000; ?>,
					responsive : [
						{
							breakpoint : 768,
							settings : {
								slidesToShow : 2,
								slidesToScroll : 2
							}
						},
						{
							breakpoint : 520,
							settings : {
								slidesToShow : 1,
								slidesToScroll : 1
							}
						}
					]
				} );
			} );
		</script>
		<?php


		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
	
	/**
	 * Exclude Ids from query
	 */
	public function excludeIds( $query, $atts, $type ) {
		
		if ( empty( $query['post__not_in'] ) ) {
			$query['post__not_in'] = array();
		}
		
		// Exclude ids
		$query['post__not_in'] = array_merge( $query['post__not_in'], $this->exclude_ids );
		
		// Remove filter after execution
		remove_filter( 'woocommerce_shortcode_products_query', array( $this, 'excludeIds' ), 100, 3 );

		return $query;
	}
	
	/**
	 * Add tax query
	 */
	public function addTaxQuery( $query, $atts, $type ) {
		$tax_query_default = array(
			'field' => 'term_id',
			'taxonomy' => '',
			'operator' => 'IN',
			'terms' => array()
		);
		
		if ( empty( $query['tax_query'] ) ) {
			$query['tax_query'] = array(
				'relation' => 'AND'
			);
		}
		
		foreach ( $this->tax_query as $tax_query ) {
			$query['tax_query'][] = array_merge( $tax_query_default, $tax_query );
		}
		
		// Remove filter after execution
		$this->tax_query = array();
		add_filter( 'woocommerce_shortcode_products_query', array( $this, 'addTaxQuery' ), 100, 3 );
		
		return $query;
	}
}

// Shortcode Options
$opts = array(
	"name"		=> 'Products Carousel',
	'description' => 'Display shop products with Touch Carousel.',
	'base'		=> 'laborator_products_carousel',
	'class'		=> 'vc_laborator_products_carousel',
	'icon'		=> 'icon-lab-products-carousel',
	'controls'	=> 'full',
	'category'  => 'Laborator',
	'params'	=> array(


		array(
			'type' => 'loop',
			'heading' => 'Products Query',
			'param_name' => 'products_query',
			'settings' => array(
				'order_by' => array('value' => 'date'),
				'post_type' => array('value' => 'product', 'hidden' => false)
			),
			'description' => 'Create WordPress loop, to populate products from your site.'
		),

		array(
			'type' => "dropdown",
			"heading" => "Filter Products by Type",
			"param_name" => "product_types_to_show",
			"value" => array(
				"Show all types of products from the above query"  => '',
				"Show only featured products from the above query."  => 'only_featured',
				"Show only products on sale from the above query."  => 'only_on_sale',
			),
			"description" => "Based on layout columns you use, select number of columns to wrap the product."
		),

		array(
			"type" => "dropdown",
			"heading" => 'Columns count',
			"param_name" => "columns",
			"std" => 4,
			"value" => array(
				"6 Columns"  => 6,
				"5 Columns"  => 5,
				"4 Columns"  => 4,
				"3 Columns"  => 3,
				"2 Columns"  => 2,
				"1 Column"   => 1,
			),
			"description" => 'Based on layout columns you use, select number of columns to wrap the product.'
		),

		array(
			"type" => "textfield",
			"heading" => 'Auto Rotate',
			"param_name" => "auto_rotate",
			"value" => "5",
			"description" => 'You can set automatic rotation of carousel, unit is seconds. Enter 0 to disable.'
		),

		array(
			"type" => "textfield",
			"heading" => 'Extra class name',
			"param_name" => "el_class",
			"value" => "",
			"description" => 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'
		),

		array(
			"type" => "css_editor",
			"heading" => 'Css',
			"param_name" => "css",
			"group" => 'Design options'
		)
	)
);

// Add & init the shortcode
vc_map( $opts );