<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

$show_map           = aurum_get_field( 'show_map');
$map_coordinates    = aurum_get_field( 'map_coordinates');

if($show_map)
{
	global $wp_scripts;
	
	wp_enqueue_script('google-maps');
	
	// Set Icon Sizes
	if ( ! is_array( $map_coordinates ) ) {
		$map_coordinates = array();
	}
	
	foreach ( $map_coordinates as $i => $pin ) {

		if ( $pin['pin_image'] ) {
			$image_size = @getimagesize( str_replace( site_url( '/' ), '', $pin['pin_image'] ) );
			
			if ( is_array( $image_size ) && count( $image_size ) >= 2 ) {
				$map_coordinates[ $i ]['size'] = array( $image_size[0], $image_size[1] );
			}
		}
	}

	?>
	<script>
		var mapChords = <?php echo json_encode($map_coordinates); ?>;
	</script>
	<div id="map"></div>
	<?php
}

$form_position 			= aurum_get_field( 'form_position');

$form_title             = aurum_get_field( 'form_title');
$form_sub_title         = aurum_get_field( 'form_sub_title');

$required_fields		= aurum_get_field( 'required_fields');
$phone_number_field		= aurum_get_field( 'phone_number_field');
$submit_button_text     = aurum_get_field( 'submit_button_text');
$privacy_text 			= aurum_get_field( 'privacy_text' );
$success_message        = aurum_get_field( 'success_message');

if( ! is_array($required_fields))
	$required_fields = array();

$is_required_name		= in_array('name', $required_fields);
$is_required_subject	= in_array('subject', $required_fields);
$is_required_email		= in_array('email', $required_fields);
$is_required_phone		= apply_filters( 'contac_form_$is_required_phone', false );
$is_required_message	= in_array('message', $required_fields);

$address_title          = aurum_get_field( 'address_title');
$address_sub_title      = aurum_get_field( 'address_sub_title');
$address_description    = aurum_get_field( 'address_description');

?>
<form id="contact-form" method="post" enctype="application/x-www-form-urlencoded" action="" novalidate="">

	<input type="hidden" name="id" value="<?php the_id(); ?>" />

	<div class="container contact-page">
		<div class="row">
			<div class="col-lg-7 col-md-7 col-sm-6<?php echo $form_position == 'right' ? ' pull-right-md' : ''; ?>">
				<?php
					/**
					 * Form title
					 */
					aurum_page_heading( array( 'h3' => $form_title, 'small' => $form_sub_title ) );
				?>

				<div class="form-success-message hidden">
					<div class="alert alert-success" role="alert"><?php echo $success_message; ?></div>
				</div>

				<div class="contact-form">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<input type="text" name="name" placeholder="<?php _e('Name', 'aurum'); echo $is_required_name ? ' *' : ''; ?>" class="form-control<?php echo $is_required_name ? ' required' : ''; ?>">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<input type="text" name="subject" placeholder="<?php _e('Subject', 'aurum'); echo $is_required_subject ? ' *' : ''; ?>" class="form-control<?php echo $is_required_subject ? ' required' : ''; ?>">
							</div>

						</div>
					</div>

					<div class="row">
						<div class="col-lg-<?php echo $phone_number_field ? 6 : 12; ?>">
							<div class="form-group">
								<input type="email" name="email" placeholder="<?php _e('E-mail', 'aurum'); echo $is_required_email ? ' *' : ''; ?>" class="form-control<?php echo $is_required_email ? ' required' : ''; ?>">
							</div>
						</div>
						<?php if ( $phone_number_field ) : ?>
						<div class="col-lg-6">
							<div class="form-group">
								<input type="text" name="phone" placeholder="<?php _e('Phone', 'aurum'); echo $is_required_phone ? ' *' : ''; ?>" class="form-control<?php echo $is_required_phone ? ' required' : ''; ?>">
							</div>
						</div>
						<?php endif; ?>
					</div>

					<div class="form-group">
						<textarea name="message" placeholder="<?php _e( 'Message', 'aurum' ); echo $is_required_message ? ' *' : ''; ?>" class="form-control<?php echo $is_required_message ? ' required' : ''; ?>" rows="5"></textarea>
					</div>
				
					<?php if ( ! empty( $privacy_text ) ) : ?>
					<div class="row">
						<div class="col-lg-9">
							<div class="field privacy-policy">
								<label>
									<input type="checkbox" name="privacy_policy" value="1" data-required-text="<?php esc_attr_e( 'You must accept the site privacy policy in order to submit this form.', 'oxygen' ) ; ?>" />
									<?php echo do_shortcode( $privacy_text ); ?>
								</label>
							</div>
						</div>
						<div class="col-lg-3">
							<button type="submit" class="btn btn-primary send-message pull-right"><?php echo $submit_button_text; ?></button>
						</div>
					</div>
					<?php else : ?>
						<button type="submit" class="btn btn-primary send-message pull-right"><?php echo $submit_button_text; ?></button>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-5 col-md-5 col-sm-6 contact-information">
				<?php
					/**
					 * Address title
					 */
					aurum_page_heading( array( 'h3' => $address_title, 'small' => $address_sub_title ) );
				?>

				<?php echo $address_description; ?>

			</div>
		</div>
	</div>
</form>