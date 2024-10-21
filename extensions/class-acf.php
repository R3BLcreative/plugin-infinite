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

class INF_ACF {


	/**
	 * Set the custom paths for ACF JSON storage
	 * 
	 * @param    array    $paths    An array of all the possible save paths for the file being saved
	 * @param    array    $post     an array containing all of the settings for the field group, post type, taxonomy, or options page
	 * 
	 * @return   array 
	 */
	public static function custom_acf_json_save_paths($paths, $post) {
		$key = '';
		$value = '';

		//Pull meta data field from JSON
		$meta_data = $post['acfe_meta'];

		//Check if meta data exists
		if ($meta_data != null) {
			//Index Meta data to bypass random string
			$Usable = array_values($meta_data);

			//Convert 2d array by pulling columns to get single element key and value arrays
			//Take first index ([0]) of each array to extract key and value
			$key = array_column($Usable, 'acfe_meta_key')[0];
			$value = array_column($Usable, 'acfe_meta_value')[0];
		}


		//Check if key and value match plugin meta data or options page parent slug is infinite dashboard 
		if (($key == 'json_save_path' && $value == 'INF') || ($post['parent_slug'] === 'infinite-dashboard')) {

			//create directory if directory doesnt exist
			if (!file_exists(plugin_dir_path(__DIR__) . 'acf-json')) {
				mkdir(plugin_dir_path(__DIR__) . 'acf-json', 0777, true);
			}

			//new acf save directory
			$paths = array(plugin_dir_path(__DIR__) . 'acf-json');
		} else {

			//default acf save directory
			$paths = array(get_stylesheet_directory() . '/acf-json');
		}

		return $paths;
	}


	/**
	 * Add a new load point (folder) for ACF to look in
	 * 
	 * @param   array   $paths    An array of all ACF the load points 
	 * 
	 * @return  array
	 */
	public static function my_acf_json_load_point($paths) {

		// Append the new path and return it
		$paths[] = plugin_dir_path(__DIR__) . 'acf-json';

		return $paths;
	}
}

$this->loader->add_filter('acf/json/save_paths', new INF_ACF, 'custom_acf_json_save_paths', 10, 2);
$this->loader->add_filter('acf/settings/load_json', new INF_ACF, 'my_acf_json_load_point');
