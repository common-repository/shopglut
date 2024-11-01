<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.
/**
 *
 * Field: accordion
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('AGSHOPGLUT_accordion')) {
	class AGSHOPGLUT_accordion extends AGSHOPGLUTP {

		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		public function render() {

			$unallows = array('accordion');

			echo esc_attr($this->field_before());

			echo '<div class="agl-accordion-items">';

			foreach ($this->field['accordions'] as $key => $accordion) {

				echo '<div class="agl-accordion-item">';

				$icon = (!empty($accordion['icon'])) ? 'agl--icon ' . $accordion['icon'] : 'agl-accordion-icon fas fa-angle-right';

				if (isset($_GET['page']) && 'shopglut_layouts' === $_GET['page'] && isset($_GET['editor']) && 'shop' === $_GET['editor']) {
					echo '<h4 class="agl-accordion-title">';
					echo '<i class="' . esc_attr($icon) . '"></i>';
					echo esc_html($accordion['title']);
					echo '</h4>';
					echo '<div class="agl-accordion-content">';
				}

				foreach ($accordion['fields'] as $field) {

					if (in_array($field['type'], $unallows)) {$field['_notice'] = true;}

					$field_id = (isset($field['id'])) ? $field['id'] : '';

					$field_default = (isset($field['default'])) ? $field['default'] : '';
					$field_value = (isset($this->value[$field_id])) ? $this->value[$field_id] : $field_default;
					$unique_id = (!empty($this->unique)) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					if (isset($_GET['page']) && 'shopglut_showcase' === $_GET['page'] && isset($_GET['editor']) && 'filter' === $_GET['editor']) {
						echo '<h4 class="agl-accordion-title">';
						echo '<i class="' . esc_attr($icon) . '"></i>';
						echo isset($field_value['accordion-title']) ? esc_html($field_value['accordion-title']) : esc_html__('Title', 'shopglut');
						echo '</h4>';
						echo '<div class="agl-accordion-content">';
					}

					AGSHOPGLUT::field($field, $field_value, $unique_id, 'field/accordion');

				}

				echo '</div>';

				echo '</div>';

			}

			echo '</div>';

			echo esc_attr($this->field_after());

		}

	}
}