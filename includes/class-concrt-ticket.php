<?php

class Concrt_Ticket {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'CONCRT_TICKET_VERSION' ) ) {
			$this->version = CONCRT_TICKET_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'concrt-ticket';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-concrt-ticket-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-concrt-ticket-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'phpqrcode/qrlib.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-concrt-ticket-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-concrt-ticket-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/exopite-simple-options/exopite-simple-options-framework-class.php';

		$this->loader = new Concrt_Ticket_Loader();

	}


	private function set_locale() {

		$plugin_i18n = new Concrt_Ticket_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Concrt_Ticket_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//custom hooks start here
		$this->loader->add_action( 'init', $plugin_admin, 'create_menu', 999 );
		//$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'woocommerce_product_data_tabs',10, 1 );   
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'custom_product_field',10 );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'save_custom_product_field', 999 );
		$this->loader->add_action( 'woocommerce_variation_options_pricing', $plugin_admin, 'add_custom_prices_to_variations',  10, 3 );
		$this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'save_prices_field_variations',  10, 2 );
		$this->loader->add_action( 'woocommerce_available_variation', $plugin_admin, 'add_custom_field_variation_data',  10 );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'generate_report',  10 );
		$this->loader->add_action( 'wp_ajax_get_concert_order_report', $plugin_admin, 'download_orders_csv' );
		$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_admin, 'show_ticket_to_print' );
	}


	private function define_public_hooks() {

		$plugin_public = new Concrt_Ticket_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'concert_ticket_register_endpoint' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'concert_ticket_add_query_var',10, 3); 
		$this->loader->add_action( 'template_redirect', $plugin_public, 'concert_ticket_endpoint_content' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		//$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'ticket_print',10, 2); 
		$this->loader->add_filter( 'wc_get_template_part', $plugin_public, 'override_woocommerce_template_part',10, 3); 
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'override_woocommerce_template',10, 3); 
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'override_woocommerce_template',10, 3); 
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'before_checkout_create_order', 20, 2); 
		$this->loader->add_action( 'woocommerce_after_variations_table', $plugin_public, 'hide_ticket_price', 10, 1); 
		$this->loader->add_action( 'woocommerce_order_status_processing', $plugin_public, 'add_unique_id', 10, 1); 
		
		$this->loader->add_action( 'wp_ajax_verify_ticket_vendor', $plugin_public, 'verify_ticket_vendor' );
		$this->loader->add_action( 'wp_ajax_nopriv_verify_ticket_vendor', $plugin_public, 'verify_ticket_vendor' );

		$this->loader->add_action( 'wp_ajax_verify_ticket', $plugin_public, 'verify_ticket' );
		$this->loader->add_action( 'wp_ajax_nopriv_verify_ticket', $plugin_public, 'verify_ticket' );

		//$this->loader->add_action( 'woocommerce_email_classes', $this, 'update_order_complete_email' );
		//$this->loader->add_filter( 'woocommerce_variation_prices_price', $plugin_public, 'idl_change_product_html',100, 3); 
		//$this->loader->add_filter( 'woocommerce_get_variation_prices_hash', $plugin_public, 'add_price_multiplier_to_variation_prices_hash',100, 3); 

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Concrt_Ticket_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
