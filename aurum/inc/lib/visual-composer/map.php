<?php
/**
 *	Mapping Attributes
 *
 *	Laborator.co
 *	www.laborator.co
 */

function aurum_vc_shortcode_after_init() {
	# ! VC_ROW
	$attributes = array(
		array(
			'type'        => 'checkbox',
			'heading'     => 'Wrap with a Container',
			'param_name'  => 'container_wrap',
			'description' => 'When using fullwidth page this setting will help you center the content with a container.',
			'value'       => array( 'Yes' => 'yes' ),
			'weight'	  => 1
		),
	);
	
	vc_add_params( 'vc_row', $attributes );
	
	
	# ! VC_TAB
	$attributes = array(
		array(
			'type'        => 'checkbox',
			'heading'     => 'Bordered',
			'param_name'  => 'bordered',
			'description' => 'Add borders to tab panels.',
			'value'       => array( 'Yes' => 'yes' )
		),
	);
	
	vc_add_params( 'vc_tabs', $attributes );
	
	
	# ! VC_TOUR
	$attributes = array(
		array(
			'type'        => 'dropdown',
			'heading'     => 'Tabs Alignment',
			'param_name'  => 'align_tabs',
			'value'       => array('Left' => 'left', 'Right' => 'right'),
			'std'		  => 'left',
			'description' => 'Set tabs to be aligned on left or right.'
		),
	);
	
	vc_add_params( 'vc_tour', $attributes );
	
	
	
	# ! VC_MESSAGE
	vc_remove_param( 'vc_message', 'style' );
	
	
	# ! VC_BUTTON
	$primary_colors = array(
		'Default' => 'btn-default',
		'Primary' => 'btn-primary',
		'Success' => 'btn-success',
		'Info' => 'btn-info',
		'Warning' => 'btn-warning',
		'Danger' => 'btn-danger',
		'Grey' => 'wpb_button',
		'Black' => "btn-inverse"
	);
	
	
	$attribute_color = array(
		'type' => 'dropdown',
		'heading' => 'Color',
		'param_name' => 'color',
		'value' => $primary_colors,
		'description' => 'Button color.',
		'param_holder_class' => 'vc_colored-dropdown'
	);
	
	vc_update_shortcode_param( 'vc_button', $attribute_color );
	
	
	
	# ! VC_TEXT_SEPARATOR
	$attributes = array(
		array(
			'type'        => 'dropdown',
			'heading'     => 'Title Style',
			'param_name'  => 'title_style',
			'value'       => array(
				'Plain'          => 'plain',
				'Squared'        => 'squared',
				'Rounded'        => 'rounded',
				'Square Filled'  => 'squared-filled',
				'Rounded Filled' => 'rounded-filled',
			),
			'std'		  => 'plain',
			'description' => 'Choose the separator title style.'
		),
	
		array(
			'type' => 'colorpicker',
			'heading' => 'Title Text Color',
			'param_name' => 'title_text_color',
			'description' => 'Set text color for the title.',
			'std' => 'rgba(255,255,255,1)',
			'dependency'  => array(
				'element' => 'title_style',
				'value'   => array( 'squared-filled', 'rounded-filled' )
			),
		),
	
		array(
			'type' => 'checkbox',
			'heading' => 'Icon',
			'param_name' => 'use_icon',
			'description' => 'Prepend an icon to the title.',
			'value' => array( 'Yes' => 'yes' ),
		),
	
	
		array(
			"type" => "fontelloicon",
			"heading" => 'Separator Icon',
			"param_name" => "icon",
			"value" => "heart",
			"description" => 'Select icon to show.',
			'dependency' => array( 'element' => 'use_icon', 'not_empty' => true )
		),
	);

	$colors_arr = array(
		'Default'	   => 'default',
		'Blue'         => 'blue',
		'Turquoise'    => 'turquoise',
		'Pink'         => 'pink',
		'Violet'       => 'violet',
		'Peacoc'       => 'peacoc',
		'Chino'        => 'chino',
		'Mulled Wine'  => 'mulled_wine',
		'Vista Blue'   => 'vista_blue',
		'Black'        => 'black',
		#'Grey'         => 'grey',
		'Orange'       => 'orange',
		'Sky'          => 'sky',
		'Green'        => 'green',
		'Juicy pink'   => 'juicy_pink',
		'Sandy brown'  => 'sandy_brown',
		'Purple'       => 'purple',
		'White'        => 'white'
	);
	
	$attribute_color = array(
		'type'                 => 'dropdown',
		'heading'              => 'Color',
		'param_name'           => 'color',
		'value'                => array_merge( $colors_arr, array( 'Custom color' => 'custom' ) ),
		'std'                  => 'default',
		'description'          => 'Separator color.',
		'param_holder_class'   => 'vc_colored-dropdown'
	);
	
	$attribute_style = array(
		'type' => 'dropdown',
		'heading' => 'Style',
		'param_name' => 'style',
		'value' => array(
			'Double Bordered'     => 'double-border',
			'Double Bordered 2'   => 'double-border-2',
			'Thick' 		 	  => 'thick',
			'Thin'                => 'plain'
		),
		'description' => 'Separator style.'
	);
	
	vc_update_shortcode_param( 'vc_text_separator', $attribute_color );
	vc_update_shortcode_param( 'vc_text_separator', $attribute_style );
	
	vc_add_params( 'vc_text_separator', $attributes );
}

add_action( 'vc_after_init', 'aurum_vc_shortcode_after_init' );