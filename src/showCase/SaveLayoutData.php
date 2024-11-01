<?php
namespace Shopglut\showcase;

class SaveLayoutData {

	public function __construct() {
		add_action('save_shopg_layout_data', array($this, 'saveShopgLayoutDatas'));
	}

	public function saveShopgLayoutDatas($post_id) {
		//print_r($_POST['agwoo_options_settings']);

		$data = !wp_verify_nonce(isset($_GET['post_nonce_check']), 'post_nonce_value') && isset($_POST['agwoo_options_settings']) ? array_filter($_POST['agwoo_options_settings']) : array();

		if (!empty($data) && !wp_verify_nonce(isset($_GET['post_nonce_check']), 'post_nonce_value') && !empty($_POST['layout_template'])) {

			global $wpdb;

			$layout_name = sanitize_text_field($_POST['layout_name']);
			$layout_template = sanitize_text_field($_POST['layout_template']);

			$table_name = $wpdb->prefix . 'shopglut_product_layout';

			$existing_record = $wpdb->get_row( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder
				$wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "shopglut_product_layout WHERE id = %d", $post_id)
			);

			$data_to_insert = array(
				'layout_name' => $layout_name,
				'layout_template' => $layout_template,
				'layout_options' => serialize($data),
			);

			if ($existing_record) {
				$wpdb->update($table_name, $data_to_insert, array('id' => $existing_record->id)); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder
			} else {
				$wpdb->insert($table_name, $data_to_insert); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder
			}
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
