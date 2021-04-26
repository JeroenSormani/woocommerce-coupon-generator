<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCCG_Generator.
 *
 * Generator class containing the generation steps.
 *
 * @class		WCCG_Generator
 * @author		Jeroen Sormani
 * @package		WooCommerce Coupon Generator
 * @version		1.0.0
 */
class WCCG_Generator {


	/**
	 * Steps.
	 *
	 * @since 1.0.0
	 * @var array $steps List of steps in the generator.
	 */
	public $steps = array();


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->steps = array(
			'1' => array(
				'name'    => __( 'Coupon options', 'coupon-generator-for-woocommerce' ),
				'handler' => 'coupon_options_handler',
			),
			'2' => array(
				'name'    => __( 'Generate coupons', 'coupon-generator-for-woocommerce' ),
				'handler' => 'generate_coupons_handler',
			),
		);
	}


	/**
	 * Current step.
	 *
	 * Get the current step number.
	 *
	 * @since 1.0.0
	 * @deprecated 2.0.0 - Steps are handled async.
	 *
	 * @return int Step number.
	 */
	public function current_step() {
		$step = 0;

		if ( isset( $_REQUEST['step'] ) ) {
			$step = absint( $_REQUEST['step'] );
		}

		return $step;
	}


	/**
	 * Output step.
	 *
	 * Output a step of the generator. Uses the step
	 * handler as a callback.
	 *
	 * @since 1.0.0
	 * @deprecated 2.0.0 - Steps are handled async.
	 *
	 * @param int $step Step to output. Leave empty to use the current step.
	 */
	public function output_step( $step = null ) {
		if ( null == $step ) {
			$step = $this->current_step();
		}

		// Fallback to first step
		if ( ! isset( $this->steps[ $step ] ) ) {
			$step = 0;
		}

		if ( ! is_array( $this->steps ) || ! isset( $this->steps[ $step ]['handler'] ) ) {
			wp_die( __( 'I\'m trying to load a step but couldn\'t find it! Please go back and try again.', 'coupon-generator-for-woocommerce' ), __( 'Could not find step', 'coupon-generator-for-woocommerce' ) );
		}

		if ( ! is_callable( array( $this, $this->steps[ $step ]['handler'] ) ) && ! is_callable( $this->steps[ $step ]['handler'] ) ) {
			wp_die( __( 'I\'m trying to load a generator step but couldn\'t find the right callback! Please go back and try again.', 'coupon-generator-for-woocommerce' ), __( 'Could not find step', 'coupon-generator-for-woocommerce' ) );
		}


		$handler = $this->steps[ $step ]['handler'];

		if ( is_callable( array( $this, $handler ) ) ) {
			call_user_func( array( $this, $handler ) );
		}

	}

	/**
	 * Output form.
	 *
	 * Output the generator.
	 *
	 * @since 2.0.0
	 */
	public function output_form() { ?>

		<div class="wrap wc-coupon-generator-wrap">

			<h1><?php _e( 'WooCommerce Coupon Generator', 'coupon-generator-for-woocommerce' ); ?></h1>

			<?php call_user_func( array( $this, 'introduction_handler' ) ); ?>

			<form id='wc-coupon-generator-form'>

				<?php

				foreach ( $this->steps as $step => $data ) {

					if ( is_callable( array( $this, $data['handler'] ) ) ) {
						call_user_func( array( $this, $data['handler'] ) );
					}

				}

				wp_nonce_field( 'wccg_generate_coupons', 'generate_coupons_nonce' );

				?>

			</form>

		</div>

		<?php

	}


	/**
	 * Introduction handler.
	 *
	 * Handler to output the instructions page.
	 *
	 * @since 1.0.0
	 */
	public function introduction_handler() {
		require_once 'views/html-coupon-generator-step-0.php';
	}


	/**
	 * Coupon options handler.
	 *
	 * Handler to output the coupon options page (step 1).
	 *
	 * @since 1.0.0
	 */
	public function coupon_options_handler() {
		require_once 'views/html-coupon-generator-step-1.php';
	}


	/**
	 * Generator options handler.
	 *
	 * Handler to output the coupon generator options (step 2).
	 *
	 * @since 1.0.0
	 * @deprecated 2.0.0 - Quantity input is shown on step 1.
	 */
	public function generator_options_handler() {
		_deprecated_function( __METHOD__, '2.0.0', 'This function is no longer used. Quantity input is shown on step 1.' );
	}


	/**
	 * Generate coupon handler.
	 *
	 * Handler to output the coupon generator page (step 3).
	 *
	 * @since 1.0.0
	 */
	public function generate_coupons_handler() {
		require_once 'views/html-coupon-generator-step-2.php';
	}


}
