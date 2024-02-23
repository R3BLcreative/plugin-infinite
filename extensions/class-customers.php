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

class Infinite_Customers {
	/**
	 * The table name for the customers table.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $table_name    The name of the customers table.
	 */
	private $table_name = 'infinite_customers';

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
		// Custom Defined Hooks & Filters Here - Class must be instantiated
		add_action('admin_init', [$this, 'ajax_db_seeder_hooks']);

		// Load config files here
		$this->tables_config = $this->get_config($this->table_name);
	}

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
	 * Get customers table content.
	 *
	 * @since    1.0.0
	 * 
	 * @var		class		$INF	The class instance of Infinite_Admin or Infinite_Public
	 */
	public function get_customers_table($INF) {
		global $wpdb;

		// Get column definitions here
		$cols = $this->tables_config->view_cols;

		// Get table rows here
		$rows = [];
		$table = $wpdb->prefix . $this->table_name;

		// Dynamic query params
		$s = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : false;
		$orderby = (isset($_REQUEST['sortby'])) ? $_REQUEST['sortby'] : 'full_name';
		$direction = (isset($_REQUEST['sortdir'])) ? $_REQUEST['sortdir'] : 'ASC';
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 25;
		$offset = (isset($_GET['pg'])) ? $limit * (intval($_GET['pg']) - 1) : 0;

		// Base queries
		$tq = "SELECT COUNT(*) FROM $table";
		$rq = "SELECT * FROM $table";
		$rq2 = " ORDER BY $orderby $direction LIMIT $limit OFFSET $offset";

		// Search queries
		if ($s) {
			$pS = addslashes($s);
			$tq .= " WHERE ";
			$rq .= " WHERE ";

			foreach ($cols as $col) {
				if ($col->search) $sqa[] = $col->slug . " LIKE '%$pS%'";
			}

			$sq = implode(' OR ', $sqa);
			$tq .= $sq;
			$rq .= $sq;
		}

		// Queries
		$rq .= $rq2;

		// Count total number of records and figure page count
		$total = $wpdb->get_var($tq);
		$pages = ceil($total / $limit);

		// Get actual records
		$results = $wpdb->get_results($rq, ARRAY_A);

		// Get primary action
		$primary_action = false;
		foreach ($this->tables_config->actions as $action) {
			if ($action->primary) $primary_action = $action->slug;
		}

		// Set additional template vars
		$actions = $this->tables_config->actions;
		$rows = $results;
		$screen = $INF->get_current_screen();
		$view = $INF->get_current_view();

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/comp_table.php';
	}

	/**
	 * Get a single customers details.
	 *
	 * @since    1.0.0
	 * 
	 * @var		class		$INF	The class instance of Infinite_Admin or Infinite_Public
	 */
	public function get_customer_details($INF) {
		global $wpdb;
	}

	/**
	 * Handles the hooks for Db Seeder AJAX calls
	 *
	 * @since    1.0.0
	 */
	public function ajax_db_seeder_hooks() {
		add_action('wp_ajax_nopriv_customers_db_seeder', [$this, 'ajax_db_seeder']);
		add_action('wp_ajax_customers_db_seeder', [$this, 'ajax_db_seeder']);
	}

	/**
	 * Create separate public methods for each table you want to seed
	 *
	 * @since    1.0.0
	 */
	public function ajax_db_seeder() {
		// Verify Nonce
		if (!wp_verify_nonce($_REQUEST['nonce'], 'customers_seeder_nonce')) {
			exit('No naughty business please');
		}

		// Runs seeders
		$this->seed_customers();

		// Return to sender
		header("Location: " . $_SERVER["HTTP_REFERER"]);

		// Just in case...
		die();
	}

	/**
	 * Create separate public methods for each table you want to seed
	 *
	 * @since    1.0.0
	 */
	public function seed_customers() {
		global $wpdb;

		require_once plugin_dir_path(dirname(__FILE__)) . 'vendor/autoload.php';

		$faker = Faker\Factory::create();
		$customers = [];

		for ($i = 1; $i <= 60; $i++) {
			$first = $faker->firstName();
			$last = $faker->lastName();
			$name = $first . ' ' . $last;
			$customers[] = [
				'first_name'			=> $first,
				'last_name'				=> $last,
				'full_name'				=> $name,
				'primary_phone'		=> $faker->phoneNumber(),
				'street1'					=> $faker->streetAddress(),
				'street2'					=> $faker->optional(40)->secondaryAddress(),
				'city'						=> $faker->city(),
				'state'						=> $faker->state(),
				'postal_code'			=> $faker->postcode(),
				'source'					=> $faker->url(),
			];
		}

		$table = $wpdb->prefix . $this->table_name;

		foreach ($customers as $customer) {
			$wpdb->insert($table, $customer);
		}
	}
}

$Infinite_Customers = new Infinite_Customers();
