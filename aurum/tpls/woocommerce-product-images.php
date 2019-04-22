<?php
/**
 *	Aurum WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

global $product;

// Slick carousel
aurum_enqueue_slick_carousel();

// Image options
$autoswitch = get_data( 'shop_single_auto_rotate_image' );

if ( ! is_numeric( $autoswitch ) ) {
	$autoswitch = 5;
}

$horizontal_gallery = aurum_woocommerce_get_single_product_thumbnails_placement() == 'horizontal'; 

$images = array();
$post_thumbnail_id = $product->get_image_id();

if ( $post_thumbnail_id ) {
	$images[] = $post_thumbnail_id;
}

$attachment_ids = $product->get_gallery_image_ids();

if ( ! empty( $attachment_ids ) ) {
	$images = array_merge( $images, $attachment_ids );
}

// Thumbnail columns
$thumbnail_columns = aurum_get_number_from_word( get_data( 'shop_product_thumbnails_columns' ) );
$thumbnail_columns = apply_filters( 'woocommerce_product_thumbnails_columns', $thumbnail_columns ? $thumbnail_columns : 5 );

// Classes
$product_images_classes = array( 'product-images' );

if ( 1 == count( $images ) ) {
	$product_images_classes[] = 'product-images--single-image';
	$product_images_classes[] = 'product-images--single-image--on';
}
?>
<div <?php aurum_class_attr( $product_images_classes ); ?>>
	
	<div class="product-images--main">
		
		<?php
			if ( ! empty( $images ) ) {
				foreach ( $images as $attachment_id ) {
					$html = aurum_woocommerce_get_gallery_image( $attachment_id, true );
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
				}
			} else {
				echo aurum_woocommerce_get_product_placeholder_image();
			}
		?>
		
	</div>
	
	<?php if ( ! empty( $images ) ) : ?>
	<div class="product-images--thumbnails columns-<?php echo $thumbnail_columns; ?>">
		
		<?php
			if ( ! empty( $images ) ) {
				foreach ( $images as $attachment_id ) {
					$html = aurum_woocommerce_get_gallery_image( $attachment_id );
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
				}
			}
		?>
		
	</div>
	<?php endif; ?>
	
</div>