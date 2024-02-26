<?php

/**
 * The plugin class that handles one-click & automatic updates.
 *
 * This is used to check for plugin updates via the GitHub repo.
 *
 * @since      1.0.0
 * @package    Infinite
 * @subpackage Infinite/includes
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */
class Infinite_Updater {
	public $plugin_name;
	public $version;
	public $cache_key;
	public $cache_allowed;

	public function __construct() {
		$this->version = INFINITE_VERSION;
		$this->plugin_name = INFINITE_SLUG;
		$this->cache_key = $this->plugin_name . '_updater';
		$this->cache_allowed = false;
	}
}
