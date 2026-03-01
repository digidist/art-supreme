<?php

/* Template Name: Page with background image */

$woss_alternate_title=get_post_meta(get_the_ID(), '_wscmb_page_alternate_title', true);
if($woss_alternate_title ==''){$woss_alternate_title = $post->post_title;}
$woss_alternate_background=get_post_meta(get_the_ID(), '_wscmb_page_alternate_background', true);

$woss_blog_layout = '';
$woss_blog_layout = get_post_meta( get_the_ID(), '_wscmb_page_layout', true );
if(!$woss_blog_layout || $woss_blog_layout == '' || $woss_blog_layout == '0'){
	$woss_blog_layout=woss_theme_data('blog_layout');
}
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
if($woss_alternate_background): ?>
	<!-- Page Parallax Header -->
	<div class="ws-parallax-header parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url($woss_alternate_background) ?>">        
		<div class="ws-overlay">            
			<div class="ws-parallax-caption">                
				<div class="ws-parallax-holder">
					<h1><?php echo esc_html($woss_alternate_title); ?></h1>                        
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

		<div class="<?php echo esc_attr($woss_page_class); ?>">				
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content-image', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>
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