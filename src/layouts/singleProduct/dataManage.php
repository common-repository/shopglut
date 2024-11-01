<?php

namespace Shopglut\layouts\singleProduct;

class dataManage {
	public function __construct() {
		add_action('wp_ajax_ddpb_save_editor_state', [$this, 'ddpb_save_editor_state']);
		add_action('wp_ajax_ddpb_load_editor_state', [$this, 'ddpb_load_editor_state']);

		add_action('wp_ajax_load_component_settings', [$this, 'load_component_settings']);
		add_action('wp_ajax_save_component_settings', [$this, 'save_component_settings']);

		add_action('admin_footer', [$this, 'load_all_components_and_settings']);

	}

	public function ddpbSaveEditorState() {
		check_ajax_referer('ajax_nonce', 'nonce');

		if (isset($_POST['elementSettings'])) {
			$elementSettings = json_decode(stripslashes($_POST['elementSettings']), true);
			update_option('ddpb_element_settings', serialize($elementSettings));

			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	public function ddpbLoadEditorState() {
		check_ajax_referer('ajax_nonce', 'nonce');

		$elementSettings = get_option('ddpb_element_settings', '');

		if ($elementSettings) {
			$elementSettings = unserialize($elementSettings);
		}

		wp_send_json_success(['elementSettings' => $elementSettings]);
	}

	public function loadAllComponentsAndSettings() {

		global $wpdb;
		$layout_id = 1; // Hardcoded layout_id for now

		// Fetch settings from database
		$saved_components = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT layout_settings FROM {$wpdb->prefix}shopglut_single_layout WHERE layout_id = %d",
				$layout_id
			)
		);

		if ($saved_components) {
			$components = json_decode($saved_components, true);
		} else {
			$components = array();
		}

		// Generate hidden divs with component settings
		echo '<div id="hidden-settings" style="display: none;">';
		foreach ($components as $component_id => $settings) {
			echo '<div class="component-settings" data-component-id="' . esc_attr($component_id) . '">';
			echo '<input type="hidden" name="component_id" value="' . esc_attr($component_id) . '">';
			echo '<label>Padding: <input type="text" name="padding" value="' . esc_attr($settings['padding']) . '"></label>';
			echo '<label>Margin: <input type="text" name="margin" value="' . esc_attr($settings['margin']) . '"></label>';
			echo '</div>';
		}

		// Generate default settings for new components
		echo '<div class="default-settings">';
		echo '<div class="component-settings" data-component-id="default">';
		echo '<input type="hidden" name="component_id" value="default">';
		echo '<label>Padding: <input type="text" name="padding" value="10px"></label>';
		echo '<label>Margin: <input type="text" name="margin" value="10px"></label>';
		echo '</div>';
		echo '</div>';

		echo '</div>';
	}

	public function loadComponentSettings() {
		global $wpdb;
		$layout_id = 1; // Hardcoded layout_id for now

		// Fetch settings from database
		$saved_components = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT layout_settings FROM {$wpdb->prefix}shopglut_single_layout WHERE layout_id = %d",
				$layout_id
			)
		);

		if ($saved_components) {
			// Convert JSON string to PHP array
			$components = json_decode($saved_components, true);
			$components_html = array();

			// Generate HTML for each component's settings
			foreach ($components as $component_id => $settings) {
				$settings_html = '<div class="component-settings" data-component-id="' . esc_attr($component_id) . '">';
				$settings_html .= '<input type="hidden" name="component_id" value="' . esc_attr($component_id) . '">';
				$settings_html .= '<label>Padding: <input type="text" name="padding" value="' . esc_attr($settings['padding']) . '"></label>';
				$settings_html .= '<label>Margin: <input type="text" name="margin" value="' . esc_attr($settings['margin']) . '"></label>';
				// Add more settings here as needed
				$settings_html .= '</div>';

				$components_html[$component_id] = $settings_html;
			}
		} else {
			// If no settings found, provide default settings
			$default_settings = array(
				'padding' => '10px',
				'margin' => '10px',
				// Add more default settings here as needed
			);

			$default_settings_html = '<div class="component-settings" data-component-id="default">';
			$default_settings_html .= '<input type="hidden" name="component_id" value="default">';
			$default_settings_html .= '<label>Padding: <input type="text" name="padding" value="' . esc_attr($default_settings['padding']) . '"></label>';
			$default_settings_html .= '<label>Margin: <input type="text" name="margin" value="' . esc_attr($default_settings['margin']) . '"></label>';
			// Add more settings here as needed
			$default_settings_html .= '</div>';

			$components_html = array(
				'default' => $default_settings_html,
			);
		}

		wp_send_json_success(array('components_html' => $components_html));
	}

	public function saveComponentSettings() {

		global $wpdb;

		$settings_data = $_POST['components']; // Parse the serialized form data
		$layout_id = 1; // Hardcoded layout_id for now

		$components_to_save = array();

		foreach ($settings_data as $component) {
			$component_id = sanitize_text_field($component['component_id']);
			$settings = array();

			foreach ($component['settings'] as $setting) {
				$settings[sanitize_text_field($setting['name'])] = sanitize_text_field($setting['value']);
			}

			$components_to_save[$component_id] = $settings;
		}
		// Convert components array to JSON
		$components_json = json_encode($components_to_save);

		// Check if a row with the same layout_id exists
		$existing_row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}shopglut_single_layout WHERE layout_id = %d",
				$layout_id
			)
		);

		if ($existing_row) {
			// Update the existing row
			$wpdb->update(
				"{$wpdb->prefix}shopglut_single_layout",
				array('layout_settings' => $components_json),
				array('layout_id' => $layout_id),
				array('%s'),
				array('%d')
			);
		} else {
			// Insert a new row
			$wpdb->insert(
				"{$wpdb->prefix}shopglut_single_layout",
				array(
					'layout_id' => $layout_id,
					'layout_settings' => $components_json,
				),
				array('%d', '%s')
			);
		}

		wp_send_json_success(array('settings' => $settings_data));
	}

	public static function get_instance() {
		static $instance;

		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}
