<?php
namespace Shopglut\showcase;

use Shopglut\showcase\Filters\FilterListTable;
use Shopglut\showcase\Filters\SettingsPage as FiltersSettings;

class AllShowcases {

	public function __construct() {
		add_filter( 'admin_body_class', array( $this, 'shopglutBodyClass' ) );

	}

	public function shopglutBodyClass( $classes ) {

		$current_screen = get_current_screen();

		if ( empty( $current_screen ) ) {
			return $classes;
		}

		if ( false !== strpos( $current_screen->id, 'shopglut_' ) ) {

			$classes .= ' shopglut-admin';
		}

		if ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] && isset( $_GET['editor'] ) && 'filter' === $_GET['editor'] ) {
			$classes .= '-shopglut-showcase-editor ';
		}

		return $classes;
	}

	public function renderCustomMenuPage() {

		$shopglut_filters = new FiltersSettings();

		if ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] && isset( $_GET['layout_id'] ) && ! wp_verify_nonce( isset( $_GET['url_nonce_check'] ), 'url_nonce_value' ) ) {
			$this->renderLayoutSettings();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] && isset( $_GET['editor'] ) && 'filter' === $_GET['editor'] ) {
			$shopglut_filters->FilterSettings();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] && isset( $_GET['view'] ) && 'badges' === $_GET['view'] ) {
			$this->renderBadgesTable();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] && isset( $_GET['view'] ) && 'tabs' === $_GET['view'] ) {
			$this->renderTabsTable();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] && isset( $_GET['view'] ) && 'swatches' === $_GET['view'] ) {
			$this->renderSwatchesTable();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] && isset( $_GET['view'] ) && 'sliders' === $_GET['view'] ) {
			$this->renderSlidersTable();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_showcase' === $_GET['page'] ) {
			$this->renderFiltersTable();
		} else {
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.', 'shopglut' ) );
		}
		;

	}

	public function settingsPageHeader( $active_menu ) {
		$logo_url = SHOPGLUT_URL . 'assets/images/header-logo.svg';
		?>
		<div class="shopglut-page-header">
			<div class="shopglut-page-header-wrap">
				<div class="shopglut-page-header-banner shopglut-pro shopglut-no-submenu">
					<div class="shopglut-page-header-banner__logo">
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="">
					</div>
					<div class="shopglut-page-header-banner__helplinks">
						<span><a rel="noopener" href="https://shopglut.appglut.com" target="_blank">
								<span class="dashicons dashicons-admin-page"></span>
								<?php echo esc_html__( 'Documentation', 'shopglut' ); ?>
							</a></span>
						<span><a class="shopglut-active" rel="noopener" href="https://www.appglut.com/plugin/shopglut/"
								target="_blank">
								<span class="dashicons dashicons-unlock"></span>
								<?php echo esc_html__( 'Unlock Pro Edition', 'shopglut' ); ?>
							</a></span>
						<span><a rel="noopener" href="https://www.appglut.com/support/" target="_blank">
								<span class="dashicons dashicons-share-alt"></span>
								<?php echo esc_html__( 'Support', 'shopglut' ); ?>
							</a></span>
					</div>
					<div class="clear"></div>
					<?php $this->settingsPageHeaderMenus( $active_menu ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function settingsPageHeaderMenus( $active_menu ) {
		$menus = $this->headerMenuTabs();

		if ( count( $menus ) < 2 ) {
			return;
		}

		?>
		<div class="shopglut-header-menus">
			<nav class="shopglut-nav-tab-wrapper nav-tab-wrapper">
				<?php foreach ( $menus as $menu ) : ?>
					<?php
					$id = $menu['id'];
					$url = esc_url_raw( ! empty( $menu['url'] ) ? $menu['url'] : '' );
					?>
					<a href="<?php echo esc_url( remove_query_arg( wp_removable_query_args(), $url ) ); ?>"
						class="shopglut-nav-tab nav-tab<?php echo esc_attr( $id ) == esc_attr( $active_menu ) ? ' shopglut-nav-active' : ''; ?>">
						<?php echo esc_html( $menu['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>
		<?php
	}

	public function defaultHeaderMenu() {
		return 'filters';
	}

	public function headerMenuTabs() {
		$tabs = [ 
			5 => [ 'id' => 'filters', 'url' => admin_url( 'admin.php?page=shopglut_showcase&view=filters' ), 'label' => esc_html__( 'Shop Filters', 'shopglut' ) ],
			10 => [ 'id' => 'badges', 'url' => admin_url( 'admin.php?page=shopglut_showcase&view=badges' ), 'label' => esc_html__( 'Product Badges', 'shopglut' ) ],
			15 => [ 'id' => 'tabs', 'url' => admin_url( 'admin.php?page=shopglut_showcase&view=tabs' ), 'label' => esc_html__( 'Product Tabs', 'shopglut' ) ],
			20 => [ 'id' => 'sliders', 'url' => admin_url( 'admin.php?page=shopglut_showcase&view=sliders' ), 'label' => esc_html__( 'Product Sliders', 'shopglut' ) ],
			25 => [ 'id' => 'swatches', 'url' => admin_url( 'admin.php?page=shopglut_showcase&view=swatches' ), 'label' => esc_html__( 'Product Swatches', 'shopglut' ) ],
		];

		ksort( $tabs );

		return $tabs;
	}

	public function activeMenuTab() {
		if ( ( ! wp_verify_nonce( isset( $_GET['url_nonce_check'] ), 'url_nonce_value' ) ) && ( strpos( $_GET['page'], 'shopglut' ) !== false ) ) {

			return isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : $this->defaultHeaderMenu();
		}

		return false;
	}

	public function renderLayoutsTable() {

		$layouts_table = new ShowcaseListTable();
		$layouts_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderFiltersTable() {

		$filters_table = new FilterListTable();
		$filters_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<form method="post" action="<?php //echo esc_url(admin_url('admin-post.php')); ?>">
				<input type="hidden" name="action" value="create_filter">
				<?php global $wpdb;
				$table_name = $wpdb->prefix . 'shopglut_showcase_filters';
				$filter_id = intval( $wpdb->get_var( "SELECT MAX(id) FROM " . $table_name ) );
				$filter_id = $filter_id ? $filter_id + 1 : 1;
				?>
				<input type="hidden" name="filter_id" value="<?php //echo esc_attr($filter_id); ?>">
				<?php // wp_nonce_field('create_filter_nonce', 'create_filter_nonce'); ?>
				<!-- <h2><?php //echo esc_html__('Shop Filters', 'shopglut'); ?><input class="add-new-h2222" type="submit" -->
				<!-- name="publish" id="publish" value="<?php //echo esc_html__("Add New Filter", 'shopglut'); ?>" /></h2> -->


			</form>
			<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
			<?php //$filters_table->display();
					?>
		</div>
		<?php
	}

	public function renderBadgesTable() {

		$tabs_table = new TabListTable();
		$tabs_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderTabsTable() {

		$tabs_table = new TabListTable();
		$tabs_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderSlidersTable() {
		$sliders_table = new SliderListTable();
		$sliders_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderSwatchesTable() {
		$sliders_table = new SliderListTable();
		$sliders_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderLayoutSettings() {
		$layoutSettings = new SettingsPage();
		$layoutSettings->shopLayoutSettings();
	}

	public static function get_instance() {
		static $instance;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}