<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Artday
 */

get_header(); ?>

<!-- Container Start -->
<div class="container ws-page-container">
	<div class="col-sm-8 col-sm-offset-2">			
		<section class="error-404 not-found text-center">
			<header>
				<h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'artday' ); ?></h1>
				<div class="ws-separator"></div>
			</header>
			
			<p><?php esc_html_e( 'Ok something obviously went wrong here. Try using the navigation at the top to find something better than this page or check out our most featured products below.', 'artday' ); ?></p>										
		</section>
	</div>

	<div class="ws-heading text-center">
		<h2><?php echo esc_attr_e('Featured products', 'artday'); ?></h2>			
	</div>
	<?php echo do_shortcode('[featured_products per_page="3"]'); ?>	
</div><!--Container -->

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("page_bottom") ) : ?>
<?php endif; ?>


<?php get_footer(); ?>
