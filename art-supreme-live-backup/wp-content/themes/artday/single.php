<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Artday
 */

$blog_layout=woss_theme_data('blog_layout');

$page_class = '';
if($blog_layout == "1"){
    $page_class="col-lg-12 col-md-12";
}else{
    $page_class="col-sm-9";
}
if($blog_layout == '2'){
    $page_class .=' pull-right';
}
get_header(); ?>
<!-- Container Start -->
<div class="container ws-page-container">
	<div class="row">

		<!-- Blog Single Container -->
		<div class="ws-journal-single">
			<div class="<?php echo esc_attr($page_class); ?>">						
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'single' ); ?>

					<?php the_posts_navigation( array(
							'prev_text' => wp_kses_post(__('<span>&#8592; Older posts</span>', 'artday')),
							'next_text' => wp_kses_post(__('<span>Newer posts &#8594;</span>', 'artday')),
						) ); ?>

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
