<?php
namespace Shopglut;

class RegisterScripts {

	private $load_assets = false;

	public function __construct() {

		add_action('wp', [$this, 'check_for_shortcode']);
		add_action('admin_init', [$this, 'check_admin_pages']);

		add_action('wp_enqueue_scripts', [$this, 'conditionally_enqueue_assets'], 99999999999999);
		add_action('admin_enqueue_scripts', [$this, 'conditionally_enqueue_assets'], 9999999999999);
	}

	public function check_for_shortcode() {
		//if (is_singular()) {
		global $post;
		//if (has_shortcode($post->post_content, 'shopg_shop_layout') || has_shortcode($post->post_content, 'shopglut_wishlist')) {
		$this->load_assets = true;
		//}
		//}
	}

	public function check_admin_pages() {
		//if (isset($_GET['page']) && in_array(trim($_GET['page']), ['shopglut_showcase', 'shopglut_layouts', 'shopglut_wishlist_settings'], true)) {
		$this->load_assets = true;
		//}
	}

	public function conditionally_enqueue_assets() {

		wp_enqueue_style('agl-fontswesome', SHOPGLUT_URL . 'src/library/model/assets/css/all.min.css', [], SHOPGLUT_VERSION,
			'all');

		// Enqueue your main styles and scripts
		if ($this->load_assets) {
			$this->plugin_css();
			$this->plugin_js();
		}

		// Check if the post contains the shortcode and add inline CSS accordingly
		if (is_singular()) {
			global $post;

			// Extract shortcodes with attributes
			$shortcode_pattern = get_shortcode_regex(['shopg_shop_layout']);
			if (preg_match_all('/' . $shortcode_pattern . '/s', $post->post_content, $matches)) {
				foreach ($matches[3] as $shortcode_attrs) {
					$atts = shortcode_parse_atts($shortcode_attrs);
					if (isset($atts['id'])) {
						$shortcode_id = absint($atts['id']);

						$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
						$dynamic_css = $dynamic_style->dynamicCss($shortcode_id);

						if (!empty($dynamic_css)) {
							wp_add_inline_style('shopglut-main', $dynamic_css);
						}
					}
				}
			}
		}

		// Check if we're on the specific admin page
		if (isset($_GET['page']) && $_GET['page'] === 'shopglut_layouts' && isset($_GET['editor']) && $_GET['editor'] === 'shop' && isset($_GET['layout_id'])) {
			$layout_id = absint($_GET['layout_id']); // Sanitize the layout_id

			$dynamic_style = new \Shopglut\layouts\shopLayout\dynamicStyle();
			$dynamic_css = $dynamic_style->dynamicCss($layout_id); // Use the layout_id

			if (!empty($dynamic_css)) {
				wp_add_inline_style('shopglut-main', $dynamic_css);
			}
		}

	}

	public function plugin_css() {
		wp_enqueue_style('shopglut-main', SHOPGLUT_URL . 'assets/css/style.css', [], SHOPGLUT_VERSION);
		wp_enqueue_style('shopglut-layouts', SHOPGLUT_URL . 'assets/css/layouts.css', [], SHOPGLUT_VERSION);
		wp_enqueue_style('agl-fontswesome', SHOPGLUT_URL . 'src/library/model/assets/css/all.min.css', [], SHOPGLUT_VERSION,
			'all');
		wp_enqueue_style('shopglut-front-style', SHOPGLUT_URL . 'assets/css/style-front.css', [], SHOPGLUT_VERSION);
		$wishlist_dynamic_style = new \Shopglut\wishlist\dynamicStyle();
		$wishlist_dynamic_css = $wishlist_dynamic_style->dynamicCss();
		if (!empty($wishlist_dynamic_css)) {
			wp_add_inline_style('shopglut-main', $wishlist_dynamic_css);
		}

	}

	public function plugin_js() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-draggable');

		wp_enqueue_script('shopglut-singleLayouts', SHOPGLUT_URL . 'assets/js/singleLayouts.js', ['jquery'], SHOPGLUT_VERSION,
			true);

		wp_enqueue_script('shopglut-shopFilter', SHOPGLUT_URL . 'assets/js/shopfilter.js', ['jquery'], SHOPGLUT_VERSION,
			true);
		wp_enqueue_script('shopglut-shopLayouts', SHOPGLUT_URL . 'assets/js/shopLayouts.js', ['jquery'], SHOPGLUT_VERSION,
			true);

		wp_enqueue_script('shopglut-shopPagination', SHOPGLUT_URL . 'assets/js/pagination.js', ['jquery'], SHOPGLUT_VERSION,
			true);

		wp_enqueue_script('shopglut-lfullscreen', SHOPGLUT_URL . 'assets/js/fullscreen.js', [], SHOPGLUT_VERSION, true);
		wp_enqueue_script('shopglut-copyshortcode', SHOPGLUT_URL . 'assets/js/copyshortcode.js', [], SHOPGLUT_VERSION, true);
		wp_enqueue_script('preview-script', SHOPGLUT_URL . 'assets/js/preview.js', ['jquery'], SHOPGLUT_VERSION, true);

		wp_localize_script('preview-script', 'custom_vars', [
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax_nonce'),
		]);

		wp_localize_script('shopglut-shopLayouts', 'ajax_data', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('shopLayouts_nonce'),
			'cart_url' => wc_get_cart_url(),
		]);

		wp_localize_script('shopglut-shopPagination', 'shopglut_ajax_object', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('shopLayouts_nonce'),
		]);

		wp_localize_script('shopglut-singleLayouts', 'layouts_vars', [
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax_nonce'),
		]);

		wp_enqueue_script('shopglut-wishlist-js', SHOPGLUT_URL . 'assets/js/wishlist.js', ['jquery'], null, true);
		wp_localize_script('shopglut-wishlist-js', 'ajax_data', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('shopLayouts_nonce'),
			'should_merge_wishlist' => get_transient('merge_wishlist_' . get_current_user_id()),
			'notification_type' => get_option('agshopglut_wishlist_options')['wishlist-general-notification'] ?? null,
			'notification_position' => get_option('agshopglut_wishlist_options')['wishlist-side-notification-appear'] ?? null,
			'side_notification_effect' => get_option('agshopglut_wishlist_options')['wishlist-side-notification-effect'] ?? null,
			'popup_notification_effect' => get_option('agshopglut_wishlist_options')['wishlist-popup-notification-effect'] ?? null,
			'post_type' => is_singular('product') ? 'product' : (is_shop() ? 'shop' : (is_product_category() || is_product_tag() || is_product_taxonomy() ? 'archive' : 'other')),
		]);

	}

	public static function get_instance() {
		static $instance = null;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}
}