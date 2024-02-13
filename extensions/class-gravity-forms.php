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

class Infinite_Gravity_Forms {
	/**
	 * The ID of the Gravity Form to get entries from.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $form_id    The ID of the Gravity Form.
	 */
	private $form_id = 1;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action('gform_user_registered', [$this, 'save_gf_to_db'], 10, 4);
	}

	/**
	 * Save GF submissions to custom db
	 *
	 * @since    1.0.0
	 * @param    object    $entry   Gravity Forms EntryObject.
	 * @param    object    $form    Gravity Forms FormObject.
	 */
	public function save_gf_to_db($user_id, $feed, $entry, $pass) {
		global $wpdb;

		if (intval($entry['form_id']) === $this->form_id) {
			$source = 'GFID:' . $entry['form_id'] . ' // EID:' . $entry['id'];
			$customer = [
				'user_id'				=> $user_id,
				'first_name'		=> $entry['1.3'],
				'last_name'			=> $entry['1.6'],
				'full_name'			=> $entry['1.3'] . ' ' . $entry['1.6'],
				'primary_phone'	=> $entry[4], // TODO: Format phone number
				'street1'				=> $entry['5.1'],
				'street2'				=> $entry['5.2'],
				'city'					=> $entry['5.3'],
				'state'					=> $entry['5.4'],
				'postal_code'		=> $entry['5.5'],
				'source'				=> $source,
			];
			$format = [
				'%d',
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
			];

			$table = $wpdb->prefix . 'infinite_customers';
			$response = $wpdb->insert($table, $customer, $format);
			// TODO: Need to check for errors if the insert returns false. Possibly due to user_id/email
		}
	}
}

$INFINITE_GRAVITY_FORMS = new Infinite_Gravity_Forms();
