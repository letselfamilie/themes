<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

global $wp_query;

// Search tabs
$search_tabs = laborator_get_search_tabs();

// Found posts
$found_posts = $wp_query->found_posts;

//global $wpdb;
//
//global $s;
//$keyword = esc_html( $s );
//
//$found_topics = array();
//
//if(!empty($keyword)){
//    $sqlQuery = "SELECT *
//             FROM {$wpdb->prefix}f_topics
//             WHERE topic_name = ".$keyword."
//             ORDER BY create_timestamp;";
//
//    echo $sqlQuery;
//
//    foreach ($wpdb->get_results($sqlQuery, ARRAY_A) as $topic) {
//        $found_topics[] = $topic;
//    }
//}


// Show add to cart link for WC_Product
$search_add_to_cart = get_data( 'search_add_to_cart' );

// Add to cart link will be shown as textual
$textual_add_to_cart = aurum_hook_return_value( true );
add_filter( 'get_data_shop_add_to_cart_textual', $textual_add_to_cart );
?>
<section class="search-header">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2>
					<?php if (!empty($found_posts)) : ?>
						<?php echo sprintf( _n( '%s result found for <strong>&quot;%s&quot;</strong>', '%s results found for <strong>&quot;%s&quot;</strong>', $found_posts, 'aurum' ), number_format_i18n( $found_posts ), get_search_query() ); ?>
					<?php else: ?>
						<?php echo sprintf( __( 'No search results for <strong>&quot;%s&quot;</strong>', 'aurum' ), get_search_query() ); ?>
					<?php endif; ?>
				</h2>
				<a href="#" class="go-back"><?php _e( '&laquo; Go back', 'aurum' ); ?></a>

				<?php if ( have_posts() && apply_filters( 'aurum_search_tabs', true ) ) : ?>
				<nav class="tabs">
					<?php

						foreach ( $search_tabs as $tab ) :

							$title   = $tab['title'];
							$link    = $tab['link'];
							$class   = $tab['active'] ? 'active' : '';

							if ( apply_filters( 'aurum_search_tabs_post_count', true ) ) {
								$count = $tab['count'];
								printf( '<a href="%s" class="%s">%s<span>%s</span></a>', $link, $class, $title, $count );
							} else {
								printf( '<a href="%s" class="%s">%s</a>', $link, $class, $title );
							}

						endforeach;

					?>
				</nav>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section class="search-results-list">
	<div class="container">

		<ul class="search-results">
			<?php
			while ( have_posts() ) : the_post();
				global $product;

				$product = null;

				$has_thumbnail = has_post_thumbnail();
				$search_meta = get_the_time( get_option( 'date_format' ) );

				if ( $post->post_type == 'page' ) {
					$search_meta = laborator_page_path( $post );
				}
				else if ( 'product' == $post->post_type && $search_add_to_cart && function_exists( 'wc_get_product' ) ) {
					$product = wc_get_product( $post );
					$search_meta = '<div class="price">' . $product->get_price_html() . '</div>';
				}

				?>
				<li class="<?php echo $has_thumbnail ? 'has-thumbnail' : ''; ?>">
					<?php
						// Thumbnail
						if ( $has_thumbnail ) {
							echo '<div class="post-thumbnail">';
								echo '<a href="' . get_permalink() . '">';
									echo aurum_image_placeholder_wrap_element( get_the_post_thumbnail( get_the_id(), apply_filters( 'aurum_search_thumb', 'thumbnail' ) ) );
								echo '</a>';
							echo '</div>';
						}
					?>

					<div class="post-details">
						<h3>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<div class="meta">

							<?php
								// Search meta
								echo $search_meta;

								// Add to cart
								if ( ! empty( $product ) ) {
									woocommerce_template_loop_add_to_cart();
								}
							?>

						</div>
					</div>

				</li>
				<?php

			endwhile;
			?>
		</ul>

			<?php
			if ( have_posts() ) {
				the_posts_pagination( array(
					'prev_text' => sprintf( '&laquo; %s', __( 'Previous', 'aurum' ) ),
					'next_text' => sprintf( '%s &raquo;', __( 'Next', 'aurum' ) ),
					'mid_size' => 3
				) );
			}
			?>

	</div>
</section>