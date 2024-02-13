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
	 * Get customers table content.
	 *
	 * @since    1.0.0
	 */

	public function get_customers_table() {
		global $wpdb;

		$content = false;

		// Define table columns here
		$cols = [
			[
				'slug'	=> 'full_name',
				'label'	=> 'Name'
			],
			[
				'slug'	=> 'primary_phone',
				'label'	=> 'Phone'
			],
			[
				'slug'	=> 'city',
				'label'	=> 'City'
			],
			[
				'slug'	=> 'state',
				'label'	=> 'State'
			],
		];

		// Get table rows here
		$rows = [];
		$table = $wpdb->prefix . $this->table_name;

		// Dynamic query params
		$orderby = 'last_name'; // TODO: Orderby should be dynamic and controlled by the user
		$direction = 'ASC'; // TODO: Direction should be dynamic and controlled by the user
		$limit = 20; // TODO: Limit should be dynamic and controlled by the user
		$offset = (isset($_GET['pg'])) ? $limit * (intval($_GET['pg']) - 1) : 0;

		$results = $wpdb->get_results("SELECT * FROM $table ORDER BY $orderby $direction LIMIT $limit OFFSET $offset", ARRAY_A);

		// Set content var
		if (!empty($results)) $content = ['cols' => $cols, 'rows' => $results];

		return $content;
	}
}