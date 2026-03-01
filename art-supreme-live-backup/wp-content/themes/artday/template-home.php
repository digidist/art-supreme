<?php

/* Template Name: Home Page */

get_header(); ?>

<!-- Container Start -->
<div class="container ws-page-container">

	<!-- Row Start -->
	<div class="row">

		<div class="col-sm-12">				
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
		
	</div><!-- Row End -->
</div><!-- Container End -->

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("page_bottom") ) : ?>
<?php endif; ?>

<?php get_footer(); ?>