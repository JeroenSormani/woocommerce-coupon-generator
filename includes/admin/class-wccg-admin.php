<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Admin class.
 *
 * Handle all general admin business.
 *
 * @class		WCCG_Admin
 * @author		Jeroen Sormani
 * @package		WooCommerce Coupon Generator
 * @version		1.0.0
 */
class WCCG_Admin {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add generator page
		add_action( 'admin_menu', array( $this, 'add_generator_page' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

		// WooCommerce screen IDs
		add_action( 'woocommerce_screen_ids', array( $this, 'add_wc_screen_id' ) );

	}


	/**
	 * Enqueue scripts.
	 *
	 * Enqueue script as javascript and style sheets.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		$current_screen = get_current_screen();

		if ( 'woocommerce_page_woocommerce_coupon_generator' == $current_screen->id ) :

			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'woocommerce-coupon-generator', plugins_url( 'assets/css/woocommerce-coupon-generator-admin.css', WooCommerce_Coupon_Generator()->file ), array(), WooCommerce_Coupon_Generator()->version );

			wp_enqueue_script( 'woocommerce-coupon-generator', plugins_url( 'assets/js/woocommerce-coupon-generator-admin.js', WooCommerce_Coupon_Generator()->file ), array( 'jquery' ), WooCommerce_Coupon_Generator()->version, true );

			wp_enqueue_script( 'wc-admin-meta-boxes' );
			wp_enqueue_script( 'wc-admin-coupon-meta-boxes' );

		endif;

	}


	/**
	 * Add generator page.
	 *
	 * Add the generator page to the WordPress admin.
	 *
	 * @since 1.0.0
	 */
	public function add_generator_page() {

		add_submenu_page( 'woocommerce', __( 'WooCommerce Coupon Generator', 'woocommerce-coupon-generator' ), __( 'Coupon generator', 'woocommerce-coupon-generator' ), 'manage_woocommerce', 'woocommerce_coupon_generator', array( $this, 'coupon_generator_callback' ) );

	}


	/**
	 * Generator callback.
	 *
	 * Initialize and output the contents of the generator
	 * page in the admin backend.
	 *
	 * @since 1.0.0
	 */
	public function coupon_generator_callback() {

		require_once plugin_dir_path( WooCommerce_Coupon_Generator()->file ) . 'includes/admin/class-wccg-generator.php';
		$coupon_generator = new WCCG_Generator();
		$coupon_generator->output_step();

	}


	/**
	 * WooCommerce screen IDs.
	 *
	 * Add the coupon generator coupon page to the WC Screen IDs.
	 *
	 * @since 1.0.0
	 *
	 * @param	array	$scren_ids	List of existing screen IDs.
	 * @return	array				List of modfied screen IDs.
	 */
	public function add_wc_screen_id( $screen_ids ) {

		$screen_ids[] = 'woocommerce_page_woocommerce_coupon_generator';

		return $screen_ids;

	}


}
