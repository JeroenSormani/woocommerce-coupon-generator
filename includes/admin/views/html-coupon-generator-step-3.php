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

?>
	<div class='wc-coupon-generator-wrap wc-coupon-generator-wrap-step-3 hidden'>
		<div class='wc-coupon-generator-coupon-data' id='poststuff'>

			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div id="wc-coupon-generator-options" class="postbox ">
						<h3 class="hndle ui-sortable-handle"><span><?php _e( 'Generating coupons', 'coupon-generator-for-woocommerce' ); ?></span></h3>
						<div class="inside">

							<div id="coupon_options" class="panel-wrap">
								<div class="panel woocommerce_options_panel" style="display: block;">

									<div class='wc-coupon-generator-progress-bar-wrap'>
										<div class="wc-coupon-generator-progress-bar">
											<div class="wc-coupon-generator-progress-percentage">0%</div>
											<span class="progress">
												<span class='inner-progress wc-coupon-generator-progress-percentage'>0%</span>
											</span>
										</div>
										<span class="spinner is-active"></span>
									</div>

									<!-- Messages -->
									<div class='wc-coupon-generator-progress-messages-wrap'>
										<pre class='wc-coupon-generator-progress-messages'></pre>
									</div>

									<div class="wc-coupon-generator-completed-actions" style="display: none;">
										<a type="button" class="button button-secondary" href="<?php echo wp_nonce_url( admin_url( '?action=wccg-export-coupons&quantity=' ), 'wccg-export-coupons' ); ?>">
											<?php _e( 'Export as txt file', 'coupon-generator-for-woocommerce' ); ?>
										</a>
										<p class="description"><?php echo __( 'Export the last {{ couponGenerator.quantity }} coupons created', 'woocommerce-advanced-shipping' ); ?></p>
									</div>

								</div><!-- .woocommerce_options_panel -->
								<div class="clear"></div>
							</div><!-- #coupon_options -->

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
