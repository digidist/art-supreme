<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Artday
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('ws-journal-single-article'); ?>>
	<header>												
		<!-- Title -->
		<?php the_title( '<h1>', '</h1>' ); ?>				
		
		<div class="ws-journal-single-tags">
			<ul>
				<li class="ws-share-icons">                                    			
					<!-- Social Icons -->		
					<a href="#x" class="facebook-sharer" onClick="<?php echo esc_js('facebookSharer()'); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x fa-inverse"></i></span></a> 
					<a href="#x" class="twitter-sharer" onClick="<?php echo esc_js('twitterSharer()'); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x fa-inverse"></i></span></a>
					<a href="#x" class="pinterest-sharer" onClick="<?php echo esc_js('pinterestSharer()'); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pinterest fa-stack-1x fa-inverse"></i></span></a>
				</li>
				<li class="ws-journal-author-tag">
					<!-- Author -->
					<?php esc_html_e('By:', 'artday');?> <?php the_author_posts_link(); ?>
				</li>
				<li class="ws-journal-category-tag">
					<!-- Category -->		
					<?php esc_html_e('Category:', 'artday');?> <?php artday_entry_footer(); ?>
				</li>
				<li>
					<!-- Date -->
					<?php echo get_the_date(); ?>
				</li>											
			</ul>
		</div>
	</header>	

	<!-- Image -->
	<figure>
		<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
	</figure>	
	
	<!-- Content -->
	<div class="ws-journal-single-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'artday' ),
				'after'  => '</div>',
			) );
		?>
	</div>
</article>
