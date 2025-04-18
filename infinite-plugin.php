<?php

/**
 * Plugin Name:       Infinite
 * Plugin URI:        https://r3blcreative.com
 * Description:       A plugin boilerplate for creating a custom backend admin portal and frontend user portal.
 * Version:           1.1.2
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

// WP one-click updates hosted on GitHub
// require 'plugin-update-checker/plugin-update-checker.php';

// use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// $myUpdateChecker = PucFactory::buildUpdateChecker(
// 	'https://github.com/R3BLcreative/plugin-infinite/',
// 	__FILE__,
// 	'plugin-infinite'
// );
// $myUpdateChecker->getVcsApi()->enableReleaseAssets();
// // $myUpdateChecker->setBranch('production');
// $myUpdateChecker->setAuthentication('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');

/**
 * Current plugin version.
 */
define('INF_VERSION', '1.1.2');

/**
 * Plugin name/slug.
 */
define('INF_SLUG', plugin_basename(__DIR__));


/**
 * Load configs from theme directory
 */
$config_path = plugin_dir_path(__FILE__) . 'config/';

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

// ROLES CONFIG
if (file_exists($config_path . 'roles.json')) {
	$roles_config = json_decode(file_get_contents($config_path . 'roles.json'));
	define('INF_ROLES', $roles_config);
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-inf-activator.php
 */
function activate_infinite() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-inf-activator.php';
	INF_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-inf-deactivator.php
 */
function deactivate_infinite() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-inf-deactivator.php';
	INF_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_infinite');
register_deactivation_hook(__FILE__, 'deactivate_infinite');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-inf.php';

/**
 * Handle logging and debugging
 */
if (function_exists('get_field')) {
	define('INF_LOG', get_field('inf_log', 'option'));
} else {
	define('INF_LOG', false);
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

	$plugin = new INF();
	$plugin->run();
}
run_infinite();
