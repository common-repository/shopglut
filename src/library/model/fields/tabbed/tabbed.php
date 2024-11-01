<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.
/**
 *
 * Field: tabbed
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('AGSHOPGLUT_Field_tabbed')) {
	class AGSHOPGLUT_tabbed extends AGSHOPGLUTP {

		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		public function render() {

			$unallows = array('tabbed');

			echo esc_attr($this->field_before());

			echo '<div class="agl-tabbed-nav agl-tabbed-nav-' . $this->field['id'] . '">';
			foreach ($this->field['tabs'] as $key => $tab) {

				$tabbed_icon = (!empty($tab['icon'])) ? '<i class="agl--icon ' . esc_attr($tab['icon']) . '"></i>' : '';
				$tabbed_active = (empty($key)) ? 'agl-tabbed-active' : '';

				echo '<a href="#" class="' . $tab['class'] . ' ' . esc_attr($tabbed_active) . '"">' . wp_kses_post($tabbed_icon) . esc_html($tab['title']) . '</a>';

			}
			echo '</div>';

			echo '<div class="agl-tabbed-contents agl-tabbed-contents-' . $this->field['id'] . '">';
			foreach ($this->field['tabs'] as $key => $tab) {

				$tabbed_hidden = (!empty($key)) ? ' hidden' : '';

				echo '<div class="agl-tabbed-content ' . $tab['class'] . ' ' . esc_attr($tabbed_hidden) . '">';

				foreach ($tab['fields'] as $field) {

					// if ( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

					//print_r($field);
					$field_id = (isset($field['id'])) ? $field['id'] : '';
					$field_default = (isset($field['default'])) ? $field['default'] : '';
					$field_value = (isset($this->value[$field_id])) ? $this->value[$field_id] : $field_default;
					$unique_id = (!empty($this->unique)) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					AGSHOPGLUT::field($field, $field_value, $unique_id, 'field/tabbed');

				}

				echo '</div>';

			}
			echo '</div>';

			echo esc_attr($this->field_after());

		}

	}
}