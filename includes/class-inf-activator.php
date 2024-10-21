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
class INF_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Create custom roles/caps
		self::custom_roles();

		// Add current configs to wp_options in order to compare and update on the fly without activation
		self::config_options();
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
				remove_role($role->slug);
				add_role($role->slug, $role->label, (array) $role->caps);

				// Add new caps to admin roles
				$admins = get_role('administrator');
				foreach ($role->caps as $cap => $grant) {
					$admins->add_cap($cap, $grant);
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
	}
}
