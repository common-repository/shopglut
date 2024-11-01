<?php
namespace Shopglut\layouts\shopLayout;

class chooseTemplates {

	public function __construct() {

		add_action( 'admin_post_create_layout', array( $this, 'handleCreateLayout' ) );

	}

	public function loadShoplayoutTemplates() {

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 3,
		);

		$tab_names = [ 
			'tab1' => [ 'name' => esc_html__( "General", 'shopglut' ), 'templates' => [ 'template1', 'template2' ] ],
			'tab2' => [ 'name' => esc_html__( "Table", 'shopglut' ), 'templates' => [ 'template4', 'template5' ] ],
			'tab3' => [ 'name' => esc_html__( "List", 'shopglut' ), 'templates' => [ 'template3' ] ],
			'tab4' => [ 'name' => esc_html__( "Free", 'shopglut' ), 'templates' => [ 'template2' ] ],
			'tab5' => [ 'name' => esc_html__( "Pro", 'shopglut' ), 'templates' => [ 'template1' ] ]
		];

		$template_names = [ 
			'template1' => esc_html__( "Template One", 'shopglut' ),
			'template2' => esc_html__( "Template Two", 'shopglut' ),
			'template3' => esc_html__( "Template Three", 'shopglut' ),
			'template4' => esc_html__( "Template Four", 'shopglut' ),
			'template5' => esc_html__( "Template Five", 'shopglut' ),
		];

		$query = new \WP_Query( $args );

		?>
		<div class="shopg-tab-container">
			<ul class="shopg-tabs">
				<?php foreach ( $tab_names as $tab_id => $tab ) : ?>
					<li class="shopg-tab" data-tab="<?php echo esc_attr( $tab_id ); ?>">
						<?php echo esc_html( $tab['name'] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php foreach ( $tab_names as $tab_id => $tab ) : ?>
				<div class="shopg-tab-content" id="<?php echo esc_attr( $tab_id ); ?>">
					<?php foreach ( $tab['templates'] as $layout_template ) : ?>
						<div class="shopg_templates_layouts column row col-3 choose-template">
							<div class="shopg_template_title">
								<h2><?php echo $template_names[ $layout_template ] ?? ''; ?></h2>
							</div>
							<div class="template_design_layouts">
								<?php
								if ( $query->have_posts() ) {
									while ( $query->have_posts() ) {
										$query->the_post();

										$layout_class = 'Shopglut\\layouts\\shopLayout\\templates\\' . $layout_template;

										if ( class_exists( $layout_class ) ) {
											$layout_instance = new $layout_class();
											$layout_instance->layout_render( [] );
										}
									}
									wp_reset_postdata();
								}
								?>
							</div>
							<div class="shopg-create-layout">
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="create_layout">
									<input type="hidden" name="layout_template" value="<?php echo esc_attr( $layout_template ); ?>">
									<?php global $wpdb;
									$table_name = $wpdb->prefix . 'shopglut_shop_layouts';
									$layout_id = intval( $wpdb->get_var( "SELECT MAX(id) FROM " . $table_name ) );
									$layout_id = $layout_id ? $layout_id + 1 : 1;
									?>
									<input type="hidden" name="layout_id" value="<?php echo esc_attr( $layout_id ); ?>">
									<?php wp_nonce_field( 'create_layout_nonce', 'create_layout_nonce' ); ?>
									<input type="submit" name="publish" id="publish" class="btn btn-green btn-large"
										value="<?php echo esc_html__( "Choose and Edit Style", 'shopglut' ); ?>" />
								</form>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public function handleCreateLayout() {
		if (
			isset( $_POST['create_layout_nonce'] ) &&
			wp_verify_nonce( $_POST['create_layout_nonce'], 'create_layout_nonce' ) &&
			current_user_can( 'manage_options' )
		) {
			$layout_id = absint( $_POST['layout_id'] );
			$layout_name = sanitize_text_field( 'Layout(#' . $layout_id . ')' );
			$layout_template = sanitize_text_field( $_POST['layout_template'] );

			global $wpdb;
			$table_name = $wpdb->prefix . 'shopglut_shop_layouts';
			$inserted = $wpdb->insert(
				$table_name,
				array(
					'id' => $layout_id,
					'layout_name' => $layout_name,
					'layout_template' => $layout_template,
				)
			);

			if ( $inserted ) {
				$redirect_url = admin_url( 'admin.php?page=shopglut_layouts&editor=shop&layout_id=' . $layout_id );
				wp_redirect( $redirect_url );
				exit;
			} else {
				wp_die( 'Database insertion error' );
			}
		} else {
			wp_die( 'Permission error' );
		}
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}