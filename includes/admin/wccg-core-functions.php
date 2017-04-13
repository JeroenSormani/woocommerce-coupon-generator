<?php
/**
 * WC Coupong Generator core functions.
 *
 * @author 		Jeroen Sormani
 * @since		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Generate coupons.
 *
 * Generate the coupons based on the $args arguments.
 *
 * @since 1.0.0
 * TODO
 */
function wccg_generate_coupons( $number, $args = array() ) {

	// Verify required values
	if ( ! isset( $args['number_of_coupons'] ) ) :
		return;
	endif;

	// TODO default args

	global $wpdb;
	$insert_coupon_ids = array();

	$wpdb->query( 'START TRANSACTION' );

	// Query coupons
	$number_of_coupons = absint( $number );
	for ( $i = 0; $i < $number_of_coupons; $i++ ) :

		$coupon_code = wccg_get_random_coupon();

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

		$insert_coupon_ids[] = $wpdb->insert_id;
		$coupon_id           = $wpdb->insert_id;

		// Set GUID
// 			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET guid=%s WHERE ID=%d", get_permalink( $coupon_id ), $coupon_id ) ); // Slow
		$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET guid=%s WHERE ID=%d", esc_url_raw( add_query_arg( array( 'post_type' => 'shop_coupon', 'p' => $coupon_id ), home_url() ) ), $coupon_id ) ); // 10% faster -1 query per coupon

	endfor;


	// Add/Replace data to array
	$product_ids = is_array( $args['product_ids'] ) ? $args['product_ids'] : explode( ',', $args['product_ids'] );
	$exclude_ids = is_array( $args['exclude_product_ids'] ) ? $args['exclude_product_ids'] : explode( ',', $args['exclude_product_ids'] );
	$meta_array = apply_filters( 'woocommerce_coupon_generator_coupon_meta_data', array(
		'discount_type'              => empty( $args['discount_type'] ) ? 'fixed_cart' : wc_clean( $args['discount_type'] ),
		'coupon_amount'              => wc_format_decimal( $args['coupon_amount'] ),
		'individual_use'             => isset( $args['individual_use'] ) ? 'yes' : 'no',
		'product_ids'                => implode( ',', array_filter( array_map( 'intval', $product_ids ) ) ),
		'exclude_product_ids'        => implode( ',', array_filter( array_map( 'intval', $exclude_ids ) ) ),
		'usage_limit'                => empty( $args['usage_limit'] ) ? '' : absint( $args['usage_limit'] ),
		'usage_limit_per_user'       => empty( $args['usage_limit_per_user'] ) ? '' : absint( $args['usage_limit_per_user'] ),
		'limit_usage_to_x_items'     => empty( $args['limit_usage_to_x_items'] ) ? '' : absint( $args['limit_usage_to_x_items'] ),
		'expiry_date'                => wc_clean( $args['expiry_date'] ),
		'free_shipping'              => isset( $args['free_shipping'] ) ? 'yes' : 'no',
		'exclude_sale_items'         => isset( $args['exclude_sale_items'] ) ? 'yes' : 'no',
		'product_categories'         => isset( $args['product_categories'] ) ? array_map( 'intval', $args['product_categories'] ) : array(),
		'exclude_product_categories' => isset( $args['exclude_product_categories'] ) ? array_map( 'intval', $args['exclude_product_categories'] ) : array(),
		'minimum_amount'             => wc_format_decimal( $args['minimum_amount'] ),
		'maximum_amount'             => wc_format_decimal( $args['maximum_amount'] ),
		'customer_email'             => array_filter( array_map( 'trim', explode( ',', wc_clean( $args['customer_email'] ) ) ) ),
	), $coupon_id );


	$insert_meta_values = '';
	// Insert all coupons meta
	foreach ( $meta_array as $key => $value ) :

		foreach ( $insert_coupon_ids as $coupon_id ) :

			$insert_meta_values .= $wpdb->prepare( '(%d, %s, %s)', $coupon_id, sanitize_title( wp_unslash( $key ) ), maybe_serialize( wp_unslash( $value ) ) );

			$meta_array_keys = array_keys( $meta_array );
			if ( $key == end( $meta_array_keys ) && $coupon_id == end( $insert_coupon_ids ) ) :
				$insert_meta_values .= ';';
			else :
				$insert_meta_values .= ', ';
			endif;

		endforeach;

	endforeach;

	$wpdb->query( "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES $insert_meta_values" );

	$wpdb->query( 'COMMIT' );

}


/**
 * Random coupon.
 *
 * Get a random coupon code.
 *
 * @since 1.0.0
 *
 * @return string Random coupon code.
 */
function wccg_get_random_coupon() {

	// Generate unique coupon code
	$random_coupon = '';
	$length        = 12;
	$charset       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$count         = strlen( $charset );

	while ( $length-- ) :
		$random_coupon .= $charset[ mt_rand( 0, $count-1 ) ];
	endwhile;

	$random_coupon = implode( '-', str_split( strtoupper( $random_coupon ), 4 ) );

	// Ensure coupon code is correctly formatted
	$coupon_code = apply_filters( 'woocommerce_coupon_code', $random_coupon );

	return $coupon_code;

}


/**
 * AJAX Process coupons.
 *
 * Process a batch of coupons. This function is fired via a ajax call.
 * Arguments are based on the $_POST['form_data'] arguments.
 *
 * @since 1.0.0
 */
function wccg_ajax_process_batch_coupons() {

	parse_str( $_POST['form_data'], $post_data );

	if ( ! isset( $post_data['generate_coupons_nonce'] ) || ! wp_verify_nonce( $post_data['generate_coupons_nonce'], 'wccg_generate_coupons' ) ) :
		die( -1 );
	endif;

	$progress   = 0;
	$message    = '';
	$batch_size = 500;
	$batch_step = absint( $_POST['batch_step'] );

	$total_number_coupons = $post_data['number_of_coupons'];
	$coupons_generated    = $batch_step * $batch_size;
	$coupons_to_generate  = min( $total_number_coupons - $coupons_generated, $batch_size );


	// Coupon generation
	$start_time = microtime( true );
	wccg_generate_coupons( $coupons_to_generate, $post_data );
	$execution_time = microtime( true ) - $start_time;

	// Step
	$coupons_generated += $coupons_to_generate;
	if ( $coupons_generated == $total_number_coupons ) :
		$batch_step  = 'done';
		$message    .= '<strong>' . sprintf( __( 'Coupon generation completed! Created %1$d coupons.', 'woocommerce-coupon-generator' ), $coupons_generated ) . '</strong><br/>';
	else :
		++$batch_step;
	endif;

	// Add message
	$message .= sprintf( __( '%1$s coupons created in %2$s seconds', 'woocommerce-coupon-generator' ), $coupons_to_generate, round( $execution_time, 3 ) );

	// Progress
	$progress = round( $coupons_generated / $total_number_coupons * 100 );

	die( json_encode( array( 'step' => $batch_step, 'progress' => $progress, 'message' => $message ) ) );

}


add_action( 'wp_ajax_wccg_generate_coupons', 'wccg_ajax_process_batch_coupons' );
