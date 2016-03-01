<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Coupon generator step 0.
 *
 * This is the initial step of the coupon generation process. It basically is a
 * explanatory page.
 *
 * @author		Jeroen Sormani
 * @package		WooCommerce Coupon Generator
 * @version		1.0.0
 */

?><div class='wrap'>

	<div class='wc-coupon-generator-wrap wc-coupon-generator-wrap-step-0'>

		<h2><?php _e( 'WooCommerce Coupon Generator', 'woocommerce-coupon-generator' ); ?></h2>

		<div class='steps'>
			<span class='step step-0 active'><a href='<?php echo esc_url( remove_query_arg( 'step' ) ); ?>'><?php _e( '0. Introduction', 'woocommerce-coupon-generator' ); ?></a></span>
			<span class='step step-1'><a href='<?php echo esc_url( add_query_arg( 'step', 1 ) ); ?>'><?php _e( '1. Coupon options', 'woocommerce-coupon-generator' ); ?></a></span>
			<span class='step step-2'><?php _e( '2. Generator options', 'woocommerce-coupon-generator' ); ?></span>
			<span class='step step-3'><?php _e( '3. Generating coupons', 'woocommerce-coupon-generator' ); ?></span>
		</div>

		<p><?php _e( 'Hi!', 'woocommerce-coupon-generator' ); ?></p>
		<p><?php _e( 'Thank you for using WooCommerce Coupon Generator. To use the coupon generator you have to go through the following steps.', 'woocommerce-coupon-generator' ); ?>
			<ul>
				<li>
					<strong><?php _e( '0. Introduction', 'woocommerce-coupon-generator' ); ?></strong>&nbsp;
					<span class='description'><?php _e( 'You are here now', 'woocommerce-coupon-generator' ); ?></span>
				</li>
				<li><?php _e( '1. Coupon settings', 'woocommerce-coupon-generator' ); ?>&nbsp;
					<span class='description'><?php _e( 'Here you can set the coupon settings you\'re used to set in the default WooCommerce coupon settings.', 'woocommerce-coupon-generator' ); ?></span>
				</li>
				<li><?php _e( '2. Generator settings', 'woocommerce-coupon-generator' ); ?>&nbsp;
					<span class='description'><?php _e( 'Set options like the amount of coupons you want to generate.', 'woocommerce-coupon-generator' ); ?></span>
				</li>
				<li><?php _e( '3. Generating coupons', 'woocommerce-coupon-generator' ); ?>&nbsp;
					<span class='description'><?php _e( 'This is the step where the coupons are actually generated.', 'woocommerce-coupon-generator' ); ?></span>
				</li>
			</ul>
		</p>
		<div class='clear'></div>

		<a href="<?php echo esc_url( add_query_arg( 'step', 1 ) ); ?>" class="continue-button-wrap">
			<span class="continue-button"><?php _e( 'Continue to the next step', 'woocommerce-coupon-generator' ); ?></span>
		</a>

	</div>

</div>
