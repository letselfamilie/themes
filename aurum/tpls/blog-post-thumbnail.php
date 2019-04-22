<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

$has_thumbnail  = ( is_single() ? get_data( 'blog_single_thumbnails' ) : get_data( 'blog_thumbnails' ) ) && has_post_thumbnail();
$thumb_size_big = apply_filters( 'lab_blog_single_image_size', 'post-thumb-big' );

if ( $has_thumbnail ) {
	$post_thumb_id = get_post_thumbnail_id();
}

if ( $more ) {
	$post_gallery = gb_field( 'post_slider_images' );
}
?>
<?php if ( $has_thumbnail ) : ?>
<div class="post-image<?php echo is_single() ? ' nivo' : ''; ?>">

	<?php if ( ! empty( $post_gallery ) ) : ?>
	
		<?php
			// Carousel options
			$autoswitch = get_data( 'blog_gallery_autoswitch' );
			
			// Build images array
			$images = array( get_post( get_post_thumbnail_id() ) );
			
			$images = array_merge( $images, $post_gallery );
			
			// Enqueue carousel
			aurum_enqueue_slick_carousel();
		?>
	
		<ul class="post-gallery" data-autoswitch="<?php echo floatval( $autoswitch == '' ? 5 : $autoswitch ); ?>">
			
			<?php
				foreach ( $images as $image ) :
				
					$attachment_id = $image->ID;
					$href = $image->guid;
					$alt = $image->_wp_attachment_image_alt;
					$is_video = false;
	
					if ( preg_match( '/youtube\.com/', $alt ) || preg_match( '/vimeo\.com/', $alt ) ) {
						$href = $alt;
						$is_video = true;
					}
					
					?>
					<li>
					
						<a href="<?php echo $href; ?>" data-lightbox-gallery="post-gallery" class="<?php echo $is_video ? ' post-is-video' : ' post-is-image'; ?>">
							<?php echo aurum_get_attachment_image( $attachment_id, $thumb_size_big ); ?>
						</a>
					</li>
					<?php
					
				endforeach;
			?>
			
		</ul>
		
	<?php else : // Simple Post Thumbnail ?>
	
		<?php
			$alt = get_post_meta( $post_thumb_id, '_wp_attachment_image_alt', true );
			$is_video = false;
				
			if ( preg_match( '/youtube\.com/', $alt ) || preg_match( '/vimeo\.com/', $alt ) ) {
				$href = $alt;
				$is_video = true;
			}
		?>

		<a href="<?php echo is_single() ? wp_get_attachment_image_url( $post_thumb_id, 'original' ) : $permalink; ?>" class="<?php echo isset( $is_video ) && $is_video ? 'post-is-video' : ( is_single() ? 'post-is-image' : '' ); ?>" title="<?php the_title_attribute(); ?>">
			<?php echo aurum_get_attachment_image( $post_thumb_id, $thumb_size_big ); ?>

			<?php if ( $hover_effect ) : ?>
				<?php if ( ! $more ) : ?>
					<span class="thumb-hover"></span>
					<em><?php esc_html_e( 'Continue reading...', 'aurum' ); ?></em>
				<?php endif; ?>
			<?php endif; ?>
		</a>
	<?php endif; ?>

</div>
<?php endif; ?>
