<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tinajam.wordpress.com/
 * @since             1.0.0
 * @package           Concrt_Ticket
 *
 * @wordpress-plugin
 * Plugin Name:       Concert Ticketing
 * Plugin URI:        https://tinajam.wordpress.com/
 * Description:       A plugin to manage concert ticketing.
 * Version:           1.0.0
 * Author:            tahir iqbal
 * Author URI:        https://tinajam.wordpress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       concrt-ticket
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CONCRT_TICKET_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-concrt-ticket-activator.php
 */
function activate_concrt_ticket() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-concrt-ticket-activator.php';
	Concrt_Ticket_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-concrt-ticket-deactivator.php
 */
function deactivate_concrt_ticket() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-concrt-ticket-deactivator.php';
	Concrt_Ticket_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_concrt_ticket' );
register_deactivation_hook( __FILE__, 'deactivate_concrt_ticket' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-concrt-ticket.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_concrt_ticket() {

	$plugin = new Concrt_Ticket();
	$plugin->run();

}
run_concrt_ticket();

add_filter( 'product_type_selector', 'misha_ticket_product_type' );
 
function misha_ticket_product_type( $product_types ){
	$product_types[ 'ticket' ] = 'Ticket';
	return $product_types;
}
 
/**
 * Step 2. Each product type has a PHP class WC_Product_{type}
 */
add_action( 'init', 'misha_create_ticket_product_class' );
add_filter( 'woocommerce_product_class', 'misha_load_ticket_product_class',10,2);
 
function misha_create_ticket_product_class(){
	class WC_Product_Ticket extends WC_Product_Variable {


		public function __construct( $product ) {

			$this->product_type = 'ticket';
			$this->supports[]   = 'ajax_add_to_cart';
			parent::__construct( $product );
		   
		}

		public function get_type() {
			return 'ticket'; // so you can use $product = wc_get_product(); $product->get_type()
		}
			
	}
}


add_filter('woocommerce_product_data_tabs','ticket_showtabs',10,1);
 function ticket_showtabs($tabs) {

			array_push($tabs['attribute']['class'], 'show_if_variable', 'show_if_ticket');
			array_push($tabs['variations']['class'], 'show_if_ticket');
		
		  return $tabs;

		}  

function producttype_custom_js() {

if ( 'product' != get_post_type() ) :
	return;
endif;

?><script type='text/javascript'>
jQuery("body").bind("DOMNodeInserted", function() {
   jQuery(this).find('.enable_variation').addClass('show_if_ticket').show();
});

</script><?php 
} 

add_action( 'admin_footer', 'producttype_custom_js' ,99);



 
function misha_load_ticket_product_class( $php_classname, $product_type ) {
	if ( $product_type == 'ticket' ) {
		$php_classname = 'WC_Product_Ticket';
	}
	return $php_classname;
}

add_filter( 'woocommerce_data_stores', function( $stores ){
	$stores['product-ticket'] = 'WC_Product_Variable_Data_Store_CPT';
	return $stores;
} ); 
	
add_action( 'woocommerce_ticket_add_to_cart', 'woocommerce_variable_add_to_cart' );


function misha_custom_add_to_cart_handler( $handler, $adding_to_cart ){
   if( $handler == 'ticket' ){
	   $handler = 'variable';
   }
   return $handler;
}
add_filter( 'woocommerce_add_to_cart_handler', 'misha_custom_add_to_cart_handler', 10, 2 );



///////////////////////////////////////////
// Utility function to change the prices with a multiplier (number)
add_filter( 'woocommerce_available_variation', 'my_variation', 10, 3);
function my_variation( $data, $product, $variation ) {

	// if($product->get_type() != 'ticket'){
	// 	return $data;
	// }
	$price =  $variation->get_price();
	$new_price = custom_price($price, $variation);

    $data['price_html'] = "<span class='ex-vat-price'>" . woocommerce_price($new_price) . "</span><br>";
    return $data;
}


function custom_price($price, $variation) {
	//$tax_fee = floatval(floatval( get_post_meta( $variation->get_id(), 'tax_fee', true )));
	$new_price = $price;
	//$tax_fee = floatval($variation->get_meta( 'tax_fee' )); // get_post_meta( $product->id, 'tax_fee', true );
	// if ( !empty( $tax_fee ) ) {
	// 	$new_price += $price*($tax_fee/100);
	// }

	$presale_fee = floatval(get_post_meta( $variation->get_id(), 'presale_fee', true ));
	if ( !empty( $presale_fee ) ) {
		$new_price += $price*($presale_fee/100); 
	}

	$system_fee = floatval(get_post_meta( $variation->get_id(), 'system_fee', true ));
	if ( !empty( $system_fee ) ) {
		$new_price +=  $system_fee; 
	}

	$marketing_fee = floatval(get_post_meta( $variation->get_id(), 'marketing_fee', true ));
	if ( !empty( $marketing_fee ) ) {
		$new_price += $marketing_fee;
	}

	return $new_price;
}


add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

function add_custom_price( $cart_object ) {
    //$custom_price = 10; // This will be your custome price  
    foreach ( $cart_object->cart_contents as $key => $value ) {
		$_product = $value['data'];
		
		$new_price = custom_price($value['data']->price, $variation = wc_get_product($_product->variation_id));
        //$value['data']->price = $new_price;
        // for WooCommerce version 3+ use: 
        $value['data']->set_price($new_price);
    }
}
