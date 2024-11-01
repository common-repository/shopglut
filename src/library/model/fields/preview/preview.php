<?php

if (!defined('ABSPATH')) {die;} // Cannot access directly.

/**
 *
 * Field: Preview
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if (!class_exists('AGSHOPGLUT_preview')) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 2.0.15
	 * @version 2.0.15
	 */
	class AGSHOPGLUT_preview extends AGSHOPGLUTP {

		/**
		 * Shortcode field constructor.
		 */
		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			if (isset($_GET['page']) && 'shopglut_layouts' === $_GET['page'] && isset($_GET['editor']) && 'shop' === $_GET['editor']) {
				?>
<div class="shopg_shop_layout_contents">
    <div id="shopg_shop_layout_contents" class="width-100" style="width: 100%;">
        <?php

				global $wpdb;

				$layout_id = !wp_verify_nonce(isset($_POST['preview_nonce_check']), 'preview_nonce_check') && isset($_GET['layout_id']) ? absint($_GET['layout_id']) : 1;

				$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

				$layout_values = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", !wp_verify_nonce(isset($_POST['preview_nonce_check']), 'preview_nonce_check') && isset($_GET['layout_id']) ? absint($_GET['layout_id']) : 1));

				$layout_array_values = unserialize($layout_values[0]->layout_settings);

				$included_products =
				isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products'])
				?
				$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-products']
				: array();
				$excluded_products =
				isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products'])
				?
				$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-exclude-products']
				: array();
				$included_categories =
				isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories'])
				?
				$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-include-categories']
				: array();
				$product_filter_option =
				isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options'])
				?
				$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-options']
				: 'all-products';
				$product_sorting =
				isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting'])
				?
				$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-sorting']
				: 'date';
				$product_order =
				isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order'])
				?
				$layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_settings_accordion']['shopg-layouts-product-order']
				: 'ASC';

				$included_products = array_diff($included_products, $excluded_products);

				$pagination_product_number = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-product-no'] : 15;

				$pagination_style = isset($layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style']) ? $layout_array_values['shopg_options_settings']['shopg_settings_options']['shopg_advanced_settings_accordion']['pagination-style'] : 'pagination-number';

				$args = array(
					'post_type' => 'product',
					'posts_per_page' => $pagination_product_number,
					'orderby' => $product_sorting,
					'order' => $product_order,
					'paged' => 1,
				);

				$meta_key = '';
				switch ($product_sorting) {
				case 'price_low_to_high':
				case 'price_high_to_low':
					$args['orderby'] = 'meta_value_num';
					$meta_key = '_price';
					break;
				case 'sales':
					$args['orderby'] = 'meta_value_num';
					$meta_key = 'total_sales';
					break;
				case 'ratings':
					$args['orderby'] = 'meta_value_num';
					$meta_key = '_wc_average_rating';
					break;
				case 'featured':
					$args['orderby'] = 'meta_value_num';
					$meta_key = '_featured';
					break;
				case 'popularity':
					$args['orderby'] = 'meta_value_num';
					$meta_key = 'product_views';
					break;
				case 'stock_quantity':
					$args['orderby'] = 'meta_value_num';
					$meta_key = '_stock';
					break;
				case 'reviews_count':
					$args['orderby'] = 'meta_value_num';
					$meta_key = '_wc_review_count';
					break;
				case 'random':
					$args['orderby'] = 'rand';
					break;
				}

				if (!empty($meta_key)) {
					$args['meta_key'] = $meta_key;
				}

				switch ($product_filter_option) {
				case 'best-selling':
					$args['meta_key'] = 'total_sales';
					$args['orderby'] = 'meta_value_num';
					break;
				case 'recent-products':
					$args['orderby'] = 'date';
					$args['order'] = 'DESC';
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
					$args['meta_query'][] = array(
						'key' => '_sale_price',
						'value' => 0,
						'compare' => '>',
						'type' => 'NUMERIC',
					);
					break;
				case 'in-stock':
					$args['meta_query'][] = array(
						'key' => '_stock_status',
						'value' => 'instock',
						'compare' => '=',
					);
					break;
				case 'out-of-stock':
					$args['meta_query'][] = array(
						'key' => '_stock_status',
						'value' => 'outofstock',
						'compare' => '=',
					);
					break;
				case 'all-products':
				default:
					break;
				}

				if (!empty($included_products)) {
					$args['post__in'] = $included_products;
				}

				if (!empty($excluded_products)) {
					$args['post__not_in'] = $excluded_products;
				}

				if (!empty($included_categories)) {
					$args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => $included_categories,
					);
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
				}

				?>

        </div>
        <?php

				?>
        <div id="no-product-found" style="display:none;"><?php echo esc_html__('No Product Found', 'shopglut'); ?>

            <?php

				if (!$file_included) {
					echo esc_html__('Layout file not found', 'shopglut');
				}
				?>
        </div>
    </div>
    <?php
wp_reset_postdata();
			}
		}

	}
}