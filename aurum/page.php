<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( aurum_get_field( 'fullwidth_page' ) ) {
	get_template_part( 'full-width-page' );
	return;
}

// Fetch post
the_post();

// Header
get_header();

// Analyze page content
$content = get_the_content();
$is_vc_page = preg_match( '/(\[vc.*?\])/', $content );

// See if its fullwidth
$is_fullwidth = false;

if ( function_exists( 'is_cart' ) && is_cart() ) {
	$is_fullwidth = true;
}

// Fullwidth content
if ( $is_fullwidth ) :

	the_content();

else :

	?>
	<div class="container page-container">
	
		<?php if ( false === $is_vc_page ) : ?>
			<div class="row">
				<div class="col-md-12">
					<h1 class="single-page-title"><?php the_title(); ?></h1>
	
					<div class="post-content">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="post-formatting"><?php the_content(); ?></div>
		<?php endif; ?>
	
	</div>
	<?php
		
endif;

// Footer
get_footer();