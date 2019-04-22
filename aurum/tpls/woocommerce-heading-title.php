<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

$shop_title_show = get_data( 'shop_title_show' );
$shop_sorting_show = get_data( 'shop_sorting_show' );

if ( $shop_title_show || $shop_sorting_show ) :

	?>
	<div class="woocommerce-shop-header woocommerce-shop-header--columned">
		
		<div class="woocommerce-shop-header--title">
			
			<h1 class="page-title">
				<?php
					// Page title
					if ( $shop_title_show ) {
						woocommerce_page_title(); 
					}
				?>
	
				<?php if ( $shop_sorting_show ) : ?>
				<small><?php woocommerce_result_count(); ?></small>
				<?php endif; ?>
			</h1>
			
		</div>
		
		<?php if ( $shop_sorting_show ) : ?>
		
			<div class="woocommerce-shop-header--sorting">
				<?php woocommerce_catalog_ordering(); ?>
			</div>
		
		<?php endif; ?>
	</div>
	<?php

else : 
	?>
	<div class="shop-spacer"></div>	
	<?php
	
endif;
