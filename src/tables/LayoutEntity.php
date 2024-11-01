<?php
namespace Shopglut\tables;

use Shopglut\Database;

class LayoutEntity {

	protected static function getTable() {
		return Database::table_shop_layouts();
	}

	public static function retrieveAll($limit = 0, $current_page = 1) {
		global $wpdb;

		$replacement = [1];
		$sql = "SELECT * FROM " . self::getTable();
		$sql .= " WHERE 1=%d";
		$sql .= " ORDER BY id DESC";
		if ($limit > 0) {
			$sql .= " LIMIT %d";
			$replacement[] = $limit;
		}

		if ($current_page > 1) {
			$sql .= "  OFFSET %d";
			$replacement[] = ($current_page - 1) * $limit;
		}

		$result = $wpdb->get_results($wpdb->prepare($sql, $replacement), 'ARRAY_A');

		$output = [];

		if (is_array($result) && !empty($result)) {
			foreach ($result as $item) {
				$output[] = $item;
			}
		}

		return $output;
	}

	public static function retrieveAllCount() {
		global $wpdb;

		$total = "SELECT COUNT(id) FROM " . self::getTable();
		return $wpdb->get_var($total);
	}

	public static function delete_layout($layout_id) {
		global $wpdb;
		$wpdb->delete(self::getTable(), ['id' => $layout_id], ['%d']); // Ensure the right table is used
	}
}