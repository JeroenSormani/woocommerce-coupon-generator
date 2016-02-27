<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Coupon generator step 2
 *
 * In this step the generation options of the coupon are shown.
 *
 * @author		Jeroen Sormani
 * @package		WooCommerce Coupon Generator
 * @version		1.0.0
 */

?><div class='wrap'>

	<div class='wc-coupon-generator-wrap wc-coupon-generator-wrap-step-2'>

		<h2><?php _e( 'WooCommerce Coupon Generator', 'woocommerce-coupon-generator' ); ?></h2>

		<div class='steps'>
			<span class='step step-0'><a href='<?php echo esc_url( remove_query_arg( 'step' ) ); ?>'><?php _e( '0. Introduction', 'woocommerce-coupon-generator' ); ?></a></span>
			<span class='step step-1'><a href='<?php echo esc_url( add_query_arg( 'step', 1 ) ); ?>'><?php _e( '1. Coupon options', 'woocommerce-coupon-generator' ); ?></a></span>
			<span class='step step-2 active'><?php _e( '2. Generator options', 'woocommerce-coupon-generator' ); ?></span>
			<span class='step step-3'><?php _e( '3. Generating coupons', 'woocommerce-coupon-generator' ); ?></span>
		</div>

		<form method='post' id='woocommerce-coupon-generator' action='<?php echo esc_url( add_query_arg( 'step', 3 ) ); ?>'><?php
			wp_nonce_field( 'wccg_generate_coupons', 'generate_coupons_nonce' );

			?><div class='wc-coupon-generator-coupon-data' id='poststuff'>

				<div id="postbox-container-0" class="postbox-container">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div id="woocommerce-coupon-generator-options" class="postbox ">
							<h3 class="hndle ui-sortable-handle"><span><?php _e( 'Generator options', 'woocommerce-coupon-generator' ); ?></span></h3>
							<div class="inside">

								<div id="coupon_options" class="panel-wrap">
									<div class="panel woocommerce_options_panel" style="display: block;">

										<p class="form-field number_of_coupons ">
											<label for="number_of_coupons"><?php _e( 'Number of coupons', 'woocommerce-coupon-generator' ); ?></label>
											<input type="number" min="1" autofocus class="short" style="width: 200px;" name="number_of_coupons" id="coupon_amount" value="1" placeholder="10">
											<img class="help_tip" data-tip='<?php _e( 'Number of coupons to generate in this batch', 'woocommerce-coupon-generator' ); ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
										</p>

									</div><!-- .woocommerce_options_panel -->
									<div class="clear"></div>
								</div><!-- #coupon_options -->

							</div><!-- .inside -->
						</div>
					</div>
				</div>

			</div>
			<div class='clear'></div>

			<a href="javascript:void(0);" class="continue-button-wrap" onclick="document.getElementById('woocommerce-coupon-generator').submit();">
				<span class="continue-button"><?php _e( 'Continue and generate coupons', 'woocommerce-coupon-generator' ); ?></span>
			</a><?php

			// Keep existing post values
			foreach ( $_POST as $key => $val ) :

				if ( is_array( $val ) ) :
					foreach ( $val as $inner_val ) :
						?><input type="hidden" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( $inner_val ); ?>" /><?php
					endforeach;
				else :
					?><input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" /><?php
				endif;

			endforeach;

		?></div>
	</form>

</div>
