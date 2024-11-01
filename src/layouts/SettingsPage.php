<?php
namespace Shopglut\layouts;

class SettingsPage {
	public function __construct() {

	}

	public function shopLayoutSettings($data) {
		$layout_id = !wp_verify_nonce(isset($_GET['layout_nonce_check']), 'layout_nonce_check') && isset($_GET['layout_id']) ? absint($_GET['layout_id']) : 1;

		$loading_gif = SHOPGLUT_URL . 'assets/images/spin.png';

		do_action('save_shopg_layout_data', $layout_id);

		do_action('shopglut_layout_metaboxes', 'shopgluts');

		global $wpdb;

		$table_name = $wpdb->prefix . 'shopglut_product_layout';

		$query = $wpdb->prepare("SELECT layout_name, layout_template FROM " . $wpdb->prefix . "shopglut_product_layout WHERE id = %d", $layout_id); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder

		$layout_data = $wpdb->get_row($query); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder

		if ($layout_data) {
			$layout_name = $layout_data->layout_name;
			$layout_template = $layout_data->layout_template;
		} else {
			?>
<div class="wrap">
    <p><?php esc_html_e('No layout data found.', 'shopglut');?></p>
</div>
<?php
return;
		}

		?>
<div id="shopg-layout-admin-settings" class="wrap layout_settings">

    <div id="shopg-showcase-loading">
        <img id="showcase-loading-image" src="<?php echo esc_url($loading_gif); ?>" alt="Loading..." />
    </div>

    <form id="shopglut_shop_layouts" method="post" action="">

        <?php $shopg_showcase_nonce = wp_create_nonce('shopg_showcase_layouts_nonce_action');?>
        <input type="hidden" name="shopg_showcase_layouts_nonce" value="<?php echo esc_attr($shopg_showcase_nonce); ?>">


        <div class="shopglut_layout_contents">
            <div class="shopglut_layout_name">
                <label for="layout_name"><?php esc_html_e('Layout Name:', 'shopglut');?></label>
                <input type="text" id="layout_name" name="layout_name" value="<?php echo esc_html($layout_name); ?>" />
                <input type="hidden" id="layout_template" name="layout_template"
                    value="<?php echo esc_html($layout_template); ?>" />
            </div>

            <div class="shopglut_layout_shortcode">
                <div class="shopglut_php_scode">
                    <span class="shopglut__sc-title"><?php echo esc_html__("Shortcode:", "shopglut"); ?></span>
                    <span class="shopglut__shortcode-selectable">
                        <i class="agl--icon far fa-copy"></i>
                        <span class="shopglut_lcopy-text">[shopg_layout id="<?php echo esc_attr($layout_id); ?>"]</span>
                    </span>
                </div>

                <div class="shopglut_php_scode">
                    <span class="shopglut__sc-title"><?php echo esc_html__("PHP Code:", 'shopglut'); ?></span>
                    <span class="shopglut__shortcode-selectable">
                        <i class="agl--icon far fa-copy"></i>
                        <span class="shopglut_lcopy-text">&lt;?php echo do_shortcode('[shopg_layout
                            id="<?php echo esc_attr($layout_id); ?>"]'); ?&gt;</span>
                    </span>
                </div>
            </div>

            <div class="editor-fullscreen">

                <a id="layout-switch-fullscreen" class="button button-secondary button-large">
                    <i class=" fa fa-expand"></i>
                    <?php echo esc_html__('FullScreen Mode', 'shopglut'); ?>
                </a>

                <div class="clear"></div>
            </div>

            <div class="agl_scode_wrap">
                <div class="shopglut-after-copy-text">
                    <i class="fa fa-check-circle"></i>
                    <?php echo esc_html__("Shortcode Copied!", "shopglut"); ?>
                </div>
            </div>
        </div>


        <div id="poststuff">
            <div id="post-body" class="metabox-holder">

                <div id="postbox-container-1" class="postbox-container">

                    <?php do_meta_boxes($this->menu_slug, 'side', '');?>

                </div>

                <div id="shopg-shoplayout-container" class="shopg-admin-edit-panel postbox-container">
                    <?php do_meta_boxes($this->menu_slug, 'normal', '');?>
                </div>

            </div>
        </div>

    </form>
</div>
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