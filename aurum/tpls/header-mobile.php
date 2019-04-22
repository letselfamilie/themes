<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

$header_sticky_menu_mobile = get_data( 'header_sticky_menu_mobile' );
$header_cart_info_show_in_header = get_data( 'header_cart_info_show_in_header' );

$nav_id = 'main-menu';

if ( has_nav_menu( 'mobile-menu' ) ) {
	$nav_id = 'mobile-menu';
}

$menu = wp_nav_menu( array(
	'theme_location' => $nav_id,
	'container' => '',
	'menu_class' => 'mobile-menu',
	'echo' => false
) );
?>
<header class="mobile-menu<?php echo $header_sticky_menu_mobile ? ' sticky-mobile' : ''; ?>">

	<section class="mobile-logo">
	
		<?php
			/**
			 * Logo
			 */
			get_template_part( 'tpls/header-logo' );
			
			/**
			 * Add to cart icon
			 */
			if ( $header_cart_info_show_in_header ) {
				aurum_show_header_cart_icon( array( 35, 35 ) );
			}
		?>

		<div class="mobile-toggles">
			<a class="toggle-menu" href="#">
				<?php echo lab_get_svg( 'images/toggle-menu.svg' ); ?>
				<span class="sr-only"><?php _e( 'Toggle Menu', 'aurum' ); ?></span>
			</a>
		</div>

	</section>

	<section class="mobile-menu--content">
		
		<?php 
			
			/**
			 * Search field
			 */
			if ( apply_filters( 'aurum_show_search_field_on_mobile', true ) ) :
			
				?>	
				<div class="search-site<?php echo get_search_query() ? ' is-visible' : ''; ?>">
			
					<?php
						/**
						 * Search form
						 */
						get_template_part( 'tpls/header-search-form' );
					?>
			
				</div>
				<?php
			
			endif;
			
			/**
			 * Menu mobile
			 */
			echo $menu;
			
			/**
			 * Cart icon under mobile menu
			 */
			if ( ! $header_cart_info_show_in_header ) {
				aurum_show_header_cart_icon();
			}
			
			/**
			 * Header top bar
			 */
			add_filter( 'get_data_header_top_style', aurum_hook_return_value( 'light' ) );
			
			get_template_part( 'tpls/header-top-bar' );
		?>
		
	</div>

</header>