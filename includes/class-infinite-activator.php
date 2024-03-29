<?php

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
		// Create custom tables
		self::custom_tables();

		// Create custom roles/caps
		self::custom_roles();

		// Add custom settings to WP options table
		self::custom_options();

		// Add current configs to wp_options in order to compare and update on the fly without activation
		self::config_options();
	}

	/**
	 * Create custom tables
	 * 
	 * dbDelta requires the SCHEMA files to follow the below rules:
	 * 
	 * You must put each field on its own line in your SQL statement.
	 * You must have two spaces between the words PRIMARY KEY and the definition of your primary key.
	 * You must use the key word KEY rather than its synonym INDEX and you must include at least one KEY.
	 * You must not use any apostrophes or backticks around field names.
	 * Do not use FOREIGN KEY
	 * CREAT $TABLE_NAME ( should be all on the same line
	 * $wpdb charset and collate should be added to the end
	 * 
	 * @since    1.0.0
	 */
	public static function custom_tables() {
		global $wpdb;

		if (defined('INF_TABLES') && property_exists(INF_TABLES, 'tables')) {
			foreach (INF_TABLES->tables as $table) {
				$tablename = $wpdb->prefix . $table->table_name;
				$schema_path = get_stylesheet_directory() . '/infinite/sql/' . $table->table_sql;

				if (file_exists($schema_path)) {
					$schema = file_get_contents($schema_path);
					$charset_collate = $wpdb->get_charset_collate();

					$query = str_replace('$TABLE_NAME', $tablename, $schema);
					$query = str_replace('$COLLATE', $charset_collate, $query);

					require_once ABSPATH . 'wp-admin/includes/upgrade.php';
					dbDelta($query);

					// var_dump($wpdb->last_error);
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
	public static function custom_roles() {
		if (defined('INF_ROLES') && property_exists(INF_ROLES, 'roles')) {
			foreach (INF_ROLES->roles as $role) {
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
	public static function custom_options() {
		if (defined('INF_SETTINGS') && property_exists(INF_SETTINGS, 'options')) {
			foreach (INF_SETTINGS->options as $option) {
				if (property_exists($option, 'save') && $option->save === true) {
					$default = (property_exists($option, 'default')) ? $option->default : '';

					add_option($option->slug, $default);
				}
			}
		}
	}

	/**
	 * Add configs to WP Options table
	 * 
	 * Long description
	 * 
	 * @since    1.0.0
	 */
	public static function config_options() {
		if (defined('INF_ADMIN')) {
			if (!get_option('INF_ADMIN')) {
				add_option('INF_ADMIN', INF_ADMIN->version);
			} else {
				update_option('INF_ADMIN', INF_ADMIN->version);
			}
		}

		if (defined('INF_PUBLIC')) {
			if (!get_option('INF_PUBLIC')) {
				add_option('INF_PUBLIC', INF_PUBLIC->version);
			} else {
				update_option('INF_PUBLIC', INF_PUBLIC->version);
			}
		}

		if (defined('INF_ROLES')) {
			if (!get_option('INF_ROLES')) {
				add_option('INF_ROLES', INF_ROLES->version);
			} else {
				update_option('INF_ROLES', INF_ROLES->version);
			}
		}

		if (defined('INF_SETTINGS')) {
			if (!get_option('INF_SETTINGS')) {
				add_option('INF_SETTINGS', INF_SETTINGS->version);
			} else {
				update_option('INF_SETTINGS', INF_SETTINGS->version);
			}
		}

		if (defined('INF_TABLES')) {
			if (!get_option('INF_TABLES')) {
				add_option('INF_TABLES', INF_TABLES->version);
			} else {
				update_option('INF_TABLES', INF_TABLES->version);
			}
		}
	}
}
