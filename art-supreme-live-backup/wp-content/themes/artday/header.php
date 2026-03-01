<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Artday
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		
		<?php
		// If WP4.3+ and no site_icon is set - show custom
		if ( function_exists( 'has_site_icon' ) && !has_site_icon() ) { ?>
			<link rel="shortcut icon" href="<?php $favicon=woss_theme_data('favicon'); echo esc_url($favicon['url']); ?>" type="image/x-icon">	
		<?php }
		// If before WP4.3 - show custom
		if ( ! function_exists( 'wp_site_icon' ) ) { ?>
			<link rel="shortcut icon" href="<?php $favicon=woss_theme_data('favicon'); echo esc_url($favicon['url']); ?>" type="image/x-icon">
		<?php } ?>
		

		<?php wp_head(); ?>
	</head>
<body <?php body_class(); ?>>

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'artday' ); ?></a>
	
	<?php if(woss_theme_data('preloader') == 1): ?>
		<!-- Loader Start -->
		<div id="preloader">
			<div class="preloader-container">
				<div class="ws-spinner"></div>
			</div>
		</div>
		<!-- End Loader Start -->
	<?php endif;?>

	<?php get_template_part( 'menu', 'index' ); ?>
