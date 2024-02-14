<?php

/**
 * Infinite helper functions.
 *
 * This is used to define helper functions that are available to the entire plugin.
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
 */
function inf_get_icon($icon) {
	$file = plugin_dir_path(dirname(__FILE__)) . 'icons/' . $icon . '.svg';

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
