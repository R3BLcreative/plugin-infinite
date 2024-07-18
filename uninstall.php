<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// Plugin name/slug
$plugin_name = plugin_basename(__DIR__);

// Remove transients
$transients = [
	'_updater'
];

foreach ($transients as $transient) {
	delete_transient($plugin_name . $transient);
}

// Remove cron events
// $timestamp = wp_next_scheduled('infinite_cron_event');
// wp_unschedule_event($timestamp, 'infinite_cron_event');

// Remove custom tables
global $wpdb;

if (defined('INF_TABLES') && property_exists(INF_TABLES, 'tables')) {
	$wpdb->query("SET FOREIGN_KEY_CHECKS = 0;");
	foreach (INF_TABLES as $table) {
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$table->table_name}");
	}
	$wpdb->query("SET FOREIGN_KEY_CHECKS = 1;");
}

// Remove custom roles/caps
if (defined('INF_ROLES') && property_exists(INF_ROLES, 'roles')) {
	foreach (INF_ROLES as $role) {
		add_role($role->slug, $role->label, $role->caps);

		if ($role->isAdmin) {
			$admins = get_role('administrator');
			foreach ($role->admin_caps as $cap) {
				$admins->add_cap($cap, true);
			}
		}
	}
}
