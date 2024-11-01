<?php

namespace Shopglut;

use Shopglut\layouts\AllLayouts;
use Shopglut\layouts\shopLayout\chooseTemplates;
use Shopglut\layouts\shopLayout\dataManage as ShopDataManage;
use Shopglut\showcase\Filters\dataManage as FilterDataManage;
use Shopglut\showcase\Filters\SettingsPage as FilterDSettings;
use Shopglut\showcase\SaveLayoutData;
use Shopglut\wishlist\dataManage as wishlistManage;

class Base {

	public function __construct() {
		Database::initialize();
		RegisterScripts::get_instance();
		RegisterMenu::get_instance();
		AllLayouts::get_instance();
		ShopDataManage::get_instance();
		chooseTemplates::get_instance();
		FilterDSettings::get_instance();
		FilterDataManage::get_instance();
		SaveLayoutData::get_instance();
		wishlistManage::get_instance();

		// Add actions
		add_action( 'init', array( $this, 'shopglutInitialFunctions' ), 9 );
		add_filter( 'update_footer', array( $this, 'shopglut_admin_footer_version' ), 999 );
	}

	public function shopglutInitialFunctions() {
		// No need to enqueue styles and scripts here as they are enqueued in render_custom_menu_page
		require_once SHOPGLUT_PATH . 'src/library/model/classes/setup.class.php';
		require_once SHOPGLUT_PATH . 'src/library/metaboxes-config.php';
		require_once SHOPGLUT_PATH . 'src/library/filters-config.php';
		require_once SHOPGLUT_PATH . 'src/library/singleLayout-settings.php';
		require_once SHOPGLUT_PATH . 'src/library/taxonomy-options.php';
		require_once SHOPGLUT_PATH . 'src/library/wishlist-settings.php';
		require_once SHOPGLUT_PATH . 'src/library/admin-options.php';

	}

	public function shopglut_admin_footer_version() {
		return "ShopGlut " . SHOPGLUT_VERSION;
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}