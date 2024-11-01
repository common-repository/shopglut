<?php
namespace Shopglut\layouts;

use Shopglut\layouts\shopLayout\chooseTemplates as ShopLayoutTemplates;
use Shopglut\layouts\shopLayout\SettingsPage as ShopLayoutEditor;
use Shopglut\layouts\shopLayout\ShopListTable;
use Shopglut\layouts\singleProduct\SettingsPage as SingleProductEditor;
use Shopglut\layouts\singleProduct\SingleProductListTable;
use Shopglut\tables\LayoutEntity;

class AllLayouts {
	public function __construct() {

		add_filter( 'admin_body_class', array( $this, 'shopglutBodyClass' ) );

	}

	public function shopglutBodyClass( $classes ) {
		$current_screen = get_current_screen();

		if ( empty( $current_screen ) ) {
			return $classes;
		}
		if ( false !== strpos( $current_screen->id, 'shopglut_' ) ) {
			$classes .= 'shopglut-admin';
		}
		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'shop' === $_GET['editor'] ) {
			$classes .= '-shopglut-shop-editor ';
		}

		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'single_product' === $_GET['editor'] ) {
			$classes .= '-sg-single-product ';
		}

		return $classes;
	}

	public function renderLayoutsPages() {

		$singleProduct_editor = new SingleProductEditor();
		$shopLayout_editor = new ShopLayoutEditor();

		if ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'single_product' === $_GET['editor'] && isset( $_GET['layout_id'] ) ) {
			$singleProduct_editor->loadSingleProductEditor();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['editor'] ) && 'shop' === $_GET['editor'] && isset( $_GET['layout_id'] ) ) {
			$shopLayout_editor->loadShopLayoutEditor();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['view'] ) && 'shop_templates' === $_GET['view'] ) {
			$this->ShoplayoutTemplatesPage();
		} elseif ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] && isset( $_GET['view'] ) ) {
			switch ( $_GET['view'] ) {
				case 'single_product':
					$this->renderSingleProduct();
					break;
				case 'cart':
					$this->renderCart();
					break;
				case 'checkout':
					$this->renderCheckout();
					break;
				case 'order_thankyou':
					$this->renderOrderThankyou();
					break;
				case 'my_account':
					$this->renderMyAccount();
					break;
				case 'archive':
					$this->renderArchive();
					break;
				case 'quick_view':
					$this->renderQuickView();
					break;
				default:
					//$this->renderLayoutsTable();
					break;
			}
		} elseif ( isset( $_GET['page'] ) && 'shopglut_layouts' === $_GET['page'] ) {
			$this->renderLayoutsTable();
		} else {
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.', 'shopglut' ) );
		}

	}

	public function renderLayoutSettings() {

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
					<?php $id = $menu['id'];
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
		return 'layouts';
	}

	public function headerMenuTabs() {
		$tabs = [ 
			5 => [ 'id' => 'layouts', 'url' => admin_url( 'admin.php?page=shopglut_layouts' ), 'label' => esc_html__( 'Shop Layouts', 'shopglut' ) ],
			10 => [ 'id' => 'single_product', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=single_product' ), 'label' => esc_html__( 'Single Product', 'shopglut' ) ],
			15 => [ 'id' => 'cart', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=cart' ), 'label' => esc_html__( 'Shop Cart', 'shopglut' ) ],
			20 => [ 'id' => 'checkout', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=checkout' ), 'label' => esc_html__( 'Shop Checkout', 'shopglut' ) ],
			25 => [ 'id' => 'order_thankyou', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=order_thankyou' ), 'label' => esc_html__( 'Order Complete', 'shopglut' ) ],
			30 => [ 'id' => 'my_account', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=my_account' ), 'label' => esc_html__( 'My Account', 'shopglut' ) ],
			35 => [ 'id' => 'archive', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=archive' ), 'label' => esc_html__( 'Archive Layouts', 'shopglut' ) ],
			40 => [ 'id' => 'quick_view', 'url' => admin_url( 'admin.php?page=shopglut_layouts&view=quick_view' ), 'label' => esc_html__( 'Quick View', 'shopglut' ) ],
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
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopglut' ) );
		}

		// Handle individual delete action
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['layout_id'] ) ) {
			$layout_id = absint( $_GET['layout_id'] );

			// Verify nonce
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'shopglut_delete_layout_' . $layout_id ) ) {
				// Delete the layout
				LayoutEntity::delete_layout( $layout_id );

				// Redirect to avoid resubmission
				wp_redirect( admin_url( 'admin.php?page=shopglut_layouts&deleted=true' ) );
				exit;
			} else {
				wp_die( esc_html__( 'Security check failed.', 'shopglut' ) );
			}
		}

		if ( isset( $_GET['deleted'] ) && $_GET['deleted'] === 'true' ) {
			echo '<div class="updated notice"><p>' . esc_html__( 'Layout deleted successfully.', 'shopglut' ) . '</p></div>';
		}
		$layouts_table = new ShopListTable();
		$layouts_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<div class="wrap shopglut-admin-contents">
			<h2><?php //echo esc_html__( 'Layouts', 'shopglut' ); ?><a
					href="<?php //echo esc_url( admin_url( 'admin.php?page=shopglut_layouts&view=shop_templates' ) ); ?>"><span
						class="add-new-h22"><?php //echo esc_html__( 'Add New Layout', 'shopglut' ); ?></span></a></h2>
			<form method="post">
				<?php //$layouts_table->display();
						?>
			</form>
		</div>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function ShoplayoutTemplatesPage() {

		$active_menu = 'layouts';
		$this->settingsPageHeader( $active_menu );
		$shopLayout_templates = new ShopLayoutTemplates();
		?>
		<div class="wrap shopglut-admin-contents shoplayouts-templates">
			<h1><?php echo esc_html__( 'PreBuilt ShopPage Templates', 'shopglut' ); ?></h1>
			<p class="subheading"><?php echo esc_html__( 'Choose your desired template to customize', 'shopglut' ); ?></p>
		</div>
		<?php $shopLayout_templates->loadShoplayoutTemplates();

	}

	public function renderSingleProduct() {
		$single_product_table = new SingleProductListTable();
		$single_product_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderCart() {
		$cart_table = new CartListTable();
		$cart_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderCheckout() {
		$checkout_table = new CheckoutListTable();
		$checkout_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}
	public function renderOrderThankyou() {
		$order_thankyou_table = new OrderCompleteListTable();
		$order_thankyou_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderMyAccount() {
		$my_account_table = new MyAccountListTable();
		$my_account_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}

	public function renderArchive() {
		$archive_table = new ArchiveListTable();
		$archive_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
		<?php
	}
	public function renderQuickView() {
		$quick_view_table = new QuickViewListTable();
		$quick_view_table->prepare_items();
		$active_menu = $this->activeMenuTab();
		$this->settingsPageHeader( $active_menu );
		?>
		<h2 style="padding-left:30px;padding-top:10px;">Not added yet, We shall try to add soon...</h2>
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