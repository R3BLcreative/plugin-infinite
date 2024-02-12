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
		$path = plugin_dir_path(__FILE__) . '/../';

		// Remove custom tables
		global $wpdb;

		$tables_config_path = $path . 'config/tables.json';
		if (file_exists($tables_config_path)) {
			$tables = json_decode(file_get_contents($tables_config_path));

			$wpdb->query("SET FOREIGN_KEY_CHECKS = 0;");
			foreach ($tables as $table) {
				$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$table->table_name}");
			}
			$wpdb->query("SET FOREIGN_KEY_CHECKS = 1;");
		}

		// Remove custom roles/caps
		$roles_config_path = $path . 'config/roles.json';
		if (file_exists($roles_config_path)) {
			$roles = json_decode(file_get_contents($roles_config_path));
			foreach ($roles as $role) {
				add_role($role->slug, $role->label, $role->caps);

				if ($role->isAdmin) {
					$admins = get_role('administrator');
					foreach ($role->admin_caps as $cap) {
						$admins->add_cap($cap, true);
					}
				}
			}
		}
	}
}
