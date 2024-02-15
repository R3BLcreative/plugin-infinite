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
				'search' => true,
			],
			[
				'slug'	=> 'primary_phone',
				'label'	=> 'Phone',
				'colCss' => 'text-left',
				'cellCss' => '',
				'sortable' => false,
				'initSort' => false,
				'search' => false,
			],
			[
				'slug'	=> 'city',
				'label'	=> 'City',
				'colCss' => 'text-left',
				'cellCss' => '',
				'sortable' => true,
				'initSort' => false,
				'search' => true,
			],
			[
				'slug'	=> 'state',
				'label'	=> 'State',
				'colCss' => 'text-left',
				'cellCss' => '',
				'sortable' => true,
				'initSort' => false,
				'search' => true,
			],
		];

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
				$sqa[] = $col['slug'] . " LIKE '%$pS%'";
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

		// Set content var
		$content = [
			'cols' => $cols,
			'rows' => $results,
			'total' => $total,
			'pages' => $pages,
			'limit' => $limit,
			's' => $s,
		];

		return $content;
	}
}
