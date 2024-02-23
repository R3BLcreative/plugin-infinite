<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Infinite
 * @subpackage Infinite/includes
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */
class Infinite {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Infinite_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if (defined('INFINITE_VERSION')) {
			$this->version = INFINITE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'infinite';

		$this->check_configs();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function check_configs() {
		if (defined('INF_ADMIN')) {
			$v = get_option('INF_ADMIN');
			if ($v != INF_ADMIN->version) $this->run_config_update('INF_ADMIN');
		}

		if (defined('INF_PUBLIC')) {
			$v = get_option('INF_PUBLIC');
			if ($v != INF_PUBLIC->version) $this->run_config_update('INF_PUBLIC');
		}

		if (defined('INF_ROLES')) {
			$v = get_option('INF_ROLES');
			if ($v != INF_ROLES->version) $this->run_config_update('INF_ROLES');
		}

		if (defined('INF_SETTINGS')) {
			$v = get_option('INF_SETTINGS');
			if ($v != INF_SETTINGS->version) $this->run_config_update('INF_SETTINGS');
		}

		if (defined('INF_TABLES')) {
			$v = get_option('INF_TABLES');
			if ($v != INF_TABLES->version) $this->run_config_update('INF_TABLES');
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function run_config_update($config) {
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-infinite-activator.php';
		$activator = new Infinite_Activator();

		switch ($config) {
			case 'INF_ADMIN':
				// nothing needs to be done. Updates will already happen live
				break;
			case 'INF_PUBLIC':
				// nothing needs to be done. Updates will already happen live
				break;
			case 'INF_ROLES':
				$activator->custom_roles();
				break;
			case 'INF_SETTINGS':
				$activator->custom_options();
				break;
			case 'INF_TABLES':
				// This handles the creation of new tables and updating table structures to the config
				$activator->custom_tables();
				break;
		}

		// Update the config options with new versions
		$activator->config_options();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Infinite_Loader. Orchestrates the hooks of the plugin.
	 * - Infinite_i18n. Defines internationalization functionality.
	 * - Infinite_Admin. Defines all hooks for the admin area.
	 * - Infinite_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Load helper functions
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/infinite-helpers.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-infinite-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-infinite-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-infinite-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-infinite-public.php';

		$this->loader = new Infinite_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Infinite_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Infinite_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Infinite_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_menu', $plugin_admin, 'register_admin_pages');
		$this->loader->add_filter('admin_body_class', $plugin_admin, 'admin_body_class');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Infinite_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Infinite_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
