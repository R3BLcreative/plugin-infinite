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

use setasign\Fpdi\Fpdi;

class INF_Rosters {
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
	private $slug = 'rosters';

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
	 * @param  GF_Form_Obj $form
	 * @param  GF_Entry_Obj $entry
	 * @param  string $label
	 * @return boolean|string
	 */
	public function get_gf_field_by_label($form, $entry, $label) {
		foreach ($form['fields'] as $field) {
			$key = $field->label;
			if (strtolower($key) == strtolower($label)) {
				return $entry[$field->id];
			}

			// Check advanced fields
			if ($field->inputs) {
				foreach ($field->inputs as $input) {
					$key2 = $input['label'];
					if (strtolower($key2) == strtolower($label)) {
						return $entry[$input['id']];
					}
				}
			}
		}

		return false;
	}

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	private function get_list_view_cols() {
		$cols = [
			[
				'slug' => 'course_id',
				'label' => 'CID',
				'css' => [
					'col' => 'text-center w-[5%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => true,
			],
			[
				'slug' => 'course_title',
				'label' => 'Course',
				'css' => [
					'col' => 'text-left w-[20%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => false,
			],
			[
				'slug' => 'schedule',
				'label' => 'Schedule',
				'css' => [
					'col' => 'text-left w-[15%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => false,
			],
			[
				'slug' => 'registered_count',
				'label' => 'Students',
				'css' => [
					'col' => 'text-center w-[10%]',
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
				'view' => 'roster-details',
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
		$gf_entry_table = $wpdb->prefix . 'gf_entry_meta';
		$cols = $this->get_list_view_cols();
		$actions = $this->get_list_view_actions();
		$showFilter = true;

		// Dynamic query params
		$s = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : false;
		$orderby = (isset($_REQUEST['sortby'])) ? $_REQUEST['sortby'] : 'course_id';
		$direction = (isset($_REQUEST['sortdir'])) ? $_REQUEST['sortdir'] : 'DESC';
		$filterby = (isset($_REQUEST['filterby'])) ? $_REQUEST['filterby'] : false;
		$filterval = (isset($_REQUEST['filterval'])) ? $_REQUEST['filterval'] : false;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 50;
		$offset = (isset($_GET['pg'])) ? $limit * (intval($_GET['pg']) - 1) : 0;

		// Base queries
		$tq = "SELECT COUNT(*) FROM $table GROUP BY schedule";
		$rq = "SELECT *, COUNT(*) as registered_count FROM $table GROUP BY course_id, schedule";
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
			// $sqa[] = "license1 LIKE '%$pS%'";
			// $sqa[] = "license2 LIKE '%$pS%'";

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

		inf_log('get_list_view: query', $rq, 'rosters');
		inf_log('get_list_view: results', $results, 'rosters');

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

		// Rosters do not have ID's so initial ID is from a single roster record use that to get data
		$RID = $_REQUEST['ID'];
		$roster = $this->read($RID);

		$data = $roster[0];

		$args = [
			'RID' => $RID,
			'title' => get_the_title($data['course_id']),
			'schedule' => $data['schedule'],
			'roster' => $roster,
		];

		get_template_part('infinite/admin/partials/view', 'roster', $args);
	}

	//----------------- CRUD METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @param  [type] $RID
	 * @return array
	 */
	public function read($RID) {
		global $wpdb;

		$table = $this->get_wp_table_name();
		$ref = $wpdb->get_row("SELECT * FROM $table WHERE ID = $RID LIMIT 1", ARRAY_A);
		$schedule = $ref['schedule'];
		$CID = $ref['course_id'];

		$STUDENTS = new INF_Students;
		$stable = $STUDENTS->get_wp_table_name();
		$roster = $wpdb->get_results("SELECT Roster.ID as RID, Roster.course_id, Roster.student_id, Roster.schedule, Roster.passed, Students.ID, Students.first_name, Students.last_name, Students.license1, Students.license2 FROM $table as Roster LEFT JOIN $stable as Students ON Roster.student_id = Students.ID WHERE Roster.schedule = '$schedule' AND Roster.course_id = $CID ORDER BY Students.first_name ASC", ARRAY_A);

		return $roster;
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $OID
	 * @return void
	 */
	public function update($OID) {
		global $wpdb;

		// Get the order
		$ORDERS = new INF_Orders;
		$otable = $ORDERS->get_wp_table_name();
		$order = $wpdb->get_row("SELECT * FROM $otable WHERE ID = $OID", ARRAY_A);

		$SID = $order['student_id'];
		$items = json_decode($order['cart'], true);

		// Add to roster
		foreach ($items as $item) {
			$roster = [
				'course_id' => intval($item['id']),
				'student_id' => intval($SID),
				'order_id' => intval($OID),
				'schedule' => $item['sched'],
			];

			$rtable = $this->get_wp_table_name();
			$wpdb->insert($rtable, $roster);
		}

		// Update order status
		$wpdb->update($otable, ['status' => 'payment_complete'], ['ID' => $OID], ['%s'], ['%d']);
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $SID
	 * @return void
	 */
	public function check_roster($SID) {
		global $wpdb;

		foreach ($_SESSION['cart']['items'] as $key => $item) {
			$id = $item['id'];
			$sched = $item['sched'];
			$table = $this->get_wp_table_name();
			$rq = "SELECT * FROM $table WHERE student_id = $SID AND course_id = $id AND schedule = '$sched' LIMIT 1";
			$result = $wpdb->get_row($rq, ARRAY_A);
			if ($result) {
				$_SESSION['cart']['items'][$key]['isRegistered'] = true;
			} else {
				$_SESSION['cart']['items'][$key]['isRegistered'] = false;
			}
		}
	}

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
		$row['course_title'] = get_the_title($row['course_id']);

		return $row;
	}

	//----------------- AJAX METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_hooks() {
		add_action('wp_ajax_nopriv_update_passed', [$this, 'ajax_update_passed']);
		add_action('wp_ajax_update_passed', [$this, 'ajax_update_passed']);
		//
		add_action('wp_ajax_nopriv_update_failed', [$this, 'ajax_update_failed']);
		add_action('wp_ajax_update_failed', [$this, 'ajax_update_failed']);
		//
		add_action('wp_ajax_nopriv_generate_signin', [$this, 'ajax_generate_signin']);
		add_action('wp_ajax_generate_signin', [$this, 'ajax_generate_signin']);
		//
		add_action('wp_ajax_nopriv_move_roster', [$this, 'ajax_move_roster']);
		add_action('wp_ajax_move_roster', [$this, 'ajax_move_roster']);
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_update_passed() {
		global $wpdb;

		// Verify Nonce
		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_infinite_nonce')) {
			exit('No naughty business please');
		}

		$table = $wpdb->prefix . $this->table_name;
		$wpdb->update($table, ['passed' => true], ['ID' => $_REQUEST['RID'], 'student_id' => $_REQUEST['SID']]);

		// Return a JSON response
		echo json_encode(['success' => true, 'result' => 'PASS']);

		die();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_update_failed() {
		global $wpdb;

		// Verify Nonce
		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_infinite_nonce')) {
			exit('No naughty business please');
		}

		$table = $wpdb->prefix . $this->table_name;
		$wpdb->update($table, ['passed' => false], ['ID' => $_REQUEST['RID'], 'student_id' => $_REQUEST['SID']]);

		// Return a JSON response
		echo json_encode(['success' => true, 'result' => 'FAIL']);

		die();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_move_roster() {
		global $wpdb;

		// Verify Nonce
		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_move_roster')) {
			exit('No naughty business please');
		}

		global $wpdb;

		$RID = intval($_REQUEST['RID']);
		$data = $_REQUEST['updates'];
		$table = $wpdb->prefix . $this->table_name;
		$customer = $wpdb->update($table, $data, ['ID' => $RID], ['%s'], ['%d']);

		// Return to sender
		header('Location: ' . admin_url('admin.php?page=infinite-rosters'));

		// Just in case...
		die();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_generate_signin() {
		global $wpdb;

		// Verify Nonce
		if (!wp_verify_nonce($_REQUEST['nonce'], 'roster_nonce')) {
			exit('No naughty business please');
		}

		// Set headers
		//header("Content-Type: application/pdf");

		// Get student info
		$RID = $_REQUEST['RID'];
		$students = $this->read($RID);
		//
		$data = $students[0];
		$CID = $data['course_id'];
		//
		$title = get_the_title($CID);
		$code = get_field('course_code', $CID);
		$instructor = get_field('instructor', $CID);

		require_once get_stylesheet_directory() . '/infinite/vendor/autoload.php';

		$tpl_path = get_stylesheet_directory() . '/infinite/pdf-templates/SigninTemplate.pdf';

		$pdf = new Fpdi('L', 'in', [8.5, 11]);
		$pdf->setSourceFile($tpl_path);
		$tpl = $pdf->importPage(1);
		$pdf->AddPage();
		$pdf->useTemplate($tpl, ['adjustPageSize' => true]);
		$pdf->SetFont('Helvetica');

		// Instructor
		$pdf->SetFontSize(10);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(4.25, 0.6);
		$pdf->Write(0, $instructor);

		// Course Title
		$pdf->SetFontSize(18);
		$pdf->SetTextColor(0, 0, 255);
		$pdf->SetXY(2.95, 1.01);
		$pdf->Write(0, $title . ' (' . $code . ')');

		// Students
		$x = .95;
		$y = 1.28;
		$inc = 0.35;
		foreach ($students as $i => $student) {
			$i++; // Multiplier
			$adjust = $inc * $i;
			//
			$pdf->SetFontSize(12);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY($x, $y + $adjust);
			$pdf->Write(0, $student['first_name'] . ' ' . $student['last_name']);
			//
			$pdf->SetFontSize(12);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY($x + 5, $y + $adjust);
			if (isset($student['license2']) && !empty($student['license2'])) {
				$lic = 'XXX-XX-' . substr($student['license2'], 7, 4);
				$pdf->Write(0, $lic);
			} else {
				$pdf->Write(0, $student['license1']);
			}
		}

		$pdf->Output();

		die();
	}
}

$this->loader->add_action('admin_init', new INF_Rosters, 'ajax_hooks');
