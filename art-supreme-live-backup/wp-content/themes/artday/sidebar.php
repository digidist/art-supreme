<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Artday
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

$sidebar_id = 'sidebar-1';
if (class_exists('Woocommerce')) {
    if(is_woocommerce() || is_shop() || is_cart() || is_checkout() || is_account_page()) {        
    	$sidebar_id = 'sidebar-shop';        
    }
}

?>
	<!-- Blog Sidebar -->	
	<div class="col-sm-3 ws-journal-sidebar">
		<div class="widget-area" role="complementary">
			<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar($sidebar_id)) ?>
		</div>
	</div>
	

