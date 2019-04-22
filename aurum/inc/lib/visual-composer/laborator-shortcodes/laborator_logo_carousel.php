<?php
/**
 *	Logo Carousel for Visual Composer
 *
 *	Laborator.co
 *	www.laborator.co
 */

class WPBakeryShortCode_laborator_logo_carousel extends WPBakeryShortCode {
	
	public function content( $atts, $content = null ) {
		
		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
		}
		
		extract( $atts );
		
		// Images
		$images = explode( ',', $images );
		
		// Custom links
		$custom_links = explode( ',', $custom_links );
		
		// Unique ID
		$id = 'logos_carousel_' . mt_rand( 100, 999 );
		
		// CSS Class
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, "lab_vc_logo_carousel wpb_content_element {$id} " . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );
		
		// Logos list
		$logos_carousel = '<ul class="logos-carousel columns-' . intval( $columns ) . '">';
		
		foreach ( $images as $i => $attachment_id ) {
			
			$image_arr = wpb_getImageBySize( array( 'attach_id' => $attachment_id, 'thumb_size' => $img_size ) );
			
			if ( ! empty( $image_arr['thumbnail'] ) ) {
				$logo_entry = $image_arr['thumbnail'];
				
				if ( isset( $custom_links[ $i ] ) ) {
					$logo_entry = sprintf( '<a href="%s" target="%s">%s</a>', $custom_links[ $i ], $target, $logo_entry );
				}
				
				$logos_carousel .= sprintf( '<li class="logo-entry">%s</li>', $logo_entry );
			}
		}
		
		$logos_carousel .= '</ul>';
		
		// Max height
		if ( ! empty( $max_height ) && is_numeric( $max_height ) ) {
			$logos_carousel .= "<style>.{$id} li { height: {$max_height}px;} .{$id} li img { width: auto; max-height: {$max_height}px !important; }</style>";
		}
		
		return sprintf( '<div class="%s">%s</div>%s', $css_class, $logos_carousel, $this->getCarouselSetup( $id, $atts ) );
	}
	
	
	/**
	 * Carousel setup
	 */
	public function getCarouselSetup( $id, $atts ) {
		
		extract( $atts );
		
		// Enqueue slick
		aurum_enqueue_slick_carousel();
		
		// Nav options
		$show_navigation = explode( ',', $show_navigation );
		$arrows = $dots = false;
		
		if ( in_array( 'next_prev', $show_navigation ) ) {
			$arrows = true;
		}
		
		if ( in_array( 'pagination', $show_navigation ) ) {
			$dots = true;
		}
		
		// Responsiveness
		$screen_md_min = 992;
		$screen_sm_min = 768;
		$screen_xs_min = 480;
		
		$columns_md = $columns_sm = $columns_xs = $columns;
		
		switch ( $columns ) {
			
			// 6,7,8 columns
			case 8:
			case 7:
			case 6:
				$columns_md = 4;
				$columns_sm = 3;
				$columns_xs = 2;
				break;
			
			// 4,5 columns
			case 5:
			case 4:
				$columns_sm = 3;
				$columns_xs = 2;
				break;
			
			// 3 columns
			case 3:
				$columns_xs = 2;
				break;
		}
		
		ob_start();
		
		?>
		<script>
			( function( $, window ) {
				$( document ).ready( function() {
					var $carousel = $( '.<?php echo $id; ?> .logos-carousel' );
					
					$carousel.slick( {
						slide : 'li',
						slidesToShow : <?php echo intval( $columns ); ?>,
						slidesToScroll : <?php echo intval( $columns ); ?>,
  
						rtl : isRTL(),
						arrows : <?php echo $arrows ? 'true' : 'false'; ?>,
						dots : <?php echo $dots ? 'true' : 'false'; ?>,
						
						// Responsive
						responsive : [
							{
								breakpoint : <?php echo $screen_md_min; ?>,
								settings : {
									slidesToShow : <?php echo intval( $columns_md ); ?>,
									slidesToScroll : <?php echo intval( $columns_md ); ?>,
								}
							},
							{
								breakpoint : <?php echo $screen_sm_min; ?>,
								settings : {
									slidesToShow : <?php echo intval( $columns_sm ); ?>,
									slidesToScroll : <?php echo intval( $columns_sm ); ?>,
								}
							},
							{
								breakpoint : <?php echo $screen_xs_min; ?>,
								settings : {
									slidesToShow : <?php echo intval( $columns_xs ); ?>,
									slidesToScroll : <?php echo intval( $columns_xs ); ?>,
								}
							},
						],
						
						<?php if ( ! empty( $autoswitch ) && $autoswitch > 0 ) : ?>
						// Autoplay
						autoplay: true,
						autoplaySpeed: <?php echo $autoswitch * 1000; ?>,
						<?php endif; ?>
					} );
				} );
			} ) ( jQuery, window );
		</script>
		<?php
		
		return ob_get_clean();
	}
}

// Shortcode Options
$target_arr = array(
	'Same window' => '_self',
	'New window' => "_blank"
);

$opts = array(
	"name"		=> 'Logos Carousel',
	"description" => 'Your clients logos into a rotative carousel.',
	"base"		=> "laborator_logo_carousel",
	"class"		=> "vc_laborator_logo_carousel",
	"icon"		=> "icon-lab-logo-carousel",
	"controls"	=> "full",
	"category"  => 'Laborator',
	"params"	=> array(

		array(
			"type" => "textfield",
			"heading" => 'Widget title',
			"param_name" => "title",
			"value" => "",
			"description" => 'Enter text which will be used as widget title. Leave blank if no title is needed.'
		),

		array(
			'type' => 'attach_images',
			'heading' => 'Images',
			'param_name' => 'images',
			'value' => '',
			'description' => 'Select images (logos) from media library.'
		),

		array(
			"type" => "textfield",
			"heading" => 'Maximum Height',
			"param_name" => "max_height",
			"value" => "",
			"description" => 'Enter maximum height for logo images in pixels. (Optional)'
		),

		array(
			'type' => 'exploded_textarea',
			'heading' => 'Custom links',
			'param_name' => 'custom_links',
			'description' => 'Enter links for each slide here. Divide links with linebreaks (Enter). Leave blank if you don\'t want to add links.',
		),

		array(
			'type' => 'dropdown',
			'heading' => 'Target',
			'param_name' => 'target',
			'value' => $target_arr,
			'dependency' => array( 'element'=>'custom_links', 'not_empty'=>true )
		),

		array(
			'type' => 'textfield',
			'heading' => 'Image size',
			'param_name' => 'img_size',
			'value' => 'thumbnail',
			'description' => 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.'
		),

		array(
			'type' => 'dropdown',
			'heading' => 'Columns per slide',
			'param_name' => 'columns',
			'value' => range( 1, 8 ),
			'std' => 5,
			'description' => 'How many logos you want to show per row (slide).',
		),

		array(
			'type' => 'checkbox',
			'heading' => 'Slider navigation',
			'param_name' => 'show_navigation',
			'description' => 'Select whether you want to display carousel navigation links.',
			'value' => array(
				'Next/previous arrows<br />' => 'next_prev' ,
				'Pagination numbers (circles)' => 'pagination'
			),
		),

		array(
			"type" => "textfield",
			"heading" => 'Auto rotate',
			"param_name" => "autoswitch",
			"value" => "5",
			"description" => 'Auto rotate slides each X seconds. Leave blank to disable auto switching.'
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
