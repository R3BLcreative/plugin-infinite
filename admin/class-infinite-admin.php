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
	 * @var      obj    $config    The JSON config data for the plugin.
	 */
	private $config = false;

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

		$config_path = plugin_dir_path(__FILE__) . '/../config/admin.json';
		if (file_exists($config_path)) {
			$this->config = json_decode(file_get_contents($config_path));
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
		if ($this->config) {
			$current = (array_key_exists('page', $_GET)) ? $_GET['page'] : false;

			if ($this->get_screen($current)) {
				$classes .= ' infinite-admin';
			}
		}

		return $classes;
	}

	/**
	 * Returns all screens
	 *
	 * @since    1.0.0
	 */
	private function get_screens() {
		if ($this->config) {
			return $this->config->submenus;
		}

		return false;
	}

	/**
	 * Returns a single screen
	 *
	 * @since    1.0.0
	 */
	private function get_screen($slug) {
		if ($this->config && $slug) {
			$screens = $this->config->submenus;

			foreach ($screens as $screen) {
				if ($screen->slug == $slug) return $screen;
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
		if ($this->config) {
			$menu = $this->config->menu;
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
	 * Display the settings page content for the page we have created.
	 *
	 * @since    1.0.0
	 */
	public function display_page() {
		$current = $_GET['page'];
		$screen = $this->get_screen($current);

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/admin_header.php';
?>
		<main id="content-wrap" class="text-body !font-body text-base py-5 px-3 mr-5">
			<?php require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/screen_' . $current . '.php'; ?>
		</main>
<?php
	}
}
