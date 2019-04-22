<?php
/**
 *	Aurum WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

if ( ! get_data( 'header_top_links' ) ) {
	return;
} 

$header_top_style       = get_data( 'header_top_style' );
$header_top_links_left  = get_data( 'header_top_links_left' );
$header_top_links_right = get_data( 'header_top_links_right' );

$header_top_left_text   = get_data( 'header_top_left_text' );
$header_top_right_text  = get_data( 'header_top_right_text' );

// Left column
$left_column = $header_top_links_left || $header_top_left_text;

// Right column
$right_column = $header_top_links_right || $header_top_right_text;

// Classes
$classes = array( 'top-menu' );

// Top menu skin
if ( in_array( $header_top_style, array( 'gray', 'light' ) ) ) {
	$classes[] = "top-menu--{$header_top_style}";
} else {
	$classes[] = 'top-menu--dark';
}

// Columns
if ( $left_column && $right_column ) {
	$classes[] = 'top-menu--columns-2';
}

?>
<div <?php aurum_class_attr( $classes ); ?>>
	
	<div class="container">
		
		<div class="row">
		
			<?php if ( $left_column ) : ?>
			<div class="col">
				
				<?php
					
					/**
					 * Show top menu text
					 */
					if ( ! empty( $header_top_left_text ) ) {
						aurum_show_top_menu_widget( $header_top_left_text, true );
					}
					
					/**
					 * Show top menu widget
					 */
					if ( 'hide' !== $header_top_links_left ) {
						aurum_show_top_menu_widget( $header_top_links_left );
					}
				?>
				
			</div>
			<?php endif; ?>
			
			<?php ?>
			<div class="col right">

<!-- CUSTOM CODE-->
                <?php if(is_user_logged_in()) {
                    $username = $user_last = get_user_meta(get_current_user_id(), "nickname", true );

                    $avatar_uri = "";
                    if(um_profile('profile_photo')){
                        $avatar_uri = um_get_avatar_uri( um_profile('profile_photo'), null);
                    } else{
                        $avatar_uri = um_get_default_avatar_uri();
                    }

                    ?>
                    <div class="user-image-container">
                        <a href="<?php echo get_site_url() ?>/user/<?php echo $username?>">
                            <img src="<?php echo $avatar_uri ?>"
                                 class="user-icon gavatar avatar avatar-26 um-avatar um-avatar-default" width="35"
                                 height="35"
                                 data-default="<?php echo um_get_default_avatar_uri() ?>"
                                 alt="<?php echo $username ?>">
                        </a>

                        <div class="user-menu-drop-down">
                            <ul>
                                <li><a href="<?php echo get_site_url() ?>/user/<?php echo $username?>">My Account</a></li>
                                <li><a href="<?php echo get_site_url() ?>/logout/">Log Out</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } else {?>

                    <br>
                    <div class="log-in-a">
                        <a href="<?php echo get_site_url() ?>/login/">Log In</a>
                    </div>
                <?php } ?>
<!---->
                <?php
					/**
					 * Show top menu widget
					 */
					if ( 'hide' !== $header_top_right_text ) {
						aurum_show_top_menu_widget( $header_top_links_right );
					}
					
					/**
					 * Show top menu text
					 */

					if ( ! empty( $header_top_right_text ) ) {
						aurum_show_top_menu_widget( $header_top_right_text, true );
					}
				?>
				
			</div>
			<?php  ?>
			
		</div>
		
	</div>
	
</div>