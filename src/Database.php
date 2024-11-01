<?php

namespace Shopglut;

class Database {
	private static $initialized = false;

	public static function table_showcase_filters() {
		global $wpdb;
		return $wpdb->prefix . 'shopglut_showcase_filters';
	}

	public static function table_shop_layouts() {
		global $wpdb;
		return $wpdb->prefix . 'shopglut_shop_layouts';
	}

	public static function table_shopg_wishlist() {
		global $wpdb;
		return $wpdb->prefix . 'shopglut_wishlist';
	}

	public static function table_single_layouts() {
		global $wpdb;
		return $wpdb->prefix . 'shopglut_single_layout';
	}

	public static function create_shop_layouts() {
		global $wpdb;

		$table_name = self::table_shop_layouts();
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			return; // Table already exists
		}

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            layout_name varchar(255) NOT NULL DEFAULT 'Layout One',
            layout_template varchar(255) NOT NULL DEFAULT 'template1',
            status varchar(50) NOT NULL DEFAULT 'not-active',
			layout_settings longtext,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}

	public static function create_single_layouts() {
		global $wpdb;

		$table_name = self::table_single_layouts();
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			return; // Table already exists
		}

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
             id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
             layout_id varchar(255) NOT NULL,
             layout_settings text NOT NULL,
             PRIMARY KEY (id),
             UNIQUE KEY layout_id (layout_id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}

	public static function create_showcase_filters() {
		global $wpdb;

		$table_name = self::table_showcase_filters();
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			return;
		}

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            filter_name varchar(255) NOT NULL,
            filter_settings longtext,
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}

	public static function create_wishlist_table() {
		global $wpdb;

		$table_name = self::table_shopg_wishlist();
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			return;
		}
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        wish_user_id varchar(255) NOT NULL,
        username varchar(255) NOT NULL,
        useremail varchar(255) NOT NULL,
        product_ids text NOT NULL,
		wishlist_notifications text NOT NULL,
        product_added_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        wishlist_sublist text NOT NULL,
        sublist_notifications text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}

	public static function initialize() {
		if (self::$initialized) {
			return;
		}
		self::create_shop_layouts();
		self::create_single_layouts();
		self::create_showcase_filters();
		self::create_wishlist_table();

		self::$initialized = true;
	}
}