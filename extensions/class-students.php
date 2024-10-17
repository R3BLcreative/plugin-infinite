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

class INF_Students {
	/**
	 * The table name for the customers table.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $table_name    The name of the customers table.
	 */
	private $table_name = 'infinite_students';

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
				'slug' => 'ID',
				'label' => 'SID',
				'css' => [
					'col' => 'text-center w-[5%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => true,
			],
			[
				'slug' => 'student_name',
				'label' => 'Student',
				'css' => [
					'col' => 'text-left w-[10%]',
					'cell' => '',
				],
				'filter' => false,
				'sort' => false,
			],
			[
				'slug' => 'primary_phone',
				'label' => 'Phone',
				'css' => [
					'col' => 'text-center w-[10%]',
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

		// Get column definitions here
		$cols = $this->tables_config->view_cols;

		// Get table rows here
		$rows = [];
		$table = $wpdb->prefix . $this->table_name;

		// Dynamic query params
		$s = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : false;
		$orderby = (isset($_REQUEST['sortby'])) ? $_REQUEST['sortby'] : 'first_name';
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

		require_once WP_PLUGIN_DIR . '/infinite-plugin/admin/partials/comp_table.php';
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
		$stable = $wpdb->prefix . $this->table_name;
		$student = $wpdb->get_row("SELECT * FROM $stable WHERE ID = $ID LIMIT 1", ARRAY_A);

		$rtable = $wpdb->prefix . 'infinite_rosters';
		$courses = $wpdb->get_results("SELECT * FROM $rtable WHERE student_id = $ID LIMIT 5", ARRAY_A);

		$args = [
			'student' => $this->format_student($student),
			'courses' => $this->format_courses($courses),
			'certificates' => $this->format_certificates($courses),
		];

		get_template_part('infinite/admin/partials/view', 'student', $args);
	}

	//----------------- CRUD METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @param  [type] $entry
	 * @return void
	 */
	public function create($entry) {
		global $wpdb;

		$customer = [
			// 'user_id'				=> $user_id,
			'first_name'		=> $entry['3.3'],
			'middle_name'		=> $entry['3.4'],
			'last_name'			=> $entry['3.6'],
			'suffix'				=> $entry['3.8'],
			'primary_phone'	=> $entry[5],
			'primary_email'	=> $entry[4],
			'street1'				=> $entry['7.1'],
			'street2'				=> $entry['7.2'],
			'city'					=> $entry['7.3'],
			'state'					=> $entry['7.4'],
			'postal_code'		=> $entry['7.5'],
			'license1'			=> $entry[10],
			'license2'			=> $entry[12],
			'tc_agreement'	=> 1,
		];
		$format = [
			// '%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
		];

		$table = $this->get_wp_table_name();
		$response = $wpdb->insert($table, $customer, $format);
		return $wpdb->insert_id;
	}

	/**
	 * Checks if exists using email (in the future check against user table)
	 *
	 * @return void
	 */
	public function read($email) {
		global $wpdb;

		$table = $this->get_wp_table_name();
		$rq = "SELECT ID FROM $table WHERE primary_email = '$email' LIMIT 1";
		$row = $wpdb->get_row($rq, ARRAY_A);

		if ($row) return $row['ID'];

		return false;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_certificate() {
		if (!wp_verify_nonce($_REQUEST['nonce'], 'cert_nonce')) exit('No naughty business please');

		global $wpdb;

		// Get student info
		$SID = $_REQUEST['SID'];
		$stable = $wpdb->prefix . 'infinite_students';
		$student = $wpdb->get_row("SELECT * FROM $stable WHERE ID = $SID LIMIT 1", ARRAY_A);
		$name = $this->format_name($student);

		// Get course info
		$CID = $_REQUEST['CID'];
		$title = get_the_title($CID);
		$code = get_field('course_code', $CID);
		$instructor = get_field('instructor', $CID);

		// Get date
		$date = $_REQUEST['date'];

		require_once get_stylesheet_directory() . '/infinite/vendor/autoload.php';

		$tpl_path = get_stylesheet_directory() . '/infinite/pdf-templates/bvwp-certificate.pdf';

		$pdf = new setasign\Fpdi\Fpdi('l');
		$page_count = $pdf->setSourceFile($tpl_path);
		$tpl = $pdf->importPage(1);
		$pdf->AddPage();
		$pdf->useTemplate($tpl, ['adjustPageSize' => true]);
		$pdf->SetFont('Helvetica');

		// Student Name
		$pdf->SetFontSize('34');
		$pdf->SetXY(10, 85);
		$pdf->Cell(0, 20, $name, 0, 0, 'C');

		// Course Title
		$pdf->SetFontSize('30');
		$pdf->SetXY(10, 125);
		$pdf->Cell(0, 20, $title, 0, 0, 'C');

		// TCEQ Course Number
		$pdf->SetFontSize('14');
		$pdf->SetXY(35, 167);
		$pdf->Cell(35, 8, $code, 0, 0, 'C');

		// Completion Date
		$pdf->SetFontSize('14');
		$pdf->SetXY(35, 185);
		$pdf->Cell(35, 8, date('m/d/Y', $date), 0, 0, 'C');

		// Instructor
		$pdf->SetFontSize('14');
		$pdf->SetXY(186, 167);
		$pdf->Cell(60, 8, $instructor, 0, 0, 'C');

		// Training Provider
		$pdf->SetFontSize('14');
		$pdf->SetXY(186, 185);
		$pdf->Cell(60, 8, 'Brazos Valley Water Protection', 0, 0, 'C');

		$pdf->Output();
	} // Move this to FPDF class

	//----------------- FORMATTER METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @param  [type] $student
	 * @return void
	 */
	private function format_student($student) {
		// foreach ($student as $key => $value) {
		// 	switch ($key) {
		// 		case 'test':
		// 			break;

		// 		default:
		// 			break;
		// 	}
		// }

		$student['full_name'] = $this->format_name($student);

		$student['license'] = (!empty($student['license1'])) ? $student['license1'] : 'XXX-XX-' . substr($student['license2'], 7, 4);

		return $student;
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $student
	 * @return void
	 */
	private function format_name($student) {
		$name = $student['first_name'];
		$name .= (!empty($student['middle_name'])) ? ' ' . $student['middle_name'] : '';
		$name .= ' ' . $student['last_name'];
		$name .= (!empty($student['suffix'])) ? ' ' . $student['suffix'] : '';

		return $name;
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $courses
	 * @return void
	 */
	private function format_courses($courses) {
		$rows = [];

		foreach ($courses as $course) {
			$rows[] = [
				'ID' => $course['ID'],
				'title' => get_the_title($course['course_id']),
				'sched' => $course['schedule'],
				'order' => $course['order_id'],
			];
		}

		return $rows;
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $courses
	 * @return void
	 */
	private function format_certificates($courses) {
		$rows = [];

		foreach ($courses as $course) {
			if (!$course['passed']) continue;

			$dates = explode('-', $course['schedule']);
			$completion_date = (count($dates) > 1) ? $dates[1] : $dates[0];

			$rows[] = [
				'course' => get_the_title($course['course_id']),
				'completion_date' => date('m/d/Y', strtotime($completion_date)),
				'SID' => $course['student_id'],
				'CID' => $course['course_id'],
			];
		}

		return $rows;
	}

	//----------------- AJAX METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_hooks() {
		add_action('wp_ajax_nopriv_generate_cert', [$this, 'get_certificate']);
		add_action('wp_ajax_generate_cert', [$this, 'get_certificate']);

		add_action('wp_ajax_nopriv_update_student', [$this, 'ajax_update_student']);
		add_action('wp_ajax_update_student', [$this, 'ajax_update_student']);
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ajax_update_student() {
		// Verify Nonce
		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_update_student')) {
			exit('No naughty business please');
		}

		global $wpdb;

		$SID = intval($_REQUEST['ID']);
		$data = $_REQUEST['updates'];
		$format = [];
		foreach ($data as $item) {
			$format[] = '%s';
		}
		$table = $wpdb->prefix . $this->table_name;
		$customer = $wpdb->update($table, $data, ['ID' => $SID], $format, ['%d']);

		// Return to sender
		header('Location: ' . $_SERVER['HTTP_REFERER']);

		// Just in case...
		die();
	}
}

$this->loader->add_action('admin_init', new INF_Students, 'ajax_hooks');
