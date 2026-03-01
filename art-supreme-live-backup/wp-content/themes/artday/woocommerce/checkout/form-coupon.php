<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() || ! empty( WC()->cart->applied_coupons ) ) { // @codingStandardsIgnoreLine.
	return;
}

?>

<!-- Coupon -->            
<div class="ws-checkout-coupon">		
	<div class="coupon-info">
		<?php echo $info_message = apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'woocommerce' ) . ' <a data-toggle="collapse" href="#coupon-collapse">' . esc_html__( 'Click here to enter your code', 'woocommerce' ) . '</a>' ); ?>	
	</div>
	<div class="collapse" id="coupon-collapse">
		<!-- Coupon -->
		<div class="ws-checkout-coupon-code">  
					
			<form class="form-inline" method="post">

				<p><input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" /></p>
				
				<!-- Button -->
				<input type="submit" class="btn ws-btn-fullwidth" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />

				<div class="clear"></div>
			</form>
		</div>
	</div>		
</div>
