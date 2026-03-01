<?php $woss_menu_style=woss_theme_data('nav_style');
$woss_top_head_text=woss_theme_data('top_head_text');

if (is_page_template('template-onepage.php')) {
	$woss_menu_style = 'style4';
	wp_enqueue_script('easing');
}
if(!$woss_menu_style) {
    $woss_menu_style = 'style3';
}
$woss_header_class ='ws-header-static';
if( is_front_page() ){
	$woss_header_class = 'ws-header-transparent';
}
?>

<?php if( $woss_menu_style !== 'style4' ){ ?>
	<!-- Top Bar Start -->
	<div class="ws-topbar">
		<div class="pull-left">
			<div class="ws-topbar-message hidden-xs">
				<p><?php echo esc_attr($woss_top_head_text); ?></p>
			</div>
		</div>
		<?php if (class_exists('Woocommerce')) { woss_woo_nav_cart(); } ?>
	</div>
	<!-- Top Bar End -->
<?php } ?>

<?php
if( $woss_menu_style == 'style1' ) {
?>

	<!-- Header Start -->
	<header class="ws-header <?php echo esc_attr($woss_header_class); ?>">

		<!-- Navbar -->
		<nav class="navbar ws-navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<!-- Logo -->
				<div class="ws-logo ws-center">
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<?php
							if( is_front_page() ){
								$logo = woss_theme_data('logo_second');
								if ($logo and isset($logo['url']) and $logo['url']) { ?>
									<img src="<?php echo esc_url($logo['url']) ?>" alt="<?php bloginfo('name'); ?>"><?php
								} else { ?>
									<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-white.png" alt="<?php bloginfo('name'); ?>"><?php
								}
							}else{
								$logo = woss_theme_data('logo');
								if ($logo and isset($logo['url']) and $logo['url']) { ?>
									<img src="<?php echo esc_url($logo['url']) ?>" alt="<?php bloginfo('name'); ?>"><?php
								} else { ?>
									<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-black.png" alt="<?php bloginfo('name'); ?>"><?php
								}
							}
						?>
					</a>
				</div>

			   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				   <?php
					if (has_nav_menu('left_nav')) {
						wp_nav_menu(array(
							'theme_location' => 'left_nav',
							'walker' => new wp_bootstrap_navwalker(),
							'menu_class' => 'nav navbar-nav navbar-left',));
					} else {
						?>
						<div class="ws-nav-notset text-left">
							<span><?php echo esc_html_e('Make your menu at Appearance => Menus', 'artday'); ?></span><br />
							<span class="ws-nav-notset-subtitle"><?php echo esc_html_e('After check Left Section and Right Section theme locations', 'artday'); ?></span>
						</div>
					<?php } ?>

					<?php
					if (has_nav_menu('right_nav')) {
						wp_nav_menu(array(
							'theme_location' => 'right_nav',
							'walker' => new wp_bootstrap_navwalker(),
							'menu_class' => 'nav navbar-nav navbar-right',));
					} ?>
				</div>
			</div>
		</nav>
	</header>
	<!-- End Header -->

<?php }

if( $woss_menu_style == 'style2' ) {
?>
	<!-- Header Start -->
    <header class="ws-header ws-header-third <?php echo esc_attr($woss_header_class); ?>">
        <!-- Logo -->
		<div class="ws-logo">
			<a href="<?php echo esc_url(home_url('/')); ?>">
				<?php
					if( is_front_page() ){
						$logo = woss_theme_data('logo_second');
						if ($logo and isset($logo['url']) and $logo['url']) { ?>
							<img src="<?php echo esc_url($logo['url']) ?>" alt="<?php bloginfo('name'); ?>"><?php
						} else { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-white.png" alt="<?php bloginfo('name'); ?>"><?php
						}
					}else{
						$logo = woss_theme_data('logo');
						if ($logo and isset($logo['url']) and $logo['url']) { ?>
							<img src="<?php echo esc_url($logo['url']) ?>" alt="<?php bloginfo('name'); ?>"><?php
						} else { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-black.png" alt="<?php bloginfo('name'); ?>"><?php
						}
					}
				?>
			</a>
		</div>

        <!-- Navbar -->
        <nav class="navbar ws-navbar navbar-default">
            <div class="container">
	            <div class="navbar-header">
	                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
	            </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<?php
						if (has_nav_menu('primary')) {
						wp_nav_menu(array(
							'theme_location' => 'primary',
							'walker' => new wp_bootstrap_navwalker(),
							'menu_class' => 'nav navbar-nav',));
						} else {
						?>
						<div class="ws-nav-notset text-center">
							<span><?php echo esc_html_e('Make your menu at Appearance => Menus', 'artday'); ?></span><br />
							<span class="ws-nav-notset-subtitle"><?php echo esc_html_e('After check Navigation Inline theme location', 'artday'); ?></span>
						</div>
					<?php } ?>
				</div>
            </div>
        </nav>
    </header>
    <!-- End Header -->

<?php }

if( $woss_menu_style == 'style3' ) {
?>

	<!-- Header Start -->
    <header class="ws-header-static">

        <!-- Navbar -->
        <nav class="navbar ws-navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- Logo -->
				<div class="ws-logo ws-center">
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<?php
						$logo = woss_theme_data('logo');
						if ($logo and isset($logo['url']) and $logo['url']) { ?>
							<img src="<?php echo esc_url($logo['url']) ?>" alt="<?php bloginfo('name'); ?>"><?php
						} else { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-black.png" alt="<?php bloginfo('name'); ?>"><?php
						} ?>
					</a>
				</div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

					<?php
					if (has_nav_menu('left_nav')) {
						wp_nav_menu(array(
							'theme_location' => 'left_nav',
							'walker' => new wp_bootstrap_navwalker(),
							'menu_class' => 'nav navbar-nav navbar-left',));
					} else {
						?>
						<div class="ws-nav-notset text-left">
							<span><?php echo esc_html_e('Make your menu at Appearance => Menus', 'artday'); ?></span><br />
							<span class="ws-nav-notset-subtitle"><?php echo esc_html_e('After check Left Section and Right Section theme locations', 'artday'); ?></span>
						</div>
					<?php } ?>

					<?php
					if (has_nav_menu('right_nav')) {
						wp_nav_menu(array(
							'theme_location' => 'right_nav',
							'walker' => new wp_bootstrap_navwalker(),
							'menu_class' => 'nav navbar-nav navbar-right',));
					} ?>

                </div>
            </div>
        </nav>
    </header>
    <!-- End Header -->

<?php }

if( $woss_menu_style == 'style4' ) {
?>

    <!-- Header Start -->
    <header class="ws-header ws-header-fourth <?php echo esc_attr($woss_header_class); ?>">

      <!-- Desktop Nav -->
      <nav class="navbar navbar-default">
        <div class="container-fluid ws-container-fluid">

          <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
          </div>

          <!-- Logo -->
          <div class="col-sm-1">
            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
              <?php
				$logo = woss_theme_data('logo');
				if ($logo and isset($logo['url']) and $logo['url']) { ?>
				<img src="<?php echo esc_url($logo['url']) ?>" alt="<?php bloginfo('name'); ?>"><?php
				} else { ?>
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-black.png" alt="<?php bloginfo('name'); ?>"><?php
				}
              ?>
            </a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="col-sm-10">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <?php if (is_page_template('template-onepage.php')) { $artday_theme_location = 'onepage'; } else{ $artday_theme_location = 'primary'; }
                if (has_nav_menu($artday_theme_location)) {
                wp_nav_menu(array(
                'theme_location' => $artday_theme_location,
                'walker' => new wp_bootstrap_navwalker(),
                'menu_class' => 'nav navbar-nav',));
                } else {
              ?>
              <div class="ws-nav-notset text-center">
                <span><?php echo esc_html_e('Make your menu at Appearance => Menus', 'artday'); ?></span><br />
                <span class="ws-nav-notset-subtitle"><?php echo esc_html_e('After check theme location', 'artday'); ?></span>
              </div>
              <?php } ?>
            </div>
          </div>

		  <?php if (class_exists('Woocommerce')) { ?> 
			  <!-- Cart -->
			  <div class="col-sm-1">
				<div class="ws-header-cart">
				  <?php global $woocommerce; $cart_count = $woocommerce->cart->get_cart_contents_count(); ?>
				  <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'cart' ) ) ); ?>"><?php esc_html_e('Cart','artday'); ?><?php echo '(' . absint($cart_count) . ')' ?></a>
				</div>
			  </div>
		  <?php } ?>

        </div>
      </nav>
    </header>
    <!-- End Header -->

<?php }
