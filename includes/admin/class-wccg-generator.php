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
			'0'	=> array(
				'name'    => __( 'Introduction', 'woocommerce-coupon-generator' ),
				'handler' => 'introduction_handler',
			),
			'1'	=> array(
				'name'    => __( 'Coupon options', 'woocommerce-coupon-generator' ),
				'handler' => 'coupon_options_handler',
			),
			'2'	=> array(
				'name'    => __( 'Generator options', 'woocommerce-coupon-generator' ),
				'handler' => 'generator_options_handler',
			),
			'3'	=> array(
				'name'    => __( 'Generate coupons', 'woocommerce-coupon-generator' ),
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
	 *
	 * @return	int	Step number.
	 */
	public function current_step() {

		$step = 0;

		if ( isset( $_REQUEST['step'] ) ) :
			$step = absint( $_REQUEST['step'] );
		endif;

		return $step;

	}


	/**
	 * Output step.
	 *
	 * Output a step of the generator. Uses the step
	 * handler as a callback.
	 *
	 * @since 1.0.0
	 *
	 * @param	int	$step	Step to output. Leave empty to use the current step.
	 */
	public function output_step( $step = null ) {

		if ( null == $step ) :
			$step = $this->current_step();
		endif;

		// Fallback to first step
		if ( ! isset( $this->steps[ $step ] ) ) :
			$step = 0;
		endif;

		if ( ! is_array( $this->steps ) || ! isset( $this->steps[ $step ]['handler'] ) ) :
			wp_die( __( 'I\'m trying to load a step but couldn\'t find it! Please go back and try again.', 'woocommerce-coupon-generator' ), __( 'Could not find step', 'woocommerce-coupon-generator' ) );
		endif;

		if ( ! is_callable( array( $this, $this->steps[ $step ]['handler'] ) ) && ! is_callable( $this->steps[ $step ]['handler'] ) ) :
			wp_die( __( 'I\'m trying to load a generator step but couldn\'t find the right callback! Please go back and try again.', 'woocommerce-coupon-generator' ), __( 'Could not find step', 'woocommerce-coupon-generator' ) );
		endif;


		$handler = $this->steps[ $step ]['handler'];

		if ( is_callable( array( $this, $handler ) ) ) :
			call_user_func( array( $this, $handler ) );
		endif;


	}

	public function introduction_handler() {

		require_once 'views/html-coupon-generator-step-0.php';

	}


	public function coupon_options_handler() {

		require_once 'views/html-coupon-generator-step-1.php';

	}

	public function generator_options_handler() {

		require_once 'views/html-coupon-generator-step-2.php';

	}

	public function generate_coupons_handler() {
$start = microtime(true);
$start_time = time();
		$this->generate_coupons();
$time = microtime(true) - $start;
$time_time = time() - $start_time;
		require_once 'views/html-coupon-generator-step-3.php';

	}


	/**
	 * Generate coupons.
	 *
	 * Generate the coupons based on the $_POST variables.
	 *
	 * @since 1.0.0
	 */
	public function generate_coupons() {

		// Verify nonce
		if ( ! isset( $_POST['generate_coupons_nonce'] ) || ! wp_verify_nonce( $_POST['generate_coupons_nonce'], 'wccg_generate_coupons' ) ) :
		error_log( 'return' );
			return;
		endif;

		// Verify required values
		if ( ! isset( $_POST['number_of_coupons'] ) ) :
			return;
		endif;

		global $wpdb;

		$wpdb->query( 'START TRANSACTION' );

		$number_of_coupons = absint( $_POST['number_of_coupons'] );
		for ( $i = 0; $i < $number_of_coupons; $i++ ) :

			$coupon_code = $this->get_random_coupon();

			// Insert coupon post
			$wpdb->query( $wpdb->prepare( "INSERT INTO $wpdb->posts SET
				post_author=%d,
				post_date=%s,
				post_date_gmt=%s,
				post_title=%s,
				post_status='publish',
				comment_status='closed',
				ping_status='closed',
				post_name=%s,
				post_modified=%s,
				post_modified_gmt=%s,
				post_type='shop_coupon'
				",
				get_current_user_id(),
				current_time( 'mysql' ),
				current_time( 'mysql', 1 ),
				sanitize_title( $coupon_code ),
				$coupon_code,
				current_time( 'mysql' ),
				current_time( 'mysql', 1 )
			) );

			$coupon_id = $wpdb->insert_id;

			// Set GUID
			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET guid=%s WHERE ID=%d", get_permalink( $coupon_id ), $coupon_id ) );

			// Add/Replace data to array
			$meta_array = apply_filters( 'woocommerce_coupon_generator_coupon_meta_data', array(
				'discount_type'  => empty( $_POST['discount_type'] ) ? 'fixed_cart' : wc_clean( $_POST['discount_type'] ),
				'coupon_amount'  => wc_format_decimal( $_POST['coupon_amount'] ),
				'individual_use'  => isset( $_POST['individual_use'] ) ? 'yes' : 'no',
				'product_ids'  => implode( ',', array_filter( array_map( 'intval', explode( ',', $_POST['product_ids'] ) ) ) ),
				'exclude_product_ids'  => implode( ',', array_filter( array_map( 'intval', explode( ',', $_POST['exclude_product_ids'] ) ) ) ),
				'usage_limit'  => empty( $_POST['usage_limit'] ) ? '' : absint( $_POST['usage_limit'] ),
				'usage_limit_per_user'  => empty( $_POST['usage_limit_per_user'] ) ? '' : absint( $_POST['usage_limit_per_user'] ),
				'limit_usage_to_x_items'  => empty( $_POST['limit_usage_to_x_items'] ) ? '' : absint( $_POST['limit_usage_to_x_items'] ),
				'expiry_date'  => wc_clean( $_POST['expiry_date'] ),
				'free_shipping'  => isset( $_POST['free_shipping'] ) ? 'yes' : 'no',
				'exclude_sale_items'  => isset( $_POST['exclude_sale_items'] ) ? 'yes' : 'no',
				'product_categories'  => isset( $_POST['product_categories'] ) ? array_map( 'intval', $_POST['product_categories'] ) : array(),
				'exclude_product_categories'  => isset( $_POST['exclude_product_categories'] ) ? array_map( 'intval', $_POST['exclude_product_categories'] ) : array(),
				'minimum_amount'  => wc_format_decimal( $_POST['minimum_amount'] ),
				'maximum_amount'  => wc_format_decimal( $_POST['maximum_amount'] ),
				'customer_email'  => array_filter( array_map( 'trim', explode( ',', wc_clean( $_POST['customer_email'] ) ) ) ),
			), $coupon_id );

			$insert_values = '';
			// Insert all meta
			foreach ( $meta_array as $key => $value ) :

				$insert_values .= $wpdb->prepare( "(%d, %s, %s)", $coupon_id, sanitize_title( wp_unslash( $key ) ), maybe_serialize( wp_unslash( $value ) ) );

				$meta_array_keys = array_keys( $meta_array );
				if ( $key != end( $meta_array_keys ) ) :
					$insert_values .= ", ";
				else :
					$insert_values .= ";";
				endif;

/* - Old way, about 30% slower
				$wpdb->query( $wpdb->prepare( "INSERT INTO $wpdb->postmeta
					(post_id, meta_key, meta_value)
					post_id=%d,
					meta_key=%s,
					meta_value=%s
					",
					$coupon_id,
					sanitize_title( wp_unslash( $key ) ),
					maybe_serialize( wp_unslash( $value ) )
				) );
*/

			endforeach;

			$wpdb->query( "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES $insert_values", $insert_values );

		endfor;

		$wpdb->query( 'COMMIT' );

	}


	/**
	 * Random coupon.
	 *
	 * Get a random coupon code.
	 *
	 * @since 1.0.0
	 *
	 * @return	string	Random coupon code.
	 */
	public function get_random_coupon() {

		// Generate unique coupon code
		$random_coupon 	= '';
		$length			= 12;
		$charset		= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$count 			= strlen( $charset );

		while ( $length-- ) :
			$random_coupon .= $charset[ mt_rand( 0, $count-1 ) ];
		endwhile;

		$random_coupon = implode( '-', str_split( strtoupper( $random_coupon ), 4 ) );

		// Ensure coupon code is correctly formatted
		$coupon_code = apply_filters( 'woocommerce_coupon_code', $random_coupon );

		return $coupon_code;

	}


}
