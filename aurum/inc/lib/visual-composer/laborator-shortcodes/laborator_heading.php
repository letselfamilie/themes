<?php
/**
 *	Heading Title for Visual Composer
 *
 *	Laborator.co
 *	www.laborator.co
 */

class WPBakeryShortCode_laborator_heading extends WPBakeryShortCode {
	
	public function content( $atts, $content = null ) {
		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
		}
		
		extract( shortcode_atts( array(
			'title'              => '',
			'sub_title'          => '',
			'text_align'         => 'left',
			'icon'               => '',
			'font_size'			 => '',
			'show_breadcrumb'    => '',
			'show_dash'    		 => '',
			'el_class'           => '',
			'css'                => '',
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_vc_pagetitle wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

		ob_start();

		// Title
		if ( $icon ) {
			wp_enqueue_style( 'vc-icons' );
			$css_class .= ' has-icon';
		}

		$show_breadcrumb = $show_breadcrumb && function_exists( 'bcn_display' );

		$css_class .= " text-aligned-{$text_align}";

		if ( $font_size ) {
			$css_class .= ' font-size-' . $font_size;
		}

		if ( $sub_title ) {
			$css_class .= ' has-subtitle';
		}

		if ( $show_breadcrumb ) {
			$css_class .= ' has-breadcrumb';
		}

		?>
		<div class="<?php echo $css_class; ?>">
			<div class="row">
				<div class="col-sm-<?php echo $show_breadcrumb ? 6 : 12; ?>">

					<h2>
						<?php if ( $icon ) : ?>
						<i class="vc-icon-<?php echo $icon; ?>"></i>
						<?php endif; ?>

						<?php echo $title; ?>
						<?php if ( $sub_title ) : ?>
						<small><?php echo $sub_title; ?></small>
						<?php endif; ?>
					</h2>

					<?php if ( $show_dash ) : ?>
					<span class="dash"></span>
					<?php endif; ?>
				</div>
				<?php if ( $show_breadcrumb ) : ?>
				<div class="col-sm-6">
					<?php
						echo '<div class="breadcrumb pull-right-md">';
					    bcn_display();
						echo '</div>';
					?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

// Shortcode Options
$opts = array(
	"name"		=> 'Heading Title',
	"description" => 'Custom heading title with other features.',
	"base"		=> "laborator_heading",
	"class"		=> "vc_laborator_heading",
	"icon"		=> "icon-lab-heading",
	"controls"	=> "full",
	"category"  => 'Laborator',
	"params"	=> array(

		array(
			"type" => "textfield",
			"heading" => 'Title',
			"param_name" => "title",
			"value" => "Page title here",
			"description" => 'What text use as page title.'
		),

		array(
			"type" => "textfield",
			"heading" => 'Sub title',
			"param_name" => "sub_title",
			"value" => "",
			"description" => 'Smaller text to display below the title.'
		),

		array(
			"type" => "dropdown",
			"heading" => 'Text align',
			"param_name" => "text_align",
			"value" => array(
				"Left"      => 'left',
				"Center"    => 'center',
				"Right"     => 'right',
			),
			"description" => 'Set the text alignment.'
		),

		array(
			"type" => "fontelloicon",
			"heading" => 'Icon',
			"param_name" => "icon",
			"value" => "",
			"description" => 'Prepend an icon to the title.'
		),

		array(
			"type" => "dropdown",
			"heading" => 'Font Size',
			"param_name" => "font_size",
			"value" => array(
				"Large"     => 'large',
				"Medium"    => 'medium',
				"Small"     => 'small',
			),
			"description" => 'Select font size of the title.'
		),

		array(
			"type" => "checkbox",
			"heading" => 'Show Breadcrumb',
			"param_name" => "show_breadcrumb",
			"std" => '',
			"value" => array(
				'Show' => 'yes',
			),
			"description" => 'This will show current path of the page. To activate this feature you must install <a href="plugin-install.php?tab=search&s=Breadcrumb+NavXT" target="_blank"> <strong>Breadcrumb NavXT</strong></a> plugin.'
		),

		array(
			"type" => "checkbox",
			"heading" => 'Dash',
			"param_name" => "show_dash",
			"std" => '',
			"value" => array(
				'Show' => 'yes',
			),
			"description" => 'Show small dash separator below the title.'
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
vc_map($opts);