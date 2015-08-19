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

	<div class='wc-coupon-generator-wrap wc-coupon-generator-wrap-step-3'>

		<h2><?php _e( 'WooCommerce Coupon Generator', 'woocommerce-coupon-generator' ); ?></h2>

		<div class='steps'>
			<span class='step step-0'><a href='<?php echo esc_url( remove_query_arg( 'step' ) ); ?>'><?php _e( '0. Introduction', 'woocommerce-coupon-generator' ); ?></a></span>
			<span class='step step-1'><a href='<?php echo esc_url( add_query_arg( 'step', 1 ) ); ?>'><?php _e( '1. Coupon options', 'woocommerce-coupon-generator' ); ?></a></span>
			<span class='step step-2'><?php _e( '2. Generator options', 'woocommerce-coupon-generator' ); ?></span>
			<span class='step step-3 active'><?php _e( '3. Generating coupons', 'woocommerce-coupon-generator' ); ?></span>
		</div>

		<div class='wc-coupon-generator-coupon-data' id='poststuff'>

			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div id="woocommerce-coupon-generator-options" class="postbox ">
						<h3 class="hndle ui-sortable-handle"><span><?php _e( 'Generating coupons', 'woocommerce-coupon-generator' ); ?></span></h3>
						<div class="inside">

							<div id="coupon_options" class="panel-wrap">
								<div class="panel woocommerce_options_panel" style="display: block;">

									Processing coupons....<br/>
									<?php echo $_POST['number_of_coupons'] . ' created in ' . $time . ' seconds'; ?><br/>
									<?php echo $_POST['number_of_coupons'] . ' created in ' . $time_time . ' seconds'; ?><br/>

								</div><!-- .woocommerce_options_panel -->
								<div class="clear"></div>
							</div><!-- #coupon_options -->

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class='clear'></div>



	</div>

</div>