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

		add_action( 'init', array( $this, 'init' ) ); // Used init because admin_init is too late for admin_menu
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

		// Plugins page
		if ( 'plugins.php' == $pagenow ) {
			add_filter( 'plugin_action_links_' . plugin_basename( WooCommerce_Coupon_Generator()->file ), array( $this, 'add_plugin_action_links' ), 10, 2 );
			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );
		}
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

		if ( strpos( $current_screen->id, 'woocommerce_coupon_generator' ) !== false ) {
			wp_enqueue_style( 'coupon-generator-for-woocommerce', plugins_url( 'assets/css/woocommerce-coupon-generator-admin.min.css', WooCommerce_Coupon_Generator()->file ), array( 'woocommerce_admin_styles', 'jquery-ui-style' ), WooCommerce_Coupon_Generator()->version );

			wp_enqueue_script( 'coupon-generator-for-woocommerce', plugins_url( 'assets/js/woocommerce-coupon-generator-admin.min.js', WooCommerce_Coupon_Generator()->file ), array( 'jquery' ), WooCommerce_Coupon_Generator()->version, true );

			// Copied from WC Core; class-wc-admin-assets.php as this is only loaded on coupon pages
			wp_enqueue_script( 'wc-admin-coupon-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-coupon.min.js', array( 'wc-admin-meta-boxes' ), WC()->version );
			wp_localize_script(
				'wc-admin-coupon-meta-boxes',
				'woocommerce_admin_meta_boxes_coupon',
				array(
					'generate_button_text' => esc_html__( 'Generate coupon code', 'woocommerce' ),
					'characters'           => apply_filters( 'woocommerce_coupon_code_generator_characters', 'ABCDEFGHJKMNPQRSTUVWXYZ23456789' ),
					'char_length'          => apply_filters( 'woocommerce_coupon_code_generator_character_length', 8 ),
					'prefix'               => apply_filters( 'woocommerce_coupon_code_generator_prefix', '' ),
					'suffix'               => apply_filters( 'woocommerce_coupon_code_generator_suffix', '' ),
				)
			);
		}
	}


	/**
	 * Add generator page.
	 *
	 * Add the generator page to the WordPress admin.
	 *
	 * @since 1.0.0
	 */
	public function add_generator_page() {
		global $admin_page_hooks;
		$parent_menu = ( isset( $admin_page_hooks['woocommerce-marketing'] ) ) ? 'woocommerce-marketing' : 'woocommerce';
		add_submenu_page( $parent_menu, __( 'WooCommerce Coupon Generator', 'coupon-generator-for-woocommerce' ), __( 'Coupon generator', 'coupon-generator-for-woocommerce' ), 'manage_woocommerce', 'woocommerce_coupon_generator', array( $this, 'coupon_generator_callback' ) );
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
		?><div class="wrap wccg-wrap"><?php
			require_once 'views/html-coupon-generator-step-0.php';
			require_once 'views/html-coupon-generator-step-1.php';
			require_once 'views/html-coupon-generator-step-2.php';
			require_once 'views/html-coupon-generator-step-3.php';
		?></div><?php
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
		if ( $file == plugin_basename( WooCommerce_Coupon_Generator()->file ) ) {
			$links = array_merge( array(
				'<a href="' . esc_url( admin_url( '/admin.php?page=woocommerce_coupon_generator' ) ) . '">' . __( 'Start generating coupons', 'coupon-generator-for-woocommerce' ) . '</a>'
			), $links );
		}

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
		if ( $file == plugin_basename( WooCommerce_Coupon_Generator()->file ) ) {
			$links[] = '<a href="https://aceplugins.com/plugin/category/woocommerce/" target="_blank">' . __( 'More WooCommerce plugins by Ace Plugins', 'coupon-generator-for-woocommerce' ) . '</a>';
		}

		return $links;
	}


}
