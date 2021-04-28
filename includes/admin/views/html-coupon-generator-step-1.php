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

?>
	<div class='wc-coupon-generator-wrap wc-coupon-generator-wrap-step-1 hidden'>
		<div class='wc-coupon-generator-coupon-data' id='poststuff'>

			<div id="post-body-content">

				<div id="titlediv">
					<div class="inside"></div>
					<textarea id="woocommerce-coupon-description" name="excerpt" cols="5" rows="2" placeholder="<?php esc_attr_e( 'Description (optional)', 'woocommerce' ); ?>"></textarea>
				</div>
			</div>

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

			<div class="continue-button-wrap">
				<a href="javascript:void(0);"  class="wccg-next"><?php _e( 'Continue to the next step', 'coupon-generator-for-woocommerce' ); ?></a>
			</div>
		</div>
	</div>
