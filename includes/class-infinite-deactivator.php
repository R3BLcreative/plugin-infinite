<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 * @subpackage Infinite/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Infinite
 * @subpackage Infinite/includes
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */
class Infinite_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// TODO: Copy this to Uninstall.php and delete this once plugin is deployed
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

		// Remove custom options
		if (defined('INF_SETTINGS') && property_exists(INF_SETTINGS, 'options')) {
			foreach (INF_SETTINGS->options as $option) {
				if (property_exists($option, 'save') && $option->save === true) {
					delete_option($option->slug);
				}
			}
		}
	}
}
