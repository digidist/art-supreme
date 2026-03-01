<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Artday
 */

 
$blog_layout=woss_theme_data('blog_layout');
$woss_parallax_header=woss_theme_data('blog_parallax_header');

$page_class = '';
if($blog_layout == "1"){
    $page_class="col-lg-12 col-md-12";
}else{
    $page_class="col-sm-9";
}
if($blog_layout == '2'){
    $page_class .=' pull-right';
}

get_header();

if($woss_parallax_header['url']): ?>
	<!-- Page Parallax Header -->
	<div class="ws-parallax-header parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url($woss_parallax_header['url']) ?>">        
		<div class="ws-overlay">            
			<div class="ws-parallax-caption">                
				<div class="ws-parallax-holder">
					<h1><?php printf( esc_html__( 'Search Results for: %s', 'artday' ), '<span>' . get_search_query() . '</span>' ); ?></h1>                   
				</div>
			</div>
		</div>            
	</div>            
	<!-- End Page Parallax Header -->
<?php endif; ?>

<!-- Container Start -->
<div class="container ws-page-container">
	<!-- Row Start -->
	<div class="row">

		<!-- Blog Container -->
		<div class="ws-journal-container">
			<div class="<?php echo esc_attr($page_class); ?>">	

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );
						?>

					<?php endwhile; ?>

					<!-- Pagination -->
					<div class="ws-journal-pagination">
						<?php the_posts_navigation( array(
								'prev_text'  => wp_kses_post(__('<span>&#8592; Older posts</span>', 'artday')),
								'next_text'  => wp_kses_post(__('<span>Newer posts &#8594;</span>', 'artday')),
							) ); ?>
					</div>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>

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
