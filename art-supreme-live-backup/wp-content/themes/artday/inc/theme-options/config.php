<?php
    /**
     * Artday Theme Config File
     *
     */

    if ( ! class_exists( 'artday_theme_options_config' ) ) {

        class artday_theme_options_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( esc_html__( 'Customize &#8220;%s&#8221;', 'artday' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'artday' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'artday' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( esc_html__( 'By %s', 'artday' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( esc_html__( 'Version %s', 'artday' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . esc_html__( 'Tags', 'artday' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . wp_kses_post(__( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'artday' )) . '</p>', esc_html__( 'http://codex.wordpress.org/Child_Themes', 'artday' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                // General theme options
                $this->sections[] = array(
                    'title'  => esc_html__( 'General Settings', 'artday' ),
                    'icon'   => 'el el-home',
                    'fields' => array(
						array(
                            'id'       => 'logo',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => esc_html__( 'Logo image', 'artday' ),
                            'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                            'desc'     => esc_html__( 'Upload your website\'s logo image.', 'artday' ),
                            'default'  => array( 'url' => get_template_directory_uri().'/assets/img/logo-black.png' ),
                        ),
                        array(
                            'id'       => 'logo_second',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => esc_html__( 'Second logo image', 'artday' ),
                            'compiler' => 'true',
                            'desc'     => esc_html__( 'Second logo (field is optional).', 'artday' ),
                            'default'  => array( 'url' => get_template_directory_uri().'/assets/img/logo-white.png' ),
                        ),
                        array(
                            'id'       => 'favicon',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => esc_html__( 'Custom favicon', 'artday' ),
                            'compiler' => 'true',
                            'desc'     => esc_html__( 'Upload a 16px x 16px Png/Gif image that will represent your website\'s favicon', 'artday' ),
                            'default'  => array( 'url' => get_template_directory_uri().'/assets/img/favicon.ico' ),
                        ),
						array(
                            'id'       => 'preloader',
                            'type'     => 'switch',
                            'title'    => esc_html__( 'Page loader', 'artday' ),
                            'desc'     => esc_html__( 'Enable or Disable the Page Loader', 'artday' ),
                            'default'  => true,
                        ),
                        array(
                            'id'       => 'demo_importer',
                            'type'     => 'switch',
                            'title'    => esc_html__( 'Demo content importer', 'artday' ),
                            'desc'     => esc_html__( 'Enable or Disable one click demo content importer', 'artday' ),
                            'default'  => false,
                        ),
                      
                    ),
                );
				
				$this->sections[] = array(
                    'icon'       => 'el-icon-star',
                    'title'      => esc_html__( 'Header', 'artday' ),
                    'fields'     => array(
						array(
                            'id'       => 'nav_style',
                            'type'     => 'radio',
                            'title'    => esc_html__( 'Main navigation style', 'artday' ),
                            'options'  => array(
                                'style1' => 'Navigation Style 1',
                                'style2' => 'Navigation Style 2',
								'style3' => 'Navigation Style 3',
								'style4' => 'Navigation Style 4',
                            ),
                            'default'  => 'style4'
                        ),
                        array(
                            'id'       => 'nav_parent_item_link',
                            'type'     => 'switch',
                            'title'    => esc_html__( 'Parent items as link', 'artday' ),
                            'desc'     => esc_html__( 'Make parent items in main navigation as links.', 'artday' ),
                            'default'  => false,
                        ),
                    ),
                );
				
				// Footer options
                $this->sections[] = array(
                    'title'  => esc_html__( 'Footer', 'artday' ),
                    'icon'   => 'el el-magic',
                    'fields' => array(
						array(
                            'id'       => 'footer_tracking_code',
                            'type'     => 'textarea',
                            'title'    => esc_html__( 'Tracking Code', 'artday' ),
                            'desc'     => esc_html__( 'Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme', 'artday' ),
                        ),
                        array(
                            'id'       => 'footer_text',
                            'type'     => 'editor',
                            'title'    => esc_html__( 'Footer Text', 'artday' ),
                            'desc' => esc_html__( 'Place here your copyright line. For ex: Copyright 2015 | My website.', 'artday' ),
                            'default'  => 'Handcrafted with love &copy; 2015 All rights reserved.',
                        ),
                      
                    ),
                );
				
				// Shop options
                $this->sections[] = array(
                    'title'  => esc_html__( 'Shop', 'artday' ),
                    'icon'   => 'el el-shopping-cart',
                    'fields' => array(
						array(
                            'id'       => 'shop_layout',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => esc_html__( 'Shop Layout', 'artday' ),
                            'subtitle' => esc_html__( 'Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'artday' ),
                            'options'  => array(
                                '1' => array(
                                    'alt' => '1 Column',
                                    'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                                ),
                                '2' => array(
                                    'alt' => '2 Column Left',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                                ),
                                '3' => array(
                                    'alt' => '2 Column Right',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                                ),
                            ),
                            'default'  => '1'
                        ),
						array(
                            'id'       => 'shop_pagination',
                            'type'     => 'radio',
                            'title'    => esc_html__( 'Pagination style', 'artday' ),
                            'subtitle' => esc_html__( 'Choose pagination style for shop and archive page.', 'artday' ),
                            'options'  => array(
                                'style1' => 'Load More Button',
                                'style2' => 'Default Pagination',
                            ),
                            'default'  => 'style1'
                        ),
						array(
							'id'    => 'shop_count_products',
							'type'  => 'text',
							'title' => esc_html__( 'Products number', 'artday' ),
							'subtitle'  => esc_html__( 'Number fo products to display on shop page.', 'artday' ),
							'validate' => 'numeric',
							'default'  => '11',
						),
						array(
							'id'    => 'top_head_text',
							'type'  => 'text',
							'title' => esc_html__( 'Top header text', 'artday' ),
							'desc'  => esc_html__( 'This text will be displayed on top header.', 'artday' ),
							'default'  => 'Free Shipping on orders over 50$ in USA',
						),
						array(
							'id'       => 'product_background',
							'type'     => 'switch',
							'title'    => 'Thumbnails background',
							'subtitle' => 'Click <code>On</code> to add background to product thumbnails.',
							'default'  => false
						),
                      
                    ),
                );
				
				// Blog options
                $this->sections[] = array(
                    'title'  => esc_html__( 'Blog', 'artday' ),
                    'icon'   => 'el el-idea',
                    'fields' => array(
						array(
                            'id'       => 'blog_layout',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => esc_html__( 'Blog Layout', 'artday' ),
                            'subtitle' => esc_html__( 'Select main content and sidebar alignment. Choose between 1, 2 or 3 layouts.', 'artday' ),
                            'options'  => array(
                                '1' => array(
                                    'alt' => '1 Column',
                                    'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                                ),
                                '2' => array(
                                    'alt' => '2 Column Left',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                                ),
                                '3' => array(
                                    'alt' => '2 Column Right',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                                ),
                            ),
                            'default'  => '1'
                        ),
						array(
                            'id'       => 'blog_style',
                            'type'     => 'select',
                            'title'    => esc_html__( 'Blog grid', 'artday' ),
                            'desc'     => esc_html__( 'Select number of columns for your blog posts grid.', 'artday' ),
                            'options'  => array(
                                'style1' => '2 columns grid',
                                'style2' => '3 columns grid',
								'style3' => 'Mixed grid',
                            ),
                            'default'  => 'style2',
							'select2'  => array( 'allowClear' => false )
                        ),
						array(
							'id'       => 'blog_parallax_header',
							'type'     => 'media',
							'title'    => esc_html__( 'Parallax Header', 'artday' ),
							'desc'     => esc_html__( 'Upload any media using the WordPress native uploader.', 'artday' ),
						),
						array(
							'id'    => 'blog_head_text',
							'type'  => 'text',
							'title' => esc_html__( 'Blog text', 'artday' ),
							'desc'  => esc_html__( 'This text will be displayed on parallax header in blog page.', 'artday' ),
							'default'  => 'Our Latest News',
						),
						array(
                            'id'       => 'post_summary',
                            'type'     => 'radio',
                            'title'    => esc_html__( 'Homepage/Archive Post Summary Type', 'artday' ),
                            'options'  => array(
                                'default' => 'Use Read More Tag',
                                'excerpt' => 'Use Excerpt',
                            ),
                            'default'  => 'excerpt'
                        ),
                        array(
                            'id'            => 'excerpt_length',
                            'type'          => 'slider',
                            'title'         => esc_html__( 'Posts excerpt length', 'artday' ),
                            'desc'          => esc_html__( 'Add your own custom excerpt length for Homepage/Archive page', 'artday' ),
                            'default'       => 35,
                            'min'           => 5,
                            'step'          => 1,
                            'max'           => 150,
                            'display_value' => 'text'
                        ),
						array(
                            'id'       => 'blog_pagination',
                            'type'     => 'radio',
                            'title'    => esc_html__( 'Pagination style', 'artday' ),
                            'subtitle' => esc_html__( 'Choose pagination style for blog and archive page.', 'artday' ),
                            'options'  => array(
                                'style1' => 'Load More Button',
                                'style2' => 'Default Pagination',
                            ),
                            'default'  => 'style1'
                        ),
                      
                    ),
                );

                $this->sections[] = array(
                    'icon'       => 'el el-website',
                    'title'      => esc_html__( 'Styling Options', 'artday' ),
                    'subsection' => false,
                    'fields'     => array(
						array(
                            'id'       => 'woss-typography-body',
                            'type'     => 'typography',
                            'title'    => esc_html__( 'Body Font', 'artday' ),
                            'subtitle' => esc_html__( 'Specify the body font properties.', 'artday' ),
                            'google'   => true,
							'line-height'=>false,
							'font-size'   => false,
							'font-weight' => false,
							'font-style' => false,
							'subsets' => false,
							'text-align' => false,
                            'color' => false,
                            'default'  => array(
                                'font-family' => 'PT Serif',
                            ),
                        ),
						array(
                            'id'       => 'woss-typography-elements',
                            'type'     => 'typography',
                            'title'    => esc_html__( 'Elements Font', 'artday' ),
                            'subtitle' => esc_html__( 'Specify the elements font properties.', 'artday' ),
                            'google'   => true,
							'line-height'=>false,
							'font-size'   => false,
							'font-weight' => false,
							'font-style' => false,
							'subsets' => false,
							'text-align' => false,
                            'color' => false,
                            'default'  => array(
                                'font-family' => 'Montserrat',
                            ),
                        ),
						array(
                            'id'       => 'woss_theme_color',
                            'type'     => 'color',
                            'title'    => esc_html__( 'Main theme color', 'artday' ),
                            'subtitle' => esc_html__( 'Pick a main theme color (default: #dd9933).', 'artday' ),
                            'default'  => '#C2A476',
                            'validate' => 'color',
                        ),
						array(
                            'id'       => 'woss_theme_hover',
                            'type'     => 'color',
                            'title'    => esc_html__( 'Main theme hover color', 'artday' ),
                            'subtitle' => esc_html__( 'Pick a main theme hover color (default: #dd9933).', 'artday' ),
                            'default'  => '#CCB48E',
                            'validate' => 'color',
                        ),
						
                        array(
                            'id'       => 'opt-ace-editor-css',
                            'type'     => 'ace_editor',
                            'title'    => esc_html__( 'CSS Code', 'artday' ),
                            'subtitle' => esc_html__( 'Paste your CSS code here.', 'artday' ),
                            'mode'     => 'css',
                            'theme'    => 'monokai',
                            'desc'     => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                            'default'  => ""
                        ),
                    )
                );
				
				$this->sections[] = array(
                    'icon'       => 'el-icon-group-alt',
                    'title'      => esc_html__( 'Social media Settings', 'artday' ),
                    'fields'     => array(
                        array(
                            'id' => 'facebook_url',
                            'type' => 'text',
                            'title' => esc_html__('Facebook', 'artday' ),
                        ),
                        array(
                            'id' => 'pinterest_url',
                            'type' => 'text',
                            'title' => esc_html__('Pinterest', 'artday' ),
                        ),
                        array(
                            'id' => 'instagram_url',
                            'type' => 'text',
                            'title' => esc_html__('Instagram', 'artday' ),
                        ),
                        array(
                            'id' => 'youtube_url',
                            'type' => 'text',
                            'title' => esc_html__('Youtube', 'artday' ),
                        ),
                        array(
                            'id' => 'twitter_url',
                            'type' => 'text',
                            'title' => esc_html__('Twitter', 'artday' ),
                        ),

                        array(
                            'id' => 'linkedin_url',
                            'type' => 'text',
                            'title' => esc_html__('Linkedin', 'artday' ),
                        ),
                        array(
                            'id' => 'google_url',
                            'type' => 'text',
                            'title' => esc_html__('Google Plus', 'artday' ),
                        ),
                        array(
                            'id' => 'tumblr_url',
                            'type' => 'text',
                            'title' => esc_html__('Thumblr', 'artday' ),
                        ),
                        array(
                            'id' => 'rss_url',
                            'type' => 'text',
                            'title' => esc_html__('RSS', 'artday' ),
                        ),
                    ),
                );

                $theme_info = '<div class="redux-framework-section-desc">';
                $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . wp_kses_post(__( '<strong>Theme URL:</strong> ', 'artday' )) . '<a href="' . $this->theme->get( 'ThemeURI' ) . '" target="_blank">' . $this->theme->get( 'ThemeURI' ) . '</a></p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-author">' .wp_kses_post(__( '<strong>Author:</strong> ', 'artday' )) . $this->theme->get( 'Author' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . wp_kses_post(__( '<strong>Version:</strong> ', 'artday' )) . $this->theme->get( 'Version' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get( 'Description' ) . '</p>';
                $tabs = $this->theme->get( 'Tags' );
                if ( ! empty( $tabs ) ) {
                    $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . wp_kses_post(__( '<strong>Tags:</strong> ', 'artday' )) . implode( ', ', $tabs ) . '</p>';
                }
                $theme_info .= '</div>';

                $this->sections[] = array(
                    'title'  => esc_html__( 'Import / Export', 'artday' ),
                    'desc'   => esc_html__( 'Import and Export your Redux Framework settings from file, text or URL.', 'artday' ),
                    'icon'   => 'el el-refresh',
                    'fields' => array(
                        array(
                            'id'         => 'opt-import-export',
                            'type'       => 'import_export',
                            'title'      => 'Import Export',
                            'subtitle'   => 'Save and restore your Redux options',
                            'full_width' => false,
                        ),
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'icon'   => 'el el-info-circle',
                    'title'  => esc_html__( 'Theme Information', 'artday' ),
                    'desc'   => wp_kses_post(__( '<p class="description">This is the Description. Again HTML is allowed</p>', 'artday' )),
                    'fields' => array(
                        array(
                            'id'      => 'opt-raw-info',
                            'type'    => 'raw',
                            'content' => $item_info,
                        )
                    ),
                );

            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => esc_html__( 'Theme Information 1', 'artday' ),
                    'content' => wp_kses_post(__( '<p>This is the tab content, HTML is allowed.</p>', 'artday' ))
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => esc_html__( 'Theme Information 2', 'artday' ),
                    'content' => wp_kses_post(__( '<p>This is the tab content, HTML is allowed.</p>', 'artday' ))
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = wp_kses_post(__( '<p>This is the sidebar content, HTML is allowed.</p>', 'artday' ));
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'redux_demo',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'ARTDAY', 'artday' ), // must not be translatable
                    'page_title'           => __( 'ARTDAY', 'artday' ), // must not be translatable
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => 'woss_theme_data',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => false,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => '',
                    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'el el-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );

                // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
                $this->args['admin_bar_links'][] = array(
                    //'id'    => 'redux-support',
                    'href'   => 'https://themeforest.net/item/artday-creative-artist-wordpress-shop/14396982/support',
                    'title' => esc_html__( 'Support', 'artday' ),
                );

                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-extensions',
                    'href'   => 'https://themeforest.net/user/wossthemes/portfolio',
                    'title' => esc_html__( 'Our Themes', 'artday' ),
                );

                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $this->args['share_icons'][] = array(
                    'url'   => 'https://themeforest.net/user/wossthemes/portfolio',
                    'title' => 'Visit us on ThemeForest',
                    'icon'  => 'el el-arrow-up'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'https://www.facebook.com/wossthemes/',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el el-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'https://twitter.com/WossThemes',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el el-twitter'
                );

                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    $this->args['intro_text'] = sprintf( wp_kses_post(__( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'artday' )), $v );
                } else {
                    $this->args['intro_text'] = wp_kses_post(__( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'artday' ));
                }
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';


                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public static function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new artday_theme_options_config();
    } else {
        echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

