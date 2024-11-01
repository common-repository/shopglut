<?php
namespace Shopglut\layouts\shopLayout;

class SettingsPage {

	public $menu_slug = 'shopglut_layouts';

	public function __construct() {

	}

	public function loadShoplayoutEditor() {

		$layout_id = ! wp_verify_nonce( isset( $_GET['layout_nonce_check'] ), 'layout_nonce_check' ) && isset( $_GET['layout_id'] ) ? absint( $_GET['layout_id'] ) : 1;

		$loading_gif = SHOPGLUT_URL . 'assets/images/loading-icon.png';

		do_action( 'save_shopg_layout_data', $layout_id );

		do_action( 'shopglut_layout_metaboxes', 'shopglut' );

		global $wpdb;

		$table_name = $wpdb->prefix . 'shopglut_shop_layouts';

		$query = $wpdb->prepare( "SELECT layout_name, layout_template FROM " . $wpdb->prefix . "shopglut_shop_layouts WHERE id = %d", $layout_id ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder

		$layout_data = $wpdb->get_row( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder

		if ( $layout_data ) {
			$layout_name = $layout_data->layout_name;
			$layout_template = $layout_data->layout_template;
		} else {
			?>
			<div class="wrap">
				<p><?php esc_html_e( 'No layout data found.', 'shopglut' ); ?></p>
			</div>
			<?php
			return;
		}

		?>
		<div id="shopg-layout-admin-settings" class="wrap layout_settings">

			<div class="loader-overlay">
				<div class="loader-container">
					<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon" class="loader-image">
					<div class="loader-dash-circle"></div>
				</div>
			</div>

			<form id="shopglut_shop_layouts" method="post" action="">

				<?php $shopg_shop_nonce = wp_create_nonce( 'shopg_shop_layouts_nonce_action' ); ?>
				<input type="hidden" name="shopg_shop_layouts_nonce" value="<?php echo esc_attr( $shopg_shop_nonce ); ?>">
				<input type="hidden" name="shopg_shop_layoutid" id="shopg_shop_layoutid"
					value="<?php echo esc_attr( $layout_id ); ?>">

				<div class="shopglut_layout_contents">

					<div class="editor-fullscreen">

						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopglut_layouts' ) ); ?>"
							class="button button-secondary button-large">
							<i class="fa-solid fa-angles-left"></i>
							<?php echo esc_html__( 'Back To Menu', 'shopglut' ); ?>
						</a>

						<div class="clear"></div>
					</div>

					<div class="shopglut_layout_name">
						<label for="layout_name"><?php esc_html_e( 'Layout Name:', 'shopglut' ); ?></label>
						<input type="text" id="layout_name" name="layout_name" value="<?php echo esc_html( $layout_name ); ?>" />
						<input type="hidden" id="layout_template" name="layout_template"
							value="<?php echo esc_html( $layout_template ); ?>" />
					</div>

					<div class="shopglut_layout_shortcode">
						<div class="shopglut_php_scode">
							<span class="shopglut__sc-title"><?php echo esc_html__( "Shortcode:", "shopglut" ); ?></span>
							<span class="shopglut__shortcode-selectable">
								<i class="agl--icon far fa-copy"></i>
								<span class="shopglut_lcopy-text">
									[shopg_shop_layout id="<?php echo esc_attr( $layout_id ); ?>"]</span>
							</span>
						</div>

						<div class="shopglut_php_scode">
							<span class="shopglut__sc-title"><?php echo esc_html__( "PHP Code:", 'shopglut' ); ?> </span>
							<span class="shopglut__shortcode-selectable">
								<i class="agl--icon far fa-copy"></i>
								<span class="shopglut_lcopy-text">&lt;?php echo do_shortcode('[shopg_shop_layout
									id="<?php echo esc_attr( $layout_id ); ?>"]');?&gt;</span>
							</span>
						</div>
					</div>

					<div class="shopglut_shop_reset">
						<button id="resetButton" class="button button-reset">
							<i class="fa fa-undo-alt"></i> <?php echo esc_html__( 'Reset', 'shopglut' ); ?>
						</button>
					</div>


					<div class="agl_scode_wrap">
						<div class="shopglut-after-copy-text">
							<i class="fa fa-check-circle"></i>
							<?php echo esc_html__( "Shortcode Copied!", "shopglut" ); ?>
						</div>
					</div>
					<div id="shopg-notification-container"></div>

				</div>


				<div id="poststuff" class="shopglut-shoplayouts">
					<div id="post-body" class="metabox-holder columns-2">

						<div id="shopg-shoplayout-settings" class="postbox-container">

							<?php do_meta_boxes( $this->menu_slug, 'side', '' ); ?>

						</div>
						<div id="shopg-shoplayout-container" class="shopg-admin-edit-panel">
							<div class="shopg-inside-loader">
								<div class="shopg-inside-loader-overlay">
									<div class="shopg-inside-loader-container">
										<img src="<?php echo esc_url( $loading_gif ); ?>" alt="Loading Icon"
											class="shopg-inside-loader-image">
										<div class="shopg-inside-loader-dash-circle"></div>
									</div>
								</div>
							</div>
							<?php do_meta_boxes( $this->menu_slug, 'normal', '' ); ?>
						</div>
					</div>

				</div>
		</div>

		</form>
		<!-- Comparison Modal -->
		<div class="shop-layouts-compare-modal">
			<div class="modal-content">
				<span class="shop-layouts-compare-modal-close">&times;</span>
				<div class="modal-body">
					<div class="comparison-data"></div>
				</div>
			</div>
		</div>

		<div id="shop-layouts-quick-view-modal" style="display: none;">
			<div class="quick-view-content">
				<span class="shop-layouts-quick-view-modal-close">&times;</span>
				<div class="product-overview"></div>

			</div>
		</div>


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

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}