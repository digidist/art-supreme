<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="row">
<div class="col-sm-6 col-sm-offset-3">

	<form method="post" class="ws-login-form">

		<?php do_action( 'woocommerce_login_form_start' ); ?>

		 <div class="form-group">
			<label for="username" class="control-label"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="text" class="form-control" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
		</div>
		<div class="form-group">
			<label for="password" class="control-label"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input class="form-control" type="password" name="password" id="password" />
		</div>			

		<!-- Checkbox -->
		<div class="pull-left">
			<div class="checkbox">
				<label for="rememberme" class="inline">
					<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember me', 'woocommerce' ); ?>
				</label>
			</div>
		</div>
		<div class="pull-right">
			<div class="ws-forgot-pass">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</div> 
		</div>
		
		<div class="clearfix"></div>			

		<?php do_action( 'woocommerce_login_form' ); ?>

		<!-- Button -->
		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
		<input type="submit" class="btn ws-btn-fullwidth" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>" />
		<div class="padding-top-x20"></div>		
		<!-- Facebook Button -->
		<?php do_action('facebook_login_button'); ?>
		
		<?php do_action( 'woocommerce_login_form_end' ); ?>

	</form>
	<!-- End Login Form --> 

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	<!-- Register Form-->
	<div class="ws-register-form">

		<!-- Link -->
		<div class="ws-register-link">
			<a href="#ws-register-modal" data-toggle="modal"><?php esc_html_e( 'Click here to sign up for an account.', 'woocommerce' ); ?> </a>
		</div>

		<!-- Register Modal -->
		<div class="modal fade" id="ws-register-modal" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">

					<div class="modal-header">
						<a class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>                                       
					</div>								

					<div class="modal-body">  

					<div class="row">
						<div class="ws-register-modal-content">
							<!-- Register Form -->										
							<form method="post" class="ws-register-form register">
								<?php do_action( 'woocommerce_register_form_start' ); ?>
								
								<h3><?php esc_html_e( 'Create An Account', 'woocommerce' ); ?></h3>
								<div class="ws-separator"></div>

								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
									
									<!-- Name -->
									<div class="form-group">
										<label for="reg_username" class="control-label"><?php esc_html_e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
										<input type="text" class="form-control" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
									</div>

								<?php endif; ?>
								
								<!-- Email -->
								<div class="form-group">
									<label for="reg_email" class="control-label"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
									<input type="email" class="form-control" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
								</div>

								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

									<div class="form-group">
										<label for="reg_password" class="control-label"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
										<input type="password" class="form-control" name="password" id="reg_password" />
									</div>

								<?php endif; ?>

								<!-- Spam Trap -->
								<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php esc_html_e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

								<?php do_action( 'woocommerce_register_form' ); ?>
								<?php do_action( 'register_form' ); ?>

								<?php do_action( 'woocommerce_register_form_end' ); ?>

								<div class="modal-footer">
									<div class="padding-top-x30"></div>	
									<!-- Button -->
									<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
									<input type="submit" class="btn ws-btn-fullwidth" name="register" value="<?php esc_attr_e( 'Create Account', 'woocommerce' ); ?>" />
									<div class="padding-top-x20"></div>										
									<?php do_action('facebook_login_button');?>
									<!-- Link -->
									<div class="ws-register-link">
										<a href="#ws-register-modal" data-toggle="modal"><?php esc_html_e( 'Already have an account? Sign in here.', 'woocommerce' ); ?></a>
									</div>
								</div>
							</form>
						</div>
					</div>	

					</div>										

				</div>
			</div>
		</div>
		<!-- End Register Modal -->
	</div>
	<!-- End Register -->

</div>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
