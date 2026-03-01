<?php
//
// Nav Cart
//

if ( ! function_exists( 'woss_woo_nav_cart' ) ) :
function woss_woo_nav_cart() {
	global $woocommerce;

	$cart_count = $woocommerce->cart->get_cart_contents_count(); ?>
	
	<div class="pull-right">   

		<!-- Shop Menu -->   
		<ul class="ws-shop-menu">

			<!-- Account -->
			<li class="ws-shop-account">
				<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="btn btn-sm" title="<?php esc_html_e('My Account','artday'); ?>"><?php esc_html_e('My Account','artday'); ?></a>
			</li>
						
			<!-- Cart -->
			<li class="ws-shop-cart">
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'cart' ) ) ); ?>" class="btn cart-top-btn btn-sm"><?php esc_html_e('Cart','artday'); ?><?php echo '(' . absint($cart_count) . ')' ?></a>                            

				<!-- Cart Popover -->
				<div class="ws-shop-minicart">                
					<div class="minicart-content">

						<?php if ( ! WC()->cart->is_empty() ) : ?>
						
							<!-- Added Items -->
							<ul class="minicart-content-items clearfix">
		
							<?php
								foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
									$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
									$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

									if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

										$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
										$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
										$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
										
										<!-- Item -->                                
										<li class="<?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?> media">
											<div class="media-left">
												<?php if ( ! $_product->is_visible() ) : ?>
													<?php echo str_replace( array( 'http:', 'https:' ), '', $_product->get_image()  ); ?>
												<?php else : ?>												
													<!-- Image -->
													<a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>">
														<?php echo str_replace( array( 'http:', 'https:' ), '', $_product->get_image(  )  ); ?>
													</a>
												<?php endif; ?>
											</div>
											
											<div class="minicart-content-details media-body">
												<!-- Title -->
												<h3><a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>"><?php echo $product_name; ?></a></h3>                        

												<!-- Price -->                                
												<span class="minicart-content-price"><?php echo $product_price; ?></span>
											</div>

											<!-- Remove -->                  
											<?php
												echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
													'<span class="minicart-content-remove"><a href="%s" title="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-times"></i></a></span>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'artday' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												), $cart_item_key );
											?>
										</li>
									<?php
									}
								}
							?>										
							</ul> 
					

						<!-- Subtotal -->
						<div class="minicart-content-total">
							<h3><?php esc_html_e( 'Subtotal', 'artday' ); ?>: <?php echo WC()->cart->get_cart_subtotal(); ?></h3>
						</div>
	

						<!-- Checkout -->
						<div class="ws-shop-menu-checkout">                    
							<div class="ws-shop-viewcart pull-left">
								<a href="<?php echo esc_url( wc_get_cart_url () ); ?>" class="btn btn-sm"><?php esc_html_e( 'View Cart', 'artday' ); ?></a>    
							</div>
							<div class="ws-shop-checkout pull-right">
								<a href="<?php echo esc_url( wc_get_checkout_url ()  ); ?>" class="btn btn-sm"><?php esc_html_e( 'Checkout', 'artday' ); ?></a>   
							</div>
						</div> 

					<?php else : ?>

						<h3 class="ws-shop-noproducts">
							<?php esc_html_e( 'No products in the cart.', 'artday' ); ?>
						</h3>

						<div class="ws-shop-noproducts-btn">
							<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="btn ws-btn-fullwidth"><?php esc_html_e( 'Visit Shop', 'artday' ); ?></a>
						</div>

					<?php endif; ?>							
					</div>
				</div>    
				<!-- End Cart Popover -->

			</li>    
		</ul>     
	</div>

<?php 
}
endif; // Nav cart

add_filter( 'woocommerce_breadcrumb_defaults', 'woss_woocommerce_breadcrumbs' );
if(!function_exists('woss_woocommerce_breadcrumbs')){
function woss_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => '',
            'wrap_before' => '<div class="ws-breadcrumb col-sm-12"><div class="container"><ol class="breadcrumb">',
            'wrap_after'  => '</ol></div></div>',
            'before'      => '<li>',
            'after'       => '</li>',
            'home'        => esc_html_x( 'Home', 'breadcrumb', 'woocommerce' ),
        );
}
}

function woss_custom_breadcrumbs() {
    if( is_shop() || is_archive() ){
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

		$taxonomy     = 'product_cat';
		$orderby      = 'name';  
		$show_count   = 0;      // 1 for yes, 0 for no
		$pad_counts   = 0;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no  
		$title        = '';  
		$empty        = 0;

		$args = array(
			 'taxonomy'     => $taxonomy,
			 'orderby'      => $orderby,
			 'show_count'   => $show_count,
			 'pad_counts'   => $pad_counts,
			 'hierarchical' => $hierarchical,
			 'title_li'     => $title,
			 'hide_empty'   => $empty
		);
		$all_categories = get_categories( $args );
		$woss_cat_all_class ='cat_item';
		if( is_shop() ){ $woss_cat_all_class .=' active';}
		
		echo '<div class="col-sm-12"><ul class="ws-shop-nav">
				<li class="'.esc_attr($woss_cat_all_class).'">
					<a href="'.esc_url(get_permalink( wc_get_page_id( 'shop' ) ) ).'">'.esc_html__('All','artday').'</a>
				</li>';
		foreach ($all_categories as $cat) {
			if($cat->category_parent == 0) {
				$category_id = $cat->term_id;
				$woss_cat_li_class ='cat_item';
				if( is_archive() ){
					$obj = get_queried_object();
					if($obj->name == $cat->name ){$woss_cat_li_class .=' active';}	
				}

				echo '<li class="'.esc_attr($woss_cat_li_class).'"><a href="'. esc_url( get_term_link($cat->slug, 'product_cat') ).'">'. esc_html($cat->name) .'</a></li>';	
			}       
		}
		echo '</ul></div>';
	}
}
add_filter('woocommerce_before_main_content','woss_custom_breadcrumbs');


/* Woocommerce settings */
function woss_woo_settings(){
	
	/* Display number of product per page */
	add_filter( 'loop_shop_per_page' , 'woss_loop_shop_per_page', 20 );
	function woss_loop_shop_per_page(){
		$woss_shop_count_products=woss_theme_data('shop_count_products', false);
		if($woss_shop_count_products){
			return intval($woss_shop_count_products);
		}
	}
	
	/* Remove page title */
	add_filter( 'woocommerce_show_page_title' , 'woss_hide_page_title' );
	function woss_hide_page_title(){
		return;
	}
	
	/* Add product subtitle*/
	add_filter( 'woocommerce_shop_loop_item_title', 'woss_add_subtitle', 5 );
	function woss_add_subtitle(){
		$woss_alternate_title=get_post_meta(get_the_ID(), '_wscmb_shop_subtitle', true);
		echo '<span class="ws-item-subtitle">' . esc_html($woss_alternate_title) . '</span>';
	}
	
	/* Single title */
	remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
	add_action('woocommerce_single_product_summary', 'woss_woocommerce_my_single_title',5);

	if ( ! function_exists( 'woss_woocommerce_my_single_title' ) ) {
	   function woss_woocommerce_my_single_title() {
		   $woss_alternate_title=get_post_meta(get_the_ID(), '_wscmb_shop_subtitle', true);
			echo '<span class="ws-item-subtitle">' . esc_html($woss_alternate_title) . '</span>';
		?>
			<h1 itemprop="name" class="product_title entry-title"><span><?php the_title(); ?></span></h1>
			<div class="ws-separator"></div>
		<?php
		}
	}

	

	/* Change add_to_cart to top */
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 10 );
	/* Remover catalog ordering and result count on archive page */
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	/* Remover category description on archive page */
	remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
	/* Remove Add to cart button from shop page */
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	/* Remove reviews from shop page */
	//remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	
	/* Product thumbnail on archive page*/
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'woss_template_loop_product_thumbnail', 10 );
	
	function woss_template_loop_product_thumbnail() {
		echo woss_get_product_thumbnail();
	}
	
	if ( ! function_exists( 'woss_get_product_thumbnail' ) ) {

		function woss_get_product_thumbnail( $size = 'shop_catalog', $deprecated1 = 0, $deprecated2 = 0 ) {
			global $post;
			
			$woss_product_background=woss_theme_data('product_background', false);
			$woss_product_background_class = 'ws-product-empty-bg';
			if($woss_product_background == true){
				$woss_product_background_class = 'ws-product-bg';
			}
			if ( has_post_thumbnail() ) {
				return '<figure class="' . esc_attr($woss_product_background_class) . '">' . get_the_post_thumbnail( $post->ID, $size ) . '</figure>';
			} elseif ( wc_placeholder_img_src() ) {
				return '<figure>' . wc_placeholder_img( $size ) . '</figure>';
			}
			
		}
	}
	
	
}
add_action('init','woss_woo_settings');

/* Custom header background on archive and shop page */
if(!function_exists('woss_custom_header_background')){
function woss_custom_header_background(){
	if(!is_single() ){

		$pageID = get_option('woocommerce_shop_page_id');
	
		$woss_alternate_title=get_post_meta($pageID, '_wscmb_page_alternate_title', true);
		$woss_alternate_background=get_post_meta($pageID, '_wscmb_page_alternate_background', true);
		
		if($woss_alternate_background): ?>
			<!-- Page Parallax Header -->
			<div class="ws-parallax-header parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url($woss_alternate_background) ?>">        
				<div class="ws-overlay">            
					<div class="ws-parallax-caption">                
						<div class="ws-parallax-holder">
							<?php
								if( is_shop() ){
									echo '<h1>';
									if( $woss_alternate_title == '' ){ woocommerce_page_title(); } else { echo esc_attr($woss_alternate_title); }
									echo '</h1>';
								} else{
									echo '<h1>';
										woocommerce_page_title();
									echo '</h1>';
									woocommerce_taxonomy_archive_description();
								}
							?>
						</div>
					</div>
				</div>            
			</div>            
			<!-- End Page Parallax Header -->
		<?php endif; ?>
		<?php
	}
	
}
}
add_filter('woocommerce_before_main_content','woss_custom_header_background', 5);

/* Change number of related products on product page */
add_filter( 'woocommerce_output_related_products_args', 'woss_related_products_args' );
  function woss_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 3 related products
	return $args;
}

/* Change number of related products on product page */
add_action( 'woocommerce_product_meta_end', 'woss_woocommerce_share', 1 );
if(!function_exists('woss_woocommerce_share')){
function woss_woocommerce_share( $args ) {
	  
	?>
		<!-- Social Links -->
		<div class="ws-product-description">            
            <div class="ws-product-social-icon">                
            	<span><?php esc_html_e('Share:', 'artday'); ?></span>
				<a class="facebook-sharer" href="#x" onClick="<?php echo esc_js('twitterSharer()'); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x fa-inverse"></i></span></a>
                <a class="twitter-sharer" href="#x" onClick="<?php echo esc_js('facebookSharer()'); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x fa-inverse"></i></span></a> 
                <a class="pinterest-sharer" href="#x" onClick="<?php echo esc_js('pinterestSharer()'); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pinterest fa-stack-1x fa-inverse"></i></span></a>
            </div>                                            
        </div>
	<?php
}
}

/* Preview image on single product page */
add_action( 'woss_after_main_content_preview_image', 'woss_woocommerce_preview_image', 5 );
  function woss_woocommerce_preview_image() {
	  $woss_preview_image=get_post_meta(get_the_ID(), '_wscmb_shop_preview_image', true);
	  if($woss_preview_image){
		 echo '<div class="ws-preview-image"><img src="'.esc_url($woss_preview_image).'" alt="'.get_the_title().'"/></div>'; 
	  }
}

/* Related products change display place */
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20);
add_action('woss_after_main_content', 'woocommerce_output_related_products',20);

/* Upsell products change display place */
remove_action('woocommerce_after_single_product_summary','woocommerce_upsell_display',15);
add_action('woss_after_main_content', 'woocommerce_upsell_display',15);