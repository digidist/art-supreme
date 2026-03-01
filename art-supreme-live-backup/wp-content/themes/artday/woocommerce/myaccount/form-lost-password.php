<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.2
 */

defined( 'ABSPATH' ) || exit;

wc_print_notices(); ?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<form method="post" class="woocommerce-ResetPassword lost_reset_password">

			<?php if( 'lost_password' == $args['form'] ) : ?>

				<div class="text-center">
					<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
				</div>
				<div class="ws-separator"></div>

				<p><label for="user_login"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?></label> <input class="input-text" type="text" name="user_login" id="user_login" /></p>

			<?php else : ?>

				<div class="text-center">
					<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce') ); ?></p>
				</div>
				<div class="ws-separator"></div>

				<p>
					<label for="password_1"><?php esc_html_e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password_1" id="password_1" />
				</p>
				<p>
					<label for="password_2"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password_2" id="password_2" />
				</p>

				<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
				<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

			<?php endif; ?>

			<div class="clear"></div>

			<?php do_action( 'woocommerce_lostpassword_form' ); ?>

			<div class="padding-top-x20"></div>
			<p>
				<input type="hidden" name="wc_reset_password" value="true" />
				<input type="submit" class="btn ws-btn-fullwidth" value="<?php echo 'lost_password' == $args['form'] ? esc_html__( 'Reset password', 'woocommerce' ) : esc_html__( 'Save', 'woocommerce' ); ?>" />
			</p>

			<?php wp_nonce_field( $args['form'] ); ?>

		</form>
	</div>
</div>

