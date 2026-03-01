<?php 
/**
 * Widgets init
 */
add_action( 'widgets_init', 'woss_init_widgets');
function woss_init_widgets() {
	register_widget( 'woss_social_widget' );
	register_widget( 'woss_instagram_widget' );
	register_widget( 'woss_cf7_widget' );
}
/**
 * Widget Name: Social Icons
 */
class woss_social_widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	 
	function __construct() {
		parent::__construct(
			'woss_social_widget',
			esc_html__( 'Artday: Social Icons', 'woss_social_widget' ),
			array( 'classname' => 'follow-widget widget-space', 'description' => esc_html__( 'A widget that displays your social icons', 'woss_social_widget' ) )
		);
	}
	
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$facebook = woss_theme_data('facebook_url');
		$twitter = woss_theme_data('twitter_url');
		$googleplus = woss_theme_data('google_url');
		$instagram = woss_theme_data('instagram_url');
		$linkedin = woss_theme_data('linkedin_url');
		$youtube = woss_theme_data('youtube_url');
		$tumblr = woss_theme_data('tumblr_url');
		$pinterest = woss_theme_data('pinterest_url');
		$rss = woss_theme_data('rss_url');
		
		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
		
			<ul class="ws-footer-social">
				<?php if($facebook) : ?><li><a href="http://facebook.com/<?php echo strip_tags($facebook); ?>" target="_blank"><i class="fa fa-facebook-square fa-lg"></i> <?php esc_html_e('Facebook', 'woss'); ?></a></li><?php endif; ?>
				<?php if($twitter) : ?><li><a href="http://twitter.com/<?php echo strip_tags($twitter); ?>" target="_blank"><i class="fa fa-twitter fa-lg"></i> <?php esc_html_e('Twitter', 'woss'); ?></a></li><?php endif; ?>
				<?php if($instagram) : ?><li><a href="http://instagram.com/<?php echo strip_tags($instagram); ?>" target="_blank"><i class="fa fa-instagram fa-lg"></i> <?php esc_html_e('Instagram', 'woss'); ?></a></li><?php endif; ?>
				<?php if($pinterest) : ?><li><a href="http://pinterest.com/<?php echo strip_tags($pinterest); ?>" target="_blank"><i class="fa fa-pinterest fa-lg"></i> <?php esc_html_e('Pinterest', 'woss'); ?></a></li><?php endif; ?>
				<?php if($linkedin) : ?><li><a href="http://linkedin.com/<?php echo strip_tags($linkedin); ?>" target="_blank"><i class="fa fa-linkedin fa-lg"></i> <?php esc_html_e('LinkedIn', 'woss'); ?></a></li><?php endif; ?>
				<?php if($googleplus) : ?><li><a href="http://plus.google.com/<?php echo strip_tags($googleplus); ?>" target="_blank"><i class="fa fa-google-plus fa-lg"></i> <?php esc_html_e('GooglePlus', 'woss'); ?></a></li><?php endif; ?>
				<?php if($tumblr) : ?><li><a href="http://<?php echo strip_tags($tumblr); ?>.tumblr.com/" target="_blank"><i class="fa fa-tumblr fa-lg"></i> <?php esc_html_e('Tumblr', 'woss'); ?></a></li><?php endif; ?>
				<?php if($youtube) : ?><li><a href="http://youtube.com/<?php echo strip_tags($youtube); ?>" target="_blank"><i class="fa fa-youtube-play fa-lg"></i> <?php esc_html_e('Youtube', 'woss'); ?></a></li><?php endif; ?>
				<?php if($rss) : ?><li><a href="<?php echo strip_tags($rss); ?>" target="_blank"><i class="fa fa-rss fa-lg"></i> <?php esc_html_e('RSS', 'woss'); ?></a><?php endif; ?>				
			</ul>
			
		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Socials', );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e('Title:', 'woss'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>
		
		<p><?php esc_html_e('Note: Set your social links in the Theme Options Page', 'woss'); ?></p>
	<?php
	}
}
/**
 * Widget Name: Instagram
 */
class woss_instagram_widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	 
	function __construct() {
		parent::__construct(
			'woss_instagram_widget',
			esc_html__( 'Artday: Instagram', 'woss_instagram_widget' ),
			array( 'classname' => 'follow-widget widget-space', 'description' => esc_html__( 'A widget that displays your Instagram feed', 'woss_instagram_widget' ) )
		);
	}
	
	function widget( $args, $instance ) {
		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$info = empty( $instance['info'] ) ? '' : $instance['info'];
		$username = empty( $instance['username'] ) ? '' : $instance['username'];
		$limit = empty( $instance['number'] ) ? 9 : $instance['number'];
		$size = empty( $instance['size'] ) ? 'original' : $instance['size'];
		$target = empty( $instance['target'] ) ? '_self' : $instance['target'];
		$link = empty( $instance['link'] ) ? '' : $instance['link'];
		echo $args['before_widget'];
		if ( $size == 'original'){
		?>
		
			<!-- Instagram Content -->
			<section id="ws-instagram-section">
				<div class="container">
					<div class="row vertical-align">		
						<!-- Instagram Information -->
						<div class="col-sm-3">       
							<div class="ws-instagram-header">
								<?php if ( ! empty( $title ) ) { echo $args['before_title'] . wp_kses_post( $title ) . $args['after_title']; }; ?>
								<br>
								<a href="//instagram.com/<?php echo trim($username); ?>" target="<?php echo esc_attr( $target ); ?>" class="ws-instagram-link">@ <?php echo trim($username); ?></a>							
								<p><?php if ( ! empty( $info ) ) { echo wp_kses_post( $info ); }; ?></p>
								<?php $linkclass = apply_filters( 'wpiw_link_class', 'clear' );
								if ( $link != '' ) {
									?><p class="<?php echo esc_attr( $linkclass ); ?>"><a href="//instagram.com/<?php echo esc_attr( trim( $username ) ); ?>" rel="me" target="<?php echo esc_attr( $target ); ?>"><?php echo wp_kses_post( $link ); ?></a></p><?php
								}
								?>
							</div>
						</div>
						<?php
		} else{
			if ( ! empty( $title ) ) { echo $args['before_title'] . wp_kses_post( $title ) . $args['after_title']; };
		}
		
		do_action( 'wpiw_before_widget', $instance );
		if ( $username != '' ) {
			$media_array = $this->scrape_instagram( $username, $limit );
			if ( is_wp_error( $media_array ) ) {
				echo wp_kses_post( $media_array->get_error_message() );
			} else {
				
				if ( $size == 'original'){
					// filter for images only?
					if ( $images_only = apply_filters( 'wpiw_images_only', false ) ) {
						$media_array = array_filter( $media_array, array( $this, 'images_only' ) );
					}
					// slice list down to required limit.
					$media_array = array_slice( $media_array, 0, $limit );
					?><!-- Instagram Item -->
						<?php
						foreach ( $media_array as $item ) {
							echo '<div class="col-sm-3 ws-instagram-item" data-sr=\'wait 0.1s, ease-in 20px\'><a href="'. esc_url( $item['link'] ) .'" target="'. esc_attr( $target ) .'"><img src="'. esc_url( $item[$size] ) .'"  alt="'. esc_attr( $item['description'] ) .'" title="'. esc_attr( $item['description'] ).'"  class="img-responsive"/></a></div>';
						} ?>
					<?php
				} else {
					
					// filter for images only?
					if ( $images_only = apply_filters( 'wpiw_images_only', false ) ) {
						$media_array = array_filter( $media_array, array( $this, 'images_only' ) );
					}
					// slice list down to required limit.
					$media_array = array_slice( $media_array, 0, $limit );
					// filters for custom classes
					$ulclass = apply_filters( 'wpiw_list_class', 'instagram-pics instagram-size-' . $size );
					$liclass = apply_filters( 'wpiw_item_class', '' );
					$aclass = apply_filters( 'wpiw_a_class', '' );
					$imgclass = apply_filters( 'wpiw_img_class', '' );
					?><ul class="<?php echo esc_attr( $ulclass ); ?>"><?php
					foreach ( $media_array as $item ) {
						// copy the else line into a new file (parts/wp-instagram-widget.php) within your theme and customise accordingly
						if ( locate_template( 'parts/wp-instagram-widget.php' ) != '' ) {
							include locate_template( 'parts/wp-instagram-widget.php' );
						} else {
							echo '<li class="'. esc_attr( $liclass ) .'"><a href="'. esc_url( $item['link'] ) .'" target="'. esc_attr( $target ) .'"  class="'. esc_attr( $aclass ) .'"><img src="'. esc_url( $item[$size] ) .'"  alt="'. esc_attr( $item['description'] ) .'" title="'. esc_attr( $item['description'] ).'"  class="'. esc_attr( $imgclass ) .'"/></a></li>';
						}
					}
					?></ul><?php
					
				}
			}
		}
		if ( $size == 'original'){
		?>
					</div>
				</div>
			</section>
			<!-- End Instagram Content -->
		<?php
		}
		do_action( 'wpiw_after_widget', $instance );
		echo $args['after_widget'];
	}
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => esc_html__( 'Instagram', 'wp-instagram-widget' ), 'info' => '', 'username' => '', 'size' => 'original', 'link' => esc_html__( '', 'wp-instagram-widget' ), 'number' => 3, 'target' => '_self' ) );
		$title = $instance['title'];
		$info = $instance['info'];
		$username = $instance['username'];
		$number = absint( $instance['number'] );
		$size = $instance['size'];
		$target = $instance['target'];
		$link = $instance['link'];
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'info' ) ); ?>"><?php esc_html_e( 'Description', 'wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'info' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'info' ) ); ?>" type="text" value="<?php echo esc_attr( $info ); ?>" /></label></p>
		
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Username', 'wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>" /></label></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of photos', 'wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" /></label></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Photo size', 'wp-instagram-widget' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" class="widefat">
				<option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php esc_html_e( 'Thumbnail', 'wp-instagram-widget' ); ?></option>
				<option value="original" <?php selected( 'original', $size ) ?>><?php esc_html_e( 'Original', 'wp-instagram-widget' ); ?></option>
			</select>
		</p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Open links in', 'wp-instagram-widget' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" class="widefat">
				<option value="_self" <?php selected( '_self', $target ) ?>><?php esc_html_e( 'Current window (_self)', 'wp-instagram-widget' ); ?></option>
				<option value="_blank" <?php selected( '_blank', $target ) ?>><?php esc_html_e( 'New window (_blank)', 'wp-instagram-widget' ); ?></option>
			</select>
		</p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Link text', 'wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" /></label></p>
		<?php
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['info'] = strip_tags( $new_instance['info'] );
		$instance['username'] = trim( strip_tags( $new_instance['username'] ) );
		$instance['number'] = ! absint( $new_instance['number'] ) ? 9 : $new_instance['number'];
		$instance['size'] = ( ( $new_instance['size'] == 'thumbnail' || $new_instance['size'] == 'original' ) ? $new_instance['size'] : 'original' );
		$instance['target'] = ( ( $new_instance['target'] == '_self' || $new_instance['target'] == '_blank' ) ? $new_instance['target'] : '_self' );
		$instance['link'] = strip_tags( $new_instance['link'] );
		return $instance;
	}
	// based on https://gist.github.com/cosmocatalano/4544576
		function scrape_instagram( $username ) {
		$username = trim( strtolower( $username ) );
		switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
				$transient_prefix = 'h';
				break;
			default:
				$url              = 'https://instagram.com/' . str_replace( '@', '', $username );
				$transient_prefix = 'u';
				break;
		}
		if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {
			$remote = wp_remote_get( $url );
			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'wp-instagram-widget' ) );
			}
			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'wp-instagram-widget' ) );
			}
			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );
			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
			}
			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
			}
			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
			}
			$instagram = array();
			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}
				$caption = __( 'Instagram Image', 'wp-instagram-widget' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}
				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'time'        => $image['node']['taken_at_timestamp'],
					'comments'    => $image['node']['edge_media_to_comment']['count'],
					'likes'       => $image['node']['edge_liked_by']['count'],
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
					'type'        => $type,
				);
			} // End foreach().
			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) );
				set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}
		if ( ! empty( $instagram ) ) {
			return unserialize( base64_decode( $instagram ) );
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wp-instagram-widget' ) );
		}
	}
	function images_only( $media_item ) {
		if ( 'image' === $media_item['type'] ) {
			return true;
		}
		return false;
	}
}
class woss_cf7_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'woss_cf7_widget',
			esc_html__( 'Artday: Newsletter', 'woss_cf7_widget' ),
			array( 'classname' => 'follow-widget widget-space', 'description' => esc_html__( 'A widget that displays your Newsletter form.', 'woss_cf7_widget' ) )
		);
	}
	function widget( $args, $instance ) {
        extract($args);
		$title = $instance['title'];
        $widget_id = 'widget-' . $this->id_base . '-' . $this->number;
        
     
		echo $before_widget;
		?>
		<!-- Subscribe Section -->
		<div class="ws-subscribe-section">
			<div class="container">				
				<!-- Subscribe Content -->
				<div class="ws-subscribe-content text-center clearfix">
					<div class="col-sm-8 col-sm-offset-2">
						<?php
						if ( $title )
							echo $before_title . $title . $after_title;    
						?>                            
						<div class="ws-separator"></div>						
						<!-- Form -->
						<?php 
							$widget_text = empty($instance['form']) ? '' : stripslashes($instance['form']);
							echo do_shortcode('[contact-form-7 id="' . $widget_text . '"]');
						?>
					</div>                
				</div>				
			</div>
		</div>
		<!-- End Subscribe Section -->  
        <?php
		
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	function form( $instance ) { 
        //Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => false ));
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
		
	<p><label for="<?php echo $this->get_field_id('form'); ?>"><?php _e('Form:') ?></label>
<?php
		$cf7posts = new WP_Query( array( 'post_type' => 'wpcf7_contact_form' ));
		if ( $cf7posts->have_posts() ) {	
			?>
			<select class="widefat" name="<?php echo $this->get_field_name('form'); ?>" id="<?php echo $this->get_field_id('form'); ?>">
			<?php
			while( $cf7posts->have_posts() ) : $cf7posts->the_post();
				?><option value="<?php the_id(); ?>"<?php selected(get_the_id(), $instance['form']); ?>><?php the_title(); ?></option>
				<?php
			endwhile;
		}
		else //no posts disable form
		{	?>
			<select class="widefat" disabled>
			<option disabled="disabled"><?php esc_attr_e('No Forms Found', 'artday'); ?></option> <?php
		}
		?>		
		</select></p> 
		<?php
	}
}