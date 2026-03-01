<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Artday
 */

$blog_layout=woss_theme_data('blog_layout');

$page_class = '';
if($blog_layout == "1"){
    $page_class="col-sm-12";
}else{
    $page_class="col-sm-9";
}
if($blog_layout == '2'){
    $page_class .=' pull-right';
}

get_header(); ?>

<!-- Container Start -->
<div class="container ws-page-container">

	<!-- Row Start -->
	<div class="row">

		<!-- Blog Container -->
		<div class="ws-journal-container">
			<div class="<?php echo esc_attr($page_class); ?>">				
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'page' ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>

				<?php endwhile; // End of the loop. ?>					
			</div>
		</div>

		<?php 
			if( $blog_layout != '1' ){
				get_sidebar(); 
			}
		?>
		
	</div><!-- Row End -->
</div><!-- Container End -->

<?php get_footer(); ?>