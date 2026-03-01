<?php
/**
 * Template part for displaying page content in template-page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Artday
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
	<?php the_content(); ?>
	<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'artday' ),
			'after'  => '</div>',
		) );
	?>
</article>
