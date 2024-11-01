<?php
namespace Shopglut\showcase;

class AddNewLayout {

	public function __construct() {
		if (isset($_GET['page']) && trim($_GET['page']) === 'shopglut_showcase') {
			// phpcs:ignore
			add_action('admin_footer', array($this, 'shopg_add_new_layout'));
		}
	}

	public function shopgAddNewLayout() {
		?>
<!-- The Modal -->
<div id="shopg_layout_modal" class="shopg_layout_modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2><?php echo esc_html__("Select Layout Design", 'shopglut'); ?></h2>
        </div>
        <div class="shopglut-product-layout">
            <?php

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 2,
		);

		$query = new \WP_Query($args);

		$design_folder = SHOPGLUT_PATH . '/src/showcase/templates';

		$design_files = glob($design_folder . '/*.php');

		if ($query->have_posts()) {

			if ($design_files && is_array($design_files)) {

				foreach ($design_files as $design_path) {
					echo '<div class="product-layout">';

					while ($query->have_posts()) {
						$query->the_post();

						$file_name = pathinfo($design_path, PATHINFO_FILENAME);

						$template_class = 'Shopglut\\showcase\\templates\\' . $file_name;

						if (class_exists($template_class)) {
							$template_instance = new $template_class();
							$template_instance->layout_render(1); // Assuming you have a method named template1_layout() in your layout class
						}
					}

					// Reset the query to start from the beginning
					$query->rewind_posts();

					// Output the Create button with a form ?>
            <div class="create-layout">
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="create_layout">
                    <input type="hidden" name="layout_template" value="<?php echo esc_attr($file_name); ?>">
                    <?php
global $wpdb;
					$table_name = $wpdb->prefix . 'shopglut_product_layout';
					$product_id = intval($wpdb->get_var("SELECT MAX(id) FROM " . $wpdb->prefix . "shopglut_product_layout")); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder
					$product_id = $product_id ? $product_id + 1 : 1;
					?>
                    <input type="hidden" name="layout_id" value="<?php echo esc_attr($product_id); ?>">
                    <?php wp_nonce_field('create_layout_nonce', 'create_layout_nonce');?>
                    <input type="submit" name="publish" id="publish" class="button button-primary button-large"
                        value="<?php echo esc_html__("Create", 'shopglut'); ?>" />
                </form>
            </div>
            <?php

					echo '</div>';
				}
			}

		} else {
			echo esc_html__('No products found', 'shopglut');
		}

		// Reset post data after the loop
		wp_reset_postdata();
		?>
        </div>



        <div class="modal-footer">
        </div>
    </div>

</div>
<?php
}

// Handle form submission

	public function handleCreateLayout() {
		// Check nonce and user permissions
		if (
			isset($_POST['create_layout_nonce']) &&
			wp_verify_nonce($_POST['create_layout_nonce'], 'create_layout_nonce') &&
			current_user_can('manage_options')
		) {
			// Sanitize and save form data to the database

			$layout_id = absint($_POST['layout_id']);
			$layout_name = sanitize_text_field('Layout(#' . $_POST['layout_id'] . ')');
			$layout_template = sanitize_text_field($_POST['layout_template']);

			// Save data to your database table
			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_product_layout';
			$wpdb->insert( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder
				$wpdb->prefix . 'shopglut_product_layout',
				array(

					'id' => $layout_id,
					'layout_name' => $layout_name,
					'layout_template' => $layout_template,
				)
			);

			// Redirect to the specified URL
			$redirect_url = admin_url('admin.php?page=shopglut_showcase&layout_id=' . $layout_id);
			wp_redirect($redirect_url);
			exit;
		} else {
			// Invalid nonce or insufficient permissions
			wp_die('Permission error');
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