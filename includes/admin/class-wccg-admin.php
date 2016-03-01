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

		if ( is_admin() ) :
			// Initialize
			add_action( 'init', array( $this, 'init' ) ); // Used init because admin_init is too late for admin_menu
		endif;

	}


	/**
	 * Admin init.
	 *
	 * Initialize admin components.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		global $pagenow;

		// Add generator page
		add_action( 'admin_menu', array( $this, 'add_generator_page' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

		// WooCommerce screen IDs
		add_action( 'woocommerce_screen_ids', array( $this, 'add_wc_screen_id' ) );

		if ( 'plugins.php' == $pagenow ) :
			// Plugins page
			add_filter( 'plugin_action_links_' . plugin_basename( WooCommerce_Coupon_Generator()->file ), array( $this, 'add_plugin_action_links' ), 10, 2 );
			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );
		endif;

	}


	/**
	 * Enqueue scripts.
	 *
	 * Enqueue script as javascript and style sheets.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts( $hook ) {

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
	 * @param  array $screen_ids List of existing screen IDs.
	 * @return array             List of modfied screen IDs.
	 */
	public function add_wc_screen_id( $screen_ids ) {

		$screen_ids[] = 'woocommerce_page_woocommerce_coupon_generator';

		return $screen_ids;

	}


	/**
	 * Plugin action links.
	 *
	 * Add links to the plugins.php page below the plugin name
	 * and besides the 'activate', 'edit', 'delete' action links.
	 *
	 * @since 1.0.2
	 *
	 * @param  array  $links List of existing links.
	 * @param  string $file  Name of the current plugin being looped.
	 * @return array         List of modified links.
	 */
	public function add_plugin_action_links( $links, $file ) {

		if ( $file == plugin_basename( WooCommerce_Coupon_Generator()->file ) ) :
			$links = array_merge( array(
				'<a href="' . esc_url( admin_url( '/admin.php?page=woocommerce_coupon_generator' ) ) . '">' . __( 'Start generating coupons', 'woocommerce-coupon-generator' ) . '</a>'
			), $links );
		endif;

		return $links;

	}


	/**
	 * Plugin row meta.
	 *
	 * Add extra plugin row meta, these are links / meta below the plugin description.
	 *
	 * @since 1.0.2
	 *
	 * @param  array  $links List of existing links.
	 * @param  string $file  Name of the current plugin being looped.
	 * @return array         List of modified links.
	 */
	public function add_plugin_row_meta( $links, $file ) {

		if ( $file == plugin_basename( WooCommerce_Coupon_Generator()->file ) ) :
			$links[] = '<a href="https://shopplugins.com/plugins/category/woocommerce/" target="_blank">' . __( 'More WooCommerce plugins by Shop Plugins', 'woocommerce-coupon-generator' ) . '</a>';
		endif;

		return $links;

	}


}
