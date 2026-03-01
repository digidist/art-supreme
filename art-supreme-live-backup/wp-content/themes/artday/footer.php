<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Artday
 */

?>

<?php
if ( is_active_sidebar( 'footer_1st_col'  )
    || is_active_sidebar( 'footer_2nd_col' )
    || is_active_sidebar( 'footer_3rd_col'  )
    || is_active_sidebar( 'footer_4th_col' )
) :  ?>

    <!-- Footer Start -->
    <footer class="ws-footer">
        <div class="container">
            <div class="row">

				<?php
				/* If none of the sidebars have widgets.*/
			    if ( ! is_active_sidebar( 'footer_1st_col'  )
			        && ! is_active_sidebar( 'footer_2nd_col' )
			        && ! is_active_sidebar( 'footer_3rd_col'  )
			        && ! is_active_sidebar( 'footer_4th_col' )
			    ) :
				endif;

				/* If all of the sidebars have widgets.*/
			    if  (  is_active_sidebar( 'footer_1st_col'  )
			        && is_active_sidebar( 'footer_2nd_col' )
			        && is_active_sidebar( 'footer_3rd_col'  )
			        && is_active_sidebar( 'footer_4th_col' )
			    ) : ?>

					<!-- About -->
					<div class="col-sm-6 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_1st_col") ) : ?>
						<?php endif; ?>
					</div>

					<!-- Support Links -->
					<div class="col-sm-2 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_2nd_col") ) : ?>
						<?php endif; ?>
					</div>

					<!-- Social Links -->
					<div class="col-sm-2 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_3rd_col") ) : ?>
						<?php endif; ?>
					</div>

					<!-- Shop -->
					<div class="col-sm-2 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_4th_col") ) : ?>
						<?php endif; ?>
					</div>

				<?php endif;

				if  (  is_active_sidebar( 'footer_1st_col'  )
			        && is_active_sidebar( 'footer_2nd_col' )
			        && is_active_sidebar( 'footer_3rd_col'  )
			        && ! is_active_sidebar( 'footer_4th_col' )
			    ) : ?>

					<div class="col-sm-4 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_1st_col") ) : ?>
						<?php endif; ?>
					</div>

					<div class="col-sm-4 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_2nd_col") ) : ?>
						<?php endif; ?>
					</div>

					<div class="col-sm-4 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_3rd_col") ) : ?>
						<?php endif; ?>
					</div>

				<?php
				endif;

				if  (  is_active_sidebar( 'footer_1st_col'  )
			    && is_active_sidebar( 'footer_2nd_col' )
			    && ! is_active_sidebar( 'footer_3rd_col'  )
			    && ! is_active_sidebar( 'footer_4th_col' )
			    ) : ?>

					<div class="col-sm-6 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_1st_col") ) : ?>
						<?php endif; ?>
					</div>

					<div class="col-sm-6 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_2nd_col") ) : ?>
						<?php endif; ?>
					</div>

				<?php
				endif;

			    if  (  is_active_sidebar( 'footer_1st_col'  )
			    && ! is_active_sidebar( 'footer_2nd_col' )
			    && ! is_active_sidebar( 'footer_3rd_col'  )
			    && ! is_active_sidebar( 'footer_4th_col' )
			    ) : ?>

					<div class="col-sm-12 ws-footer-col">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_1st_col") ) : ?>
						<?php endif; ?>
					</div>

				<?php
				endif;
				?>

            </div>
        </div>
    </footer>
    <!-- Footer End -->
<?php endif; ?>

    <!-- Footer Bar Start -->
    <div class="ws-footer-bar">
        <div class="container">

			<!-- Copyright -->
			<?php $footer_text=woss_theme_data('footer_text'); ?>
            <div class="pull-left">
                <?php if($footer_text != '') {
					echo '<p>'.wp_kses_post($footer_text).'</p>';
				} else { ?>
					<p><?php esc_html_e('Handcrafted with love &copy; 2015 All rights reserved.', 'artday'); ?> </p>
				<?php } ?>
            </div>

            <!-- Payments -->
            <div class="pull-right">
                <ul class="ws-footer-payments">
                    <li><i class="fa fa-cc-visa fa-lg"></i></li>
                    <li><i class="fa fa-cc-paypal fa-lg"></i></li>
                    <li><i class="fa fa-cc-mastercard fa-lg"></i></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Footer Bar End -->

<?php wp_footer(); ?>

</body>
</html>
