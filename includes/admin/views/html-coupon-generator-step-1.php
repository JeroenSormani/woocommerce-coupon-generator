<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Coupon generator step 1.
 *
 * In this step the options of the coupon are shown as known from the regular
 * add coupon screen.
 *
 * @author		Jeroen Sormani
 * @package		WooCommerce Coupon Generator
 * @version		1.0.0
 */

?><div class='wrap'>

	<div class='wc-coupon-generator-wrap wc-coupon-generator-wrap-step-1'>

		<h2><?php _e( 'WooCommerce Coupon Generator', 'woocommerce-coupon-generator' ); ?></h2>

		<div class='steps'>
			<span class='step step-0'><a href='<?php echo esc_url( remove_query_arg( 'step' ) ); ?>'><?php _e( '0. Introduction', 'woocommerce-coupon-generator' ); ?></a></span>
			<span class='step step-1 active'><?php _e( '1. Coupon options', 'woocommerce-coupon-generator' ); ?></span>
			<span class='step step-2'><?php _e( '2. Generator options', 'woocommerce-coupon-generator' ); ?></span>
			<span class='step step-3'><?php _e( '3. Generating coupons', 'woocommerce-coupon-generator' ); ?></span>
		</div>

		<form method='post' id='wc-coupon-generator' action='<?php echo esc_url( add_query_arg( 'step', 2 ) ); ?>'>
			<div class='wc-coupon-generator-coupon-data' id='poststuff'>

				<div id="postbox-container-2" class="postbox-container">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div id="woocommerce-coupon-data" class="postbox ">
							<h3 class="hndle ui-sortable-handle"><span><?php _e( 'Coupon Data', 'woocommerce' ); ?></span></h3>
							<div class="inside"><?php

								$temp_coupon = wp_insert_post( array(
									'post_type'   => 'shop_coupon',
									'post_status' => 'draft',
									'post_title'  => 'temp_generator_coupon',
								) );
								global $thepostid;
								$thepostid = $temp_coupon;
								WC_Meta_Box_Coupon_Data::output( (object) array( 'ID' => null ) );
								wp_delete_post( $temp_coupon, true );

							?></div>
						</div>
					</div>
				</div>
				<div class='clear'></div>

				<a href="javascript:void(0);" class="continue-button-wrap" onclick="document.getElementById('wc-coupon-generator').submit();">
					<span class="continue-button"><?php _e( 'Continue to the next step', 'woocommerce-coupon-generator' ); ?></span>
				</a>

			</div>
		</form>

	</div>

</div>
