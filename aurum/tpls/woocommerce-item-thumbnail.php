<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

global $post, $product;

$item_preview_type  = get_data( 'shop_item_preview_type' );
$product_images     = $product->get_gallery_image_ids();

// Link open <a>
woocommerce_template_loop_product_link_open();

// Thumbnail size
$thumbnail_size = aurum_woocommerce_get_thumbnail_image_size();

// Primary Thumbnail
if ( has_post_thumbnail() ) {
	echo aurum_get_attachment_image( get_post_thumbnail_id(), $thumbnail_size );
} else {
	echo aurum_woocommerce_get_product_placeholder_image();
}

// Remove Duplicate Images
if ( has_post_thumbnail() ) {
	$post_thumb_id = get_post_thumbnail_id();

	foreach ( $product_images as $i => $attachment_id ) {
		if ( $post_thumb_id == $attachment_id  || ! wp_get_attachment_url( $attachment_id ) ) {
			unset( $product_images[ $i ] );
		}
	}
}

// Other Thumbnails
if ( count( $product_images ) && $item_preview_type != 'none' ) :

	if ( in_array( $item_preview_type, array( 'fade', 'slide' ) ) ) :

		$attachment_id = reset($product_images);

		if ( $attachment = aurum_get_attachment_image( $attachment_id, $thumbnail_size, null, null, array( 'class' => 'shop-image' ) ) ) {
			echo $attachment;
		}

	endif;


	if ( $item_preview_type == 'gallery' ) :

		foreach ( $product_images as $attachment_id ) {
			if ( $attachment = aurum_get_attachment_image( $attachment_id, $thumbnail_size, null, null, array( 'class' => 'shop-image' ) ) ) {
				echo $attachment;
			}
		}

	endif;

endif;

// Link close </a>
woocommerce_template_loop_product_link_close();
