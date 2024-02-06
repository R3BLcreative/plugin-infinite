<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://r3blcreative.com
 * @since             1.0.0
 * @package           R3bl_Wp_Admin
 *
 * @wordpress-plugin
 * Plugin Name:       R3BL WP Admin
 * Plugin URI:        https://r3blcreative.com
 * Description:       A plugin boilerplate for creating a custom backend admin portal. Built on React, MUI, and TailwindCSS.
 * Version:           1.0.0
 * Author:            R3BL Creative - James Cook
 * Author URI:        https://r3blcreative.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       r3bl-wp-admin
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
define( 'R3BL_WP_ADMIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-r3bl-wp-admin-activator.php
 */
function activate_r3bl_wp_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-r3bl-wp-admin-activator.php';
	R3bl_Wp_Admin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-r3bl-wp-admin-deactivator.php
 */
function deactivate_r3bl_wp_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-r3bl-wp-admin-deactivator.php';
	R3bl_Wp_Admin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_r3bl_wp_admin' );
register_deactivation_hook( __FILE__, 'deactivate_r3bl_wp_admin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-r3bl-wp-admin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_r3bl_wp_admin() {

	$plugin = new R3bl_Wp_Admin();
	$plugin->run();

}
run_r3bl_wp_admin();
