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
	 * @var string
	 */
	public $last_error;

	/**
	 * The table name for the class table.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $table_name    The name of the class table.
	 */
	private $table_name = 'infinite_orders';

	/**
	 * The tables config object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      obj    $tables_config    The tables config object.
	 */
	private $tables_config;

	/**
	 * The init function
	 * 
	 * Use this to add custom hooks and filters to WP or to pre-load config files.
	 * In order for this function to work with WP hooks and filters, a class instance must
	 * be instantiated at the bottom of this doc.
	 */
	public function __construct() {
		// Load config files here
		$this->tables_config = $this->get_config($this->table_name);
	}

	//----------------- UTILITY METHODS -----------------\\

	/**
	 * Retrieves the tables config file and returns the specific tables config
	 * 
	 * @var		string		$table_name		The name of the table to get
	 */
	public function get_config($table_name) {
		if (defined('INF_TABLES') && property_exists(INF_TABLES, 'tables')) {
			foreach (INF_TABLES->tables as $table) {
				if ($table->table_name == $table_name) return $table;
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_wp_table_name() {
		global $wpdb;

		return $wpdb->prefix . $this->table_name;
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
}
