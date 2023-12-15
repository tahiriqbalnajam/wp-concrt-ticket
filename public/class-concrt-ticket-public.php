<?php

use nguyenary\QRCodeMonkey\QRCode;

class Concrt_Ticket_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/concrt-ticket-public.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/concrt-ticket-public.js', array( 'jquery' ), $this->version, false );

	}

	public function concert_ticket_register_endpoint() {
		add_rewrite_endpoint( 'ticket', EP_ROOT | EP_PAGES );
		add_rewrite_endpoint( 'ticket/sample', EP_ROOT | EP_PAGES );
	}

	public function concert_ticket_add_query_var( $vars ) {
		$vars[] = 'ticket';
		return $vars;
	}

	public function concert_ticket_endpoint_content() {
		global $wp_query;
		if ( ! isset( $wp_query->query_vars['ticket'] ) ) {
			return;
		}
		$query_val = $wp_query->query_vars['ticket'];
		if(strpos($query_val, 'sample') !== false) {
			$variation_id = explode('/', $query_val)[1];
			$this->ticket_sample($variation_id);
		} else {
			$order_id = $wp_query->query_vars['ticket'];
			$this->ticket_print($order_id);
		}
		
		exit;
	}

	public function ticket_print($order_encrypt_id) {
		$order_exp_id = explode('-', $order_encrypt_id)[0];
		$order_number = explode('-', $order_encrypt_id)[1];
		$order_id = $this->decrypt($order_exp_id);
		$order = wc_get_order( $order_id );
		if(!$order) {
			echo 'Invalid ticket';
			die();
		}
		$qrcode = new QRCode(site_url( 'ticket/'.$order_encrypt_id));
     	$total=0; 
		ob_start();
		include 'partials/ticket_print.php';
		$content = ob_get_contents();
		ob_get_clean();

		echo $content;

	}

	public function ticket_sample($variation_id) {
		
		$variation = new WC_Product_Variation($variation_id);
		if(!$variation) {
			echo 'Invalid sample id';
			die();
		}
		$qrcode = new QRCode(site_url( 'ticket/sample'.$variation_id));
     	$total=0; 
		ob_start();
		include 'partials/ticket_sample.php';
		$content = ob_get_contents();
		ob_get_clean();

		echo $content;

	}

	public function update_order_complete_email( $emails ) {
		$emails['WC_Email_Customer_Completed_Order']->template_html  = 'emails/customer-completed-order.php';
		$emails['WC_Email_Customer_Completed_Order']->template_plain = 'emails/plain/customer-completed-order.php';
		return $emails;
	}


	function override_woocommerce_template_part( $template, $slug, $name ) {
		// UNCOMMENT FOR @DEBUGGING
		// echo '<pre>';
		// echo 'template: ' . $template . '<br/>';
		// echo 'slug: ' . $slug . '<br/>';
		// echo 'name: ' . $name . '<br/>';
		// echo '</pre>';
		// Template directory.
		// E.g. /wp-content/plugins/my-plugin/woocommerce/
		$template_directory = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/woocommerce/templates/';
		if ( $name ) {
			$path = $template_directory . "{$slug}-{$name}.php";
		} else {
			$path = $template_directory . "{$slug}.php";
		}
		return file_exists( $path ) ? $path : $template;
	}

	function override_woocommerce_template( $template, $template_name, $template_path ) {
		$template_directory = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/woocommerce/templates/';
		$path = $template_directory . $template_name;
		return file_exists( $path ) ? $path : $template;
	}


	public function add_price_multiplier_to_variation_prices_hash( $price_hash, $product, $for_display ) {
		$price_hash[] = get_price_multiplier();
		return $price_hash;
	}

	public function idl_change_product_html( $price, $variation, $product) {
		return '454545';
		// $value = floatval( $variation->get_meta( 'custom_field' ) );
		// if( $value > 0 ) {
		// 	$price = $value; 
		// }

		// $tax_fee = floatval( floatval( get_post_meta( $variation->get_id(), 'tax_fee', true ) ));
		// //$tax_fee = floatval($variation->get_meta( 'tax_fee' )); // get_post_meta( $product->id, 'tax_fee', true );
		// if ( ! empty( $tax_fee ) ) {
		// 	$price = $price*($tax_fee/100);
		// 	$price = $tax_fee;
		// }

		$price = floatval($variation->get_meta( 'presale_fee' ));
		if ( ! empty( $presale_fee ) ) {
			$price += $price*($presale_fee/100); 
		}

		$system_fee = get_post_meta( $product->id, 'system_fee', true );
		if ( ! empty( $system_fee ) ) {
			$price += $price + $system_fee; 
		}

		$marketing_fee = get_post_meta( $product->id, 'marketing_fee', true );
		if ( ! empty( $marketing_fee ) ) {
			$price += $price + $marketing_fee;
		}

		return  $price;

	}

	public function hide_ticket_price($product) {
		global $product;
			ob_start(); //Side note...not sure I need Output buffering here, feel free to let me know.
			if($product->get_type() == 'ticket') {
		//Here's the input (value is populated with a separate radio group using JS)
		?>

<style>.edgtf-single-product-summary .price {
    display: none !important;
}
</style>

		<?php
			}
			$content = ob_get_contents();
			ob_end_flush();
			return $content;
		
	}

	public function before_checkout_create_order( $order_id, $posted ) {
		$randomNumber = $this->encrypt($order_id);
		$order = wc_get_order( $order_id );
		$order->update_meta_data( 'ticket_order_number', $randomNumber );
		$order->save();
	}

	function encrypt($data) {
		$encrypted = strtoupper(strtr($data, '0123456789', 'LMNOPQRSTU'));
		$randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
		return $encrypted.'-'.$randomNumber;
	}
	
	function decrypt($encryptedData) {
		$decrypted = strtr($encryptedData, 'LMNOPQRSTU', '0123456789');
		return $decrypted;
	}
}
