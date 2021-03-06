<?php
	// define template url constant
	define("template_url", get_template_directory_uri());
	define("watergun_url", get_bloginfo("url"));

	//deactivate WordPress function
	remove_shortcode('gallery', 'gallery_shortcode');
	add_theme_support( 'post-thumbnails', array('post', 'watergunner', 'project') );  
    set_post_thumbnail_size( 220, 118, true ); // Normal post thumbnails
    add_image_size( 'featured', 955, 450, true ); // Full size screen
    add_image_size( 'gallery', 466, 262, true ); // sidebar gallery size

	/*  Show the post gallery just if it exists  */
	function contains_attachments( $post_id ) {
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $post_id
		);

		$attachments = get_posts( $args );

		return $attachments;

	}

	//remove blog class from the first page
	function remove_blog_from_cpt_classes($classes, $class){
		global $post;
		if (is_home()){
			foreach($classes as &$str){
				if(strpos($str, "blog") > -1){
					$str = "";
				}
			}
		}
		return $classes;
	}
//	add_filter("body_class", "remove_blog_from_cpt_classes", 10, 2);


	//Helper function for creating new post types
	function get_previous_content($content, $id) {
        $custom = get_post_custom($id);
		
		if (is_object($custom) || is_array($custom) || isset($custom)) {
			if (isset($custom[$content][0])) {
				$previous = $custom[$content][0];
			} else {
				$previous = '';
			}
		} else { $previous = ''; }

		return $previous;

	}


	//create post type: Projects
	get_template_part("internal/register_projects");

	//create post type: Watergunners
	get_template_part("internal/register_watergunners");

	//create back-end for home page
	get_template_part("internal/home_meta");

	//create back-end for about page
	get_template_part("internal/about_meta");

	//create back-end for about page
	get_template_part("internal/contact_meta");

	//improved get adjacent posts query
	get_template_part("internal/adjacent_posts");
/**
 * Load javascripts used by the theme
 */
	
function custom_theme_js(){
	wp_register_script( 'infinite_scroll',  template_url . '/js/plugins/infinitescroll.js', array('jquery'),null,true );
	wp_enqueue_script('infinite_scroll');
}
add_action('wp_enqueue_scripts', 'custom_theme_js');


/**
 * Infinite Scroll
 */
function custom_infinite_scroll_js() {
     ?>
    <script>
	
    var infinite_scroll = {
        loading: {
            img: "<?php echo template_url; ?>/imgs/loading.gif",
			msgText: "<?php _e( '', 'custom' ); ?>",
			selector: '.infinite',
            finishedMsg: "<?php _e( '', 'custom' ); ?>"
        },
        "nextSelector":".next a",
        "navSelector":".navigation",
        "itemSelector":"li.small-work",
        "contentSelector":".infinite ul"
    };
	jQuery( infinite_scroll.contentSelector ).infinitescroll( infinite_scroll );

    </script>
    <?php
}
add_action( 'wp_footer', 'custom_infinite_scroll_js',100 );


/**
 * If we go beyond the last page and request a page that doesn't exist,
 * force WordPress to return a 404.
 * See http://core.trac.wordpress.org/ticket/15770
 */
function custom_paged_404_fix( ) {
    global $wp_query;
    if ( is_404() || !is_paged() || 0 != count( $wp_query->posts ) )
        return;
    $wp_query->set_404();
    status_header( 404 );
    nocache_headers();
}
add_action( 'wp', 'custom_paged_404_fix' );


?>
