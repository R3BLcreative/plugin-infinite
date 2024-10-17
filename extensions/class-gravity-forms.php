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

class INF_Gravity_Forms {
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	public $last_error;

	/**
	 * The ID of the Gravity Form to get entries from.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $form_id    The ID of the Gravity Form.
	 */
	private $form_id;

	/**
	 * Undocumented function
	 */
	public function __construct() {
		// TODO: This needs to be set in ACF settings field
		$this->form_id = 1;
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function init() {
		// Checkout flows
		add_filter('gform_validation_' . $this->form_id, [$this, 'validate_cart'], 10, 2);
		add_filter('gform_validation_message_' . $this->form_id, [$this, 'invalid_cart_msg'], 10, 2);

		// add_action('gform_user_registered', [$this, 'save_gf_to_db'], 10, 4);

		add_action('gform_entry_created', [$this, 'save_gf_to_db'], 10, 2);
		add_action('gform_confirmation_' . $this->form_id, [new INF_Stripe, 'redirect_to_stripe_checkout'], 10, 4);

		// Changes address field full state names to state abbreviations
		add_filter('gform_us_states', [$this, 'use_state_abbreviations']);

		// Removes license info from display in admin
		add_filter('gform_settings_display_license_details', '__return_false');
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function admin_init() {
		add_filter('acf/load_field/name=checkout_forms', [$this, 'acf_load_gravity_form_choices']);
	}

	//----------------- UTILITY METHODS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function use_state_abbreviations($states) {
		$new_states = [];
		foreach ($states as $state) {
			$new_states[GF_Fields::get('address')->get_us_state_code($state)] = $state;
		}

		return $new_states;
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $field
	 * @return void
	 */
	public function acf_load_gravity_form_choices($field) {
		$forms = GFAPI::get_forms();

		inf_log('acf_load_gravity_form_choices:', $forms, 'gf');

		foreach ($forms as $form) {
			$id = rgar($form, 'id');
			$title = rgar($form, 'title');
			$field['choices'][$id] = $title;
		}

		return $field;
	}

	//----------------- SUBMISSION FLOWS -----------------\\

	/**
	 * Undocumented function
	 *
	 * @param  [type] $validation_result
	 * @return void
	 */
	public function validate_cart($validation_result) {
		$form  = $validation_result['form'];
		$entry = GFFormsModel::get_current_lead();
		$email = rgar($entry, '4');

		// Check student DB and get ID if exists
		$STUDENTS = new INF_Students;
		$SID = $STUDENTS->read($email);
		if (!$SID) return $validation_result;

		// Sets SESSION var isRegistered on each cart item
		$ROSTERS = new INF_Rosters;
		$ROSTERS->check_roster($SID);

		$isValid = [];
		foreach ($_SESSION['cart']['items'] as $item) {
			if ($item['isRegistered']) {
				$isValid[] = false;
			}
		}

		if (in_array(false, $isValid)) $validation_result['is_valid'] = false;
		// $validation_result['is_valid'] = false;

		return $validation_result;
	}

	/**
	 * Undocumented function
	 *
	 * @param  [type] $msg
	 * @param  [type] $form
	 * @return void
	 */
	public function invalid_cart_msg($msg, $form) {
		$entry = GFFormsModel::get_current_lead();
		$email = rgar($entry, '4');
		$new_msg = '';

		$STUDENTS = new INF_Students;
		$SID = $STUDENTS->read($email);
		$ROSTERS = new INF_Rosters;
		$ROSTERS->check_roster($SID);

		foreach ($_SESSION['cart']['items'] as $item) {
			if ($item['isRegistered']) {
				$new_msg .= '<li class="gform_submission_error">&bull; ' . get_the_title($item['id']) . '</li>';
			}
		}

		if (!empty($new_msg)) {
			$msg .= '<div><h2 class="gform_submission_error hide_summary">
		<span class="gform-icon gform-icon--close"></span>
		Looks like you are already registered for the following course(s):
		</h2>
		<ul style="margin:5px 0 5px 20px;">' . $new_msg . '</ul></div>';
		}

		return $msg;
	}

	/**
	 * Save GF submissions to custom db
	 *
	 * @since    1.0.0
	 * @param    object    $entry   Gravity Forms EntryObject.
	 * @param    object    $form    Gravity Forms FormObject.
	 */
	public function save_gf_to_db($entry, $form) {
		if ($form['id'] == $this->form_id) {
			// Check if already exists using email
			$STUDENTS = new INF_Students;
			$SID = $STUDENTS->read($entry[4]);

			// Create new if doesn't exist
			if (!$SID) $SID = $STUDENTS->create($entry);

			// Add to other tables
			// $_SESSION['rosters'] = $this->add_to_roster($SID);
			$ORDERS = new INF_Orders;
			$_SESSION['order_id'] = $ORDERS->create($SID);
		}
	}
}

//----------------- INIT -----------------\\

$this->loader->add_action('admin_init', new INF_Gravity_Forms, 'admin_init');
$this->loader->add_action('init', new INF_Gravity_Forms, 'init');
