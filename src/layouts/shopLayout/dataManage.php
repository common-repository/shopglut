<?php

namespace Shopglut\layouts\shopLayout;

use Shopglut\Database;

class dataManage {

	public function __construct() {

		add_shortcode('shopg_shop_layout', array($this, 'shopg_render_layout_shortcode'));

		add_action('wp_ajax_save_shopg_shopdata', array($this, 'save_shopg_shopdata'));
		add_action('wp_ajax_retrive_shopg_shopdata', array($this, 'retrive_shopg_shopdata'));

		add_action('wp_ajax_add_to_cart', [$this, 'add_to_cart']);
		add_action('wp_ajax_nopriv_add_to_cart', [$this, 'add_to_cart']);

		add_action('wp_ajax_shopglut_add_to_wishlist', [$this, 'shopglut_add_to_wishlist']);
		add_action('wp_ajax_nopriv_shopglut_add_to_wishlist', [$this, 'shopglut_add_to_wishlist']);

		add_action('wp_ajax_bulk_action', [$this, 'bulk_action']);
		add_action('wp_ajax_nopriv_bulk_action', [$this, 'bulk_action']);

		add_action('wp_ajax_add_to_comparison', [$this, 'add_to_comparison']);
		add_action('wp_ajax_nopriv_add_to_comparison', [$this, 'add_to_comparison']);
		add_action('wp_ajax_remove_from_comparison', [$this, 'remove_from_comparison']);
		add_action('wp_ajax_nopriv_remove_from_comparison', [$this, 'remove_from_comparison']);
		add_action('wp_ajax_load_comparison_table', [$this, 'load_comparison_table']);
		add_action('wp_ajax_nopriv_load_comparison_table', [$this, 'load_comparison_table']);

		add_action('wp_ajax_quick_view_product', [$this, 'quick_view_product']);
		add_action('wp_ajax_nopriv_quick_view_product', [$this, 'quick_view_product']);

		add_action('wp_ajax_reset_shopglut_layouts', [$this, 'reset_shopglut_layouts']);

	}

	public function shopg_render_layout_shortcode($atts) {
		global $wpdb, $post;

		$loading_gif = SHOPGLUT_URL . 'assets/images/loading-icon.png';

		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'shopg_shop_layout'
		);

		$layout_id = absint($atts['id']);
		$post_id = get_the_ID();
		$paged = absint(isset($atts['paged']) ? $atts['paged'] : 1);

		if (!$layout_id) {
			return esc_html__('Invalid layout ID', 'shopglut');
		}

		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';
		$layout_values = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $layout_id));

		if (empty($layout_values)) {
			return esc_html__('Layout not found', 'shopglut');
		}

		$layout_array_values = unserialize($layout_values[0]->layout_settings);

		ob_start();

		?>

<div id="shopg_shop_layout_contents" data-shortcode-id="<?php echo esc_attr($layout_id); ?>"
    data-page-id="<?php echo get_the_ID(); ?>">
    <div
        class="shopg_shop_layouts column row <?php echo isset($layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop']) ? esc_html($layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop']) : 'col-3'; ?>">
        <div class="loader-overlay">
            <div class="loader-container">
                <img src="<?php echo esc_url($loading_gif); ?>" alt="Loading Icon" class="loader-image">
                <div class="loader-dash-circle"></div>
            </div>
        </div>
        <?php

		$included_products = array_diff(
			isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] : array(),
			isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] : array()
		);

		// Setup query arguments
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] : 15,
			'post__in' => !empty($included_products) ? $included_products : null,
			'paged' => $paged,
		);

		$product_query = new \WP_Query($args);

		if ($product_query->have_posts()) {
			while ($product_query->have_posts()) {
				$product_query->the_post();
				$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_values[0]->layout_template;

				if (class_exists($layout_class)) {
					$layout_instance = new $layout_class();
					$layout_instance->layout_render($layout_array_values);
				}
			}
		}

		wp_reset_postdata();

		?>
        <div class="shop-layouts-compare-modal">
            <div class="modal-content">
                <span class="shop-layouts-compare-modal-close">&times;</span>
                <div class="modal-body">
                    <div class="comparison-data"></div>
                </div>
            </div>
        </div>

        <div id="shop-layouts-quick-view-modal" style="display: none;">
            <div class="quick-view-content">
                <span class="shop-layouts-quick-view-modal-close">&times;</span>
                <div class="product-overview"></div>

            </div>
        </div>
    </div>
    <div id="shopg-notification-container"></div>
    <?php
$pagination_style = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] : 'pagination-number';
		$pagination = new pagination();
		echo $pagination->render_pagination($pagination_style, $paged, $product_query->max_num_pages, $post_id);
		?>
</div>
<?php
return ob_get_clean();
	}

	public function retrive_shopg_shopdata() {
		global $wpdb;

		// Sanitize and retrieve the layout ID from the POST request
		$post_id = sanitize_text_field($_POST['shopg_shop_layoutid']);
		$current_page_id = sanitize_text_field($_POST['page_id']); // Get the page ID passed from AJAX

		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

		// Get layout settings from the database
		$layout_values = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", absint($post_id)));
		if (!$layout_values) {
			wp_send_json_error(array('html' => '<h1>Invalid Layout</h1>'));
			return;
		}

		$layout_array_values = unserialize($layout_values[0]->layout_settings);

		// Determine pagination style and product number per page
		$pagination_style = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'])
		? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style']
		: 'pagination-number';

		$paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
		$pagination_product_number = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'])
		? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no']
		: 15;

		// Prepare product query arguments
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $pagination_product_number,
			'paged' => $paged,
		);

		$query = new \WP_Query($args);

		if ($query->have_posts()) {
			ob_start();
			?>
<div class="shopg_shop_layout_contents">
    <div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
        <div
            class="shopg_shop_layouts column row <?php echo esc_html($layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop'] ?? 'col-3'); ?>">
            <?php
while ($query->have_posts()) {
				$query->the_post();
				$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_values[0]->layout_template;
				if (class_exists($layout_class)) {
					$layout_instance = new $layout_class();
					$layout_instance->layout_render($layout_array_values);
				}
			}
			?>
        </div>
    </div>
</div>
<?php

			// Prepare pagination HTML using your pagination class
			$pagination = new pagination();
			$pagination_links = $pagination->render_pagination($pagination_style, $paged, $query->max_num_pages, $current_page_id);

			$output = ob_get_clean();
			// Send both the product HTML and pagination links back in the AJAX response
			wp_send_json_success(array('html' => $output, 'pagination' => $pagination_links, 'max_pages' => $query->max_num_pages));
		} else {
			wp_send_json_error(array('html' => '<h1>No Products Found</h1>'));
		}
	}

	public function save_shopg_shopdata() {

		$data = isset($_POST['shopg_options_settings']) ? ($_POST['shopg_options_settings']) : array();

		if (!empty($data) && !wp_verify_nonce(isset($_GET['post_nonce_check']), 'post_nonce_value') && !empty($_POST['layout_template'])) {

			global $wpdb;

			$post_id = sanitize_text_field($_POST['shopg_shop_layoutid']);
			$layout_name = sanitize_text_field($_POST['layout_name']);
			$layout_template = sanitize_text_field($_POST['layout_template']);
			$paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1; // Handle pagination

			$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

			$existing_record = $wpdb->get_row(
				$wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $post_id)
			);

			$data_to_insert = array(
				'layout_name' => $layout_name,
				'layout_template' => $layout_template,
				'layout_settings' => serialize($data),
			);

			if ($existing_record) {
				$wpdb->update($table_name, $data_to_insert, array('id' => $existing_record->id));
			} else {
				$wpdb->insert($table_name, $data_to_insert);
			}
			ob_start();
			?>
<div class="shopg_shop_layout_contents">
    <div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
        <?php

			$layout_values = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", absint($post_id)));

			$layout_array_values = unserialize($layout_values[0]->layout_settings);

			$included_products = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'] : array();
			$excluded_products = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'] : array();
			$included_categories = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'] : array();
			$excluded_categories = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-categories']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-categories'] : array();
			$include_tags = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-tags']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-tags'] : array();
			$exclude_tags = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-tags']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-tags'] : array();
			$product_type = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-type']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-type'] : array();
			$product_option = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'] : 'all-products';
			$product_sorting = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'] : 'date';
			$product_order = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'] : 'ASC';
			$pagination_style = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style']) ? isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style']) : 'pagination-number';

			$included_products = array_diff($included_products, $excluded_products);

			$args = array(
				'post_type' => 'product',
				'posts_per_page' => -1,
				'post__in' => !empty($included_products) ? $included_products : null,
				'post__not_in' => !empty($excluded_products) ? $excluded_products : null,
				'tax_query' => array(
					'relation' => 'AND',
				),
				'order' => $product_order,
				'paged' => $paged, // Handle pagination
			);

			if (!empty($included_categories)) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $included_categories,
					'operator' => 'IN',
				);
			}

			if (!empty($excluded_categories)) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $excluded_categories,
					'operator' => 'NOT IN',
				);
			}

			if (!empty($include_tags)) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_tag',
					'field' => 'term_id',
					'terms' => $include_tags,
					'operator' => 'IN',
				);
			}

			if (!empty($exclude_tags)) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_tag',
					'field' => 'term_id',
					'terms' => $exclude_tags,
					'operator' => 'NOT IN',
				);
			}

			if (!empty($product_type)) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_type',
					'field' => 'term_id',
					'terms' => $product_type,
					'operator' => 'IN',
				);
			}

			switch ($product_option) {
			case 'best-selling':
				$args['meta_key'] = 'total_sales';
				$args['orderby'] = 'meta_value_num';
				break;
			case 'recent-products':
				$args['orderby'] = 'date';
				break;
			case 'featured-products':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => 'featured',
					'operator' => 'IN',
				);
				break;
			case 'rated-products':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby'] = 'meta_value_num';
				break;
			case 'sale-products':
				$args['meta_query'] = array(
					array(
						'key' => '_sale_price',
						'value' => 0,
						'compare' => '>',
						'type' => 'NUMERIC',
					),
				);
				break;
			case 'in-stock':
				$args['meta_query'] = array(
					array(
						'key' => '_stock_status',
						'value' => 'instock',
						'compare' => '=',
					),
				);
				break;
			case 'out-of-stock':
				$args['meta_query'] = array(
					array(
						'key' => '_stock_status',
						'value' => 'outofstock',
						'compare' => '=',
					),
				);
				break;
			}

			switch ($product_sorting) {
			case 'title':
			case 'name':
			case 'ID':
			case 'author':
			case 'date':
			case 'modified':
			case 'rand':
				// These are handled by default 'orderby'
				$args['orderby'] = $product_sorting;
				break;

			case 'sales':
				$args['meta_key'] = 'total_sales';
				$args['orderby'] = 'meta_value_num';
				break;

			case 'price_low_to_high':
				$args['meta_key'] = '_price';
				$args['orderby'] = 'meta_value_num';
				break;

			case 'price_high_to_low':
				$args['meta_key'] = '_price';
				$args['orderby'] = 'meta_value_num';
				break;

			case 'ratings':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby'] = 'meta_value_num';
				break;

			case 'featured':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => 'featured',
					'operator' => 'IN',
				);
				break;

			case 'random':
				$args['orderby'] = 'rand';
				break;

			default:
				$args['orderby'] = 'date'; // Default to date if no match
				break;
			}

			$query = new \WP_Query($args);

			$file_included = false;

			?>
        <div
            class="shopg_shop_layouts column row <?php echo isset($layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop']) ? esc_html($layout_array_values['shopg_settings_options']['shopg_settings_element_accordion']['shopg-column-grid']['shopg-column-grid-select-type-desktop']) : 'col-3' ?>">

            <?php

			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();

					$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_values[0]->layout_template;

					if (class_exists($layout_class)) {
						$layout_instance = new $layout_class();
						$layout_instance->layout_render($layout_array_values);
						$file_included = true;
					}
				}

				wp_reset_postdata();
			}

			?>
        </div>
        <?php

			if (!$file_included) {
				echo esc_html__('Layout file not found', 'shopglut');
			}
			?>
    </div>
</div>
<?php
$output = ob_get_clean();
			wp_send_json_success(array('html' => $output));
		}
		wp_send_json_error($_POST);

	}

	public function add_to_cart() {
		check_ajax_referer('shopLayouts_nonce', 'nonce');

		$product_id = intval($_POST['product_id']);
		$user_id = get_current_user_id();

		if ($user_id && $product_id) {
			WC()->cart->add_to_cart($product_id);
			wp_send_json_success();
		} else {
			wp_send_json_error('Invalid product ID or user not logged in.');
		}
	}

	public function shopglut_add_to_wishlist() {
		check_ajax_referer('shopLayouts_nonce', 'nonce');

		$product_id = intval($_POST['product_id']);
		$user_id = get_current_user_id();

		if ($user_id && $product_id) {
			$wishlist = $this->get_user_wishlist($user_id);

			if (!$wishlist) {
				$wishlist = [];
			}

			if (!in_array($product_id, $wishlist)) {
				$this->store_user_action($user_id, $product_id, 'wishlist');
				wp_send_json_success();
			} else {
				wp_send_json_error('Product already in wishlist.');
			}
		} else {
			wp_send_json_error('Invalid product ID or user not logged in.');
		}
	}

	public function bulk_action() {
		check_ajax_referer('shopLayouts_nonce', 'nonce');

		$action_type = sanitize_text_field($_POST['action_type']);
		$product_ids = array_map('intval', $_POST['product_ids']);
		$user_id = get_current_user_id();

		if ($user_id && !empty($product_ids) && in_array($action_type, ['add_to_cart', 'remove'])) {
			foreach ($product_ids as $product_id) {
				if ($action_type === 'add_to_cart') {
					WC()->cart->add_to_cart($product_id);
				} elseif ($action_type === 'remove') {
					$this->remove_user_action($user_id, $product_id, 'wishlist');
				}
			}
			wp_send_json_success();
		} else {
			wp_send_json_error('Invalid product IDs, action type, or user not logged in.');
		}
	}

	private function store_user_action($user_id, $product_id, $action_type) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_user_actions';
		$wpdb->insert(
			$table_name,
			[
				'user_id' => $user_id,
				'product_id' => $product_id,
				'action_type' => $action_type,
				'timestamp' => current_time('mysql'),
			],
			[
				'%d',
				'%d',
				'%s',
				'%s',
			]
		);
	}

	private function remove_user_action($user_id, $product_id, $action_type) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_user_actions';
		$wpdb->delete(
			$table_name,
			[
				'user_id' => $user_id,
				'product_id' => $product_id,
				'action_type' => $action_type,
			],
			[
				'%d',
				'%d',
				'%s',
			]
		);
	}

	private function get_user_wishlist($user_id) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_user_actions';
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT product_id FROM $table_name WHERE user_id = %d AND action_type = %s",
			$user_id,
			'wishlist'
		));
		return wp_list_pluck($results, 'product_id');
	}

	public function remove_from_comparison() {
		check_ajax_referer('shopLayouts_nonce', 'nonce');
		global $wpdb;
		$user_id = get_current_user_id();
		$product_id = intval($_POST['product_id']);

		$table_name = $wpdb->prefix . 'shopglut_user_actions';
		$wpdb->delete($table_name, [
			'user_id' => $user_id,
			'product_id' => $product_id,
			'action_type' => 'compare',
		]);

		wp_send_json_success(true);
	}

	public function get_comparison_product_data($product_id) {
		$product = wc_get_product($product_id);

		if (!$product) {
			return [];
		}

		return [
			'id' => $product->get_ID(),
			'name' => $product->get_name(),
			'price' => $product->get_price_html(),
			'weight' => $product->get_weight(),
			'dimensions' => $product->get_dimensions(),
			'sku' => $product->get_sku(),
			'stock_status' => $product->get_stock_status(),
			'rating' => $product->get_average_rating(),
			'reviews' => $product->get_review_count(),
			'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
			'description' => $product->get_short_description(),
			'attributes' => $product->get_attributes(),
			'categories' => wc_get_product_category_list($product_id),
			'tags' => wc_get_product_tag_list($product_id),
			'regular_price' => wc_price($product->get_regular_price()),
			'sale_price' => $product->is_on_sale() ? wc_price($product->get_sale_price()) : '',
		];
	}

	public function load_comparison_table() {
		check_ajax_referer('shopLayouts_nonce', 'nonce');
		global $wpdb;

		$user_id = get_current_user_id();
		$product_id = intval($_POST['product_id']);

		$table_name = $wpdb->prefix . 'shopglut_user_actions';

		$exists = $wpdb->get_var($wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND product_id = %d AND action_type = %s",
			$user_id,
			$product_id,
			'compare'
		));

		if (!$exists && $product_id) {
			$wpdb->insert($table_name, [
				'user_id' => $user_id,
				'product_id' => $product_id,
				'action_type' => 'compare',
			]);
		}

		$comparison_items = $wpdb->get_results($wpdb->prepare(
			"SELECT product_id FROM $table_name WHERE user_id = %d AND action_type = %s",
			$user_id,
			'compare'
		));

		$product_ids = wp_list_pluck($comparison_items, 'product_id');
		$comparison_data = array_map([$this, 'get_comparison_product_data'], $product_ids);

		$available_fields = [];
		if ($comparison_data) {
			foreach ($comparison_data as $product) {
				foreach ($product as $key => $value) {
					if (!empty($value) && !in_array($key, $available_fields)) {
						$available_fields[] = $key;
					}
				}
			}
		}

		ob_start();
		if ($comparison_data) {
			?>
<table class="comparison-table">
    <thead>
        <tr>
            <th><?php esc_html_e('Product', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>

            <th>
                <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>">
                <h2><?php echo esc_html($product['name']); ?></h2>
                <button class="shopg-shop-remove-compare" data-product-id="<?php echo esc_attr($product['id']); ?>">
                    <?php esc_html_e('Remove', 'shopglut');?>
                </button>
            </th>
            <?php endforeach;?>
        </tr>
    </thead>
    <tbody>
        <?php if (in_array('price', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Price', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo wp_kses_post($product['price']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('weight', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Weight', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo esc_html($product['weight']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('dimensions', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Dimensions', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo esc_html($product['dimensions']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('sku', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('SKU', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo esc_html($product['sku']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('stock_status', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Stock Status', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo esc_html($product['stock_status']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('rating', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Rating', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo esc_html($product['rating']) . ' (' . esc_html($product['reviews']) . ')'; ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('categories', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Categories', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo wp_kses_post($product['categories']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('tags', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Tags', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo wp_kses_post($product['tags']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('regular_price', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Regular Price', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo wp_kses_post($product['regular_price']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
        <?php if (in_array('sale_price', $available_fields)): ?>
        <tr>
            <th><?php esc_html_e('Sale Price', 'shopglut');?></th>
            <?php foreach ($comparison_data as $product): ?>
            <td><?php echo wp_kses_post($product['sale_price']); ?></td>
            <?php endforeach;?>
        </tr>
        <?php endif;?>
    </tbody>
</table>
<?php
} else {
			echo '<p>No items in your comparison list.</p>';
		}
		$content = ob_get_clean();

		wp_send_json_success($content);

	}

	public function quick_view_product() {
		check_ajax_referer('shopLayouts_nonce', 'nonce');

		$product_id = intval($_POST['product_id']);
		$product = wc_get_product($product_id);

		if (!$product) {
			wp_send_json_error('Invalid product.');
		}

		ob_start();

		// Function to format the price
		function format_price_range($product) {
			if ($product->is_type('variable')) {
				$min_price = wc_get_price_to_display($product, array('price' => $product->get_variation_price('min', true)));
				$max_price = wc_get_price_to_display($product, array('price' => $product->get_variation_price('max', true)));

				// Return formatted price range
				return sprintf('%s - %s', wc_price($min_price), wc_price($max_price));
			}
			// Return the price HTML for simple products
			return $product->get_price_html();
		}

		?>
<div class="product-quick-view">
    <div class="product-images">
        <?php echo $product->get_image(); ?>
    </div>
    <div class="product-summary">
        <h1 class="product-title"><?php echo esc_html($product->get_name()); ?></h1>
        <div class="price"><?php echo wp_kses_post(format_price_range($product)); ?></div>
        <div class="description"><?php echo wp_kses_post($product->get_short_description()); ?></div>
        <div class="product-meta">
            <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'woocommerce') . ' ', '</span>'); ?>
        </div>
    </div>
</div>
<?php

		$output = ob_get_clean();

		wp_send_json_success($output);
	}

	public function reset_shopglut_layouts() {

		check_ajax_referer('shopLayouts_nonce', 'nonce');

		global $wpdb;

		$table_name = Database::table_shop_layouts();

		$result = $wpdb->query("UPDATE $table_name SET layout_settings = ''");

		if ($result !== false) {
			wp_send_json_success('Settings has been reset.');
		} else {
			wp_send_json_error('Failed to reset the Settings.');
		}
	}

	public static function get_instance() {
		static $instance;
		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}