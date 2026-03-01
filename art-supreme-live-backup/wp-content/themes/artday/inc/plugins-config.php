<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'artday_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function artday_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
	
		// Theme Shortcodes
		array(
			'name'                  => 'Artday Shortcodes',
			'version'				=> '1.2.3',
			'slug'                  => 'woss-shortcodes',
			'source'                => get_template_directory() . '/inc/files/woss-shortcodes.zip',
			'required'              => true,
		),

		// Facebook Login
		array(
			'name'                  => 'Facebook Login',
			'version'				=> '1.2.3',
			'slug'                  => 'woss-facebook-login',
			'source'                => get_template_directory() . '/inc/files/wp-facebook-login.zip',
			'required'              => false,
		),

		// Envato Market
		array(
			'name'                  => 'Envato Market',
			'version'				=> '2.0.1',
			'slug'                  => 'envato-market',
			'source'                => get_template_directory() . '/inc/files/envato-market.zip',
			'required'              => false,
		),			

        // Visual Composer
        array(
            'name'                  => 'WPBakery Visual Composer',
            'slug'                  => 'js_composer',
            'source'                => get_template_directory() . '/inc/files/js_composer.zip',
            'required'              => true,
            'version'               => '5.5.5',
            'force_activation'      => false,
            'force_deactivation'    => false,
        ),

        // Revolution Slider
        array(
            'name'                  => 'Revolution Slider',
            'slug'                  => 'revslider',
            'source'                => get_template_directory() . '/inc/files/revslider.zip',
            'required'              => true,
            'version'               => '5.4.8',
            'force_activation'      => false,
            'force_deactivation'    => false,
        ),
		
		// Layered Popups
        array(
            'name'                  => 'Layered Popups',
            'slug'                  => 'layered-popups',
            'source'                => get_template_directory() . '/inc/files/layered-popups.zip',
            'required'              => false,
            'version'               => '6.45',
            'force_activation'      => false,
            'force_deactivation'    => false,
        ),

        // Redux Framework
        array(
            'name'                  => 'Redux Framework',
            'slug'                  => 'redux-framework',
            'required'              => true,
            'version'               => '3.6.11',
        ),

        // CMB2
        array(
            'name'                  => 'CMB2',
            'slug'                  => 'cmb2',
            'required'              => true,
            'version'               => '2.4.2',
        ),

        // Contact Form 7
        array(
            'name' 		            => 'Contact Form 7',
            'slug' 		            => 'contact-form-7',
            'required' 	            => false,
        ),

        // Woocommerce
        array(
            'name' 		            => 'Woocommerce',
            'slug' 		            => 'woocommerce',
            'required' 	            => false,
        ),

    );

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}