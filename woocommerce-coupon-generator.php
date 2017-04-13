<?php
/**
 * Plugin Name: 	WooCommerce Coupon Generator
 * Plugin URI:		http://jeroensormani.com/
 * Description:		Easily generate <strong>MILLIONS</strong> of unique coupons for your online store. Use all the coupon settings you are familiar with!
 * Version: 		1.0.1
 * Author: 			Jeroen Sormani
 * Author URI: 		http://jeroensormani.com/
 * Text Domain: 	woocommerce-coupon-generator
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
	public $version = '1.0.1';


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

		// Initialize plugin parts
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

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

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
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) :
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		endif;

		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) :
				return;
			endif;
		endif;

		if ( is_admin() ) :

			// Functions
			require_once plugin_dir_path( __FILE__ ) . 'includes/admin/wccg-core-functions.php';

			// Classes
			require_once plugin_dir_path( __FILE__ ) . 'includes/admin/class-wccg-admin.php';
			$this->admin = new WCCG_Admin();

		endif;

		// Load textdomain
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

		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-coupon-generator' );

		// Load textdomain
		load_textdomain( 'woocommerce-coupon-generator', WP_LANG_DIR . '/woocommerce-coupon-generator/woocommerce-coupon-generator-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce-coupon-generator', false, basename( dirname( __FILE__ ) ) . '/languages' );

	}


}


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
if ( ! function_exists( 'WooCommerce_Coupon_Generator' ) ) :

	function WooCommerce_Coupon_Generator() {

		return WooCommerce_Coupon_Generator::instance();

	}


endif;

WooCommerce_Coupon_Generator();
