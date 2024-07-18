<?php

/**
 * Infinite helper functions.
 *
 * This is used to define helper functions that are 
 * available to the entire plugin.
 *
 *
 * @since      1.0.0
 * @package    Infinite
 * @subpackage Infinite/includes
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */

/**
 * Get the SVG code for an icon
 *
 * @since      1.0.0
 * @package    Infinite
 * 
 * @var		string		$icon		the icon file name without the .ext
 * @var		string		$path		optional path to file
 */
function inf_get_icon($icon, $path = false) {
	$path = ($path) ? $path : 'icons/';
	$file = plugin_dir_path(dirname(__FILE__)) . $path . $icon . '.svg';

	if (file_exists($file)) {
		return file_get_contents($file);
	}

	return false;
}

/**
 * Check if a select field option is selected or should be the default selection
 *
 * @since      1.0.0
 * @package    Infinite
 * 
 * @var		string		$value				The value of the current option item
 * @var 	string		$key					The $_REQUEST key to compare the value with 
 * @var 	bool			$isDefault		Is the current option the default selection
 */
function inf_is_selected($value, $key, $isDefault = false) {
	if (!isset($_REQUEST[$key]) && $isDefault) return true;

	if (!isset($_REQUEST[$key]) && !$isDefault) return false;

	if (isset($_REQUEST[$key]) && $_REQUEST[$key] == $value) return true;

	return false;
}

function inf_log($message, $payload = false, $log = 'admin') {
	// Check if logging is turned on
	if (INF_LOG) {
		// Set filename to today timestamp
		$filename = strtotime('TODAY') . '_' . $log . '.log';

		// Set paths to log files
		$the_path = get_stylesheet_directory() . '/infinite/logs';

		// Generate a timestamp for new entry
		$entry_timestamp = date('m/d/Y h:i:s A', strtotime('NOW'));

		// Generate entry
		$entry = $entry_timestamp . "\r\n";
		$entry .= $message . "\r\n";
		$entry .= ($payload) ? json_encode($payload) . "\r\n" : '';

		// Check if file exists
		if (file_exists($the_path . '/' . $filename)) {
			// Get current file content
			$entry .= "\r\n\r\n";
			$entry .= file_get_contents($the_path . '/' . $filename);
		}

		// Create logs directory if it doesn't exist
		if (!is_dir($the_path)) {
			mkdir($the_path);
		}

		// Write to file
		file_put_contents($the_path . '/' . $filename, $entry);
	}
}
