<?php
/**
 * Artday functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Artday
 */

if ( ! function_exists( 'artday_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function artday_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Artday, use a find and replace
	 * to change 'artday' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'artday', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

    /*
     * WooCommerce support
     */
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	
	// Selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'arrivals-thumb', 475, 530, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'left_nav' => esc_html__( 'Left section( style 1 and 3)', 'artday' ),
		'right_nav' => esc_html__( 'Right section( style 1 and 3)', 'artday' ),
		'primary' => esc_html__( 'Navigation inline( style 2)', 'artday' ),
		'onepage' => esc_html__( 'One Page Navigation', 'artday' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'artday_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     *
     * Priority 0 to make it available to lower priority callbacks.
     *
     * @global int $content_width
     */
    $GLOBALS['content_width'] = apply_filters( 'artday_content_width', 640 );
}
endif; // artday_setup
add_action( 'after_setup_theme', 'artday_setup' );

/**
 * Extend VC
 */
 if(class_exists('Vc_Manager')) {
    function woss_extend_composer() {
		require_once( get_template_directory() . '/wpbakery/vc-extend.php' );
    }
    add_action('init', 'woss_extend_composer', 20);
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function artday_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'artday' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__('Add widgets here to appear in your blog sidebar.', 'artday' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'artday' ),
		'id'            => 'sidebar-shop',
		'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'artday' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Bottom of the page', 'artday' ),
		'id'            => 'page_bottom',
		'description'   => esc_html__('Add widgets here to appear at bottom of the page.', 'artday' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	// Footer widgets
	register_sidebar(array(
		'name'          => esc_html__('Footer 1-st Widget Area', 'artday' ),
		'id'            => 'footer_1st_col',
		'description'   => esc_html__('Add widgets for 1-st widget area', 'artday' ),
		'before_widget' => '<div id="%1$s" class="bar widget-space %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="sidebar-title"><h3>',
		'after_title'   => '</h3></div><div class="ws-footer-separator"></div>',
	));
	register_sidebar(array(
		'name'          => esc_html__('Footer 2-nd Widget Area', 'artday' ),
		'id'            => 'footer_2nd_col',
		'description'   => esc_html__('Add widgets for 2-nd widget area', 'artday' ),
		'before_widget' => '<div id="%1$s" class="bar widget-space %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="sidebar-title"><h3>',
		'after_title'   => '</h3></div><div class="ws-footer-separator"></div>',
	));
	register_sidebar(array(
		'name'          => esc_html__('Footer 3-rd Widget Area', 'artday' ),
		'id'            => 'footer_3rd_col',
		'description'   => esc_html__('Add widgets for 3-rd widget area', 'artday' ),
		'before_widget' => '<div id="%1$s" class="bar widget-space %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="sidebar-title"><h3>',
		'after_title'   => '</h3></div><div class="ws-footer-separator"></div>',
	));
	register_sidebar(array(
		'name'          => esc_html__('Footer 4-th Widget Area', 'artday' ),
		'id'            => 'footer_4th_col',
		'description'   => esc_html__('Add widgets for 4-th widget area', 'artday' ),
		'before_widget' => '<div id="%1$s" class="bar widget-space %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="sidebar-title"><h3>',
		'after_title'   => '</h3></div><div class="ws-footer-separator"></div>',
	));
}
add_action( 'widgets_init', 'artday_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function artday_scripts() {
	
	// Enqueue Bootstrap
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/plugins/bootstrap/css/bootstrap.min.css');
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/plugins/bootstrap/js/bootstrap.min.js', array('jquery'),'', true);
	
	// Enqueue Font-Awesome
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/plugins/font-awesome/css/font-awesome.min.css');
	
	if( is_single() ){
		wp_enqueue_script('postshare', get_template_directory_uri() . '/assets/js/postshare.js', array('jquery'),'', true);
	}
	wp_enqueue_script('parallax', get_template_directory_uri() . '/assets/plugins/parallax.min.js', array('jquery'),'', true);
	wp_enqueue_script('scrollreveal', get_template_directory_uri() . '/assets/js/plugins/scrollReveal.min.js', array('jquery'),'', true);
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Owl Carousel
	wp_enqueue_style('owl', get_template_directory_uri() . '/assets/js/plugins/owl-carousel/owl.carousel.css');   
	wp_enqueue_script('owl', get_template_directory_uri() . '/assets/js/plugins/owl-carousel/owl.carousel.min.js', array('jquery'),'', true);
	
	wp_enqueue_script('dropdownhover', get_template_directory_uri() . '/assets/js/plugins/bootstrap-dropdownhover.min.js', array('jquery'),'', true);
	wp_enqueue_script('sticky', get_template_directory_uri() . '/assets/js/plugins/jquery.sticky.js', array('jquery'),'', true);
	
	// One page
	if (is_page_template('template-onepage.php')) {
		wp_register_script('easing', get_template_directory_uri() . '/assets/js/plugins/jquery.easing.min.js', array('jquery'),'', true); 
	}
	
	wp_enqueue_script('artday-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'),'', true);
	
	wp_enqueue_style( 'artday-style', get_stylesheet_uri() );
	
	// Enqueue dynamic styles
	wp_enqueue_style( 'artday-dynamic', get_template_directory_uri().'/assets/css/dynamic.php');
	
	// Localize script
	$translation_array = array( 'woss_template_directory_uri' => get_template_directory_uri());
    wp_localize_script( 'jquery', 'woss_data', $translation_array );
	
}
add_action( 'wp_enqueue_scripts', 'artday_scripts' );

// Enqueue admin styles
function artday_admin_styles() {
    wp_enqueue_style('artday-admin', get_template_directory_uri() . '/inc/theme-options/admin.css');
}
add_action( 'admin_enqueue_scripts',  'artday_admin_styles' );

// Enqueue fonts
function artday_fonts_styles_url() {
    $fonts_url = '';
 
    /* Translators: If there are characters in your language that are not
    * supported by Lora, translate this to 'off'. Do not translate
    * into your own language.
    */
	$merriweather = esc_html_x( 'on', 'Merriweather font: on or off', 'artday' );
	$lato = esc_html_x( 'on', 'Lato font: on or off', 'artday' );
 
    /* Translators: If there are characters in your language that are not
    * supported by Open Sans, translate this to 'off'. Do not translate
    * into your own language.
    */
 
    if ( 'off' !== $merriweather || 'off' !== $lato ) {
        $font_families = array();
 
		if ( 'off' !== $merriweather ) {
            $font_families[] = 'PT Serif';
        }
		
		if ( 'off' !== $lato ) {
            $font_families[] = 'Montserrat';
        }
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}
function artday_fonts_styles() {
    wp_enqueue_style( 'artday-fonts', artday_fonts_styles_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'artday_fonts_styles' );

/**
 * Theme includes
 */
if ( file_exists( get_template_directory() . '/inc/theme-options/config.php' ) ) {
    require_once( get_template_directory() . '/inc/theme-options/config.php' );
}

require_once( get_template_directory() . '/inc/plugins-config.php' ); 	                // Plugins Manager
require_once(get_template_directory() . '/inc/theme-options/metaboxes.php' );      		// Metaboxes

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Navigation bootstrap
 */
require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
 * WooCommerce setup
 */
if (class_exists('Woocommerce')) {
	require_once( get_template_directory() . '/woocommerce/config.php');
}

/**
 * Theme Options page data helper function
 */
if(!function_exists('woss_theme_data'))
{
    function woss_theme_data($key,$default=false)
    {
        global $woss_theme_data;
        if(isset($woss_theme_data[$key]))
        {
            return $woss_theme_data[$key];
        }else{
            return $default;
        }
    }
}

if(!function_exists('woss_theme_data_media')){
    function woss_theme_data_media($key,$default=false)
    {
        global $woss_theme_data;
        if(isset($woss_theme_data[$key]['url']))
        {
            return $woss_theme_data[$key]['url'];
        }else{
            return $default;
        }
    }
}

/**
 * Custom comments list
 */
if(!function_exists('woss_mytheme_comment')){
function woss_mytheme_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class('media blog-comments-item'); ?> id="li-comment-<?php comment_ID() ?>">
    <div id="comment-<?php comment_ID(); ?>" class="thecomment">
        <div class="ws-comment-author media-left">
            <?php echo get_avatar($comment, 75); ?>
        </div>
        <div class="media-body ws-comments-body">

            <h4 class="ws-comment-name media-heading clearfix">
                <?php printf(esc_html__('%s', "artday"), get_comment_author_link()) ?>
            </h4>            

            <?php if ($comment->comment_approved == '0') : ?>
                <em><?php esc_html_e('Your comment is awaiting moderation.', "artday") ?></em>
                <br />
            <?php endif; ?>

            <div class="ws-comment-meta">
                <?php edit_comment_link( esc_attr__('(Edit)', "artday"),'  ','') ?>
            </div>           
        </div>
        <div class="ws-comment-content">
        	<?php comment_text() ?>
        </div>
        <div class="clearfix">
            <span class="ws-comment-date">
                <?php printf( esc_attr__('%1$s', "artday"), get_comment_date(),  get_comment_time()) ?>
            </span>
			<span class="ws-comment-reply">
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</span>
        </div>
    </div>
<?php
}
}
// Can't use get_template_directory link to CODEX https://codex.wordpress.org/Function_Reference/is_plugin_active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
/**
 * Shop Pagination
 */
if (is_plugin_active('woss-shortcodes/woss-shortcodes.php')) {
	$woss_shop_pagination=woss_theme_data('shop_pagination');
	if($woss_shop_pagination == 'style1'){
		$woocommerce_woss = new WossInfiniteScrollWoocommerce();
		$woocommerce_woss -> set_number_of_product_items_per_page();
	}
}

/**
 * Custom Form Classes for Contact Form 7 Plugin
 */
add_filter( 'wpcf7_form_class_attr', 'woss_custom_form_class' );
function woss_custom_form_class( $class ) {
	$class .= ' form-horizontal ws-contact-form';
	return $class;
}

/**
 * Function for limit content
 */
function woss_string_limit_words($string, $word_limit){
	$words = explode(' ', $string, ($word_limit + 1));
	
	if(count($words) > $word_limit) {
		array_pop($words);
	}
	
	return implode(' ', $words);
}

/**
 * Blog load more button
 */
function true_load_posts(){
	$args = unserialize(stripslashes($_POST['query']));
	$args['paged'] = $_POST['page'] + 1; // Next page
	$args['post_status'] = 'publish';
	$q = new WP_Query($args);
	if( $q->have_posts() ):
		while($q->have_posts()): $q->the_post();
			get_template_part( 'template-parts/content', get_post_format() );
			$woss_blog_style=woss_theme_data('blog_style');
					
			if( isset($GLOBALS['woss_count'] ) && !is_archive() ){ 
				$woss_count = $GLOBALS['woss_count'];
			}else{
				$woss_count = 3;
			}
	
			if($woss_blog_style == 'style1' && 0 == $woss_count % 2){
				echo '<div class="clearfix"></div>'; 
			}
			
			if($woss_blog_style == 'style3'){
				$woss_count_col = $woss_count -2; 
				if( $woss_count%3){	
					echo '<div class="clearfix"></div>';
				}
			}
		endwhile;
	endif;
	wp_reset_postdata();
	die();
}
add_action('wp_ajax_loadmore', 'true_load_posts');
add_action('wp_ajax_nopriv_loadmore', 'true_load_posts');

/**
 * Tracking code from THEME OPTIONS page
 */
if ( function_exists('woss_footer_tracking_code') ) {
	add_action('wp_footer', 'woss_footer_tracking_code');
}

function woss_footer_tracking_code() {
	$woss_footer_tracking_code=woss_theme_data('footer_tracking_code');
	if($woss_footer_tracking_code) {
		echo $woss_footer_tracking_code;
	}
}
?>