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

class INF_Schedules {
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
	private $slug = 'schedules';

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

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	private function get_list_view_cols() {
		$cols = [
			[
				'slug' => 'ID',
				'label' => 'CID',
				'css' => [
					'col' => 'text-center w-[5%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => true,
			],
			[
				'slug' => 'title',
				'label' => 'Course',
				'css' => [
					'col' => 'text-left w-[10%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => true,
			],
			[
				'slug' => 'primary_phone',
				'label' => 'Phone',
				'css' => [
					'col' => 'text-left w-[10%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => false,
			],
			[
				'slug' => 'primary_email',
				'label' => 'Email',
				'css' => [
					'col' => 'text-left w-[10%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => false,
			]
		];

		return $cols;
	}

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	private function get_list_view_actions() {
		$actions = [
			[
				'slug' => 'view',
				'icon' => 'view',
				'label' => 'View',
				'view' => 'student-details',
				'verify' => false,
			]
		];

		return $actions;
	}

	//----------------- VIEW METHODS -----------------\\

	/**
	 * Get customers table content.
	 *
	 * @since    1.0.0
	 * 
	 * @var		class		$INF	The class instance of Infinite_Admin or Infinite_Public
	 */
	public function get_list_view($INF) {
		global $wpdb;

		// Setup vars
		$rows = [];
		$table = $this->get_wp_table_name();
		$cols = $this->get_list_view_cols();
		$actions = $this->get_list_view_actions();
		$showFilter = true;

		// Dynamic query params
		$s = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : false;
		$orderby = (isset($_REQUEST['sortby'])) ? $_REQUEST['sortby'] : 'ID';
		$direction = (isset($_REQUEST['sortdir'])) ? $_REQUEST['sortdir'] : 'DESC';
		$filterby = (isset($_REQUEST['filterby'])) ? $_REQUEST['filterby'] : false;
		$filterval = (isset($_REQUEST['filterval'])) ? $_REQUEST['filterval'] : false;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 50;
		$offset = (isset($_GET['pg'])) ? $limit * (intval($_GET['pg']) - 1) : 0;

		// Base queries
		$tq = "SELECT COUNT(*) FROM $table";
		$rq = "SELECT * FROM $table";
		$rq2 = " ORDER BY $orderby $direction LIMIT $limit OFFSET $offset";

		// Filter queries
		if ($filterby && $filterval) {
			$tq .= " AND $filterby = '$filterval'";
			$rq .= " AND $filterby = '$filterval'";
		}

		// Search queries
		if ($s) {
			$pS = addslashes($s);
			$tq .= " AND ";
			$rq .= " AND ";

			foreach ($cols as $col) {
				$col_slug = $col['slug'];

				// switch ($col_slug) {
				// 	case 'tracking_id_link':
				// 		$col_slug = 'tracking_id';
				// 		break;
				// }

				$sqa[] = $col_slug . " LIKE '%$pS%'";
			}

			// Include additional cols in search
			$sqa[] = "license1 LIKE '%$pS%'";
			$sqa[] = "license2 LIKE '%$pS%'";

			$sq = implode(' OR ', $sqa);
			$tq .= '(' . $sq . ')';
			$rq .= '(' . $sq . ')';
		}

		// Queries
		$rq .= $rq2;

		// Count total number of records and figure page count
		$total = $wpdb->get_var($tq);
		$pages = ceil($total / $limit);

		// Get actual records
		$results = $wpdb->get_results($rq, ARRAY_A);

		inf_log('get_list_view: query', $rq, 'schedules');
		inf_log('get_list_view: results', $results, 'schedules');

		// Get filterby values
		if ($filterby) {
			$filterVals = $wpdb->get_results("SELECT DISTINCT $filterby FROM $table ORDER BY $filterby ASC", ARRAY_A);
			$filterVals = array_column($filterVals, $filterby);

			if (in_array('Archived', $filterVals)) {
				$i = array_search('Archived', $filterVals);
				unset($filterVals[$i]);
				$filterVals = array_values($filterVals);
			}
		}

		// Set additional template vars
		$rows = $this->format_results($results);
		$screen = $INF->get_current_screen();
		$view = $INF->get_current_view();
		$bulk_bar_data = [
			'total' => $total,
			'showBulkActions' => true,
			'actions' => [
				'batch_action' => [
					'display' => 'Action',
					'target' => '_blank'
				],
				'batch_actions' => [
					'display' => 'Actions',
					'target' => '_self'
				],
			],
		];

		require_once plugin_dir_path(__DIR__) . 'admin/partials/comp_table.php';
	}

	/**
	 * Get a single customers details.
	 *
	 * @since    1.0.0
	 * 
	 * @var		class		$INF	The class instance of Infinite_Admin or Infinite_Public
	 */
	public function get_details_view($INF) {
		global $wpdb;

		$ID = $_REQUEST['ID'];
		$table = $this->get_wp_table_name();
		$schedules = $wpdb->get_row("SELECT * FROM $table WHERE ID = $ID LIMIT 1", ARRAY_A);

		$args = [
			'schedules' => $this->format_row($schedules),
		];

		get_template_part('infinite/admin/partials/view', 'student', $args);
	}

	//----------------- CRUD METHODS -----------------\\



	//----------------- FORMATTING METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @param  array $rows
	 * @return array $rows
	 */
	public function format_results($rows) {
		foreach ($rows as $i => $row) {
			$rows[$i] = $this->format_row($row);
		}

		return $rows;
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $row
	 * @return void
	 */
	public function format_row($row) {
		// Format DB columns
		foreach ($row as $key => $value) {
			switch ($key) {
				case 'col_name':
					// Format
					break;
				default:
					break;
			}
		}

		// Additional Formatters
		// $row['key'] = 'value';

		return $row;
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

$this->loader->add_action('admin_init', new INF_Schedules, 'ajax_hooks');
