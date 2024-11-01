<?php

namespace Shopglut\showcase\Filters;

class SettingsPage {
	public $menu_slug = 'shopglut_showcase';

	public function __construct() {

	}

	public function FilterSettings() {

		do_action('shopglut_layout_metaboxes', 'shopglut');

		$filter_id = !wp_verify_nonce(isset($_GET['layout_nonce_check']), 'layout_nonce_check') && isset($_GET['filter_id']) ? absint($_GET['filter_id']) : 1;
		global $wpdb;

		$table_name = $wpdb->prefix . 'shopglut_showcase_filters';

		$query = $wpdb->prepare("SELECT filter_name FROM $table_name WHERE id = %d", $filter_id);

		$result = $wpdb->get_row($query);

		if ($result) {
			$filter_name = $result->filter_name;
		}

		$loading_gif = SHOPGLUT_URL . 'assets/images/loading-icon.png';

		?>
<div id="shopg-layout-admin-settings" class="wrap layout_settings">

    <div class="loader-overlay">
        <div class="loader-container">
            <img src="<?php echo esc_url($loading_gif); ?>" alt="Loading Icon" class="loader-image">
            <div class="loader-dash-circle"></div>
        </div>
    </div>

    <form id="shopglut_shop_filter" method="post" action="">

        <?php $shopg_shop_nonce = wp_create_nonce('shopg_shop_layouts_nonce_action');?>
        <input type="hidden" name="shopg_shop_layouts_nonce" value="<?php echo esc_attr($shopg_shop_nonce); ?>">
        <input type="hidden" name="shopg_shop_filter_id" id="shopg_shop_filter_id"
            value="<?php echo esc_attr($filter_id); ?>">

        <div class="shopglut_layout_contents">

            <div class="editor-fullscreen">

                <a href="<?php echo esc_url(admin_url('admin.php?page=shopglut_showcase')); ?>"
                    class="button button-secondary button-large">
                    <i class="fa-solid fa-angles-left"></i>
                    <?php echo esc_html__('Back To Menu', 'shopglut'); ?>
                </a>

                <div class="clear"></div>
            </div>

            <div class="shopglut_filter_name">
                <label for="filter_name"><?php esc_html_e('Filter Name:', 'shopglut');?></label>
                <input type="text" id="filter_name" name="filter_name"
                    value="<?php echo esc_html__($filter_name, 'shopglut'); ?>" />
            </div>

        </div>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder filter-editor columns-2">

                <div id="shopg-filter-container" class="shopg-admin-edit-panel postbox-container">
                    <?php do_meta_boxes($this->menu_slug, 'side', '');?>
                </div>
                <div id="shopg-filter-preview">
                    <div id="postbox-container-1" class="postbox-container">
                        <?php do_meta_boxes($this->menu_slug, 'normal', '');?>

                    </div>
                </div>
            </div>
        </div>
</div>

</form>

</div>
<style>
html.wp-toolbar {
    padding-top: 0px !important;
}
</style>
<?php
}

	public static function get_instance() {
		static $instance;

		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}