<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://tinajam.wordpress.com/
 * @since      1.0.0
 *
 * @package    Concrt_Ticket
 * @subpackage Concrt_Ticket/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Concrt_Ticket
 * @subpackage Concrt_Ticket/includes
 * @author     tahir iqbal <tahiriqbal09@gmail.com>
 */
class Concrt_Ticket_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'concrt-ticket',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
