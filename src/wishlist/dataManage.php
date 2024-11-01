<?php
namespace Shopglut\wishlist;

class dataManage {

	private $options;
	private $cron_token;
	public function __construct() {

		$this->options = get_option( 'agshopglut_wishlist_options' );
		$this->cron_token = get_option( 'shopglut_wishlist_cron_token', '' );

		// Generate a token if it doesn't exist
		if ( empty( $this->cron_token ) ) {
			$this->cron_token = $this->generate_cron_token();
			update_option( 'shopglut_wishlist_cron_token', $this->cron_token );
		}
		$this->add_actions();
	}

	private function add_actions() {
		// Set cookie for guest user ID if the user is not logged in
		add_action( 'init', function () {
			if ( ! is_user_logged_in() && ! isset( $_COOKIE['shopglutw_guest_user_id'] ) ) {
				$guest_id = 'guest_' . uniqid();
				setcookie( 'shopglutw_guest_user_id', $guest_id, time() + ( 86400 * 30 ), '/' ); // Cookie expires in 30 days
			}
		} );


		// Register shortcode
		add_shortcode( 'shopglut_wishlist', [ $this, 'shopglut_wishlist_shortcode' ] );

		// Register AJAX actions
		add_action( 'wp_ajax_load_wishlist_content', [ $this, 'load_wishlist_content' ] );
		add_action( 'wp_ajax_nopriv_load_wishlist_content', [ $this, 'load_wishlist_content' ] );

		add_action( 'wp_ajax_load_account_wishlist_content', [ $this, 'load_account_wishlist_content' ] );
		add_action( 'wp_ajax_nopriv_load_account_wishlist_content', [ $this, 'load_account_wishlist_content' ] );

		add_action( 'wp_ajax_shopglut_remove_from_wishlist', [ $this, 'shopglut_remove_from_wishlist' ] );
		add_action( 'wp_ajax_nopriv_shopglut_remove_from_wishlist', [ $this, 'shopglut_remove_from_wishlist' ] );

		add_action( 'wp_ajax_wishlist_add_to_cart', [ $this, 'wishlist_add_to_cart' ] );
		add_action( 'wp_ajax_nopriv_wishlist_add_to_cart', [ $this, 'wishlist_add_to_cart' ] );

		add_action( 'wp_ajax_shopglut_add_to_cart_and_checkout', [ $this, 'shopglut_add_to_cart_and_checkout' ] );
		add_action( 'wp_ajax_nopriv_shopglut_add_to_cart_and_checkout', [ $this, 'shopglut_add_to_cart_and_checkout' ] );

		// Add Wishlist to My Account Page
		if ( isset( $this->options['wishlist-page-account-page'] ) && $this->options['wishlist-page-account-page'] === '1' ) {
			// Add Wishlist to My Account Page
			add_filter( 'woocommerce_account_menu_items', [ $this, 'add_my_account_menu_item' ] );
			add_action( 'init', [ $this, 'add_my_account_endpoint' ] );
			// Set dynamic endpoint and hook for content
			$endpoint = sanitize_title( $this->options['wishlist-page-account-page-name'] );
			add_action( "woocommerce_account_{$endpoint}_endpoint", [ $this, 'my_account_wishlist_content' ] );
		}
		// Hook into WooCommerce product updates
		// add_action( 'wp_ajax_save_list_notification_preferences', [ $this, 'save_list_notification_preferences' ] );
		// add_action( 'woocommerce_update_product', [ $this, 'shopglut_product_update_handler' ], 10, 1 );
		// add_action( 'woocommerce_product_set_stock', [ $this, 'shopglut_product_stock_handler' ], 10, 1 );
		// add_action( 'woocommerce_product_update_price', [ $this, 'shopglut_product_price_handler' ], 10, 1 );

		add_action( 'wp_ajax_save_list_notification_preferences', [ $this, 'save_list_notification_preferences' ] );
		add_action( 'woocommerce_update_product', [ $this, 'shopglut_product_update_handler' ], 10, 1 );
		// Add wishlist button to product page

		if ( ! empty( $this->options['wishlist-enable-product-page'] ) ) {
			$position = $this->options['wishlist-product-position'] ?? 'after-cart';
			$hook = $this->determine_hook_position( $position );
			add_action( $hook, [ $this, 'shopglut_add_wishlist_button_single' ], 15 );
		}

		if ( ! empty( $this->options['wishlist-enable-shop-page'] ) ) {
			$position = $this->options['wishlist-shop-position'] ?? 'after-cart';
			$hook = $this->determine_shop_hook_position( $position );
			add_action( $hook, [ $this, 'shopglut_add_wishlist_button_shop' ], 15 );
		}

		// Add wishlist button to category pages
		if ( ! empty( $this->options['wishlist-enable-archive-page'] ) ) {
			$position = $this->options['wishlist-archive-position'] ?? 'after-cart';
			$hook = $this->determine_archive_hook_position( $position );
			add_action( $hook, [ $this, 'shopglut_add_wishlist_button_category' ], 20 );
		}
		// AJAX handlers for wishlist actions
		add_action( 'wp_ajax_toggle_wishlist', [ $this, 'toggle_wishlist_callback' ] );
		add_action( 'wp_ajax_nopriv_toggle_wishlist', [ $this, 'toggle_wishlist_callback' ] );
		add_action( 'wp_ajax_merge_guest_wishlist', [ $this, 'merge_guest_wishlist' ] );
		add_action( 'wp_ajax_nopriv_merge_guest_wishlist', [ $this, 'merge_guest_wishlist' ] );

		// Set a transient when a user logs in to potentially merge guest wishlist data
		add_action( 'wp_login', [ $this, 'set_merge_wishlist_transient' ], 10, 2 );

		// wishlist sublist
		add_action( 'wp_footer', [ $this, 'add_subwishlist_modal' ] );
		add_action( 'wp_footer', [ $this, 'add_notification_modal' ] );

		add_action( 'wp_ajax_create_wishlist_sublist', [ $this, 'create_wishlist_sublist' ] );
		add_action( 'wp_ajax_nopriv_create_wishlist_sublist', [ $this, 'create_wishlist_sublist' ] );

		add_action( 'wp_ajax_delete_wishlist_sublist', [ $this, 'delete_wishlist_sublist' ] );
		add_action( 'wp_ajax_nopriv_delete_wishlist_sublist', [ $this, 'delete_wishlist_sublist' ] );

		add_action( 'wp_ajax_get_wishlist_sublists', [ $this, 'get_wishlist_sublists' ] );
		add_action( 'wp_ajax_nopriv_get_wishlist_sublists', [ $this, 'get_wishlist_sublists' ] );
		add_action( 'wp_ajax_add_product_to_wishlist_sublist', [ $this, 'add_product_to_wishlist_sublist' ] );
		add_action( 'wp_ajax_nopriv_add_product_to_wishlist_sublist', [ $this, 'add_product_to_wishlist_sublist' ] );

		add_action( 'wp_ajax_get_user_notifications', [ $this, 'get_user_notifications' ] );

		// Handle the cron endpoint for email sending
		add_action( 'init', [ $this, 'register_email_cron_endpoint' ] );
		add_action( 'template_redirect', [ $this, 'handle_email_cron' ] );
		add_action( 'send_wishlist_email_event', [ $this, 'send_scheduled_wishlist_emails' ] );

	}

	private function determine_hook_position( $position ) {
		switch ( $position ) {
			case 'before-cart':
				return 'woocommerce_before_add_to_cart_button';
			case 'after-product-meta':
				return 'woocommerce_product_meta_end';
			default:
				return 'woocommerce_after_add_to_cart_button';
		}
	}

	private function determine_shop_hook_position( $position ) {
		switch ( $position ) {
			case 'before-cart':
				// Hook directly before the Add to Cart button in the shop loop
				return 'woocommerce_after_shop_loop_item_title';
			case 'after-product-meta':
				// Hook after product meta or title, closest to cart button in the loop
				return 'woocommerce_after_shop_loop_item';
			default:
				// Hook after the Add to Cart button by default
				return 'woocommerce_after_shop_loop_item';
		}
	}

	private function determine_archive_hook_position( $position ) {
		switch ( $position ) {
			case 'before-cart':
				// Hook directly before the Add to Cart button in the shop loop
				return 'woocommerce_after_shop_loop_item_title';
			case 'after-product-meta':
				// Hook after product meta or title, closest to cart button in the loop
				return 'woocommerce_after_shop_loop_item';
			default:
				// Hook after the Add to Cart button by default
				return 'woocommerce_after_shop_loop_item';
		}
	}

	public function set_merge_wishlist_transient( $user_login, $user ) {
		set_transient( 'merge_wishlist_' . $user->ID, true, 60 ); // expires in 60 seconds
	}

	public function merge_guest_wishlist() {
		//check_ajax_referer('shopLayouts_nonce', 'nonce');

		global $wpdb;
		$user_id = get_current_user_id();
		$guest_id = $_POST['guest_id'] ?? '';

		if ( $this->options['wishlist-merge-guestlist'] === '1' && $guest_id ) {
			$table_name = $wpdb->prefix . 'shopglut_wishlist';
			$guest_wishlist = $wpdb->get_var( $wpdb->prepare(
				"SELECT product_ids FROM $table_name WHERE wish_user_id = %s", $guest_id
			) );

			if ( $guest_wishlist ) {
				$guest_product_ids = explode( ',', $guest_wishlist );
				foreach ( $guest_product_ids as $guest_product_id ) {
					if ( ! empty( $guest_product_id ) ) {
						// Check if product already exists in the user's wishlist
						$existing_entry = $wpdb->get_var( $wpdb->prepare(
							"SELECT product_ids FROM $table_name WHERE wish_user_id = %d", $user_id
						) );
						$product_ids_array = $existing_entry ? explode( ',', $existing_entry ) : [];
						if ( ! in_array( $guest_product_id, $product_ids_array ) ) {
							$product_ids_array[] = $guest_product_id;
							$updated_product_ids = implode( ',', $product_ids_array );
							$wpdb->update( $table_name, [ 'product_ids' => $updated_product_ids, 'product_added_time' => current_time( 'mysql' ) ], [ 'wish_user_id' => $user_id ] );
						}
					}
				}

				// Remove the guest wishlist entry
				$wpdb->delete( $table_name, [ 'wish_user_id' => $guest_id ] );
			}
			wp_send_json_success( 'Wishlist merged successfully' );
		} else {
			wp_send_json_error( 'Guest to User Merge Was not Sucessful' );
		}
	}

	public function save_list_notification_preferences() {
		global $wpdb;

		parse_str( $_POST['form_data'], $form_data );
		$user_id = intval( $form_data['user_id'] );
		$wishlist_type = sanitize_text_field( $form_data['wishlist_type'] );
		$list_name = sanitize_text_field( $form_data['list_name'] );
		$notifications = isset( $form_data['notifications'] ) ? $form_data['notifications'] : [];

		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		if ( $wishlist_type === 'main' ) {
			$current_data = $wpdb->get_var( $wpdb->prepare( "SELECT wishlist_notifications FROM $table_name WHERE wish_user_id = %d", $user_id ) );
			$current_notifications = $current_data ? json_decode( $current_data, true ) : [];

			$to_add = array_diff( $notifications, $current_notifications );
			$to_remove = array_diff( $current_notifications, $notifications );

			$updated_notifications = array_merge( array_diff( $current_notifications, $to_remove ), $to_add );
			$encoded_notifications = json_encode( array_values( $updated_notifications ) );

			$result = $wpdb->update( $table_name, [ 
				'wishlist_notifications' => $encoded_notifications,
			], [ 'wish_user_id' => $user_id ] );

		} else if ( $wishlist_type === 'list' ) {
			$current_data = $wpdb->get_var( $wpdb->prepare( "SELECT sublist_notifications FROM $table_name WHERE wish_user_id = %d", $user_id ) );
			$current_notifications = $current_data ? json_decode( $current_data, true ) : [];

			$list_notifications = isset( $current_notifications[ $list_name ] ) ? $current_notifications[ $list_name ] : [];

			$to_add = array_diff( $notifications, $list_notifications );
			$to_remove = array_diff( $list_notifications, $notifications );

			$updated_notifications = array_merge( array_diff( $list_notifications, $to_remove ), $to_add );
			$current_notifications[ $list_name ] = array_values( $updated_notifications );

			$encoded_notifications = json_encode( $current_notifications );

			$result = $wpdb->update( $table_name, [ 
				'sublist_notifications' => $encoded_notifications,
			], [ 'wish_user_id' => $user_id ] );
		}

		if ( $result === false ) {
			error_log( 'Database error: ' . $wpdb->last_error );
			wp_send_json_error( 'Failed to update preferences.' );
			return;
		}

		wp_send_json_success();
	}
	public function shopglut_product_update_handler( $product_id ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return;
		}

		// Check stock status
		$stock_status = $product->get_stock_status();
		if ( $stock_status === 'instock' ) {
			$this->shopglut_check_notifications( $product_id, 'in_stock' );
		} elseif ( $stock_status === 'outofstock' ) {
			$this->shopglut_check_notifications( $product_id, 'out_of_stock' );
		}

		// Check if product is low on stock
		if ( $product->managing_stock() && $product->get_stock_quantity() <= $product->get_low_stock_amount() ) {
			$this->shopglut_check_notifications( $product_id, 'low_stock' );
		}

		// Check for price drop
		$regular_price = (float) $product->get_regular_price();
		$sale_price = (float) $product->get_sale_price();
		if ( $sale_price && $sale_price < $regular_price ) {
			$this->shopglut_check_notifications( $product_id, 'price_drop' );
		}

		// Always send a product update notification
		$this->shopglut_check_notifications( $product_id, 'product_update' );
	}

	public function shopglut_check_notifications( $product_id, $notification_type ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$users = $wpdb->get_results( "SELECT * FROM $table_name" );

		foreach ( $users as $user ) {
			$product_ids = explode( ',', $user->product_ids );
			$wishlist_notifications = json_decode( $user->wishlist_notifications, true );
			$sublist_notifications = json_decode( $user->sublist_notifications, true );
			$wishlist_sublists = json_decode( $user->wishlist_sublist, true );

			// Main wishlist notifications
			if ( in_array( $product_id, $product_ids ) && in_array( $notification_type, $wishlist_notifications ) ) {
				$this->shopglut_send_email_notification( $user->useremail, $product_id, $notification_type );
			}

			// Sublist notifications
			foreach ( $wishlist_sublists as $list_name => $list_products ) {
				if ( in_array( $product_id, $list_products ) && isset( $sublist_notifications[ $list_name ] ) && in_array( $notification_type, $sublist_notifications[ $list_name ] ) ) {
					$this->shopglut_send_email_notification( $user->useremail, $product_id, $notification_type, $list_name );
				}
			}
		}
	}

	public function shopglut_send_email_notification( $user_email, $product_id, $notification_type, $list_name = '' ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return;
		}

		$product_url = get_permalink( $product_id ); // Get the product URL
		$subject = "Notification: ";
		$message = "";

		switch ( $notification_type ) {
			case 'product_update':
				$subject .= "Product Update - " . $product->get_name();
				$message = "Dear User,\n\nThe product '{$product->get_name()}' has been updated. Please check the latest details here: {$product_url}\n\nThank you!";
				break;
			case 'in_stock':
				$subject .= "Product Back In Stock - " . $product->get_name();
				$message = "Dear User,\n\nGood news! The product '{$product->get_name()}' is now back in stock. Check it out here: {$product_url}\n\nDon't miss it!\n\nThank you!";
				break;
			case 'out_of_stock':
				$subject .= "Product Out of Stock - " . $product->get_name();
				$message = "Dear User,\n\nWe regret to inform you that the product '{$product->get_name()}' is now out of stock. More details here: {$product_url}\n\nThank you!";
				break;
			case 'price_drop':
				$subject .= "Price Drop Alert - " . $product->get_name();
				$message = "Dear User,\n\nGreat news! The price for the product '{$product->get_name()}' has dropped. See it here: {$product_url}\n\nThank you!";
				break;
			case 'low_stock':
				$subject .= "Low Stock Warning - " . $product->get_name();
				$message = "Dear User,\n\nHurry! The product '{$product->get_name()}' is running low on stock. Grab it here before it's gone: {$product_url}\n\nThank you!";
				break;
		}

		if ( $list_name ) {
			$message .= "\n\nThis notification is regarding your sublist: '{$list_name}'.";
		}

		wp_mail( $user_email, $subject, $message );
	}



	public function add_my_account_menu_item( $menu_items ) {
		// Get custom page name from options
		$page_name = ! empty( $this->options['wishlist-page-account-page-name'] ) ? $this->options['wishlist-page-account-page-name'] : __( 'My Wishlist', 'shopglut' );

		// Insert wishlist menu item as per custom name
		$menu_items = array_slice( $menu_items, 0, 1, true ) +
			[ sanitize_title( $page_name ) => esc_html( $page_name ) ] +
			array_slice( $menu_items, 1, null, true );

		return $menu_items;
	}

	// Register the custom endpoint for the Wishlist page
	public function add_my_account_endpoint() {
		// Get the sanitized endpoint from the custom page name
		$endpoint = ! empty( $this->options['wishlist-page-account-page-name'] ) ? sanitize_title( $this->options['wishlist-page-account-page-name'] ) : 'my-wishlist';
		add_rewrite_endpoint( $endpoint, EP_ROOT | EP_PAGES );
	}

	// Content for My Wishlist page in My Account
	public function my_account_wishlist_content() {
		echo $this->shopglut_account_wishlist();
	}
	public function load_wishlist_content() {
		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		$content = $this->shopglut_wishlist_shortcode();

		if ( $content ) {
			wp_send_json_success( [ 'content' => $content, 'status' => $_SERVER['REQUEST_URI'] ] );
		} else {
			wp_send_json_error( 'Failed to load wishlist content' );
		}
	}

	public function load_account_wishlist_content() {

		check_ajax_referer( 'shopLayouts_nonce', 'nonce' );

		$content = $this->shopglut_account_wishlist();

		if ( $content ) {
			wp_send_json_success( ( $content ) );
		} else {
			wp_send_json_error( $content );
		}
	}

	public function shopglut_wishlist_shortcode() {
		global $wpdb;

		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();

		$options = get_option( 'agshopglut_wishlist_options' );
		$wishlist_table = $wpdb->prefix . 'shopglut_wishlist';

		// Fetch main wishlist items and sublist
		$wishlist_data = $wpdb->get_row( $wpdb->prepare(
			"SELECT product_ids, wishlist_sublist FROM $wishlist_table WHERE wish_user_id = %s",
			$user_id
		) );

		// Decode the wishlist_sublist if exists
		$wishlist_sublist = $wishlist_data ? json_decode( $wishlist_data->wishlist_sublist, true ) : [];
		ob_start();

		// Check the enable-movelist option
		if ( isset( $options['wishlist-enable-multilist-tabs'] ) && $options['wishlist-enable-multilist-tabs'] === '1' && ! empty( $wishlist_sublist ) ) {
			// Render tabs with main wishlist and sublists
			echo '<div class="shoglut-wishlist-tabs">';
			echo '<ul class="tab-titles">';
			echo '<li class="tab-title active" data-tab="main-wishlist">Main Wishlist</li>';

			// Create tabs for each list in wishlist_sublist
			foreach ( $wishlist_sublist as $list_name => $products ) {
				echo '<li class="tab-title" data-tab="' . esc_attr( $list_name ) . '">' . esc_html( $list_name ) . '</li>';
			}
			echo '</ul>';

			// Main Wishlist Tab Content with Subscribe Button
			echo '<div class="tab-content active" id="main-wishlist">';
			$product_ids_single_tab = ! empty( $wishlist_data->product_ids ) ? array_map( 'trim', explode( ',', $wishlist_data->product_ids ) ) : [];
			$product_ids_filter = array_filter( $product_ids_single_tab, function ($value) {
				return ! empty( $value );
			} );
			if ( is_user_logged_in() && ( isset( $this->options['wishlist-enable-wishlist-subscription'] ) && $this->options['wishlist-enable-wishlist-subscription'] && $this->options['wishlist-enable-wishlist-subscription'] === '1' ) && ! empty( $product_ids_filter ) ) {
				echo '<button class="shopglutw-subscribe-notification-btn" data-wishlist-type="main" style="float: right;">' . esc_html__( 'Subscribe for Notifications', 'shopglut' ) . '</button>';
			}

			$this->render_wishlist_table( $product_ids_single_tab, 'main' );
			echo '</div>';

			// Additional Tabs Content for each sublist with Subscribe Button
			foreach ( $wishlist_sublist as $list_name => $product_ids ) {
				echo '<div class="tab-content" id="' . esc_attr( $list_name ) . '">';
				$product_ids_filter = array_filter( $product_ids, function ($value) {
					return ! empty( $value );
				} );
				if ( is_user_logged_in() && ( isset( $this->options['wishlist-enable-wishlist-subscription'] ) && $this->options['wishlist-enable-wishlist-subscription'] === '1' ) && ! empty( $product_ids_filter ) ) {
					echo '<button class="shopglutw-subscribe-notification-btn" data-wishlist-type="list" style="float: right;">' . esc_html__( 'Subscribe for Notifications', 'shopglut' ) . '</button>';
				}

				$this->render_wishlist_table( $product_ids, 'list', $list_name );
				echo '</div>';
			}
			echo '</div>'; // End of tabs container

		} else {
			// Render a single table for the main wishlist when movelist is disabled or no sublists exist
			echo '<div class="shoglut-wishlist-tabs" id="shopglut-normal-wishlist">';
			$product_ids_single = ! empty( $wishlist_data->product_ids ) ? array_map( 'trim', explode( ',', $wishlist_data->product_ids ) ) : [];
			$product_ids_filter = array_filter( $product_ids_single, function ($value) {
				return ! empty( $value );
			} );
			if ( is_user_logged_in() && ( isset( $this->options['wishlist-enable-wishlist-subscription'] ) && $this->options['wishlist-enable-wishlist-subscription'] === '1' ) && ! empty( $product_ids_filter ) ) {
				echo '<button class="shopglutw-subscribe-notification-btn" data-wishlist-type="main" style="float: right;">' . esc_html__( 'Subscribe for Notifications', 'shopglut' ) . '</button>';
			}

			$this->render_wishlist_table( $product_ids_single, 'main' );

			echo '</div>';
		}

		echo "<div id='shopglut-wishlist-notification'></div>";

		return ob_get_clean();
	}
	private function render_wishlist_table( $product_ids, $wishlist_type = 'main', $list_name = '' ) {
		$product_ids = array_filter( $product_ids );

		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			echo '<p>' . __( 'No wishlist products available.', 'shopglut' ) . '</p>';
			return;
		}

		$attribute_taxonomies = wc_get_attribute_taxonomies();

		echo '<table class="shopglut-wishlist-table">';
		echo '<thead><tr>';

		// Conditionally display table headers based on switcher options and dynamic attributes
		if ( ! empty( $this->options['wishlist-page-show-product-image'] ) ) {
			echo '<th>' . __( 'Product Image', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-page-show-product-name'] ) ) {
			echo '<th>' . __( 'Product Name', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-page-show-product-price'] ) ) {
			echo '<th>' . __( 'Unit Price', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-page-show-product-quantity'] ) ) {
			echo '<th>' . __( 'Quantity', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-page-show-product-availability'] ) ) {
			echo '<th>' . __( 'Availability', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-page-show-product-short-description'] ) ) {
			echo '<th>' . __( 'Description', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-page-show-product-sku'] ) ) {
			echo '<th>' . __( 'SKU', 'shopglut' ) . '</th>';
		}

		// Loop through dynamic attributes and add headers based on switcher settings
		// Loop through dynamic attributes and add headers based on switcher settings
		foreach ( $attribute_taxonomies as $attribute ) {
			$attr_option_id = 'wishlist-page-show-' . $attribute->attribute_name;
			if ( ! empty( $this->options[ $attr_option_id ] ) ) {
				echo '<th>' . esc_html( ucfirst( wc_attribute_label( $attribute->attribute_label ) ) ) . '</th>';
			}
		}

		if ( ! empty( $this->options['wishlist-page-show-product-add-to-cart'] ) ) {
			echo '<th>' . __( 'Add to Cart', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-page-show-product-checkout'] ) ) {
			echo '<th>' . __( 'Checkout', 'shopglut' ) . '</th>';
		}

		echo '<th>' . __( 'Remove', 'shopglut' ) . '</th>';

		echo '</tr></thead>';
		echo '<tbody>';

		foreach ( $product_ids as $product_id ) {
			if ( is_numeric( $product_id ) ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					echo '<tr data-product-id="' . esc_attr( $product_id ) . '">';

					// Product details and dynamic attribute values
					if ( ! empty( $this->options['wishlist-page-show-product-image'] ) ) {
						echo '<td>' . $product->get_image( 'thumbnail' ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-page-show-product-name'] ) ) {
						echo '<td>' . esc_html( $product->get_name() ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-page-show-product-price'] ) ) {
						echo '<td>' . wc_price( $product->get_price() ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-page-show-product-quantity'] ) ) {
						echo '<td>
						<input type="number" class="quantity" min="1" value="1" data-product-id="' . esc_attr( $product_id ) . '" />
					</td>';
					}
					if ( ! empty( $this->options['wishlist-page-show-product-availability'] ) ) {
						echo '<td>' . ( $product->is_in_stock() ? __( 'In Stock', 'shopglut' ) : __( 'Out of Stock', 'shopglut' ) ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-page-show-product-short-description'] ) ) {
						echo '<td>' . esc_html( $product->get_short_description() ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-page-show-product-sku'] ) ) {
						echo '<td>' . esc_html( $product->get_sku() ) . '</td>';
					}

					// Dynamic attributes
					foreach ( $attribute_taxonomies as $attribute ) {
						$attr_option_id = 'wishlist-page-show-' . $attribute->attribute_name;
						if ( ! empty( $this->options[ $attr_option_id ] ) ) {
							$attribute_value = $product->get_attribute( $attribute->attribute_name );
							echo '<td>' . esc_html( $attribute_value ) . '</td>';
						}
					}

					// Add to Cart button with quantity support
					if ( ! empty( $this->options['wishlist-page-show-product-add-to-cart'] ) ) {
						$remove_class = $this->options['wishlist-remove-if-add-to-cart'] == '1' ? 'remove-after-add' : '';
						echo '<td>
                     <button class="add-to-cart-btn ' . esc_attr( $remove_class ) . '" data-product-id="' . esc_attr( $product_id ) . '" data-quantity="1" data-wishlist-type="' . esc_attr( $wishlist_type ) . '" ' .
							' ' .
							( $wishlist_type === 'list' ? 'data-list-name="' . esc_attr( $list_name ) . '"' : '' ) . '>' . __( 'Add to Cart', 'shopglut' ) . '</button>
                      </td>';
					}

					// Dynamic checkout link
					if ( ! empty( $this->options['wishlist-page-show-product-checkout'] ) ) {
						echo '<td>
                <a href="#" class="checkout-link" data-product-id="' . esc_attr( $product_id ) . '" data-quantity="1">
                      ' . __( 'Checkout', 'shopglut' ) . '</a> </td>';
					}

					// Remove button with optional attributes for sublists
					echo '<td><button class="remove-btn" data-wishlist-type="' . esc_attr( $wishlist_type ) . '" ' .
						'data-product-id="' . esc_attr( $product_id ) . '" ' .
						( $wishlist_type === 'list' ? 'data-list-name="' . esc_attr( $list_name ) . '"' : '' ) . '>' .
						'<i class="fa fa-times"></i></button></td>';

					echo '</tr>';
				}
			}
		}

		echo '</tbody>';
		echo '</table>';
	}

	public function shopglut_account_wishlist() {
		global $wpdb;

		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
		$options = get_option( 'agshopglut_wishlist_options' );
		$wishlist_table = $wpdb->prefix . 'shopglut_wishlist';

		// Fetch main wishlist items and sublist
		$wishlist_data = $wpdb->get_row( $wpdb->prepare(
			"SELECT product_ids, wishlist_sublist FROM $wishlist_table WHERE wish_user_id = %s",
			$user_id
		) );

		// Decode the wishlist_sublist if exists
		$wishlist_sublist = $wishlist_data ? json_decode( $wishlist_data->wishlist_sublist, true ) : [];
		ob_start();

		// Check the enable-movelist option
		if ( isset( $options['wishlist-enable-multilist-tabs'] ) && $options['wishlist-enable-multilist-tabs'] === '1' && ! empty( $wishlist_sublist ) ) {
			// Render tabs with main wishlist and sublists
			echo '<div class="shoglut-wishlist-tabs">';
			echo '<ul class="tab-titles">';
			echo '<li class="tab-title active" data-tab="main-wishlist">Main Wishlist</li>';

			// Create tabs for each list in wishlist_sublist
			foreach ( $wishlist_sublist as $list_name => $products ) {
				echo '<li class="tab-title" data-tab="' . esc_attr( $list_name ) . '">' . esc_html( $list_name ) . '</li>';
			}
			echo '</ul>';

			// Main Wishlist Tab Content with Subscribe Button
			echo '<div class="tab-content active" id="main-wishlist">';
			$product_ids_single_tab = ! empty( $wishlist_data->product_ids ) ? array_map( 'trim', explode( ',', $wishlist_data->product_ids ) ) : [];
			$product_ids_filter = array_filter( $product_ids_single_tab, function ($value) {
				return ! empty( $value );
			} );
			if ( is_user_logged_in() && ( isset( $this->options['wishlist-enable-wishlist-subscription'] ) && $this->options['wishlist-enable-wishlist-subscription'] === '1' ) && ! empty( $product_ids_filter ) ) {
				echo '<button class="shopglutw-subscribe-notification-btn" data-wishlist-type="main" style="float: right;">' . esc_html__( 'Subscribe for Notifications', 'shopglut' ) . '</button>';
			}
			$this->render_account_wishlist_table( $product_ids_single_tab, 'main' );
			echo '</div>';

			// Additional Tabs Content for each sublist with Subscribe Button
			foreach ( $wishlist_sublist as $list_name => $product_ids ) {
				echo '<div class="tab-content" id="' . esc_attr( $list_name ) . '">';
				$product_ids_filter = array_filter( $product_ids, function ($value) {
					return ! empty( $value );
				} );
				if ( is_user_logged_in() && ( isset( $this->options['wishlist-enable-wishlist-subscription'] ) && $this->options['wishlist-enable-wishlist-subscription'] === '1' ) && ! empty( $product_ids_filter ) ) {
					echo '<button class="shopglutw-subscribe-notification-btn" data-wishlist-type="list" style="float: right;">' . esc_html__( 'Subscribe for Notifications', 'shopglut' ) . '</button>';
				}

				$this->render_account_wishlist_table( $product_ids, 'list', $list_name );
				echo '</div>';
			}
			echo '</div>'; // End of tabs container

		} else {
			// Render a single table for the main wishlist when movelist is disabled or no sublists exist
			echo '<div class="shoglut-wishlist-tabs" id="shopglut-normal-wishlist">';
			$product_ids_single = ! empty( $wishlist_data->product_ids ) ? array_map( 'trim', explode( ',', $wishlist_data->product_ids ) ) : [];
			$product_ids_filter = array_filter( $product_ids_single, function ($value) {
				return ! empty( $value );
			} );
			if ( is_user_logged_in() && ( isset( $this->options['wishlist-enable-wishlist-subscription'] ) && $this->options['wishlist-enable-wishlist-subscription'] === '1' ) && ! empty( $product_ids_filter ) ) {
				echo '<button class="shopglutw-subscribe-notification-btn" data-wishlist-type="main" style="float: right;">' . esc_html__( 'Subscribe for Notifications', 'shopglut' ) . '</button>';
			}

			$this->render_account_wishlist_table( $product_ids_single, 'main' );

			echo '</div>';
		}

		echo "<div id='shopglut-wishlist-notification'></div>";

		return ob_get_clean();
	}

	private function render_account_wishlist_table( $product_ids, $wishlist_type = 'main', $list_name = '' ) {
		$product_ids = array_filter( $product_ids );

		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			echo '<p>' . __( 'No wishlist products available.', 'shopglut' ) . '</p>';
			return;
		}

		$attribute_taxonomies = wc_get_attribute_taxonomies();

		echo '<table class="shopglut-wishlist-table">';
		echo '<thead><tr>';

		// Conditionally display table headers based on switcher options and dynamic attributes
		if ( ! empty( $this->options['wishlist-account-page-show-product-image'] ) ) {
			echo '<th>' . __( 'Product Image', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-name'] ) ) {
			echo '<th>' . __( 'Product Name', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-price'] ) ) {
			echo '<th>' . __( 'Unit Price', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-quantity'] ) ) {
			echo '<th>' . __( 'Quantity', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-availability'] ) ) {
			echo '<th>' . __( 'Availability', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-short-description'] ) ) {
			echo '<th>' . __( 'Description', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-sku'] ) ) {
			echo '<th>' . __( 'SKU', 'shopglut' ) . '</th>';
		}

		// Loop through dynamic attributes and add headers based on switcher settings
		foreach ( $attribute_taxonomies as $attribute ) {
			$attr_option_id = 'wishlist-account-page-show-' . $attribute->attribute_name;
			if ( ! empty( $this->options[ $attr_option_id ] ) ) {
				echo '<th>' . esc_html( ucfirst( wc_attribute_label( $attribute->attribute_label ) ) ) . '</th>';
			}
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-add-to-cart'] ) ) {
			echo '<th>' . __( 'Add to Cart', 'shopglut' ) . '</th>';
		}

		if ( ! empty( $this->options['wishlist-account-page-show-product-checkout'] ) ) {
			echo '<th>' . __( 'Checkout', 'shopglut' ) . '</th>';
		}

		echo '<th>' . __( 'Remove', 'shopglut' ) . '</th>';

		echo '</tr></thead>';
		echo '<tbody>';

		foreach ( $product_ids as $product_id ) {
			if ( is_numeric( $product_id ) ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					echo '<tr data-product-id="' . esc_attr( $product_id ) . '">';

					// Product details and dynamic attribute values
					if ( ! empty( $this->options['wishlist-account-page-show-product-image'] ) ) {
						echo '<td>' . $product->get_image( 'thumbnail' ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-account-page-show-product-name'] ) ) {
						echo '<td>' . esc_html( $product->get_name() ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-account-page-show-product-price'] ) ) {
						echo '<td>' . wc_price( $product->get_price() ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-account-page-show-product-quantity'] ) ) {
						echo '<td>
                    <input type="number" class="quantity" min="1" value="1" data-product-id="' . esc_attr( $product_id ) . '" />
                    </td>';
					}

					if ( ! empty( $this->options['wishlist-account-page-show-product-availability'] ) ) {
						echo '<td>' . ( $product->is_in_stock() ? __( 'In Stock', 'shopglut' ) : __( 'Out of Stock', 'shopglut' ) ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-account-page-show-product-short-description'] ) ) {
						echo '<td>' . esc_html( $product->get_short_description() ) . '</td>';
					}

					if ( ! empty( $this->options['wishlist-account-page-show-product-sku'] ) ) {
						echo '<td>' . esc_html( $product->get_sku() ) . '</td>';
					}

					// Dynamic attributes
					foreach ( $attribute_taxonomies as $attribute ) {
						$attr_option_id = 'wishlist-account-page-show-' . $attribute->attribute_name;
						if ( ! empty( $this->options[ $attr_option_id ] ) ) {
							$attribute_value = $product->get_attribute( $attribute->attribute_name );
							echo '<td>' . esc_html( $attribute_value ) . '</td>';
						}
					}

					// Add to Cart button with quantity support
					if ( ! empty( $this->options['wishlist-account-page-show-product-add-to-cart'] ) ) {
						$remove_class = $this->options['wishlist-account-remove-if-add-to-cart'] == '1' ? 'remove-after-add' : '';
						echo '<td>
                    <button class="add-to-cart-btn ' . esc_attr( $remove_class ) . '" data-product-id="' . esc_attr( $product_id ) . '" data-quantity="1" data-wishlist-type="' . esc_attr( $wishlist_type ) . '" ' .
							' ' .
							( $wishlist_type === 'list' ? 'data-list-name="' . esc_attr( $list_name ) . '"' : '' ) . '>' . __( 'Add to Cart', 'shopglut' ) . '</button>
                    </td>';
					}

					// Dynamic checkout link
					if ( ! empty( $this->options['wishlist-account-page-show-product-checkout'] ) ) {
						echo '<td>
                    <a href="#" class="checkout-link" data-product-id="' . esc_attr( $product_id ) . '" data-quantity="1">' . __( 'Checkout', 'shopglut' ) . '</a>
                    </td>';
					}

					// Remove button with optional attributes for sublists
					echo '<td><button class="remove-btn-account" data-wishlist-type="' . esc_attr( $wishlist_type ) . '" ' .
						'data-product-id="' . esc_attr( $product_id ) . '" ' .
						( $wishlist_type === 'list' ? 'data-list-name="' . esc_attr( $list_name ) . '"' : '' ) . '>' .
						'<i class="fa fa-times"></i></button></td>';

					echo '</tr>';
				}
			}
		}

		echo '</tbody>';
		echo '</table>';
	}

	public function shopglut_remove_from_wishlist() {
		global $wpdb;

		// Retrieve the product ID and sanitize it
		$product_id = intval( $_POST['product_id'] );
		$wishlist_type = sanitize_text_field( $_POST['wishlist_type'] );
		$list_name = isset( $_POST['list_name'] ) ? sanitize_text_field( $_POST['list_name'] ) : null;
		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();

		if ( $user_id && $product_id ) {
			$wishlist_table = $wpdb->prefix . 'shopglut_wishlist';

			// Fetch current wishlist data for the user
			$wishlist_data = $wpdb->get_row( $wpdb->prepare(
				"SELECT product_ids, wishlist_sublist FROM $wishlist_table WHERE wish_user_id = %s",
				$user_id
			) );

			if ( $wishlist_data ) {
				// Decode the main wishlist product IDs and the wishlist_sublist
				$product_ids = explode( ',', $wishlist_data->product_ids );
				$wishlist_sublist = $wishlist_data->wishlist_sublist ? json_decode( $wishlist_data->wishlist_sublist, true ) : [];

				if ( $wishlist_type === 'main' ) {
					// Remove the product from the main product_ids list
					$updated_product_ids = array_diff( $product_ids, [ $product_id ] );
					$updated_product_ids_string = implode( ',', $updated_product_ids );

					// Update only the product_ids in the main wishlist
					$wpdb->update(
						$wishlist_table,
						[ 'product_ids' => $updated_product_ids_string ],
						[ 'wish_user_id' => $user_id ],
						[ '%s' ],
						[ '%s' ]
					);

				} elseif ( $wishlist_type === 'list' && $list_name && isset( $wishlist_sublist[ $list_name ] ) ) {
					// Remove the product from the specific sublist
					$wishlist_sublist[ $list_name ] = array_diff( $wishlist_sublist[ $list_name ], [ $product_id ] );

					// Remove the list if it’s empty after deletion
					if ( empty( $wishlist_sublist[ $list_name ] ) ) {
						unset( $wishlist_sublist[ $list_name ] );
					}

					// Update only the wishlist_sublist
					$wpdb->update(
						$wishlist_table,
						[ 'wishlist_sublist' => json_encode( $wishlist_sublist ) ],
						[ 'wish_user_id' => $user_id ],
						[ '%s' ],
						[ '%s' ]
					);
				}

				wp_send_json_success();
			} else {
				wp_send_json_error( 'Could not find wishlist data for the user.' );
			}
		} else {
			wp_send_json_error( 'Invalid product ID or user not logged in.' );
		}
	}

	private function get_shopglutw_guest_user_id() {
		if ( isset( $_COOKIE['shopglutw_guest_user_id'] ) ) {
			return sanitize_text_field( $_COOKIE['shopglutw_guest_user_id'] );
		}
		return ''; // Return an empty string or handle as needed if the cookie is not set
	}

	public function wishlist_add_to_cart() {

		$product_id = intval( $_POST['product_id'] );
		$quantity = isset( $_POST['quantity'] ) ? intval( $_POST['quantity'] ) : 1; // Default to 1 if quantity not set
		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();

		if ( $user_id && $product_id ) {
			WC()->cart->add_to_cart( $product_id, $quantity ); // Add product with specified quantity
			wp_send_json_success();
		} else {
			wp_send_json_error( 'Invalid product ID or user not logged in.' );
		}
	}

	public function shopglut_add_to_cart_and_checkout() {
		$product_id = intval( $_POST['product_id'] );
		$quantity = intval( $_POST['quantity'] );

		if ( $product_id && $quantity > 0 ) {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}

		// Return the checkout URL as a JSON response
		$checkout_url = wc_get_checkout_url();
		wp_send_json_success( [ 'redirect_url' => $checkout_url ] );
	}

	public function shopglut_add_wishlist_button_single() {
		// Ensure WooCommerce is active
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		global $wpdb, $product; // Access the global $wpdb and product object

		if ( $this->options['wishlist-general-outofstock'] == '1' && ! $product->is_in_stock() ) {
			return; // Exit the function without displaying the wishlist button
		}

		// Retrieve guest or current user ID
		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
		$product_id = $product->get_id(); // Get the current product ID

		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$existing_entry = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE wish_user_id = %s AND FIND_IN_SET(%d, product_ids)",
			$user_id,
			$product_id
		) );

		$button_class = '';
		$href = '#';

		$move_href = '#';
		$move_button_class = "move_to_list";
		$move_button_text = __( "Move to List", 'shopglut' );

		if ( $existing_entry > 0 ) {
			$icon = $this->options['wishlist-product-added-icon'] ?? 'fa fa-heart';
			$button_text = $this->options['wishlist-product-button-text-after-added'] ?? __( 'Added to Wishlist', 'shopglut' );
			$second_click_action = $this->options['wishlist-product-second-click'] ?? 'remove-wishlist';

			// Handle the second click action based on the existing entry
			switch ( $second_click_action ) {
				case 'goto-wishlist':
					$href = esc_url( get_permalink( $this->options['wishlist-general-page'] ) );
					break;
				case 'redirect-to-checkout':
					$button_class = "checkout-link";
					$href = esc_url( wc_get_checkout_url() );
					break;
				case 'show-already-exist':
					$button_class = "already-added";
					break;
				default:
					$button_class = "shopgw-added";
					$href = '#';
					break;
			}
		} else {
			$icon = $this->options['wishlist-product-icon'] ?? 'fa-regular fa-heart';
			$button_text = $this->options['wishlist-product-button-text'] ?? __( 'Add to Wishlist', 'shopglut' );
			$button_class = 'not-shopgw-added';
			$href = '#';
		}

		if ( $this->options['wishlist-require-login'] == true && ! is_user_logged_in() ) {
			$href = wp_login_url( site_url( $_SERVER['REQUEST_URI'] ) );
			$button_class = "login-required";
			$button_text = __( $this->options['wishlist-require-login-btn-text'], 'shopglut' );
			$icon = $this->options['wishlist-require-login-btn-icon'];
			$move_href = wp_login_url( site_url( $_SERVER['REQUEST_URI'] ) );
			$move_button_class = "login-required";
			$move_button_text = __( "Move to List", 'shopglut' );
		}

		echo "<div class='shopglut_wishlist_container'>";
		echo "<div class='shopglut_wishlist single-product'>";
		if ( $this->options['wishlist-product-option'] === 'only-icon' ) {
			echo '<a href="' . $href . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
		} elseif ( $this->options['wishlist-product-option'] === 'button-with-icon' ) {
			$icon_position = $this->options['wishlist-product-icon-position'] ?? 'text-right';
			if ( $icon_position === 'text-left' ) {
				echo '<a href="' . $href . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i> <span class="button-text">' . esc_html( $button_text ) . '</span></a>';
			} else {
				echo '<a href="' . $href . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span> <i class="' . esc_attr( $icon ) . '"></i></a>';
			}
		} else {
			echo '<a href="' . $href . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span></a>';
		}
		echo "</div>";
		if ( $this->options['wishlist-product-enable-movelist'] === '1' && is_user_logged_in() ) {
			echo "<div class='shopglut_wishlist_movelist single-product'>";
			echo '<a href="' . $move_href . '" class="button ' . esc_attr( $move_button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $move_button_text ) . '</span></a>';
			echo "</div>";
		}
		echo "</div>";

	}

	public function shopglut_add_wishlist_button_shop() {
		global $wpdb, $product;

		// Check if product exists and WooCommerce is active
		if ( ! function_exists( 'is_woocommerce' ) || ! $product || ! is_shop() ) {
			return;
		}

		// Retrieve guest or current user ID
		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
		$product_id = $product->get_id();

		// Check if product is already in the wishlist
		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$existing_entry = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE wish_user_id = %s AND FIND_IN_SET(%d, product_ids)",
			$user_id,
			$product_id
		) );

		// Define button settings based on whether the product is in the wishlist
		if ( $existing_entry > 0 ) {
			$icon = $this->options['wishlist-shop-added-icon'] ?? 'fa fa-heart';
			$button_text = $this->options['wishlist-shop-button-text-after-added'] ?? __( 'Added To Wishlist', 'shopglut' );
			$second_click_action = $this->options['wishlist-shop-second-click'] ?? 'remove-wishlist';
			$button_class = "shopgw-added";

			switch ( $second_click_action ) {
				case 'goto-wishlist':
					$href = esc_url( get_permalink( $this->options['wishlist-general-page'] ) );
					break;
				case 'redirect-to-checkout':
					$button_class = "checkout-link";
					$href = esc_url( wc_get_checkout_url() );
					break;
				case 'show-already-exist':
					$button_class .= " already-added";
					$href = '#';
					break;
				default:
					$href = '#';
					break;
			}
		} else {
			$icon = $this->options['wishlist-shop-icon'] ?? 'fa-regular fa-heart';
			$button_text = $this->options['wishlist-shop-button-text'] ?? __( 'Add To Wishlist', 'shopglut' );
			$button_class = "not-shopgw-added";
			$href = '#';
		}

		// Add login requirement if necessary
		if ( $this->options['wishlist-require-login'] == true && ! is_user_logged_in() ) {
			$href = wp_login_url( site_url( $_SERVER['REQUEST_URI'] ) );
			$button_class = "login-required";
			$button_text = __( $this->options['wishlist-require-login-btn-text'], 'shopglut' );
			$icon = $this->options['wishlist-require-login-btn-icon'];
		}

		// Render the wishlist button
		echo "<div class='shopglut_wishlist_container'>";
		echo "<div class='shopglut_wishlist shop-page'>";
		if ( $this->options['wishlist-shop-option'] === 'only-icon' ) {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
		} elseif ( $this->options['wishlist-shop-option'] === 'button-with-icon' ) {
			$icon_position = $this->options['wishlist-shop-icon-position'] ?? 'text-right';
			if ( $icon_position === 'text-left' ) {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i> <span class="button-text">' . esc_html( $button_text ) . '</span></a>';
			} else {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span> <i class="' . esc_attr( $icon ) . '"></i></a>';
			}
		} else {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span></a>';
		}
		echo "</div>";

		// Optionally render MoveList button
		if ( $this->options['wishlist-shop-enable-movelist'] === '1' && is_user_logged_in() ) {
			$move_button_text = __( "Move to List", 'shopglut' );
			echo "<div class='shopglut_wishlist_movelist shop-page'>";
			echo '<a href="#" class="button move_to_list" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $move_button_text ) . '</span></a>';
			echo "</div>";
		}

		echo "</div>";
	}

	public function shopglut_add_wishlist_button_category() {
		global $wpdb, $product;

		// Ensure WooCommerce is active and the current page is a product category or archive page
		if ( ! function_exists( 'is_woocommerce' ) || ! $product || ! is_product_category() && ! is_product_tag() && ! is_product_taxonomy() ) {
			return;
		}

		// Check if the user wants to filter by category or tag
		$filter_option = $this->options['wishlist-archive-select-cat-option'] ?? 'all-categories';

		// Handle Category-specific display setting
		if ( $filter_option === 'select-category' ) {
			// Ensure $categories is an array
			$categories = (array) ( $this->options['wishlist-archive-select-category'] ?? [] );
			$product_categories = wp_get_post_terms( $product->get_id(), 'product_cat', [ 'fields' => 'ids' ] );

			if ( ! array_intersect( $product_categories, $categories ) ) {
				return; // Exit if the product category is not in the selected categories
			}
		}

		// Handle Tag-specific display setting
		if ( $filter_option === 'select-tag' ) {
			// Ensure $tags is an array
			$tags = (array) ( $this->options['wishlist-archive-select-category'] ?? [] );
			$product_tags = wp_get_post_terms( $product->get_id(), 'product_tag', [ 'fields' => 'ids' ] );

			if ( ! array_intersect( $product_tags, $tags ) ) {
				return; // Exit if the product tag is not in the selected tags
			}
		}

		$user_id = is_user_logged_in() ? get_current_user_id() : $this->get_shopglutw_guest_user_id();
		$product_id = $product->get_id();

		$table_name = $wpdb->prefix . 'shopglut_wishlist';
		$existing_entry = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE wish_user_id = %s AND FIND_IN_SET(%d, product_ids)",
			$user_id,
			$product_id
		) );

		if ( $existing_entry > 0 ) {
			$icon = $this->options['wishlist-archive-added-icon'] ?? 'fa fa-heart';
			$button_text = $this->options['wishlist-archive-button-text-after-added'] ?? __( 'Added To Wishlist', 'shopglut' );
			$second_click_action = $this->options['wishlist-archive-second-click'] ?? 'remove-wishlist';
			$button_class = "shopgw-added";

			switch ( $second_click_action ) {
				case 'goto-wishlist':
					$href = esc_url( get_permalink( $this->options['wishlist-general-page'] ) );
					break;
				case 'redirect-to-checkout':
					$button_class = "checkout-link";
					$href = esc_url( wc_get_checkout_url() );
					break;
				case 'show-already-exist':
					$button_class .= " already-added";
					$href = '#';
					break;
				default:
					$href = '#';
					break;
			}
		} else {
			$icon = $this->options['wishlist-archive-icon'] ?? 'fa-regular fa-heart';
			$button_text = $this->options['wishlist-archive-button-text'] ?? __( 'Add To Wishlist', 'shopglut' );
			$button_class = "not-shopgw-added";
			$href = '#';
		}

		if ( $this->options['wishlist-require-login'] == true && ! is_user_logged_in() ) {
			$href = wp_login_url( site_url( $_SERVER['REQUEST_URI'] ) );
			$button_class = "login-required";
			$button_text = __( $this->options['wishlist-require-login-btn-text'], 'shopglut' );
			$icon = $this->options['wishlist-require-login-btn-icon'];
		}

		echo "<div class='shopglut_wishlist_container'>";
		echo "<div class='shopglut_wishlist archive-page'>";
		if ( $this->options['wishlist-archive-option'] === 'only-icon' ) {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
		} elseif ( $this->options['wishlist-archive-option'] === 'button-with-icon' ) {
			$icon_position = $this->options['wishlist-archive-icon-position'] ?? 'text-right';
			if ( $icon_position === 'text-left' ) {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><i class="' . esc_attr( $icon ) . '"></i> <span class="button-text">' . esc_html( $button_text ) . '</span></a>';
			} else {
				echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span> <i class="' . esc_attr( $icon ) . '"></i></a>';
			}
		} else {
			echo '<a href="' . esc_url( $href ) . '" class="button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $button_text ) . '</span></a>';
		}
		echo "</div>";

		if ( $this->options['wishlist-archive-enable-movelist'] === '1' && is_user_logged_in() ) {
			$move_button_text = __( "Move to List", 'shopglut' );
			echo "<div class='shopglut_wishlist_movelist archive-page'>";
			echo '<a href="#" class="button move_to_list" data-product-id="' . esc_attr( $product_id ) . '"><span class="button-text">' . esc_html( $move_button_text ) . '</span></a>';
			echo "</div>";
		}

		echo "</div>";
	}

	public function toggle_wishlist_callback() {
		global $wpdb;

		$user_id = get_current_user_id();
		$product_id = intval( $_POST['product_id'] );
		$is_added = intval( $_POST['is_added'] );
		$guest_id = sanitize_text_field( $_POST['shog_wishlist_guest_id'] );
		$post_type = sanitize_text_field( $_POST['post_type'] );
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		if ( $post_type === 'product' ) {
			$second_click_action = $this->options['wishlist-product-second-click'];
		} else if ( $post_type === 'archive' ) {
			$second_click_action = $this->options['wishlist-archive-second-click'];
		} else if ( $post_type === 'shop' ) {
			$second_click_action = $this->options['wishlist-shop-second-click'];

		}
		$wishlist_page_url = get_permalink( $this->options['wishlist-general-page'] );
		$checkout_page_url = wc_get_checkout_url();

		if ( $is_added ) {

			if ( $post_type === 'product' ) {
				$wishlist_button_text = $this->options['wishlist-product-button-text'];
				$wishlist_icon = $this->options['wishlist-product-icon'];
			} elseif ( $post_type === 'archive' ) {
				$wishlist_button_text = $this->options['wishlist-archive-button-text'];
				$wishlist_icon = $this->options['wishlist-archive-icon'];
			} elseif ( $post_type === 'shop' ) {
				$wishlist_button_text = $this->options['wishlist-shop-button-text'];
				$wishlist_icon = $this->options['wishlist-shop-icon'];
			}

		} else {

			if ( $post_type === 'product' ) {
				$wishlist_added_button_text = $this->options['wishlist-product-button-text-after-added'];
				$wishlist_added_icon = $this->options['wishlist-product-added-icon'];
			} elseif ( $post_type === 'archive' ) {
				$wishlist_added_button_text = $this->options['wishlist-archive-button-text-after-added'];
				$wishlist_added_icon = $this->options['wishlist-archive-added-icon'];
			} elseif ( $post_type === 'shop' ) {
				$wishlist_added_button_text = $this->options['wishlist-shop-button-text-after-added'];
				$wishlist_added_icon = $this->options['wishlist-shop-added-icon'];
			}

		}

		// Debugging Logs
		error_log( "User ID: $user_id, Guest ID: $guest_id, Product ID: $product_id, Is Added: $is_added" );

		if ( $user_id ) {
			// Logged-in User Handling
			$user = get_userdata( $user_id );
			$username = $user->user_login;
			$useremail = $user->user_email;

			if ( $is_added ) {
				// Remove from wishlist for logged-in user
				$product_ids = $wpdb->get_var( $wpdb->prepare( "SELECT product_ids FROM $table_name WHERE wish_user_id = %d", $user_id ) );
				if ( $product_ids ) {
					$product_ids_array = explode( ',', $product_ids );
					$product_ids_array = array_diff( $product_ids_array, [ $product_id ] );
					$updated_product_ids = implode( ',', $product_ids_array );

					$wpdb->update(
						$table_name,
						[ 'product_ids' => $updated_product_ids, 'product_added_time' => current_time( 'mysql' ) ],
						[ 'wish_user_id' => $user_id ]
					);

					switch ( $second_click_action ) {
						case 'goto-wishlist':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['href'] = $wishlist_page_url;
							$response_data['notification_text'] = $this->options['wishlist-product-removed-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'show-already-exist':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['href'] = '';
							$response_data['class'] = 'already-added';
							$response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );
							$response_data['perform_toggle'] = 'already-added'; // No toggle, just show notification
							break;
						case 'redirect-to-checkout':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['href'] = $checkout_page_url;
							$response_data['class'] = 'checkout-link';
							$response_data['notification_text'] = $this->options['wishlist-product-removed-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'remove-wishlist':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['notification_text'] = $this->options['wishlist-product-removed-notification-text'];
							$response_data['perform_toggle'] = true; // Ensure normal toggle behavior
							break;
					}
					wp_send_json_success( $response_data );
					exit;
				} else {
					wp_send_json_error( [ 'message' => 'No products found for removal.' ] );
					exit;
				}
			} else {
				// Add to wishlist for logged-in user
				$existing_entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE wish_user_id = %d", $user_id ) );
				if ( $existing_entry ) {
					// Update existing entry
					$product_ids = $existing_entry->product_ids;
					$product_ids_array = explode( ',', $product_ids );
					if ( ! in_array( $product_id, $product_ids_array ) ) {
						$product_ids_array[] = $product_id;
						$updated_product_ids = implode( ',', $product_ids_array );

						$wpdb->update(
							$table_name,
							[ 'product_ids' => $updated_product_ids, 'product_added_time' => current_time( 'mysql' ) ],
							[ 'wish_user_id' => $user_id ]
						);
					}
					switch ( $second_click_action ) {
						case 'goto-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $wishlist_page_url;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'show-already-exist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['class'] = 'already-added';
							$response_data['perform_toggle'] = 'already-added';
							$response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );// No toggle, just show notification
							break;
						case 'redirect-to-checkout':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $checkout_page_url;
							$response_data['class'] = 'checkout-link';
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'remove-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = true; // Ensure normal toggle behavior
							break;
					}
					wp_send_json_success( $response_data );
					exit;
				} else {
					// Insert new entry for logged-in user
					$wpdb->insert(
						$table_name,
						[ 
							'wish_user_id' => $user_id,
							'username' => $username,
							'useremail' => $useremail,
							'product_ids' => $product_id,
							'wishlist_notifications' => '',
							'product_added_time' => current_time( 'mysql' ),
							'wishlist_sublist' => '',
							'sublist_notifications' => ''
						]
					);
					switch ( $second_click_action ) {
						case 'goto-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $wishlist_page_url;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'show-already-exist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['class'] = 'already-added';
							$response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );
							$response_data['perform_toggle'] = 'already-added';
							break;
						case 'redirect-to-checkout':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $checkout_page_url;
							$response_data['class'] = 'checkout-link';
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'remove-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = true; // Ensure normal toggle behavior
							break;
					}
					wp_send_json_success( $response_data );
					exit;
				}
			}
		} elseif ( $guest_id ) {
			// Guest User Handling
			$existing_entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE wish_user_id = %s", $guest_id ) );
			if ( $is_added ) {
				// Remove from wishlist for guest user
				if ( $existing_entry ) {
					$product_ids = $existing_entry->product_ids;
					$product_ids_array = explode( ',', $product_ids );
					$product_ids_array = array_diff( $product_ids_array, [ $product_id ] );
					$updated_product_ids = implode( ',', $product_ids_array );

					$wpdb->update(
						$table_name,
						[ 'product_ids' => $updated_product_ids, 'product_added_time' => current_time( 'mysql' ) ],
						[ 'wish_user_id' => $guest_id ]
					);
					switch ( $second_click_action ) {
						case 'goto-wishlist':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['href'] = $wishlist_page_url;
							$response_data['notification_text'] = $this->options['wishlist-product-removed-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'show-already-exist':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['class'] = 'already-added';
							$response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );
							$response_data['perform_toggle'] = 'already-added';
							break;
						case 'redirect-to-checkout':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['href'] = $checkout_page_url;
							$response_data['class'] = 'checkout-link';
							$response_data['notification_text'] = $this->options['wishlist-product-removed-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'remove-wishlist':
							$response_data['button_text'] = $wishlist_button_text;
							$response_data['button_icon'] = $wishlist_icon;
							$response_data['notification_text'] = $this->options['wishlist-product-removed-notification-text'];
							$response_data['perform_toggle'] = true; // Ensure normal toggle behavior
							break;
					}
					wp_send_json_success( $response_data );
					exit;
				} else {
					wp_send_json_error( [ 'message' => __( 'No product found for removal.', 'shopglut' ) ] );
					exit;
				}
			} else {
				// Add to wishlist for guest user
				if ( $existing_entry ) {
					// Update existing entry
					$product_ids = $existing_entry->product_ids;
					$product_ids_array = explode( ',', $product_ids );
					if ( ! in_array( $product_id, $product_ids_array ) ) {
						$product_ids_array[] = $product_id;
						$updated_product_ids = implode( ',', $product_ids_array );

						$wpdb->update(
							$table_name,
							[ 'product_ids' => $updated_product_ids, 'product_added_time' => current_time( 'mysql' ) ],
							[ 'wish_user_id' => $guest_id ]
						);
					}
					switch ( $second_click_action ) {
						case 'goto-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $wishlist_page_url;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'show-already-exist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['class'] = 'already-added';
							$response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );
							$response_data['perform_toggle'] = 'already-added';
							break;
						case 'redirect-to-checkout':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $checkout_page_url;
							$response_data['class'] = 'checkout-link';
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'remove-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = true; // Ensure normal toggle behavior
							break;
					}
					wp_send_json_success( $response_data );
					exit;
				} else {
					// Insert new entry for guest user
					$wpdb->insert(
						$table_name,
						[ 
							'wish_user_id' => $guest_id,
							'username' => 'Guest',
							'useremail' => 'guest@example.com',
							'product_ids' => $product_id,
							'product_added_time' => current_time( 'mysql' ),
							'wishlist_sublist' => '',
							'wishlist_notifications' => '',
							'sublist_notifications' => '',
						]
					);
					switch ( $second_click_action ) {
						case 'goto-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $wishlist_page_url;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'show-already-exist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['class'] = 'already-added';
							$response_data['notification_text'] = __( 'Product already added to the wishlist', 'shopglut' );
							$response_data['perform_toggle'] = 'already-added';
							break;
						case 'redirect-to-checkout':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['href'] = $checkout_page_url;
							$response_data['class'] = 'checkout-link';
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = false; // No toggle, just redirect
							break;
						case 'remove-wishlist':
							$response_data['button_text'] = $wishlist_added_button_text;
							$response_data['button_icon'] = $wishlist_added_icon;
							$response_data['notification_text'] = $this->options['wishlist-product-added-notification-text'];
							$response_data['perform_toggle'] = true; // Ensure normal toggle behavior
							break;
					}
					wp_send_json_success( $response_data );
					exit;
				}
			}
		} else {
			wp_send_json_error( [ 'message' => 'User ID and Guest ID are both missing.' ] );
			exit;
		}

		wp_send_json_error( [ 'message' => 'Unexpected error occurred.' ] );
		exit;
	}

	// Handle AJAX to create/update a wishlist sublist
	public function create_wishlist_sublist() {
		$list_name = sanitize_text_field( $_POST['list_name'] );
		$user_id = get_current_user_id();

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		// Check if the user already has a wishlist entry
		$row_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE wish_user_id = %d", $user_id ) );

		if ( $row_exists ) {
			// If a row exists, update the wishlist_sublist
			$current_sublists = $wpdb->get_var( $wpdb->prepare( "SELECT wishlist_sublist FROM $table_name WHERE wish_user_id = %d", $user_id ) );
			$sublists = json_decode( $current_sublists, true ) ?: [];

			// Initialize an empty array for the list name if it doesn't exist
			if ( ! isset( $sublists[ $list_name ] ) ) {
				$sublists[ $list_name ] = [];
			}

			// Update the existing sublist with the new data
			$wpdb->update(
				$table_name,
				[ 'wishlist_sublist' => json_encode( $sublists ), 'product_added_time' => current_time( 'mysql' ) ],
				[ 'wish_user_id' => $user_id ]
			);
		} else {
			// If no row exists, insert a new one with an empty list for the given list name
			$sublists = [ $list_name => [] ];

			$wpdb->insert(
				$table_name,
				[ 
					'wish_user_id' => $user_id,
					'username' => wp_get_current_user()->user_login,
					'useremail' => wp_get_current_user()->user_email,
					'product_ids' => '', // Assuming this is not used
					'product_added_time' => current_time( 'mysql' ),
					'wishlist_sublist' => json_encode( $sublists ),
					'wishlist_notifications' => '',
					'sublist_notifications' => ''
				]
			);
		}

		wp_send_json_success();
	}

	// Handle AJAX to get all wishlist sublists
	public function get_wishlist_sublists() {
		$user_id = get_current_user_id();
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		$sublists = json_decode( $wpdb->get_var( $wpdb->prepare( "SELECT wishlist_sublist FROM $table_name WHERE wish_user_id = %d", $user_id ) ), true ) ?: [];

		// Return the entire sublists array to include both list names and product IDs
		wp_send_json_success( $sublists );
	}

	public function add_product_to_wishlist_sublist() {
		$checked_lists = isset( $_POST['checked_lists'] ) ? (array) $_POST['checked_lists'] : [];
		$unchecked_lists = isset( $_POST['unchecked_lists'] ) ? (array) $_POST['unchecked_lists'] : [];
		$product_id = intval( $_POST['product_id'] );
		$user_id = get_current_user_id();

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		// Retrieve the current sublists for the user
		$current_sublists = json_decode( $wpdb->get_var( $wpdb->prepare( "SELECT wishlist_sublist FROM $table_name WHERE wish_user_id = %d", $user_id ) ), true ) ?: [];

		// Remove the product from all unchecked lists
		foreach ( $unchecked_lists as $list_name ) {
			if ( isset( $current_sublists[ $list_name ] ) ) {
				// Remove product from the list
				$current_sublists[ $list_name ] = array_diff( $current_sublists[ $list_name ], [ $product_id ] );
			}
		}

		// Add the product to all checked lists if not already present
		foreach ( $checked_lists as $list_name ) {
			if ( ! isset( $current_sublists[ $list_name ] ) ) {
				$current_sublists[ $list_name ] = [];
			}
			if ( ! in_array( $product_id, $current_sublists[ $list_name ] ) ) {
				$current_sublists[ $list_name ][] = $product_id;
			}
		}

		// Update the wishlist in the database
		$wpdb->update(
			$table_name,
			[ 'wishlist_sublist' => json_encode( $current_sublists ), 'product_added_time' => current_time( 'mysql' ) ],
			[ 'wish_user_id' => $user_id ]
		);

		wp_send_json_success();
	}

	public function delete_wishlist_sublist() {
		$list_name = sanitize_text_field( $_POST['list_name'] );
		$user_id = get_current_user_id();

		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		// Fetch the current wishlist sublist
		$current_sublists = $wpdb->get_var( $wpdb->prepare( "SELECT wishlist_sublist FROM $table_name WHERE wish_user_id = %d", $user_id ) );
		$sublists = json_decode( $current_sublists, true ) ?: [];

		// Remove the specified list if it exists
		if ( isset( $sublists[ $list_name ] ) ) {
			unset( $sublists[ $list_name ] );

			// Update the database with the modified sublist
			$wpdb->update(
				$table_name,
				[ 'wishlist_sublist' => json_encode( $sublists ), 'product_added_time' => current_time( 'mysql' ) ],
				[ 'wish_user_id' => $user_id ]
			);

			wp_send_json_success();
		} else {
			wp_send_json_error( [ 'message' => 'List not found.' ] );
		}
	}

	public function add_subwishlist_modal() {
		?>
		<div id="shopgMovetoListModal">

			<div id="shopgMovetoListContainer">

				<button onclick="jQuery('#shopgMovetoListModal, #shopgMovetoListModalOverlay').hide();"
					style="margin-top:10px;">
					<i class="fa-solid fa-circle-xmark"></i>
				</button>

				<h3><?php echo esc_html__( 'Create or Add to Wishlist', 'shopglut' ); ?></h3>

				<label for="listName"><?php echo esc_html__( 'List Name:', 'shopglut' ); ?></label>
				<div style="display:flex; align-items:center;">
					<input type="text" id="listName"
						placeholder="<?php echo esc_attr__( 'Enter new list name', 'shopglut' ); ?>"
						style="flex:1; margin-right:5px; padding:5px;">
					<button id="createListBtn"><?php echo esc_html__( 'Create', 'shopglut' ); ?></button>
				</div>

				<div id="checkboxList" style="margin-top:15px;">
				</div>

				<div id="addToListContainer">
					<div id="successMessage"
						style="display:none; color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 10px; margin-bottom: 15px; border: 1px solid transparent; border-radius: .25rem;">
						<?php echo esc_html__( 'Product successfully updated to the list.', 'shopglut' ); ?>
					</div>
					<button id="addToListBtn" style="margin-top:15px;">
						<?php echo esc_html__( 'Add or Update to List', 'shopglut' ); ?>
					</button>
				</div>

				<?php if ( isset( $this->options['wishlist-general-page'] ) && $this->options['wishlist-general-page'] ) : ?>
					<div id="goToWishlistContainer">
						<a href="<?php echo esc_url( get_permalink( $this->options['wishlist-general-page'] ) ); ?>">
							<button id="goToWishlistBtn"><?php echo esc_html__( 'Go to Wishlist', 'shopglut' ); ?></button>
						</a>
					</div>
				<?php endif; ?>

			</div>

		</div>

		<?php
	}

	public function add_notification_modal() {

		$user_id = get_current_user_id();

		?>
		<div id="shopglutw-subscribe-notification-modal" style="display:none;">
			<div class="modal-content">
				<span class="close-modal">&times;</span>
				<h3><?php _e( 'Subscribe to Notifications', 'shopglut' ); ?></h3>
				<form id="notification-form">
					<input type="hidden" name="wishlist_type">
					<input type="hidden" name="list_name">
					<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">

					<label><input type="checkbox" name="notifications[]" value="product_update">
						<?php _e( 'Product Update', 'shopglut' ); ?></label><br>
					<label><input type="checkbox" name="notifications[]" value="in_stock">
						<?php _e( 'Product In Stock', 'shopglut' ); ?></label><br>
					<label><input type="checkbox" name="notifications[]" value="out_of_stock">
						<?php _e( 'Product Out of Stock', 'shopglut' ); ?></label><br>
					<label><input type="checkbox" name="notifications[]" value="price_drop">
						<?php _e( 'Price Drop', 'shopglut' ); ?></label><br>
					<label><input type="checkbox" name="notifications[]" value="low_stock">
						<?php _e( 'Low Stock Warning', 'shopglut' ); ?></label><br>

					<button type="submit"><?php _e( 'Subscribe/Unsubscribe to Email Notifications', 'shopglut' ); ?></button>
				</form>
			</div>
		</div>
		<?php
	}


	public function get_user_notifications() {
		global $wpdb;
		$user_id = intval( $_POST['user_id'] );
		$wishlist_type = sanitize_text_field( $_POST['wishlist_type'] );
		$list_name = sanitize_text_field( $_POST['list_name'] );
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		if ( $wishlist_type === 'main' ) {
			$result = $wpdb->get_var( $wpdb->prepare( "SELECT wishlist_notifications FROM $table_name WHERE wish_user_id = %d", $user_id ) );
			$notifications = json_decode( $result, true );
		} else {
			$result = $wpdb->get_var( $wpdb->prepare( "SELECT sublist_notifications FROM $table_name WHERE wish_user_id = %d", $user_id ) );
			$notifications = json_decode( $result, true );
			$notifications = $notifications[ $list_name ] ?? [];
		}

		wp_send_json_success( [ 'notifications' => $notifications, $result ] );
	}

	private function generate_cron_token() {
		return bin2hex( random_bytes( 16 ) );
	}

	public function register_email_cron_endpoint() {
		add_rewrite_rule( '^send-shopglut-wishlist-emails/?$', 'index.php?send_wishlist_emails=1', 'top' );
	}

	public function handle_email_cron() {
		if ( get_query_var( 'send_wishlist_emails', false ) && isset( $_GET['cronkey'] ) ) {
			if ( $_GET['cronkey'] === $this->cron_token ) {
				$this->schedule_email_events();
				echo 'Emails scheduled successfully.';
			} else {
				echo 'Invalid token.';
			}
			exit;
		}
	}

	public function schedule_email_events() {
		// Only schedule if send_email is set to 'yes'
		if ( $this->options['wishlist-email-mail']['send_email'] !== 'yes' ) {
			return;
		}

		$wishlist_items = $this->get_wishlist_items();
		foreach ( $wishlist_items as $item ) {
			if ( $item->user_email === 'guest@example.com' ) {
				continue; // Skip guest emails
			}

			$time_value = (int) $this->options['wishlist-email-mail']['time_value'];
			$time_unit = $this->options['wishlist-email-mail']['time_unit'];
			$time_in_seconds = $time_value * ( $time_unit === 'minute' ? 60 : ( $time_unit === 'hour' ? 3600 : 86400 ) );
			$scheduled_time = strtotime( $item->time_added ) + $time_in_seconds;

			// Schedule an email using WordPress Cron as a backup
			if ( ! wp_next_scheduled( 'send_wishlist_email_event', [ $item->user_email ] ) ) {
				wp_schedule_single_event( $scheduled_time, 'send_wishlist_email_event', [ $item->user_email ] );
			}

			// For server-side cron, call the email function directly
			if ( current_time( 'timestamp' ) >= $scheduled_time ) {
				$this->send_email_to_user( $item->user_email );
			}
		}
	}

	public function send_scheduled_wishlist_emails( $user_email = '' ) {
		if ( $user_email ) {
			$this->send_email_to_user( $user_email );
		} else {
			// Handle cases where server-side cron should process all emails
			$wishlist_items = $this->get_wishlist_items();
			foreach ( $wishlist_items as $item ) {
				if ( $item->user_email === 'guest@example.com' ) {
					continue; // Skip guest emails
				}

				$time_value = (int) $this->options['wishlist-email-mail']['time_value'];
				$time_unit = $this->options['wishlist-email-mail']['time_unit'];
				$time_in_seconds = $time_value * ( $time_unit === 'minute' ? 60 : ( $time_unit === 'hour' ? 3600 : 86400 ) );
				$scheduled_time = strtotime( $item->time_added ) + $time_in_seconds;

				if ( current_time( 'timestamp' ) >= $scheduled_time ) {
					$this->send_email_to_user( $item->user_email );
				}
			}
		}
	}

	public function send_email_to_user( $user_email ) {
		// Skip sending emails to guest@example.com
		if ( $user_email === 'guest@example.com' ) {
			return;
		}

		// Fetch the email configuration from the options
		$from_name = sanitize_text_field( $this->options['wishlist-email-from-name'] );
		$from_address = sanitize_email( $this->options['wishlist-email-from-address'] );
		$email_body = wp_kses_post( $this->options['wishlist-email-body'] );

		// Use the helper function to get product titles by email
		$product_titles = $this->get_wishlist_product_titles_by_email( $user_email );

		// Append the product titles to the email body
		if ( ! empty( $product_titles ) ) {
			$product_list = implode( ', ', $product_titles );
			$email_body .= '<br><br>' . __( 'Your wishlist added items are:', 'shopglut' ) . ' ' . $product_list;
		} else {
			$email_body .= '<br><br>' . __( 'No items in your wishlist.', 'shopglut' );
		}

		// Set email headers
		$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . $from_name . ' <' . $from_address . '>' );

		// Send the email using wp_mail
		wp_mail( $user_email, $from_name, $email_body, $headers );
	}



	public function get_wishlist_product_titles_by_email( $user_email ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		// Get the wishlist entry for the specific user email
		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE useremail = %s", $user_email ) );

		if ( $user ) {
			// Extract product IDs from the main product_ids field
			$product_ids = array_filter( explode( ',', $user->product_ids ) );

			// Decode wishlist_sublist JSON, check if valid array, and merge with main product IDs
			$wishlist_sublist = json_decode( $user->wishlist_sublist, true );
			if ( is_array( $wishlist_sublist ) ) {
				foreach ( $wishlist_sublist as $sublist => $sublist_ids ) {
					$product_ids = array_merge( $product_ids, $sublist_ids );
				}
			}

			// Remove duplicates and fetch product titles
			$product_ids = array_unique( $product_ids );
			$product_titles = [];
			foreach ( $product_ids as $product_id ) {
				$product_title = get_the_title( $product_id );
				if ( $product_title ) {
					$product_titles[] = $product_title;
				}
			}

			return $product_titles;
		}

		return [];
	}
	public function get_wishlist_items() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'shopglut_wishlist';

		// Query to select all wishlist items based on the latest product added time
		$results = $wpdb->get_results(
			"SELECT wish_user_id, useremail, product_added_time 
         FROM $table_name 
         WHERE product_added_time IS NOT NULL",
			OBJECT
		);

		// Process and return wishlist items as an array of objects
		$wishlist_items = [];
		foreach ( $results as $row ) {
			$wishlist_items[] = (object) [ 
				'user_email' => $row->useremail,
				'time_added' => $row->product_added_time,
			];
		}

		return $wishlist_items;
	}

	public static function get_instance() {
		static $instance;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}