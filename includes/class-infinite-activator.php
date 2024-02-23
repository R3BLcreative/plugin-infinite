<?php

/**
 * Fired during plugin activation
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 * @subpackage Infinite/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Infinite
 * @subpackage Infinite/includes
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */
class Infinite_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$path = plugin_dir_path(dirname(__FILE__));

		// Create custom tables
		self::custom_tables($path);

		// Create custom roles/caps
		self::custom_roles($path);

		// TODO: Add custom settings to WP options table
		// self::custom_options($path);
	}

	/**
	 * Create custom tables
	 * 
	 * Long description
	 * 
	 * @since    1.0.0
	 */
	public static function custom_tables($path) {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$config_path = $path . 'config/tables.json';
		if (file_exists($config_path)) {
			$tables = json_decode(file_get_contents($config_path));

			foreach ($tables as $table) {
				$tablename = $wpdb->prefix . $table->table_name;
				$schema_path = $path . 'sql/' . $table->table_sql;

				if (file_exists($schema_path)) {
					$schema = file_get_contents($schema_path);

					$query = str_replace('INFINITE_TABLE_NAME', $tablename, $schema);
					$query = str_replace('INFINITE_TABLE_PREFIX ', $wpdb->prefix, $query);

					maybe_create_table($tablename, $query);
				}
			}
		}
	}

	/**
	 * Create custom roles/caps
	 * 
	 * Long description
	 * 
	 * @since    1.0.0
	 */
	public static function custom_roles($path) {
		$config_path = $path . 'config/roles.json';

		if (file_exists($config_path)) {
			$roles = json_decode(file_get_contents($config_path));

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

	/**
	 * Add custom settings to WP Options table
	 * 
	 * Long description
	 * 
	 * @since    1.0.0
	 */
	public static function custom_options($path) {
		$config_path = $path . 'config/settings.json';

		if (file_exists($config_path)) {
			$settings = json_decode(file_get_contents($config_path));

			foreach ($settings as $setting) {
			}
		}
	}
}
