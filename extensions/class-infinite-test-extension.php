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

class Infinite_Test_Extension_Class {

	public function get_content() {
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

		return $rows;
	}
}
