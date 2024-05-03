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
	 */
	public function __construct() {
		$this->version = INFINITE_VERSION;
		$this->plugin_name = INFINITE_SLUG;
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

		if (file_exists(get_stylesheet_directory() . '/infinite/admin/css/infinite-admin-custom.css')) {
			wp_enqueue_style($this->plugin_name . '-custom', get_stylesheet_directory_uri() . '/infinite/admin/css/infinite-admin-custom.css', [], filemtime(get_stylesheet_directory() . '/infinite/admin/css/infinite-admin-custom.css'), 'all');
		} else {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/infinite-admin.css', [], filemtime(plugin_dir_path(dirname(__FILE__)) . 'admin/css/infinite-admin.css'), 'all');
		}
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
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/infinite-admin.js', [], filemtime(plugin_dir_path(dirname(__FILE__)) . 'admin/js/infinite-admin.js'), ['in_footer' => true]);

		if (file_exists(get_stylesheet_directory() . '/infinite/admin/js/infinite-admin-custom.js')) {
			wp_enqueue_script($this->plugin_name . '-custom', get_stylesheet_directory_uri() . '/infinite/admin/js/infinite-admin-custom.js', [], filemtime(get_stylesheet_directory() . '/infinite/admin/js/infinite-admin-custom.js'), ['in_footer' => true]);
		}
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
				$classes .= ' infinite-css';
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

		// [ ] Need to run permissions checks on each view and prevent display if not permitted

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
		}
	}

	/**
	 * Get the SVG code for the admin logo
	 *
	 * @since      1.0.0
	 * @package    Infinite
	 * 
	 */
	public function get_admin_logo() {
		if (file_exists(get_stylesheet_directory() . '/infinite/admin/images/admin_logo.svg')) {
			$file = file_get_contents(get_stylesheet_directory() . '/infinite/admin/images/admin_logo.svg');
		} else {
			$file = file_get_contents(plugin_dir_path(__FILE__) . '/images/admin_logo.svg');
		}

		return $file;
	}

	/**
	 * Get the SVG code for the admin icon
	 *
	 * @since      1.0.0
	 * @package    Infinite
	 * 
	 */
	public function get_admin_icon() {
		if (file_exists(get_stylesheet_directory() . '/infinite/admin/images/admin_icon.svg')) {
			$file = file_get_contents(get_stylesheet_directory() . '/infinite/admin/images/admin_icon.svg');
		} else {
			$file = file_get_contents(plugin_dir_path(__FILE__) . '/images/admin_icon.svg');
		}

		return $file;
	}

	/**
	 * Returns the menu icon SVG code
	 *
	 * @since    1.0.0
	 */
	public function get_menu_icon_svg() {
		return base64_encode($this->get_admin_icon());
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
		$admin_logo = $this->get_admin_logo();

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

		// [ ] Need to run permissions checks on each view and prevent display if not permitted

		if ($views) {
			$current = $current_view->slug;
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
}
