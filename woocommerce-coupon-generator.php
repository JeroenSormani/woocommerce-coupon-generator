<?php
/**
 * Plugin Name: 	WooCommerce Coupon Generator
 * Plugin URI:		https://jeroensormani.com/
 * Description:		Easily generate <strong>MILLIONS</strong> of unique coupons for your online store. Use all the coupon settings you are familiar with!
 * Version: 		1.2.0
 * Author: 			Jeroen Sormani
 * Author URI: 		https://jeroensormani.com/
 * Text Domain: 	coupon-generator-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WooCommerce_Coupon_Generator.
 *
 * Main WooCommerce_Coupon_Generator class initializes the plugin.
 *
 * @class		WooCommerce_Coupon_Generator
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class WooCommerce_Coupon_Generator {


	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string $version Plugin version number.
	 */
	public $version = '1.2.0';


	/**
	 * Plugin file.
	 *
	 * @since 1.0.0
	 * @var string $file Plugin file path.
	 */
	public $file = __FILE__;


	/**
	 * Instance of WooCommerce_Coupon_Generator.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The instance of WooCommerce_Coupon_Generator.
	 */
	private static $instance;


	/**
	 * Construct.
	 *
	 * Initialize the class and plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 * @return object Instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Check if WooCommerce is active
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! function_exists( 'WC' ) ) {
			return;
		}


		if ( is_admin() ) {
			require_once plugin_dir_path( __FILE__ ) . 'includes/admin/wccg-core-functions.php';

			// Classes
			require_once plugin_dir_path( __FILE__ ) . 'includes/admin/class-wccg-admin.php';
			$this->admin = new WCCG_Admin();
		}

		$this->load_textdomain();
	}


	/**
	 * Textdomain.
	 *
	 * Load the textdomain based on WP language.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'coupon-generator-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}


}


if ( ! function_exists( 'WooCommerce_Coupon_Generator' ) ) {

	/**
	 * The main function responsible for returning the WooCommerce_Coupon_Generator object.
	 *
	 * Use this function like you would a global variable, except without needing to declare the global.
	 *
	 * Example: <?php WooCommerce_Coupon_Generator()->method_name(); ?>
	 *
	 * @since 1.0.0
	 *
	 * @return object WooCommerce_Coupon_Generator class object.
	 */
	function WooCommerce_Coupon_Generator() {
		return WooCommerce_Coupon_Generator::instance();
	}
}

WooCommerce_Coupon_Generator();
