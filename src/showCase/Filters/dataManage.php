<?php

namespace Shopglut\showcase\Filters;

class dataManage {

	public function __construct() {

		add_action('admin_post_create_filter', array($this, 'handleCreateFilter'));

		add_action('wp_ajax_save_shopg_filterdata', array($this, 'save_shopg_filterdata'));

	}

	public function handleCreateFilter() {
		if (
			isset($_POST['create_filter_nonce']) &&
			wp_verify_nonce($_POST['create_filter_nonce'], 'create_filter_nonce') &&
			current_user_can('manage_options')
		) {
			$filter_id = absint($_POST['filter_id']);
			$filter_name = sanitize_text_field('Filter(#' . $filter_id . ')');

			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_showcase_filters';
			$inserted = $wpdb->insert(
				$table_name,
				array(
					'id' => $filter_id,
					'filter_name' => $filter_name,
				)
			);

			if ($inserted) {
				$redirect_url = admin_url('admin.php?page=shopglut_showcase&editor=filter&filter_id=' . $filter_id);
				wp_redirect($redirect_url);
				exit;
			} else {
				wp_die('Database insertion error');
			}
		} else {
			wp_die('Permission error');
		}
	}

	public function save_shopg_filterdata() {

		$data = isset($_POST['shopg_filter_settings']) ? ($_POST['shopg_filter_settings']) : array();

		if (!empty($data) && !wp_verify_nonce(isset($_GET['post_nonce_check']), 'post_nonce_value') && !empty($_POST['filter_name'])) {

			global $wpdb;

			$post_id = sanitize_text_field($_POST['shopg_shop_filter_id']);
			$layout_name = sanitize_text_field($_POST['filter_name']);

			$table_name = $wpdb->prefix . 'shopglut_showcase_filters';

			$existing_record = $wpdb->get_row(
				$wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $post_id)
			);

			$data_to_insert = array(
				'filter_name' => $layout_name,
				'filter_settings' => serialize($data),
			);

			if ($existing_record) {
				$wpdb->update($table_name, $data_to_insert, array('id' => $existing_record->id));
			} else {
				$wpdb->insert($table_name, $data_to_insert);
			}

			wp_send_json_success(true);
		}
		wp_send_json_error($_POST);
	}

	public static function get_instance() {
		static $instance;
		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}