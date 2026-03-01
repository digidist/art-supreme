<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Artday
 */

$woss_blog_layout=woss_theme_data('blog_layout');
$woss_parallax_header=woss_theme_data('blog_parallax_header');
$woss_head_text=woss_theme_data('blog_head_text');

$woss_page_class = '';
if($woss_blog_layout == "1"){
    $woss_page_class="col-sm-12";
}else{
    $woss_page_class="col-sm-9";
}
if($woss_blog_layout == '2'){
    $woss_page_class .=' pull-right';
}

get_header();

if($woss_parallax_header['url']): ?>
	<!-- Page Parallax Header -->
	<div class="ws-parallax-header parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url($woss_parallax_header['url']) ?>">        
		<div class="ws-overlay">            
			<div class="ws-parallax-caption">                
				<div class="ws-parallax-holder">
					<h1><?php echo esc_html($woss_head_text); ?></h1>                        
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
		
			<ul class="ws-journal-nav col-sm-12"> <?php wp_list_categories('title_li=&hierarchical=1&depth=1&number=8'); ?> </ul>
				
			<div class="<?php echo esc_attr($woss_page_class); ?>">	
				<div class="row">
					<?php if ( have_posts() ) : ?>
						
						<?php if ( is_home() && ! is_front_page() ) : ?>
							<header>
								<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
							</header>
						<?php endif; ?>

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

								$woss_blog_style=woss_theme_data('blog_style');
								
								if($woss_blog_style == 'style1' && 0 == $woss_count % 2){
									echo '<div class="clearfix"></div>'; 
								}
								
								if($woss_blog_style == 'style3'){
									$woss_count_col = $woss_count -2; 
									if(0 == $woss_count_col%3){	
										echo '<div class="clearfix"></div>'; 
									}
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
											'prev_text' => wp_kses_post(__('<span>&#8592; Older posts</span>', 'artday')),
											'next_text' => wp_kses_post(__('<span>Newer posts &#8594;</span>', 'artday')),
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
			if( $woss_blog_layout != '1' ){
				get_sidebar(); 
			}
		?>
		
	</div><!-- Row End -->
</div><!-- Container End -->

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("page_bottom") ) : ?>
<?php endif; ?>

<?php get_footer(); ?>