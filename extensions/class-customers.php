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
				'label'	=> 'Name',
				'colCss' => 'text-left',
				'cellCss' => '',
				'sortable' => true,
				'initSort' => true,
			],
			[
				'slug'	=> 'primary_phone',
				'label'	=> 'Phone',
				'colCss' => 'text-left',
				'cellCss' => '',
				'sortable' => false,
				'initSort' => false,
			],
			[
				'slug'	=> 'city',
				'label'	=> 'City',
				'colCss' => 'text-left',
				'cellCss' => '',
				'sortable' => true,
				'initSort' => false,
			],
			[
				'slug'	=> 'state',
				'label'	=> 'State',
				'colCss' => 'text-left',
				'cellCss' => '',
				'sortable' => true,
				'initSort' => false,
			],
		];

		// Get table rows here
		$rows = [];
		$table = $wpdb->prefix . $this->table_name;

		// Dynamic query params
		$orderby = (isset($_REQUEST['sortby'])) ? $_REQUEST['sortby'] : 'full_name';
		$direction = (isset($_REQUEST['sortdir'])) ? $_REQUEST['sortdir'] : 'ASC';
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 25;
		$offset = (isset($_GET['pg'])) ? $limit * (intval($_GET['pg']) - 1) : 0;

		// Count total number of records and figure page count
		$total = $wpdb->get_var("SELECT COUNT(*) FROM $table");
		$pages = ceil($total / $limit);

		// Get actual records
		$results = $wpdb->get_results("SELECT * FROM $table ORDER BY $orderby $direction LIMIT $limit OFFSET $offset", ARRAY_A);

		// Set content var
		if (!empty($results)) {
			$content = [
				'cols' => $cols,
				'rows' => $results,
				'total' => $total,
				'pages' => $pages,
				'limit' => $limit
			];
		}

		return $content;
	}
}
