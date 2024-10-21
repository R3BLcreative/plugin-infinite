<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Infinite
 * @subpackage Infinite/public
 * @author     R3BL Creative - James Cook <jcook@r3blcreative.com>
 */
class INF_Public {

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
		$this->version = INF_VERSION;
		$this->plugin_name = INF_SLUG;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function init() {
		$this->add_shortcodes();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function add_shortcodes() {
		// add_shortcode('shortcode', [$this, 'sc_shortcode']);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/inf-public.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/inf-public.js', ['jquery'], $this->version, ['in_footer' => true]);
	}

	/**
	 * Add CSS identifier to public body class
	 *
	 * @since    1.0.0
	 */
	public function public_body_class($classes) {
		if (defined('INF_PUBLIC')) {
			$current = (array_key_exists('page', $_GET)) ? $_GET['page'] : false;

			if ($this->get_screen($current)) {
				$classes .= ' inf-css';
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

		if (defined('INF_PUBLIC')) {
			$screens = INF_PUBLIC->screens;

			// Add menu config to screens to handle the dashboard screen
			$screens[] = INF_PUBLIC->menu;
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
		if (defined('INF_PUBLIC') && $slug) {
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
	 * Undocumented function
	 *
	 * @return void
	 */
	public function localize_script() {
		// Localize ajaxurl for javascript
		$nonce = wp_create_nonce('ajax_inf_nonce');
		wp_localize_script($this->plugin_name, 'inf_ajax_obj', [
			'ajaxurl' => admin_url('admin-ajax.php?nonce=' . $nonce),
		]);
	}

	/* -------------------------
		SHORTCODES
	------------------------- */

	// public function sc_shortcode($atts) {
	// 	// Set default params and merge with user atts
	// 	$params = shortcode_atts(['test' => 'Hello'], $atts);

	// 	ob_start();
	// 	$content = ob_get_contents();
	// 	ob_end_clean();

	// 	return $content;
	// }
}
