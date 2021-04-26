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

?><fieldset id="step-1">

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
						wp_delete_post( $temp_coupon, true );?>

					</div>
				</div>
			</div>
		</div>
		<div class='clear'></div>

	</div>

	<div id="wc-coupon-generator-coupon-error" class="inline error" style="display:none;"><ul></ul></div>

	<div class="generate">

		<label for="number_of_coupons"><?php _e( 'Number of coupons to generate', 'coupon-generator-for-woocommerce' ); ?></label>
		<input type="number" min="1" autofocus class="short" style="width: 200px;" name="number_of_coupons" id="coupon_quantity" value="1" placeholder="10">

		<button type="submit" class="next button button-primary button-large">
			<span><?php _e( 'Generate', 'coupon-generator-for-woocommerce' ); ?></span>
		</button>

	</div>

</fieldset>
