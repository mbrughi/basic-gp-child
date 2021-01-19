<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

function generatepress_child_enqueue_scripts() {
	if ( is_rtl() ) {
		wp_enqueue_style( 'generatepress-rtl', trailingslashit( get_template_directory_uri() ) . 'rtl.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 100 );


/*
 * INIT of custom definitions
 *
 */

/* add font awesome */
add_action( 'wp_enqueue_scripts', 'enqueue_load_fa' );
function enqueue_load_fa() {
	wp_enqueue_style( 'font-awesome-5', '//pro.fontawesome.com/releases/v5.10.0/css/all.css', array(), null );
}
//
function add_font_awesome_5_cdn_attributes( $html, $handle ) {
    if ( 'font-awesome-5' === $handle ) {
        return str_replace( "media='all'", "media='all' integrity='sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p' crossorigin='anonymous'", $html );
    }
    return $html;
}
add_filter( 'style_loader_tag', 'add_font_awesome_5_cdn_attributes', 10, 2 );
/* end fontawesome */

// theme copyright
add_filter( 'generate_copyright','mb_custom_copyright' );
function mb_custom_copyright() {
    echo '&copy; '.date('Y').' Il mio sito web - P.IVA 000000000';
}

/*
 * Remove Gutenberg welcome popup
 * @link       https://marcobrughi.com
 *
 * @author     Marco Brughi <marco.brughi@mail.com>
 *
 */
function mb_enqueue_admin_script() {
	if ( is_admin() ) {
		wp_add_inline_script(
			'wp-edit-post',
			'wp.data.select( "core/edit-post" ).isFeatureActive( "welcomeGuide" ) && wp.data.dispatch( "core/edit-post" ).toggleFeature( "welcomeGuide" );'
		);
	}
}
add_action( 'admin_enqueue_scripts', 'mb_enqueue_admin_script' );

/*
 * Insert Add to top button
 *
 */
add_action( 'wp_enqueue_scripts', 'enqueue_add_to_top' );
function enqueue_add_to_top() {
	wp_enqueue_script( 'add-to-top', get_stylesheet_directory_uri() . '/js/btot.js', array(), '1.0.0' ,true );
}
add_action( 'generate_before_footer','mb_add_to_top' );  
function mb_add_to_top() { ?> 
	<a class="top-link hide" href="" id="js-top">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 6"><path d="M12 6H0l6-6z"/></svg>
  <span class="screen-reader-text">Back to top</span>
</a>
<?php }


/*
 * Insert Google Analytics Code
 * 99 to right before </head> tag
 */
 function hook_javascript() {
    ?>
        <script>
            <!-- Google Tag Manager -->

			<!-- End Google Tag Manager -->
        </script>
    <?php
}
add_action('wp_head', 'hook_javascript', 99);
// Add Google Tag code which is supposed to be placed after opening body tag.
add_action( 'wp_body_open', 'mb_add_custom_body_open_code' );
function mb_add_custom_body_open_code() {
    echo '<!-- Google Tag Manager (noscript) --> 
			 <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXX"
			 height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->
	'.PHP_EOL;
}

/*
 * Images webp format
 * 
 */
//** *Enable upload for webp image files.*/
function mb_webp_upload($addmimetype) {
    $addmimetype['webp'] = 'image/webp';
    return $addmimetype;
}
add_filter('mime_types', 'mb_webp_upload');
//** * Enable preview / thumbnail for webp image files.*/
function display_webp($result, $path) {
    if ($result === false) {
        $webp_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );

        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $webp_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }
}


/*
 * 404 page width no sidebar
 *
 */
add_filter( 'generate_sidebar_layout','generate_custom_search_sidebar_layout' );
function generate_custom_search_sidebar_layout( $layout )
{
 	if ( is_404() )
 	 	return 'no-sidebar';
 	return $layout;

 } 
