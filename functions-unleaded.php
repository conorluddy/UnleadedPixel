<?php
/**
 * unleadedbase functions and definitions
 *
 * @package unleadedbase
 */









// Excerpts
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


function new_excerpt_more($more) {
       global $post;
	return '<a href="'. get_permalink($post->ID) . '">......</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');








// Thumbnails and images
////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Enable thumbnails
add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(390, 235, true); // Normal post thumbnails


// Create custom sizes
// This is then pulled through to your theme useing the_post_thumbnail('custombig');
if ( function_exists( 'add_image_size' ) ) {

	//
	add_image_size('homelarge', 810, 540, true); 

}










// Custom menu areas
////////////////////////////////////////////////////////////////////////////////////////////////////////////
register_nav_menus( array(
	//
	'header-nav-1' => "Header Menu 1",
	'header-nav-2' => "Header Menu 2",
	//
	'footer-nav-1' => "Footer Menu 1",
	'footer-nav-2' => "Footer Menu 2",
	'footer-nav-3' => "Footer Menu 3",
	'footer-nav-4' => "Footer Menu 4"
) );






////////////////////////////////////////////////////////////////////////////////////////////////////////////


/**
 * Enqueue scripts and styles
 */
function unleaded_scripts() {
	
	wp_enqueue_style( 'gfonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400,800,400italic,700,700italic' );
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	
	

	//Modernizer
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.js', false, '2.6.2');

	//JQUERY 1.9!
	wp_deregister_script('jquery'); 
	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js', false, '1.9.0'); 
	wp_enqueue_script('jquery');


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	};
	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	};

}
add_action( 'wp_enqueue_scripts', 'unleaded_scripts' );







////////////////////////////////////////////////////////////////////////////////////////////////////////////
function unleaded_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = 'site-navigation paging-navigation left';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>


	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?> row">


	<h4 class="assistive-text twelve columns"><?php _e( 'Previous and next articles', 'unleaded' ); ?></h4>	


	<?php if ( is_single() ) : // navigation links for single posts ?>
	<div class="twelve columns">
		<ul class="button-group">
			<li>
				<?php previous_post_link( '<div class="nav-previous button"><i class="icon-left"></i> %link</div>', '<span class="meta-nav">' . _x( '', 'Previous post link', 'unleaded' ) . '</span> %title' ); ?>
			</li>
			<li>
				<?php next_post_link( '<div class="nav-next button">%link <i class="icon-right"></i></div>', '%title <span class="meta-nav">' . _x( '', 'Next post link', 'unleaded' ) . '</span>' ); ?>
			</li>
		</ul>
	</div>
	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
	<div class="twelve columns">
		<ul class="button-group">
			<li>
				<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous button"><?php next_posts_link( __( '<i class="icon-left"></i> Older posts', 'unleaded' ) ); ?></div>
				<?php endif; ?>
			</li>
			<li>
			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next button"><?php previous_posts_link( __( 'Newer posts <i class="icon-right"></i>', 'unleaded' ) ); ?></div>
			<?php endif; ?>
			</li>
		</ul>
	</div>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}




///
/// Show thumbnails in the admin area...
add_filter('manage_posts_columns', 'posts_columns', 5);
add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);
function posts_columns($defaults){
    $defaults['riv_post_thumbs'] = __('Thumbs');
    return $defaults;
}
function posts_custom_columns($column_name, $id){
        if($column_name === 'riv_post_thumbs'){
        echo the_post_thumbnail( array(300,100) );
    }
}






// // Call Googles HTML5 Shim, but only for users on old versions of IE
function wpfme_IEhtml5_shim () {
global $is_IE;
 	if ($is_IE)
 	echo '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
}
add_action('wp_head', 'wpfme_IEhtml5_shim');












?>