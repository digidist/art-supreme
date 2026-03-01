<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
					<?php
						the_archive_title( '<h1>', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>                       
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

		<?php if( is_category() ){ ?> <ul class="ws-journal-nav col-sm-12"> <?php wp_list_categories('title_li=&hierarchical=1&depth=1&number=8'); ?> </ul> <?php } ?>
		
		<!-- Blog Container -->
		<div class="ws-journal-container">
			<div class="<?php echo esc_attr($page_class); ?>">
				<div class="row">

					<?php if ( have_posts() ) : ?>

						<?php global $woss_count; $woss_count = 0;  ?>
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); $woss_count++; ?>

							<?php

								/*
								 * Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', get_post_format() );
								
								if(0 == $woss_count%3){	
									echo '<div class="clearfix"></div>'; 
								}
							?>

						<?php endwhile; ?>

							<?php $woss_blog_pagination=woss_theme_data('blog_pagination'); 
							if($woss_blog_pagination == 'style1' && function_exists('woss_load_more_blog_button') ){
								woss_load_more_blog_button();
							}
							else{
								?>
								<!-- Pagination -->
								<div class="ws-journal-pagination col-sm-12">
									<?php the_posts_navigation( array(
											'prev_text'          => wp_kses_post(__('<span>&#8592; Older posts</span>', 'artday')),
											'next_text'          => wp_kses_post(__('<span>Newer posts &#8594;</span>', 'artday')),
										) ); ?>
								</div>
								<?php
							} 
						?>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content', 'none' ); ?>

					<?php endif; ?>

				</div>
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
