<?php

//
// Custom Visual Composer Scripts for a Theme Integration
//

//vc_disable_frontend();

vc_remove_element('vc_raw_js');
//vc_remove_element('vc_wp_tagcloud');
//vc_remove_element('vc_wp_custommenu');
//vc_remove_element('vc_wp_pages');
//vc_remove_element('vc_wp_links');
//vc_remove_element('vc_posts_grid');
//vc_remove_element('vc_wp_search');
//vc_remove_element('vc_wp_meta');
//vc_remove_element('vc_wp_recentcomments');
//vc_remove_element('vc_wp_posts');
//vc_remove_element('vc_wp_text');
//vc_remove_element('vc_wp_categories');
//vc_remove_element('vc_wp_archives');
//vc_remove_element('vc_wp_rss');
//vc_remove_element('vc_wp_calendar');
//vc_remove_element('vc_gmaps');
//vc_remove_element('vc_posts_slider');
//vc_remove_element('vc_carousel');
//vc_remove_element('vc_posts_grid');

//vc_remove_element('vc_flickr');
//vc_remove_element('vc_pinterest');
//vc_remove_element('vc_button');
//vc_remove_element('vc_button2'); // do
//vc_remove_element('vc_cta_button');
//vc_remove_element('vc_cta_button2');
//vc_remove_element('contact-form-7');


// Special Heading

vc_map( array(
    "name" => esc_html__("Special Heading", "artday"),
    "base" => "special_heading",
    "class" => "font-awesome",
    "icon" => "fa-header",
    "description" => "Heading text",
    "category" => 'Content',
    "params" => array(
		array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Heading', 'artday' ),
            'param_name' => 'heading',
            'value' => array(
                esc_html__( 'H2', 'artday' ) => 'h2',
                esc_html__( 'H3', 'artday' ) => 'h3',
				esc_html__( 'H4', 'artday' ) => 'h4',
				esc_html__( 'H5', 'artday' ) => 'h5'
            ),
            'description' => esc_html__( 'Choose heading.', 'artday' ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Title", 'artday'),
            "param_name" => "title",
            "description" => esc_html__("Heading text.", 'artday'),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Text align', 'artday' ),
            'param_name' => 'woss_text_align',
            'value' => array(
				esc_html__( 'Align left', 'artday' ) => 'text-left',
				esc_html__( 'Align right', 'artday' ) => "text-right",
                esc_html__( 'Align center', 'artday' ) => 'text-center',
                esc_html__( 'Justify', 'artday' ) => 'text-justify'
            ),
            'description' => esc_html__( 'Select text align.', 'artday' )
        )

    )
));

// Interactive Banner

vc_map( array(
    "name" => esc_html__("Interactive Banner", "artday"),
    "base" => "interactive_banner",
    "class" => "font-awesome",
    "icon" => "fa-header",
    "description" => "Displays the banner image with information",
    "category" => 'Content',
    "params" => array(
		array(
            "type" => "attach_image",
            "heading" => "Image",
            "param_name" => "attached_image",
            "description" => esc_html__("Image for your Interactive Banner.", "artday"),
        ),
		
		array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Heading', 'artday' ),
            'param_name' => 'heading',
            'value' => array(
                esc_html__( 'H2', 'artday' ) => 'h2',
                esc_html__( 'H3', 'artday' ) => 'h3'
            ),
            'description' => esc_html__( 'Choose heading.', 'artday' ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Name", 'artday'),
            "param_name" => "banner_name",
            "description" => esc_html__("Name of the banner.", 'artday'),
        ),
		array(
            "type" => "textarea",
            "heading" => esc_html__("Banner content", "artday"),
            "param_name" => "banner_textarea",
            'description' => esc_html__( 'Banner description.', 'artday' ),
        ),
		array(
            "type" => "textfield",
            "heading" => esc_html__("Phone", 'artday'),
            "param_name" => "banner_phone",
            "description" => esc_html__("Phone number.", 'artday'),
        ),
    )
));

// Team member

vc_map( array(
    "name" => esc_html__("Team Member", "artday"),
    "base" => "team_member",
    "class" => "font-awesome",
    "icon" => "fa-user",
    "category" => 'Content',
    "description" => "Add out team Member",
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Member Name", "artday"),
            "param_name" => "team_name",
            "value" => "John Doe",
            "description" => esc_html__("Member Name", "artday")
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Job", "artday"),
            "param_name" => "team_position",
            "value" => "",
            "description" => esc_html__("Member Position.", "artday")
        ),
        array(
            "type" => "attach_image",
            "holder" => "div",
            "class" => "",
            "heading" => "Avatar",
            "param_name" => "team_img_src",
            "value" => "",
            "description" => esc_html__("Photo or avatar of member.", "artday")
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Description", "artday"),
            "param_name" => "team_description",
            "value" => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim.",
            "description" => esc_html__("Short description about member.", "artday")
        ),
    )
));

// Process

vc_map( array(
    "name" => esc_html__("Process", "artday"),
    "base" => "process",
    "class" => "font-awesome",
    "icon" => "fa-user",
    "category" => 'Content',
    "description" => "Add your process",
    "params" => array(
		array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Process title", "artday"),
            "param_name" => "process_title",
            "value" => "",
            "description" => esc_html__("Title of your process.", "artday")
        ),
		array(
            "type" => "attach_image",
            "holder" => "div",
            "class" => "",
            "heading" => "Image",
            "param_name" => "process_img_src",
            "value" => "",
            "description" => esc_html__("Photo of yourprocess.", "artday")
        ),
		array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Description", "artday"),
            "param_name" => "content",
            "value" => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim.",
            "description" => esc_html__("Short description about your process.", "artday")
        ),
	)
));

// Referring Banner

vc_map( array(
    "name" => esc_html__("Referring Banner", "artday"),
    "base" => "referring_banner",
    "class" => "font-awesome",
    "icon" => "fa-header",
    "description" => "Displays the banner image with link",
    "category" => 'Content',
    "params" => array(
		array(
            "type" => "attach_image",
            "heading" => "Image",
            "param_name" => "attached_image",
            "description" => esc_html__("Image for your Interactive Banner.", "artday"),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Title", 'artday'),
            "param_name" => "banner_title",
            "description" => esc_html__("Title of the banner.", 'artday'),
        ),
		array(
            "type" => "vc_link",
            "heading" => esc_html__("Url", 'artday'),
            "param_name" => "banner_url",
            "description" => esc_html__("Phone url.", 'artday'),
        ),
    )
));

// Call to action

vc_map( array(
    'name' => esc_html__( 'Call to Action', 'artday' ),
    'base' => 'cta',
	"class" => "font-awesome",
    "icon" => "fa-header",
    'category' => array( esc_html__( 'Content', 'artday' ) ),
    'description' => esc_html__( 'Catch visitors attention with CTA block', 'artday' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Heading first line', 'artday' ),
            'param_name' => 'cta_title',
            'description' => esc_html__( 'Text for the first heading line.', 'artday' )
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__( 'Text', 'artday' ),
            'param_name' => 'cta_description',
            'description' => esc_html__( 'Enter your description.', 'artday' )
        ),
        array(
            'type' => 'vc_link',
            'heading' => esc_html__( 'URL (Link)', 'artday' ),
            'param_name' => 'cta_link',
            'description' => esc_html__( 'Button link.', 'artday' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Text on the button', 'artday' ),
            'param_name' => 'cta_button_text',
            'value' => esc_html__( 'Show all journal items', 'artday' ),
            'description' => esc_html__( 'Text on the button.', 'artday' )
        ),
    )
) );

// New Arrivals

vc_map( array(
    'name' => esc_html__( 'New arrivals', 'artday' ),
    'base' => 'new_arrivals',
	"class" => "font-awesome",
    "icon" => "fa-header",
    'category' => array( esc_html__( 'Content', 'artday' ) ),
    'description' => esc_html__( 'Lists new arrivals', 'artday' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Per page', 'artday' ),
			'value' => 6,
            'param_name' => 'arrivals_pre_page',
            'description' => esc_html__( 'The "per_page" shortcode determines how many products to show on the page.', 'artday' )
        ),
    )
) );