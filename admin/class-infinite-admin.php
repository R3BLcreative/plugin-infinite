<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 * @subpackage Infinite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Infinite
 * @subpackage Infinite/admin
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */
class Infinite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The config info for building out the WordPress admin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      obj    $admin_config    The JSON config data for the plugin.
	 */
	private $admin_config = false;

	/**
	 * The config info for building out the settings page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      obj    $settings_config    The JSON config data for the settings.
	 */
	private $settings_config = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$config_path = plugin_dir_path(dirname(__FILE__)) . '/config/';
		if (file_exists($config_path . 'admin.json')) {
			$this->admin_config = json_decode(file_get_contents($config_path . 'admin.json'));
		}
		if (file_exists($config_path . 'settings.json')) {
			$this->settings_config = json_decode(file_get_contents($config_path . 'settings.json'));
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Infinite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Infinite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/infinite-admin.css', array(), filemtime(plugin_dir_path(dirname(__FILE__)) . 'admin/css/infinite-admin.css'), 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Infinite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Infinite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/infinite-admin.js', array(), filemtime(plugin_dir_path(dirname(__FILE__)) . 'admin/js/infinite-admin.js'), false);
	}

	/**
	 * Add CSS identifier to admin body class
	 *
	 * @since    1.0.0
	 */
	public function admin_body_class($classes) {
		if ($this->admin_config) {
			$current = (array_key_exists('page', $_GET)) ? $_GET['page'] : false;

			if ($this->get_screen($current)) {
				$classes .= ' infinite-admin';
			}
		}

		return $classes;
	}

	/**
	 * Returns all screen configs
	 *
	 * @since    1.0.0
	 */
	private function get_screens() {
		if ($this->admin_config) {
			return $this->admin_config->screens;
		}

		return false;
	}

	/**
	 * Returns a single screen config
	 *
	 * @since    1.0.0
	 */
	private function get_screen($slug) {
		if ($this->admin_config && $slug) {
			$screens = $this->admin_config->screens;

			foreach ($screens as $screen) {
				if ($screen->slug == $slug) return $screen;
			}
		}

		return false;
	}

	/**
	 * Returns the current screen's config
	 *
	 * @since    1.0.0
	 */
	public function get_current_screen() {
		$current = $_GET['page'];
		return $this->get_screen($current);
	}

	/**
	 * Returns the current screen's config
	 *
	 * @since    1.0.0
	 */
	public function get_current_view() {
		$current_screen = $_GET['page'];
		$current_view = (isset($_GET['view'])) ? $_GET['view'] : false;

		$screen = $this->get_screen($current_screen);
		if (property_exists($screen, 'nav_items')) {
			$views = $screen->nav_items;

			foreach ($views as $view) {
				if ((!$current_view && $view->main_view) || $view->slug == $current_view) return $view;
			}
		}

		return false;
	}

	/**
	 * Register the Admin menus for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_admin_pages() {
		$icon = 'data:image/svg+xml;base64,' . $this->get_menu_icon_svg();

		// Add main menu page
		if ($this->admin_config) {
			$menu = $this->admin_config->menu;
			add_menu_page($menu->page_title, $menu->menu_title, $menu->caps, $menu->slug, [$this, $menu->cb], $icon, $menu->order);

			// Add submenu pages
			foreach ($this->get_screens() as $screen) {
				add_submenu_page($screen->parent, $screen->page_title, $screen->menu_title, $screen->caps, $screen->slug, [$this, $screen->cb], $screen->order);
			}
		}
	}

	/**
	 * Returns the menu icon SVG code
	 *
	 * @since    1.0.0
	 */
	public function get_menu_icon_svg() {
		return base64_encode(file_get_contents(plugin_dir_path(__FILE__) . '/images/icon_menu.svg'));
	}

	/**
	 * Display the dashboard page.
	 *
	 * @since    1.0.0
	 */
	public function infinite_dashboard() {
		$screen = $this->get_current_screen();

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/admin_dashboard.php';
	}

	/**
	 * Display the settings page.
	 *
	 * @since    1.0.0
	 */
	public function infinite_settings() {
		$screen = $this->get_current_screen();

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/admin_settings.php';
	}

	/**
	 * Display all non-dashboard pages.
	 *
	 * @since    1.0.0
	 */
	public function infinite_page() {
		$screen = $this->get_current_screen();

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/admin_page.php';
	}

	/**
	 * Display header
	 *
	 * @since    1.0.0
	 */
	public function infinite_header() {
		$screen = $this->get_current_screen();

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/temp_header.php';
	}

	/**
	 * Display nav
	 *
	 * @since    1.0.0
	 */
	public function infinite_nav() {
		$screen = $this->get_current_screen();
		$view = (isset($_GET['view'])) ? $_GET['view'] : false;

		if (property_exists($screen, 'nav_items')) {
			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/temp_nav.php';
		}
	}

	/**
	 * Display page/view content
	 *
	 * @since    1.0.0
	 */
	public function infinite_content() {
		$view = $this->get_current_view();

		if (property_exists($view, 'class')) {
			$class_name = "$view->class";

			if ($class_name == '__CLASS__') {
				if (method_exists(__CLASS__, $view->method)) {
					call_user_func([__CLASS__, $view->method], $this);
				}
			} elseif (class_exists($class_name)) {
				$CLASS = new $class_name;

				if (method_exists($CLASS, $view->method)) {
					call_user_func([$CLASS, $view->method], $this);
				}
			}
		}
	}

	/**
	 * Display pagination bar
	 *
	 * @since    1.0.0
	 */
	public function infinite_pagination($total, $pages) {
		$screen = $this->get_current_screen();
		$view = (isset($_GET['view'])) ? $_GET['view'] : false;
		$pg = (isset($_GET['pg'])) ? intval($_GET['pg']) : 1;

		if ($pages > 1) {
			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/comp_pagination.php';
		}
	}

	/**
	 * Display filters bar
	 *
	 * @since    1.0.0
	 */
	public function infinite_filters($data) {
		$screen = $this->get_current_screen();
		$view = (isset($_GET['view'])) ? $_GET['view'] : false;

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/comp_filters.php';
	}

	/**
	 * Get the settings options for the current settings view
	 *
	 * @since    1.0.0
	 */
	private function get_settings_view_options($view) {
		foreach ($this->settings_config as $setting) {
			if ($setting->view == $view) $options[] = $setting;
		}

		return $options;
	}

	/**
	 * Get the settings options for the general settings view
	 *
	 * @since    1.0.0
	 */
	public function get_general_settings() {
		$options = $this->get_settings_view_options('settings-general');

		print_r($options);
		$nonce = wp_create_nonce('customers_seeder_nonce');
		$link = admin_url('admin-ajax.php?action=customers_db_seeder&nonce=' . $nonce);
?>
		<a href="<?php echo $link; ?>" data-nonce="<?php echo $nonce; ?>" class="infinite-button btn-primary">Seed the DB</a>
<?php
		// TODO: Scope out option types (button, toggle, slider, text, number, select, etc.)
		// TODO: Option types should be components
		// TODO: Each page should be wrapped in a form with a submit button for now.
	}
}
