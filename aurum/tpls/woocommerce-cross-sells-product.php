<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

global $product;

?>
<li class="product-item">

	<?php if ( has_post_thumbnail() ) : $image = aurum_get_attachment_image( get_post_thumbnail_id(), aurum_woocommerce_get_thumbnail_image_size() ); ?>
	<div class="image">
		<?php
			// Product thumbnail
			printf( '<a href="%s">%s</a>', $product->get_permalink(), $image );
		?>
	</div>
	<?php endif; ?>
	
	<div class="product-details">
		
		<h4>
			<a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_name(); ?></a>
		</h4>

		<p class="price"><?php echo $product->get_price_html(); ?></p>
		
	</div>
	
	<div class="product-link">
		
		<?php woocommerce_template_loop_add_to_cart(); ?>
		
	</div>
	
</li>
