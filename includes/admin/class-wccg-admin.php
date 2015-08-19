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

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'woocommerce-coupon-generator', plugins_url( 'assets/css/woocommerce-coupon-generator-admin.css', WooCommerce_Coupon_Generator()->file ), WooCommerce_Coupon_Generator()->version, true );

		wp_enqueue_script( 'wc-admin-meta-boxes' );
		wp_enqueue_script( 'wc-admin-coupon-meta-boxes' );
/*


$params = array(
				'remove_item_notice'            => __( 'Are you sure you want to remove the selected items? If you have previously reduced this item\'s stock, or this order was submitted by a customer, you will need to manually restore the item\'s stock.', 'woocommerce' ),
				'i18n_select_items'             => __( 'Please select some items.', 'woocommerce' ),
				'i18n_do_refund'                => __( 'Are you sure you wish to process this refund? This action cannot be undone.', 'woocommerce' ),
				'i18n_delete_refund'            => __( 'Are you sure you wish to delete this refund? This action cannot be undone.', 'woocommerce' ),
				'i18n_delete_tax'               => __( 'Are you sure you wish to delete this tax column? This action cannot be undone.', 'woocommerce' ),
				'remove_item_meta'              => __( 'Remove this item meta?', 'woocommerce' ),
				'remove_attribute'              => __( 'Remove this attribute?', 'woocommerce' ),
				'name_label'                    => __( 'Name', 'woocommerce' ),
				'remove_label'                  => __( 'Remove', 'woocommerce' ),
				'click_to_toggle'               => __( 'Click to toggle', 'woocommerce' ),
				'values_label'                  => __( 'Value(s)', 'woocommerce' ),
				'text_attribute_tip'            => __( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ),
				'visible_label'                 => __( 'Visible on the product page', 'woocommerce' ),
				'used_for_variations_label'     => __( 'Used for variations', 'woocommerce' ),
				'new_attribute_prompt'          => __( 'Enter a name for the new attribute term:', 'woocommerce' ),
				'calc_totals'                   => __( 'Calculate totals based on order items, discounts, and shipping?', 'woocommerce' ),
				'calc_line_taxes'               => __( 'Calculate line taxes? This will calculate taxes based on the customers country. If no billing/shipping is set it will use the store base country.', 'woocommerce' ),
				'copy_billing'                  => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
				'load_billing'                  => __( 'Load the customer\'s billing information? This will remove any currently entered billing information.', 'woocommerce' ),
				'load_shipping'                 => __( 'Load the customer\'s shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
				'featured_label'                => __( 'Featured', 'woocommerce' ),
				'prices_include_tax'            => esc_attr( get_option( 'woocommerce_prices_include_tax' ) ),
				'round_at_subtotal'             => esc_attr( get_option( 'woocommerce_tax_round_at_subtotal' ) ),
				'no_customer_selected'          => __( 'No customer selected', 'woocommerce' ),
				'plugin_url'                    => WC()->plugin_url(),
				'ajax_url'                      => admin_url( 'admin-ajax.php' ),
				'order_item_nonce'              => wp_create_nonce( 'order-item' ),
				'add_attribute_nonce'           => wp_create_nonce( 'add-attribute' ),
				'save_attributes_nonce'         => wp_create_nonce( 'save-attributes' ),
				'calc_totals_nonce'             => wp_create_nonce( 'calc-totals' ),
				'get_customer_details_nonce'    => wp_create_nonce( 'get-customer-details' ),
				'search_products_nonce'         => wp_create_nonce( 'search-products' ),
				'grant_access_nonce'            => wp_create_nonce( 'grant-access' ),
				'revoke_access_nonce'           => wp_create_nonce( 'revoke-access' ),
				'add_order_note_nonce'          => wp_create_nonce( 'add-order-note' ),
				'delete_order_note_nonce'       => wp_create_nonce( 'delete-order-note' ),
				'calendar_image'                => WC()->plugin_url().'/assets/images/calendar.png',
				'post_id'                       => isset( $post->ID ) ? $post->ID : '',
				'base_country'                  => WC()->countries->get_base_country(),
				'currency_format_num_decimals'  => wc_get_price_decimals(),
				'currency_format_symbol'        => get_woocommerce_currency_symbol(),
				'currency_format_decimal_sep'   => esc_attr( wc_get_price_decimal_separator() ),
				'currency_format_thousand_sep'  => esc_attr( wc_get_price_thousand_separator() ),
				'currency_format'               => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ), // For accounting JS
				'rounding_precision'            => WC_ROUNDING_PRECISION,
				'tax_rounding_mode'             => WC_TAX_ROUNDING_MODE,
				'product_types'                 => array_map( 'sanitize_title', get_terms( 'product_type', array( 'hide_empty' => false, 'fields' => 'names' ) ) ),
				'i18n_download_permission_fail' => __( 'Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'woocommerce' ),
				'i18n_permission_revoke'        => __( 'Are you sure you want to revoke access to this download?', 'woocommerce' ),
				'i18n_tax_rate_already_exists'  => __( 'You cannot add the same tax rate twice!', 'woocommerce' ),
				'i18n_product_type_alert'       => __( 'Your product has variations! Before changing the product type, it is a good idea to delete the variations to avoid errors in the stock reports.', 'woocommerce' )
			);

			wp_localize_script( 'wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $params );
*/
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
