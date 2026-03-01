<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Artday
 */

$woss_count_class = '4';

$woss_blog_style=woss_theme_data('blog_style');
if($woss_blog_style !== '' && $woss_blog_style == 'style1'){
	$woss_count_class = '6';
}
if($woss_blog_style !== '' && $woss_blog_style == 'style3'){
	if( isset($GLOBALS['woss_count'] ) && !is_archive() ){ 
		$woss_count = $GLOBALS['woss_count'];
	}else{
		$woss_count = 3;
	}

	if ($woss_count == 1 || $woss_count == 2 ){
		$woss_count_class = '6';
	}
}

$woss_post_summary=woss_theme_data('post_summary');
?>


<!-- Article Item -->
<article id="post-<?php the_ID(); ?>" <?php post_class('ws-journal-article col-sm-'.absint($woss_count_class).''); ?>>			
	<header>	
		<!-- Image -->		
		<div class="ws-journal-image">
            <figure>
                <a href="<?php the_permalink()?>">
					<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
				</a>
            </figure>
        </div>               

		<!-- Title -->
		<div class="ws-journal-title">
			<?php the_title( sprintf( '<h3><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>			
		</div>		
	</header>

	<div class="ws-journal-content">
	
		<?php 
			if($woss_post_summary == 'default'):
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses(__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'artday' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );
			
			else: $woss_excerpt_length=woss_theme_data('excerpt_length'); if (!$woss_excerpt_length) { $woss_excerpt_length = 35; }
				echo woss_string_limit_words(get_the_excerpt(), absint($woss_excerpt_length)); ?>&hellip;
		<?php endif; ?>		

	</div>
	
	<!-- Date -->
	<div class="ws-journal-date col-sm-6 col-no-p">
		<?php printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
		esc_html_x( 'Posted on', 'Used before publish date.', 'artday' ),
		esc_url( get_permalink() ),
		get_the_date()
	); ?>
	</div>
	<div class="ws-blog-btn col-sm-6 col-no-p">
		<a class="ws-btn" href="<?php the_permalink()?>" role="button"><?php esc_html_e('More', 'artday'); ?><i class="fa fa-long-arrow-right"></i></a>
	</div>

	<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'artday' ),
			'after'  => '</div>',
		) );
	?>
</article>