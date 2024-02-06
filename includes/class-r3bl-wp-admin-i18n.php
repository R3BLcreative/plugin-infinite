<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    R3bl_Wp_Admin
 * @subpackage R3bl_Wp_Admin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    R3bl_Wp_Admin
 * @subpackage R3bl_Wp_Admin/includes
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */
class R3bl_Wp_Admin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'r3bl-wp-admin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
