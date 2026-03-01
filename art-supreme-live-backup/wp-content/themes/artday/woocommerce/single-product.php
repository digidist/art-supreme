<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$pageID = get_option('woocommerce_shop_page_id');
$woss_shop_layout=woss_theme_data('shop_layout');


$woss_page_class = '';
if($woss_shop_layout == "1"){
    $woss_page_class="col-sm-12";
}else{
    $woss_page_class="col-sm-9";
}
if($woss_shop_layout == '2'){
    $woss_page_class .=' pull-right';
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<div class="ws-journal-container">
			<div class="<?php echo esc_attr($woss_page_class); ?>">
				
			<?php while ( have_posts() ) : the_post(); ?>

				<?php wc_get_template_part( 'content', 'single-product' ); ?>

			<?php endwhile; // end of the loop. ?>
			
			</div>
		</div>
		
	<?php
		if( $woss_shop_layout !== '1'){
			/**
			 * woocommerce_sidebar hook
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			do_action( 'woocommerce_sidebar' );
		}
	?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	

<?php get_footer( 'shop' ); ?>
