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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
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
		if (defined('INF_ADMIN')) {
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
		$screens = [];

		if (defined('INF_ADMIN')) {
			$screens = INF_ADMIN->screens;

			// Add menu config to screens to handle the dashboard screen
			$screens[] = INF_ADMIN->menu;
		}

		// Add menu config to screens to handle the settings screen
		if (defined('INF_SETTINGS')) {
			$screens[] = INF_SETTINGS->menu;
		}

		if (!empty($screens)) return $screens;

		return false;
	}

	/**
	 * Returns a single screen config
	 *
	 * @since    1.0.0
	 */
	private function get_screen($slug) {
		if (defined('INF_ADMIN') && $slug) {
			$screens = $this->get_screens();

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
	 * Returns all view configs for the current screen
	 *
	 * @since    1.0.0
	 */
	private function get_views() {
		$screen = $this->get_current_screen();

		// Handle standard screen views
		if ($screen && property_exists($screen, 'views')) {
			return $screen->views;
		}

		// Handle settings screen views
		if (defined('INF_SETTINGS') && $_GET['page'] == 'infinite-settings') {
			if (property_exists(INF_SETTINGS, 'views')) {
				return INF_SETTINGS->views;
			}
		}

		return false;
	}

	/**
	 * Returns the current view's config
	 *
	 * @since    1.0.0
	 */
	public function get_current_view() {
		$current_view = (isset($_GET['view'])) ? $_GET['view'] : false;
		$views = $this->get_views();

		if ($views) {
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
		if (defined('INF_ADMIN')) {
			$menu = INF_ADMIN->menu;
			add_menu_page($menu->page_title, $menu->menu_title, $menu->caps, $menu->slug, [$this, 'infinite_dashboard'], $icon, $menu->order);

			// Add parent submenu page
			add_submenu_page($menu->slug, $menu->page_title, $menu->page_title, $menu->caps, $menu->slug, [$this, 'infinite_dashboard'], 1);

			// Add submenu pages
			foreach (INF_ADMIN->screens as $screen) {
				add_submenu_page($menu->slug, $screen->page_title, $screen->menu_title, $screen->caps, $screen->slug, [$this, 'infinite_page'], $screen->order);
			}

			// Add settings submenu page
			if (defined('INF_SETTINGS')) {
				$smenu = INF_SETTINGS->menu;
				add_submenu_page($menu->slug, $smenu->page_title, $smenu->menu_title, $smenu->caps, $smenu->slug, [$this, 'infinite_settings'], 100);
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
		$views = $this->get_views();
		$current_view = $this->get_current_view();
		$current = $current_view->slug;

		if ($views) {
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
		$current_view = $this->get_current_view();
		$view = $current_view->slug;

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/comp_filters.php';
	}

	/**
	 * Get the settings options for the current settings view
	 *
	 * @since    1.0.0
	 */
	private function get_options($view) {
		if (defined('INF_SETTINGS') && property_exists(INF_SETTINGS, 'options')) {
			foreach (INF_SETTINGS->options as $option) {
				if ($option->view == $view) $options[] = $option;
			}
			return $options;
		}

		return false;
	}

	/**
	 * Get the settings options for the general settings view
	 *
	 * @since    1.0.0
	 */
	public function display_settings() {
		$current_view = $this->get_current_view();
		$view = $current_view->slug;
		$options = $this->get_options($view);

		print_r($options);
		$nonce = wp_create_nonce('customers_seeder_nonce');
		$link = admin_url('admin-ajax.php?action=customers_db_seeder&nonce=' . $nonce);
?>
		<a href="<?php echo $link; ?>" data-nonce="<?php echo $nonce; ?>" class="infinite-button btn-primary">Seed the DB</a>
<?php
		// TODO: Scope out option types (button, toggle, slider, text, number, select, etc.)
		// TODO: Option types should be components
		// TODO: Each page should be wrapped in a form with a submit button for now.
		// TODO: Are configs available to the activator/deactivator class??? If so, update those.
	}
}
