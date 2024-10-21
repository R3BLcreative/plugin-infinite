<?php

/**
 * Class that extends custom functionality to the plugin
 *
 * A class definition that allows for drop-in extension of the plugin that enables 
 * additional customizations and integrations that taylor to the clients needs and 
 * overall project scope.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 * @subpackage Infinite/extensions
 */

class INF_Orders {
	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	public $last_error;

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $slug = 'orders';

	/**
	 * The init function
	 * 
	 * Use this to add custom hooks and filters to WP or to pre-load config files.
	 * In order for this function to work with WP hooks and filters, a class instance must
	 * be instantiated at the bottom of this doc.
	 */
	public function __construct() {
		// Version control
		if (!get_option($this->slug)) {
			add_option($this->slug, $this->version);
			$this->custom_tables();
		} else {
			$version = get_option($this->slug);
			update_option($this->slug, $this->version);
			if ($version != $this->version) $this->custom_tables();
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function custom_tables() {
		global $wpdb;

		$table = $this->get_wp_table_name();
		$schema_path = plugin_dir_path(__DIR__) . 'sql/' . $this->slug . '.sql';

		if (file_exists($schema_path)) {
			$schema = file_get_contents($schema_path);
			$charset_collate = $wpdb->get_charset_collate();

			$query = str_replace('$TABLE_NAME', $table, $schema);
			$query = str_replace('$COLLATE', $charset_collate, $query);

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta($query);

			inf_log('custom_tables: last error', $wpdb->last_error, 'schedules');
		}
	}

	//----------------- UTILITY METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function get_wp_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'inf_' . $this->slug;
	}

	//----------------- CRUD METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @param  [type] $SID
	 * @return void
	 */
	public function create($SID) {
		global $wpdb;

		$data = [
			'cart' => json_encode($_SESSION['cart']['items']),
			'student_id' => $SID,
			'status' => 'payment_incomplete',
		];
		$format = [
			'%s',
			'%d',
			'%s'
		];

		$table = $this->get_wp_table_name();
		$wpdb->insert($table, $data, $format);
		return $wpdb->insert_id;
	}

	//----------------- AJAX METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_hooks() {
	}
}

$this->loader->add_action('admin_init', new INF_Orders, 'ajax_hooks');
