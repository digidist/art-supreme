<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category ARTDAY
 * @package  Artday
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

add_action( 'cmb2_init', 'woss_page_fileds' );
function woss_page_fileds() {

    $prefix = '_wscmb_';

    $cmb_about_page = new_cmb2_box( array(
        'id'           => $prefix . 'page_fileds',
        'title'        => esc_html__( 'Page settings', 'artday' ),
        'object_types' => array( 'page', ), // Post type
    ) );

	$cmb_about_page->add_field( array(
        'name' => 'Alternate page title:',
        'desc' => 'Set alternate page title. Will be displayed in header section. (Only for Default Template)',
        'id'   => $prefix . 'page_alternate_title',
        'type' => 'text',
        // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
    ) );
	
	$cmb_about_page->add_field( array(
        'name' => 'Background image:',
        'desc' => 'Upload your custom header background image.',
        'id'   => $prefix . 'page_alternate_background',
        'type' => 'file',
        // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
    ) );

    $cmb_about_page->add_field( array(
        'name' => 'Page layout:',
        'desc' => 'Choose layout for this page.',
        'id'   => $prefix . 'page_layout',
        'type' => 'select',
        'options' => array(
            '0' => esc_html__( 'Default set in Theme Options', 'artday' ),
            '1' => esc_html__( 'Fullwidth', 'artday' ),
            '2'   => esc_html__( 'Sidebar Left', 'artday' ),
            '3'     => esc_html__( 'Sidebar Right', 'artday' ),
        ),
    ) );

	
	$cmb_shop_page = new_cmb2_box( array(
        'id'           => $prefix . 'shop_fileds',
        'title'        => esc_html__( 'Product settings', 'artday' ),
        'object_types' => array( 'product', ), // Post type
    ) );

	$cmb_shop_page->add_field( array(
        'name' => 'Product preview image:',
        'desc' => 'Upload your prodcut preview image.',
        'id'   => $prefix . 'shop_preview_image',
        'type' => 'file',
        // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
    ) );
	
	$cmb_shop_page->add_field( array(
        'name' => 'Product subtitle:',
        'desc' => 'Set subtitle. (Archive page only)',
        'id'   => $prefix . 'shop_subtitle',
        'type' => 'text',
    ) );
}
