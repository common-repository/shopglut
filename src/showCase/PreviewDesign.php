<?php
namespace Shopglut\showcase;

class PreviewDesign {

	public function __construct() {

	}

	public static function shopg_showcase_layouts_html($data) {

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 8,
		);
		$query = new \WP_Query($args);
		?>
<div
    class="shopg_shop_layouts column row <?php echo isset($data['agwoo_options_settings[shopg_settings_options][shopg_settings_element_accordion][shopg-column-grid][shopg-column-grid-select-type-desktop]']) ? esc_html($data['agwoo_options_settings[shopg_settings_options][shopg_settings_element_accordion][shopg-column-grid][shopg-column-grid-select-type-desktop]']) : 'col-4'; ?>">
    <?php
if ($query->have_posts()):

			while ($query->have_posts()): $query->the_post();

				$layout_class = 'Shopglut\\showcase\\templates\\' . $data['layout_template'];

				if (class_exists($layout_class)) {
					$data_instance = new $layout_class();
				}

				$data_instance->layout_render($data);

			endwhile;
		else:
			echo esc_html__('No products found', 'shopglut');
		endif;

		wp_reset_postdata();

		?>
</div>


<?php

	}

	public function wpshopglut_shortcode_execute($attributes) {

		$post_id = '';
		$content_data = array();
		$content_display_options = array();
		$tax_meta_array = array();
		$icon_meta_array = array();

		ob_start();

		self::shopg_showcase_layouts_html($post_id, $content_data, $content_display_options, $tax_meta_array, $icon_meta_array);

		return ob_get_clean();
	}

}