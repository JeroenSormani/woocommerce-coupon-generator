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

?>
	<h2><?php _e( 'WooCommerce Coupon Generator', 'coupon-generator-for-woocommerce' ); ?></h2>

	<div class='steps'>
		<span class='step step-0 active'><a href='<?php echo esc_url( remove_query_arg( 'step' ) ); ?>'><?php _e( '0. Introduction', 'coupon-generator-for-woocommerce' ); ?></a></span>
		<span class='step step-1'><a href='<?php echo esc_url( add_query_arg( 'step', 1 ) ); ?>'><?php _e( '1. Coupon options', 'coupon-generator-for-woocommerce' ); ?></a></span>
		<span class='step step-2'><?php _e( '2. Generator options', 'coupon-generator-for-woocommerce' ); ?></span>
		<span class='step step-3'><?php _e( '3. Generating coupons', 'coupon-generator-for-woocommerce' ); ?></span>
	</div>

	<div class='wc-coupon-generator-wrap wc-coupon-generator-wrap-step-0 active-step'>

		<p><?php _e( 'Hi!', 'coupon-generator-for-woocommerce' ); ?></p>
		<p><?php _e( 'Thank you for using WooCommerce Coupon Generator. To use the coupon generator you have to go through the following steps.', 'coupon-generator-for-woocommerce' ); ?>
			<ul>
				<li>
					<strong><?php _e( '0. Introduction', 'coupon-generator-for-woocommerce' ); ?></strong>&nbsp;
					<span class='description'><?php _e( 'You are here now', 'coupon-generator-for-woocommerce' ); ?></span>
				</li>
				<li><?php _e( '1. Coupon settings', 'coupon-generator-for-woocommerce' ); ?>&nbsp;
					<span class='description'><?php _e( 'Here you can set the coupon settings you\'re used to set in the default WooCommerce coupon settings.', 'coupon-generator-for-woocommerce' ); ?></span>
				</li>
				<li><?php _e( '2. Generator settings', 'coupon-generator-for-woocommerce' ); ?>&nbsp;
					<span class='description'><?php _e( 'Set options like the amount of coupons you want to generate.', 'coupon-generator-for-woocommerce' ); ?></span>
				</li>
				<li><?php _e( '3. Generating coupons', 'coupon-generator-for-woocommerce' ); ?>&nbsp;
					<span class='description'><?php _e( 'This is the step where the coupons are actually generated.', 'coupon-generator-for-woocommerce' ); ?></span>
				</li>
			</ul>
		</p>
		<div class='clear'></div>

		<div class="continue-button-wrap">
			<a href="javascript:void(0);"  class="wccg-next"><?php _e( 'Continue to the next step', 'coupon-generator-for-woocommerce' ); ?></a>
		</div>

	</div>
