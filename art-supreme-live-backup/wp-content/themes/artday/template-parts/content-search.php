<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Artday
 */

?>

<!-- Search Item -->
<div class="ws-search-item">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<!-- Header -->
		<header>				
			<!-- Title -->
			<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>					
		</header>

		<!-- Content -->
		<main>
			<?php the_excerpt(); ?>
		</main>			
	</article>
</div>
