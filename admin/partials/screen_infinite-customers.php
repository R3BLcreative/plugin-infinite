<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 * @subpackage Infinite/admin/partials
 */

$cols = [
	[
		'slug'		=> 'name',
		'label'		=> 'Name'
	],
	[
		'slug'		=> 'test',
		'label'		=> 'Testing'
	]
];

$rows = [
	[
		'name'	=> 'James Cook',
		'test'	=> 'Some data',
	],
	[
		'name'	=> 'John Doe',
		'test'	=> 'Other data',
	],
	[
		'name'	=> 'Jane Doe',
		'test'	=> 'More data',
	],
];

require_once plugin_dir_path(dirname(__FILE__)) . 'partials/admin_table.php';
