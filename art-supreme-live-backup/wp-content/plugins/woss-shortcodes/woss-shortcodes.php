<?php
/*
Plugin Name: Artday Shortcodes
Plugin URI: http://themeforest.net/user/WossThemes
Description: Shortcodes for WossThemes - ARTDAY Version
Version: 1.2.4
Author: WossThemes
Author URI: http://themeforest.net/user/WossThemes
*/

/*-----------------------------------------------------------------------------------*/
/*	Shortcodes for ARTDAY
/*-----------------------------------------------------------------------------------*/

// Theme Widgets
require_once ('includes/woss-widgets.php');

function artday_demo_importer(){
    if ( is_plugin_active( 'woss-shortcodes/woss-shortcodes.php' ) ) {
        $demo_importer=woss_theme_data('demo_importer', false);
        if($demo_importer == true) {
            require dirname(__FILE__).  '/importer/init.php';
        }
    }
}
add_action( 'init', 'artday_demo_importer' );


class WossInfiniteScrollWoocommerce {

	public $url = ''; // URL of plugin installation
	public $path = ''; // Path of plugin installation
	public $file = ''; // Path of this file
    public $settings; // Settings object


	//settings variables
	public $number_of_products 			= "";
	public $icon 						= "";
	public $ajax_method		 			= "load_more_button";//Prefered ajax method -- Infinite scroll | Load More | Simple
	public $load_more_button_animate 	= "";//checkbox on - off
	public $load_more_button_transision = "";//animation type
	public $wrapper_result_count		= "";//wrapper for pagination
	public $wrapper_breadcrumb  		= "";//wrapper for pagination
	public $wrapper_products 			= "";//wrapper for products
	public $wrapper_pagination			= "";//wrapper for pagination
	public $selector_next				= "";//selector next
	public $load_more_button_text		= "";//text of load more button
	public $animate_to_top				= "";//animate to top on/off
	public $pixels_from_top				= "";//pixels from top number
	public $start_loading_x_from_end	= "";


	function __construct() {

        $this->file = __file__;
        $this->path = dirname($this->file) . '/';
        $this->url = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__file__)) . '/';


		require_once ($this->path . 'include/php/settings.php');

		$this->settings = new InfiniteWoocommerceScrollSettings($this->file);

		$this->number_of_products = get_option('woss_number_of_products', '8');

		$preloader= wp_get_attachment_thumb_url(get_option('woss_preloader_icon'))==""?$this->url."include/icons/ajax-loader.gif":wp_get_attachment_thumb_url(get_option('woss_preloader_icon'));

		$this->icon 						= $preloader;
		$this->ajax_method					= 'method_load_more_button';

		$this->wrapper_result_count 		= get_option('woss_wrapper_result_count')==""?".woocommerce-result-count":get_option('woss_wrapper_result_count');
		$this->wrapper_breadcrumb	 		= get_option('woss_wrapper_breadcrumb')==""?".woocommerce-breadcrumb":get_option('woss_wrapper_breadcrumb');

		$this->wrapper_products 			= get_option('woss_products_selector')==""?"ul.products":get_option('woss_products_selector');
		$this->wrapper_pagination 			= get_option('woss_pagination_selector')==""?".pagination, .woo-pagination, .woocommerce-pagination, .emm-paginate, .wp-pagenavi, .pagination-wrapper":get_option('woss_pagination_selector');

		$this->selector_next 				= get_option('woss_next_page_selector')==""?".next":get_option('woss_next_page_selector');
		$this->load_more_button_animate 	= get_option('woss_load_more_button_animate');
		$this->load_more_button_transision  = get_option('woss_animation_method_load_more_button');

		$this->animate_to_top				= get_option('woss_animate_to_top');
		$this->pixels_from_top				= get_option('woss_pixels_from_top')==""?"0":get_option('woss_pixels_from_top');

		$this->start_loading_x_from_end		= get_option('woss_start_loading_x_from_end')==""?"0":get_option('woss_start_loading_x_from_end');


		add_action('woocommerce_before_shop_loop', array($this, 'before_products'), 3);

		// Wrap shop pagination
		add_action('woocommerce_pagination', array($this, 'before_pagination'), 3);
		add_action('woocommerce_pagination', array($this, 'after_pagination'), 40);
		//add_action('plugins_loaded', array($this,'configLang'));

		// Register frontend scripts and styles
		add_action('wp_enqueue_scripts', array($this,'register_frontend_assets'));
		add_action('wp_enqueue_scripts', array($this, 'load_frontend_assets'));
		add_action('wp_enqueue_scripts', array($this, 'localize_frontend_script_config'));

    }

	public function register_frontend_assets() {
        // Add frontend assets in footer
        wp_register_script('custom-isw', $this->url . 'include/js/custom.js', array('jquery'), false, true);
		//wp_register_style('ias-frontend-style', $this->url . 'include/css/style.css');
    }

	public function load_frontend_assets() {
		//load all scripts
        wp_enqueue_script( 'custom-isw' );
		wp_enqueue_style( 'ias-animate-css' );
		//wp_enqueue_style( 'ias-frontend-style' );
		wp_enqueue_style( 'ias-frontend-custom-style' );
    }

	public function localize_frontend_script_config() {

        $handle = 'custom-isw';
        $object_name = 'options_isw';
	    $error = __('There was a problem.Please try again.', "infinite-scroll-woocommerce");
		$this->load_more_button_text		= get_option('woss_button_text')==""?__("Load More", "infinite-scroll-woocommerce"):get_option('woss_button_text');

        $l10n = array(
			'error' 						=>	$error,
			'ajax_method'					=>  $this->ajax_method,
            'number_of_products' 			=>	$this->number_of_products,
			'wrapper_result_count'	 		=>	$this->wrapper_result_count,
			'wrapper_breadcrumb'	 		=>	$this->wrapper_breadcrumb,
			'wrapper_products'	 			=>	$this->wrapper_products,
			'wrapper_pagination'	 		=>	$this->wrapper_pagination,
			'selector_next'	 				=>	$this->selector_next,
			'icon' 							=>	$this->icon,
			'load_more_button_text' 		=>	$this->load_more_button_text,
			'load_more_button_animate'		=>  $this->load_more_button_animate,
			'load_more_transition'			=>  $this->load_more_button_transision,
			'animate_to_top'				=>  $this->animate_to_top,
			'pixels_from_top'				=>  $this-> pixels_from_top,
			'start_loading_x_from_end'		=>  $this-> start_loading_x_from_end,
            'paged' 						=> (get_query_var('paged')) ? get_query_var('paged') : 1
        );

        wp_localize_script($handle, $object_name, $l10n);

    }

	public function before_products() {

		if ($this->ajax_method!='method_simple_ajax_pagination'){
			//remove Result Count
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}

    }

    public function after_products() {
        $html = '</div>';
		echo $html;
    }

	public function before_pagination($template_name = '', $template_path = '', $located = '') {
        echo '<div class="woss-shop-pagination-wrapper">';
    }

    public function after_pagination($template_name = '', $template_path = '', $located = '') {
        echo '</div>';
    }

	public function set_number_of_product_items_per_page(){
	    //Replased with php 7.2 support
		//add_filter( 'loop_shop_per_page', create_function( '$cols', "return $this->number_of_products;" ), $this->number_of_products );
        add_filter( 'loop_shop_per_page', function( $cols){ return $this->number_of_products;} );
	}

}

// Load more button on blog page
if(!function_exists('woss_load_more_blog_button')){
function woss_load_more_blog_button(){
	global $wp_query;
	if (  $wp_query->max_num_pages > 1 ) :

		wp_enqueue_script( 'artday_loadmore', get_template_directory_uri() . '/assets/js/loadmore.js', array('jquery') );	?>
		<script>
			var ajaxurl = '<?php echo esc_url(site_url()) ?>/wp-admin/admin-ajax.php';
			var true_posts = '<?php echo serialize($wp_query->query_vars); ?>';
			var current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
			var max_pages = '<?php echo $wp_query->max_num_pages; ?>';
		</script>

		<div id="true_loadmore"><?php esc_html_e('Load more', 'artday'); ?></div><div id="woss_blog_preloader"></div>

	<?php endif;
}
}

// Special Heading

function woss_heading($atts, $content=null) {
    extract(shortcode_atts(array(
		"heading" 				=> 'h2',
        "title" 				=> '',
        "woss_text_align"		=> 'text-left'
    ), $atts));

	$output = '';

	if($heading == 'h2'){
		$output .= '<div class="ws-heading '.$woss_text_align.'">';
		$output .= '<h2>'.$title.'</h2>';
		$output .= '<div class="ws-separator"></div>';
		$output .= '</div>';
	}elseif($heading == 'h3'){
		$output .= '<h3 class="ws-heading '.$woss_text_align.'">'.$title.'</h3>';
		$output .= '<div class="ws-separator"></div>';
	}elseif($heading == 'h4'){
		$output .= '<div class="ws-heading '.$woss_text_align.'">';
		$output .= '<h4>'.$title.'</h4>';
		$output .= '</div>';
	}elseif($heading == 'h5'){
		$output .= '<div class="ws-heading '.$woss_text_align.'">';
		$output .= '<h5>'.$title.'</h5>';
		$output .= '</div>';
	}

    return $output;
}
add_shortcode('special_heading', 'woss_heading');

// Intercative Banner

function woss_interactive_banner($atts, $content=null) {
    extract(shortcode_atts(array(
		"attached_image" 		=> '',
        "banner_name" 				=> '',
		"banner_textarea" 				=> '',
		"banner_phone" 				=> '',
        "woss_text_align"		=> 'text-left'
    ), $atts));


$image = wp_get_attachment_image_src($attached_image, array(370, 210));
if(isset($image[0]) and $image[0]){
	$image = '<img src="'.$image[0].'" alt/>';
} else {
	$image = '<img src="http://placehold.it/370x210" width="370" height="210" alt="placeholder370x210">';
}

$phone_custom = '';
if($banner_phone != ''){
	$phone_custom = '<abbr title="Phone">P:</abbr> '.$banner_phone.'';
}

$output = '	<div class="ws-contact-offices text-center">
				<div class="ws-contact-office-item" data-sr="wait 0.1s, ease-in 20px">
					<div class="thumbnail">
						'.$image.'
						<div class="ws-overlay">
							<div class="caption">
								<strong>'.$banner_name.'</strong>
								<div class="ws-contact-separator"></div>
								<address>
									'.$banner_textarea.'
									'.$phone_custom.'
								</address>
							</div>
						</div>
					</div>
				</div>
			</div>';

    return $output;
}
add_shortcode('interactive_banner', 'woss_interactive_banner');

// Team Member

function woss_team_member($atts, $content=null) {
    extract(shortcode_atts(array(
		"team_name" => 'John Doe',
        "team_description" => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim.',
        "team_position" => '',
        "team_img_src" => '',
    ), $atts));


	$image = wp_get_attachment_image_src($team_img_src, array(570, 570));
    if(isset($image[0]) and $image[0]){
        $image = '<img src="'.$image[0].'" alt="'.$team_name.'" class="img-responsive" />';
    } else {
        $image = '<img src="http://placehold.it/570x570" width="570" height="570" alt="placeholder570x570">';
    }

$output = '	<!-- Team Members -->
			<div class="ws-about-team">
				<div class="ws-about-team-item text-center" data-sr="wait 0.1s, ease-in 20px">
					'.$image.'
					<div class="caption">
						<h3>'.$team_name.'</h3>
						<div class="ws-separator"></div>
						<h5>'.$team_position.'</h5>
						<p>'.$team_description.'</p>
					</div>
				</div>
			</div>';

    return $output;
}
add_shortcode('team_member', 'woss_team_member');

// Process

function woss_process($atts, $content=null) {
    extract(shortcode_atts(array(
		"process_title" => '',
        //"process_description" => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim.',
        "process_img_src" => '',
    ), $atts));


	$image = wp_get_attachment_image_src($process_img_src, array(555, 700));
    if(isset($image[0]) and $image[0]){
        $image = '<img src="'.$image[0].'" alt="'.$process_title.'" class="img-responsive" />';
    } else {
        $image = '<img src="http://placehold.it/555x700" width="555" height="700" alt="placeholder555x700">';
    }

$output = '	<!-- Process -->
			<div class="row vertical-align">
				<div class="col-sm-6" data-sr="wait 0.1s, ease-in 20px">
					<h3 class="ws-heading">'.$process_title.'</h3>
					<div class="ws-footer-separator"></div>
					'.$content.'
				</div>

				<div class="col-sm-6">
					'.$image.'
				</div>
			</div>';

    return $output;
}
add_shortcode('process', 'woss_process');

// Referring Banner

function woss_referring_banner($atts, $content=null) {
    extract(shortcode_atts(array(
		"attached_image" 			=> '',
        "banner_title" 			=> '',
		"banner_url" 			=> '',
    ), $atts));


	$image = wp_get_attachment_image_src($attached_image, array(360, 360));
	if(isset($image[0]) and $image[0]){
		$image = '<img src="'.$image[0].'" alt/>';
	} else {
		$image = '<img src="http://placehold.it/555x640" width="555" height="640" alt="placeholder555x640">';
	}


	$a_href = $a_title = $a_target = '';

	$banner_url = ( $banner_url == '||' ) ? '' : $banner_url;
	$banner_url = vc_build_link( $banner_url );
	$use_link = false;
	if ( strlen( $banner_url['url'] ) > 0 ) {
		$use_link = true;
		$a_href = $banner_url['url'];
		$a_title = $banner_url['title'];
		$a_target = strlen( $banner_url['target'] ) > 0 ? $banner_url['target'] : '_self';
	}

	$output = '	<div class="ws-featured-collections clearfix">
					<!-- Item -->
					<div class="featured-collections-item">
						<a href="'.esc_url($a_href).'" target="'.$a_target.'" title="'.$a_title.'">
							<div class="thumbnail">
								'.$image.'
								<div class="ws-overlay">
									<div class="caption">
										<h3>'.$banner_title.'</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>';

	return $output;
}
add_shortcode('referring_banner', 'woss_referring_banner');

// CTA

function woss_cta($atts, $content=null) {
	extract(shortcode_atts(array(
		"cta_title" => '',
		"cta_description" => '',
		"cta_link" => '',
		"cta_button_text" => 'Show all journal items',
	), $atts));

	$a_href = $a_title = $a_target = '';
	$cta_link = ( $cta_link == '||' ) ? '' : $cta_link;
	$cta_link = vc_build_link( $cta_link );
	$use_link = false;
	if ( strlen( $cta_link['url'] ) > 0 ) {
		$use_link = true;
		$a_href = $cta_link['url'];
		$a_title = $cta_link['title'];
		$a_target = strlen( $cta_link['target'] ) > 0 ? $cta_link['target'] : '_self';
	}


    $output = '';
    $output .= '<section class="ws-call-section">
					<div class="ws-overlay-call">     
						<div class="ws-parallax-caption">
							<div class="ws-parallax-holder">
								<div class="col-sm-6 col-sm-offset-3">
									<h2>'.$cta_title.'</h2>
									<div class="ws-separator"></div>
									<p>'.$cta_description.'</p>
									<div class="ws-call-btn">
										<a href="'.$a_href.'" title="'.$a_title.'" target="'.$a_target.'">'.$cta_button_text.'</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>';

    return $output;

}
add_shortcode('cta', 'woss_cta');

// New Arrivals

function woss_new_arrivals($atts, $content=null) {
	extract(shortcode_atts(array(
		"arrivals_pre_page" => '',
	), $atts));


    echo '<div id="ws-items-carousel">';


			$args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => $arrivals_pre_page, 'orderby' =>'date','order' => 'DESC' );
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post(); global $product;
			//global $post;

			$woss_product_background=woss_theme_data('product_background', false);
			$woss_product_background_class = 'ws-product-empty-bg';
			if($woss_product_background == true){
				$woss_product_background_class = 'ws-product-bg';
			} ?>

				<!-- Item -->
				<div class="ws-works-item" data-sr='wait 0.1s, ease-in 20px'>
					<a id="id-<?php the_id(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<div class="ws-item-offer">
							<!-- Image -->
							<figure class="<?php echo esc_attr($woss_product_background_class); ?>">
								<?php if (has_post_thumbnail( $loop->post->ID )) { echo get_the_post_thumbnail($loop->post->ID, 'arrivals-thumb'); } else { echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" width="65px" height="115px" class="img-responsive" />'; } ?>
							</figure>
						</div>

						<div class="ws-works-caption text-center">
							<?php $woss_alternate_title=get_post_meta(get_the_ID(), '_wscmb_shop_subtitle', true);  ?>
							<!-- Item Category -->
							<div class="ws-item-category"><?php if($woss_alternate_title !== ''){ echo esc_attr($woss_alternate_title); } ?></div>

							<!-- Title -->
							<h3 class="ws-item-title"><?php the_title(); ?></h3>

							<div class="ws-item-separator"></div>

							<!-- Price -->
							<div class="ws-item-price"><?php echo $product->get_price_html(); ?></div>
						</div>
					</a>
				</div>

			<?php endwhile; ?>
			<?php wp_reset_query();

	echo '</div>';

}
add_shortcode('new_arrivals', 'woss_new_arrivals');
?>
