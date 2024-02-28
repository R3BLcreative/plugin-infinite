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
 * @package           Infinite
 *
 * @wordpress-plugin
 * Plugin Name:       Infinite
 * Plugin URI:        https://r3blcreative.com
 * Description:       A plugin boilerplate for creating a custom backend admin portal and frontend user portal.
 * Version:           1.0.0
 * Author:            R3BL Creative - James Cook
 * Author URI:        https://r3blcreative.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       infinite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Current plugin version.
 */
define('INFINITE_VERSION', '1.0.0');

/**
 * Plugin name/slug.
 */
define('INFINITE_SLUG', plugin_basename(__DIR__));


/**
 * Load configs from child theme directory
 */
$config_path = get_stylesheet_directory() . '/infinite-child/config/';

// ADMIN CONFIG
if (file_exists($config_path . 'admin.json')) {
	$admin_config = json_decode(file_get_contents($config_path . 'admin.json'));
	define('INF_ADMIN', $admin_config);
}

// PUBLIC CONFIG
if (file_exists($config_path . 'public.json')) {
	$public_config = json_decode(file_get_contents($config_path . 'public.json'));
	define('INF_PUBLIC', $public_config);
}

// TABLES CONFIG
if (file_exists($config_path . 'tables.json')) {
	$tables_config = json_decode(file_get_contents($config_path . 'tables.json'));
	define('INF_TABLES', $tables_config);
}

// ROLES CONFIG
if (file_exists($config_path . 'roles.json')) {
	$roles_config = json_decode(file_get_contents($config_path . 'roles.json'));
	define('INF_ROLES', $roles_config);
}

// SETTINGS CONFIG
if (file_exists($config_path . 'settings.json')) {
	$settings_config = json_decode(file_get_contents($config_path . 'settings.json'));
	define('INF_SETTINGS', $settings_config);
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-infinite-activator.php
 */
function activate_infinite() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-infinite-activator.php';
	Infinite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-infinite-deactivator.php
 */
function deactivate_infinite() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-infinite-deactivator.php';
	Infinite_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_infinite');
register_deactivation_hook(__FILE__, 'deactivate_infinite');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-infinite.php';

/**
 * This code reads the extensions directory and loads the files 
 * located in it for auto-loading of extensions.
 */
if ($handle = opendir(get_stylesheet_directory() . '/infinite-child/extensions')) {
	while (false !== ($file = readdir($handle))) {
		if ('.' === $file) continue;
		if ('..' === $file) continue;
		if ('index.php' === $file) continue;

		require_once get_stylesheet_directory() . '/infinite-child/extensions/' . $file;
	}

	closedir($handle);
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_infinite() {

	$plugin = new Infinite();
	$plugin->run();
}
run_infinite();
