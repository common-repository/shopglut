<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Set a unique slug-like ID
$AGSHOPGLUT_WISHLIST_OPTIONS = 'agshopglut_wishlist_options';

//

// Create Woo options
AGSHOPGLUT::createOptions( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	// menu settings
	'menu_title' => esc_html__( 'Wishlist Options', 'shopglut' ),
	'menu_slug' => 'shopglut_wishlist_settings',
	'menu_parent' => 'shopglut_layouts',
	'menu_type' => 'submenu',
	'menu_capability' => 'manage_options',
	'framework_title' => esc_html__( 'Wishlist Options', 'shopglut' ),
	'show_reset_section' => true,
	'shortcode_option' => '[shopglut_wishlist]',
	'framework_class' => 'shopglut_wishlist_settings',
	'footer_credit' => __( "ShopGlut (WIshlist)", 'shopglut' ),
) );

//
// Create a top-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'id' => 'primary_tab', // Set a unique slug-like ID
	'title' => __( 'Settings', 'shopglut' ),
	'icon' => 'fa fa-cog',
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab', // The slug id of the parent section
	'title' => __( 'General', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-require-login',
			'type' => 'switcher',
			'title' => __( 'Require Login', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'wishlist-require-login-btn-text',
			'type' => 'text',
			'title' => __( 'Require Login Button Text', 'shopglut' ),
			'default' => 'Wishlist Require Login',
			'dependency' => array( 'wishlist-require-login', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-require-login-btn-icon',
			'type' => 'icon',
			'title' => __( 'Require Login Button Icon', 'shopglut' ),
			'default' => 'fa-solid fa-lock',
			'dependency' => array( 'wishlist-require-login', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-merge-guestlist',
			'type' => 'switcher',
			'title' => __( 'Merge Guest Wishlist After Login', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'wishlist-require-login', '==', 'false' ),
		),

		array(
			'id' => 'wishlist-enable-wishlist-subscription',
			'type' => 'switcher',
			'title' => __( 'Enable Wishlist Product Subscription', 'shopglut' ),
			'subtitle' => __( 'Subscriptio works with logged users', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-enable-multilist-tabs',
			'type' => 'switcher',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Enable MultiList Tabs', 'shopglut' ),
			'subtitle' => __( 'Multi List works with logged users', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'wishlist-general-page',
			'type' => 'select',
			'title' => esc_html__( 'Wishlist Page', 'shopglut' ),
			'options' => 'pages',
			'query_args' => array(
				'posts_per_page' => -1, // for get all pages (also it's same for posts).
			),
		),

		array(
			'id' => 'wishlist-general-notification',
			'type' => 'select',
			'title' => __( 'Wishlist after Added Notification', 'shopglut' ),
			'options' => array(
				'notification-off' => __( 'Notification Off', 'shopglut' ),
				'side-notification' => __( 'Browser Side Notification', 'shopglut' ),
				'popup-notification' => __( 'Popup Notification', 'shopglut' ),
			),
			'default' => 'notification-off',
		),

		array(
			'id' => 'wishlist-side-notification-appear',
			'type' => 'select',
			'title' => __( 'Side Notification Appear', 'shopglut' ),
			'options' => array(
				'top-left' => __( 'From Top Left', 'shopglut' ),
				'top-middle' => __( 'From Top Middle', 'shopglut' ),
				'top-right' => __( 'From Top Right', 'shopglut' ),
				'middle-left' => __( 'From Middle Left', 'shopglut' ),
				'middle-right' => __( 'From Middle Right', 'shopglut' ),
				'bottom-left' => __( 'From Bottom Left', 'shopglut' ),
				'bottom-middle' => __( 'From Bottom Middle', 'shopglut' ),
				'bottom-right' => __( 'From Bottom Right', 'shopglut' ),
			),
			'default' => 'bottom-right',
			'dependency' => array( 'wishlist-general-notification', '==', 'side-notification' ),
		),

		array(
			'id' => 'wishlist-side-notification-effect',
			'type' => 'select',
			'title' => __( 'Side Notification Effect', 'shopglut' ),
			'options' => array(
				'fade-in-out' => __( 'Fade In/Out', 'shopglut' ),
				'slide-down-up' => __( 'Slide Down/Up', 'shopglut' ),
				'slide-from-left' => __( 'Slide from Left', 'shopglut' ),
				'slide-from-right' => __( 'Slide from Right', 'shopglut' ),
				'bounce' => __( 'Bounce', 'shopglut' ),
			),
			'default' => 'fade-in-out',
			'dependency' => array( 'wishlist-general-notification', '==', 'side-notification' ),
		),
		array(
			'id' => 'wishlist-popup-notification-effect',
			'type' => 'select',
			'title' => __( 'PopUp Notification Effect', 'shopglut' ),
			'options' => array(
				'fade-in-out' => __( 'Fade In/Out', 'shopglut' ),
				'zoom-in' => __( 'Zoom In', 'shopglut' ),
				'bounce' => __( 'Bounce', 'shopglut' ),
				'shake' => __( 'Shake', 'shopglut' ),
				'drop-in' => __( 'Drop In from Top', 'shopglut' ),
			),
			'default' => 'fade-in-out',
			'dependency' => array( 'wishlist-general-notification', '==', 'popup-notification' ),
		),

		array(
			'id' => 'wishlist-product-added-notification-text',
			'type' => 'text',
			'title' => __( 'Product Added Notification Text', 'shopglut' ),
			'default' => __( 'Product Added to Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-general-notification', 'any', 'side-notification,popup-notification' ),
		),
		array(
			'id' => 'wishlist-product-removed-notification-text',
			'type' => 'text',
			'title' => __( 'Product Removed Notification Text', 'shopglut' ),
			'default' => __( 'Product Removed from Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-general-notification', 'any', 'side-notification,popup-notification' ),
		),

		array(
			'id' => 'wishlist-general-outofstock',
			'type' => 'switcher',
			'title' => __( 'Hide Wishlist for Out of Stock', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-page-account-page',
			'type' => 'switcher',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Enable Wishlist Page in Account Page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 0,
		),

		array(
			'id' => 'wishlist-page-account-page-name',
			'type' => 'text',
			'title' => __( 'Account Page Name', 'shopglut' ),
			'desc' => __( 'After change Save again Settings > Permalinks', 'shopglut' ),
			'default' => __( 'My Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-page-account-page', '==', '1' ),

		),

	) ) );

$wishlist_page_options = array(
	'product-image',
	'product-name',
	'product-price',
	'product-quantity',
	'product-availability',
	'product-short-description',
	'product-sku',
	'product-add-to-cart',
	'product-checkout',
);

$attribute_taxonomies = wc_get_attribute_taxonomies();

// Loop through attributes and add them to filter options
if ( ! empty( $attribute_taxonomies ) ) {
	foreach ( $attribute_taxonomies as $attribute ) {
		if ( isset( $attribute->attribute_name ) ) {
			$attribute_name = $attribute->attribute_name;
			$wishlist_page_options[] = $attribute_name;
		}
	}
}

// Create fields array dynamically based on wishlist page options
$wishlist_page_fields = array();

foreach ( $wishlist_page_options as $option ) {
	$wishlist_page_fields[] = array(
		'id' => 'wishlist-page-show-' . str_replace( '_', '-', $option ),
		'type' => 'switcher',
		'title' => __( 'Show ' . ucwords( str_replace( '-', ' ', $option ) ), 'shopglut' ),
		'text_on' => __( 'Yes', 'shopglut' ),
		'text_off' => __( 'No', 'shopglut' ),
		'default' => 1,
	);
}

$wishlist_page_fields[] = array(
	'id' => 'wishlist-remove-if-add-to-cart',
	'type' => 'switcher',
	'title' => __( 'Remove Wishlist if added to Cart', 'shopglut' ),
	'text_on' => __( 'Yes', 'shopglut' ),
	'text_off' => __( 'No', 'shopglut' ),
	'default' => 0,
);

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Wishlist Page', 'shopglut' ),
	'fields' => $wishlist_page_fields,
) );

$wishlist_account_page_options = array(
	'product-image',
	'product-name',
	'product-price',
	'product-quantity',
	'product-availability',
	'product-short-description',
	'product-sku',
	'product-add-to-cart',
	'product-checkout',
);

// Loop through attributes and add them to filter options
if ( ! empty( $attribute_taxonomies ) ) {
	foreach ( $attribute_taxonomies as $attribute ) {
		if ( isset( $attribute->attribute_name ) ) {
			$attribute_name = $attribute->attribute_name;
			$wishlist_account_page_options[] = $attribute_name;
		}
	}
}

// Create fields array dynamically based on wishlist page options
$wishlist_account_page_fields = array();

foreach ( $wishlist_account_page_options as $option ) {
	$wishlist_account_page_fields[] = array(
		'id' => 'wishlist-account-page-show-' . str_replace( '_', '-', $option ),
		'type' => 'switcher',
		'title' => __( 'Show ' . ucwords( str_replace( '-', ' ', $option ) ), 'shopglut' ),
		'text_on' => __( 'Yes', 'shopglut' ),
		'text_off' => __( 'No', 'shopglut' ),
		'default' => 1,
	);
}

$wishlist_account_page_fields[] = array(
	'id' => 'wishlist-account-remove-if-add-to-cart',
	'type' => 'switcher',
	'title' => __( 'Remove Wishlist if added to Cart', 'shopglut' ),
	'text_on' => __( 'Yes', 'shopglut' ),
	'text_off' => __( 'No', 'shopglut' ),
	'default' => 0,
);

AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Account Page', 'shopglut' ),
	'fields' => $wishlist_account_page_fields,
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Product Page', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-enable-product-page',
			'type' => 'switcher',
			'title' => __( 'Enable Wishlist for Product Page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-product-second-click',
			'type' => 'select',
			'title' => __( 'After Added Click Action', 'shopglut' ),
			'options' => array(
				'remove-wishlist' => __( 'Remove From Wishlist', 'shopglut' ),
				'goto-wishlist' => __( 'Goto Wishlist Page', 'shopglut' ),
				'show-already-exist' => __( 'Show Already Product Added', 'shopglut' ),
				'redirect-to-checkout' => __( 'Redirect to Checkout Page', 'shopglut' ),
			),
			'default' => 'remove-wishlist',
			'dependency' => array( 'wishlist-enable-product-page', '==', 'true' ),

		),

		array(
			'id' => 'wishlist-product-position',
			'type' => 'select',
			'title' => __( 'Select Wishlist Position', 'shopglut' ),
			'options' => array(
				'after-cart' => __( 'After Add To Cart Button', 'shopglut' ),
				'before-cart' => __( 'Before Add To Cart Button', 'shopglut' ),
				'after-product-meta' => __( 'After Product Meta', 'shopglut' ),
			),
			'default' => 'after-cart',
			'dependency' => array( 'wishlist-enable-product-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-product-enable-movelist',
			'type' => 'switcher',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Enable MoveList Button', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'wishlist-enable-product-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-product-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist Option', 'shopglut' ),
			'options' => array(
				'button-with-icon' => __( 'Button Text With Icon', 'shopglut' ),
				'only-button' => __( 'Button Text Only', 'shopglut' ),
				'only-icon' => __( 'Icon Only', 'shopglut' ),
			),
			'default' => 'button-with-icon',
			'dependency' => array( 'wishlist-enable-product-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-product-button-text',
			'type' => 'text',
			'title' => __( 'Button Text', 'shopglut' ),
			'default' => __( 'Add To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-product-button-text-after-added',
			'type' => 'text',
			'title' => __( 'Button Text After Added', 'shopglut' ),
			'default' => __( 'Added To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-product-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Icon', 'shopglut' ),
			'default' => 'fa-regular fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-product-page', '==', 'true' ),
				array( 'wishlist-product-option', 'any', 'button-with-icon,only-icon' ),
			),
		),
		array(
			'id' => 'wishlist-product-added-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Added Icon', 'shopglut' ),
			'default' => 'fa fa-heart',
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|any', 'true|button-with-icon,only-icon' ),
		),

		array(
			'id' => 'wishlist-product-icon-position',
			'type' => 'button_set',
			'title' => __( 'Icon Position', 'shopglut' ),
			'options' => array(
				'text-left' => __( 'Text Left', 'shopglut' ),
				'text-right' => __( 'Text Right', 'shopglut' ),
			),
			'default' => 'text-right',
			'dependency' => array( 'wishlist-enable-product-page|wishlist-product-option', '==|==', 'true|button-with-icon' ),
		),

	),
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Shop Page', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-enable-shop-page',
			'type' => 'switcher',
			'title' => __( 'Enable Wishlist for Shop Page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-shop-second-click',
			'type' => 'select',
			'title' => __( 'After Added Click Action', 'shopglut' ),
			'options' => array(
				'remove-wishlist' => __( 'Remove From Wishlist', 'shopglut' ),
				'goto-wishlist' => __( 'Goto Wishlist Page', 'shopglut' ),
				'show-already-exist' => __( 'Show Already Product Added', 'shopglut' ),
				'redirect-to-checkout' => __( 'Redirect to Checkout Page', 'shopglut' ),
			),
			'default' => 'remove-wishlist',
			'dependency' => array( 'wishlist-enable-shop-page', '==', 'true' ),

		),

		array(
			'id' => 'wishlist-shop-position',
			'type' => 'select',
			'title' => __( 'Select Wishlist Position', 'shopglut' ),
			'options' => array(
				'after-cart' => __( 'After Add To Cart Button', 'shopglut' ),
				'before-cart' => __( 'Before Add To Cart Button', 'shopglut' ),
				'after-product-meta' => __( 'After Product Meta', 'shopglut' ),
			),
			'default' => 'after-cart',
			'dependency' => array( 'wishlist-enable-shop-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-shop-enable-movelist',
			'type' => 'switcher',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Enable MoveList Button', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'wishlist-enable-shop-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-shop-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist Option', 'shopglut' ),
			'options' => array(
				'button-with-icon' => __( 'Button Text With Icon', 'shopglut' ),
				'only-button' => __( 'Button Text Only', 'shopglut' ),
				'only-icon' => __( 'Icon Only', 'shopglut' ),
			),
			'default' => 'button-with-icon',
			'dependency' => array( 'wishlist-enable-shop-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-shop-button-text',
			'type' => 'text',
			'title' => __( 'Button Text', 'shopglut' ),
			'default' => __( 'Add To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-shop-page|wishlist-shop-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-shop-button-text-after-added',
			'type' => 'text',
			'title' => __( 'Button Text After Added', 'shopglut' ),
			'default' => __( 'Added To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-shop-page|wishlist-shop-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-shop-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Icon', 'shopglut' ),
			'default' => 'fa-regular fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-shop-page', '==', 'true' ),
				array( 'wishlist-shop-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-shop-added-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Added Icon', 'shopglut' ),
			'default' => 'fa fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-shop-page', '==', 'true' ),
				array( 'wishlist-shop-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-shop-icon-position',
			'type' => 'button_set',
			'title' => __( 'Icon Position', 'shopglut' ),
			'options' => array(
				'text-left' => __( 'Text Left', 'shopglut' ),
				'text-right' => __( 'Text Right', 'shopglut' ),
			),
			'default' => 'text-right',
			'dependency' => array( 'wishlist-enable-shop-page|wishlist-shop-option', '==|==', 'true|button-with-icon' ),
		),

	),
) );

// Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'primary_tab',
	'title' => __( 'Archive Page', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-enable-archive-page',
			'type' => 'switcher',
			'title' => __( 'Enable Wishlist for Archive Page', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
		),

		array(
			'id' => 'wishlist-archive-second-click',
			'type' => 'select',
			'title' => __( 'After Added Click Action', 'shopglut' ),
			'options' => array(
				'remove-wishlist' => __( 'Remove From Wishlist', 'shopglut' ),
				'goto-wishlist' => __( 'Goto Wishlist Page', 'shopglut' ),
				'show-already-exist' => __( 'Show Already Product Added', 'shopglut' ),
				'redirect-to-checkout' => __( 'Redirect to Checkout Page', 'shopglut' ),
			),
			'default' => 'remove-wishlist',
			'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-archive-position',
			'type' => 'select',
			'title' => __( 'Select Wishlist Position', 'shopglut' ),
			'options' => array(
				'after-cart' => __( 'After Add To Cart Button', 'shopglut' ),
				'before-cart' => __( 'Before Add To Cart Button', 'shopglut' ),
				'after-product-meta' => __( 'After Product Meta', 'shopglut' ),
			),
			'default' => 'after-cart',
			'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-archive-enable-movelist',
			'type' => 'switcher',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Enable MoveList Button', 'shopglut' ),
			'text_on' => __( 'Yes', 'shopglut' ),
			'text_off' => __( 'No', 'shopglut' ),
			'default' => 1,
			'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-archive-select-cat-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist to Show', 'shopglut' ),
			'options' => array(
				'all-categories' => __( 'All Categories & Tags', 'shopglut' ),
				'select-category' => __( 'Select Category', 'shopglut' ),
				'select-tag' => __( 'Select Tag', 'shopglut' ),
			),
			'default' => 'all-categories',
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
			),
		),

		array(
			'id' => 'wishlist-archive-select-category',
			'type' => 'select',
			'title' => esc_html__( 'Select Categories', 'shopglut' ),
			'chosen' => true,
			'multiple' => true,
			'placeholder' => esc_html__( 'Choose Category', 'shopglut' ),
			'options' => 'categories',
			'query_args' => array(
				'taxonomy' => 'product_cat',
			),
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-select-cat-option', '==', 'select-category' ),
			),
		),

		array(
			'id' => 'wishlist-archive-select-tag',
			'type' => 'select',
			'title' => esc_html__( 'Select Tags', 'shopglut' ),
			'chosen' => true,
			'multiple' => true,
			'placeholder' => esc_html__( 'Choose Tag', 'shopglut' ),
			'options' => 'categories',
			'query_args' => array(
				'taxonomy' => 'product_tag',
			),
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-select-cat-option', '==', 'select-tag' ),
			),
		),

		array(
			'id' => 'wishlist-archive-option',
			'type' => 'button_set',
			'title' => __( 'Wishlist Option', 'shopglut' ),
			'options' => array(
				'button-with-icon' => __( 'Button Text With Icon', 'shopglut' ),
				'only-button' => __( 'Button Text Only', 'shopglut' ),
				'only-icon' => __( 'Icon Only', 'shopglut' ),
			),
			'default' => 'button-with-icon',
			'dependency' => array( 'wishlist-enable-archive-page', '==', 'true' ),
		),

		array(
			'id' => 'wishlist-archive-button-text',
			'type' => 'text',
			'title' => __( 'Button Text', 'shopglut' ),
			'default' => __( 'Add To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-archive-page|wishlist-archive-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-archive-button-text-after-added',
			'type' => 'text',
			'title' => __( 'Button Text After Added', 'shopglut' ),
			'default' => __( 'Added To Wishlist', 'shopglut' ),
			'dependency' => array( 'wishlist-enable-archive-page|wishlist-archive-option', '==|any', 'true|button-with-icon,only-button' ),
		),

		array(
			'id' => 'wishlist-archive-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Icon', 'shopglut' ),
			'default' => 'fa-regular fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-archive-added-icon',
			'type' => 'icon',
			'title' => __( 'Wishlist Added Icon', 'shopglut' ),
			'default' => 'fa fa-heart',
			'dependency' => array(
				array( 'wishlist-enable-archive-page', '==', 'true' ),
				array( 'wishlist-archive-option', 'any', 'button-with-icon,only-icon' ),
			),
		),

		array(
			'id' => 'wishlist-archive-icon-position',
			'type' => 'button_set',
			'title' => __( 'Icon Position', 'shopglut' ),
			'options' => array(
				'text-left' => __( 'Text Left', 'shopglut' ),
				'text-right' => __( 'Text Right', 'shopglut' ),
			),
			'default' => 'text-right',
			'dependency' => array( 'wishlist-enable-archive-page|wishlist-archive-option', '==|==', 'true|button-with-icon' ),
		),

	),
) );

// Create a top-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'id' => 'secondry_tab', // Set a unique slug-like ID
	'icon' => 'fa fa-palette', // Set a unique slug-like ID
	'title' => __( 'Appearance', 'shopglut' ),
) );

// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'General', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-locked-background',
			'type' => 'color',
			'title' => __( 'Wishlist Locked Background', 'shopglut' ),
			'default' => '#dd3333',
		),

		array(
			'id' => 'wishlist-locked-font-color',
			'type' => 'color',
			'title' => __( 'Wishlist Locked Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-locked-icon-color',
			'type' => 'color',
			'title' => __( 'Wishlist Locked Icon Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-locked-icon-color',
			'type' => 'color',
			'title' => __( 'Wishlist Locked Icon Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-notification-added-bg-color',
			'type' => 'color',
			'title' => __( 'Notification Button Color(Added)', 'shopglut' ),
			'default' => 'rgba(45,206,24,0.68)',
		),

		array(
			'id' => 'wishlist-notification-removed-bg-color',
			'type' => 'color',
			'title' => __( 'Notification Button Color(Removed)', 'shopglut' ),
			'default' => 'rgba(221,8,8,0.68)',
		),

		array(
			'id' => 'wishlist-notification-font-color',
			'type' => 'color',
			'title' => __( 'Notification Font Color', 'shopglut' ),
			'default' => '#fff',
		),

	),
) );

// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Wishlist Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-page-list-tab-color',
			'type' => 'color',
			'title' => __( 'MultiList Tab Color', 'shopglut' ),
			'default' => '#a3a3a3',
		),

		array(
			'id' => 'wishlist-page-list-tab-font-color',
			'type' => 'color',
			'title' => __( 'MultiList Tab Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-list-active-tab-color',
			'type' => 'color',
			'title' => __( 'MultiList Active Tab Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-list-active-tab-font-color',
			'type' => 'color',
			'title' => __( 'MultiList Active Tab Font Color', 'shopglut' ),
			'default' => '#000',
		),

		array(
			'id' => 'wishlist-page-table-header-color',
			'type' => 'color',
			'title' => __( 'Table Head Background Color', 'shopglut' ),
			'default' => '#a3a3a3',
		),

		array(
			'id' => 'wishlist-page-table-head-font-color',
			'type' => 'color',
			'title' => __( 'Table Head Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-subscription-btn-color',
			'type' => 'color',
			'title' => __( 'Subscribe Button Color', 'shopglut' ),
			'default' => '#0073aa',
		),

		array(
			'id' => 'wishlist-page-subscription-btn-font-color',
			'type' => 'color',
			'title' => __( 'Subscribe Button Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-body-color-choice',
			'type' => 'select',
			'title' => __( 'Body Color Option', 'shopglut' ),
			'options' => array(
				'body-same-color' => __( 'Body Same Color', 'shopglut' ),
				'body-oddeven-color' => __( 'Body Odd Even Color', 'shopglut' ),
			),
			'default' => 'body-same-color',
		),

		array(
			'id' => 'wishlist-page-body-color',
			'type' => 'color',
			'title' => __( 'Table Body Color', 'shopglut' ),
			'default' => '#fff',
			'dependency' => array( 'wishlist-page-body-color-choice', '==', 'body-same-color' ),
		),

		array(
			'id' => 'wishlist-page-body-hover-color',
			'type' => 'color',
			'title' => __( 'Table Body Hover Color', 'shopglut' ),
			'default' => '#f1f1f1',
		),
		array(
			'id' => 'wishlist-page-body-odd-color',
			'type' => 'color',
			'title' => __( 'Body Odd Row Color', 'shopglut' ),
			'default' => '#fff',
			'dependency' => array( 'wishlist-page-body-color-choice', '==', 'body-oddeven-color' ),
		),

		array(
			'id' => 'wishlist-page-body-even-color',
			'type' => 'color',
			'title' => __( 'Body Even Row Color', 'shopglut' ),
			'default' => '#fff',
			'dependency' => array( 'wishlist-page-body-color-choice', '==', 'body-oddeven-color' ),
		),

		array(
			'id' => 'wishlist-page-table-head-font-color',
			'type' => 'color',
			'title' => __( 'Table Head Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-page-table-body-font-color',
			'type' => 'color',
			'title' => __( 'Table Body Font Color', 'shopglut' ),
			'default' => '#000',
		),

		array(
			'id' => 'wishlist-page-addtocart-button-color',
			'type' => 'color',
			'title' => __( 'Add to Cart Button Color', 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-page-addtocart-button-font-color',
			'type' => 'color',
			'title' => __( 'Add to Cart Button Font Color', 'shopglut' ),
			'default' => '#000',
		),
		array(
			'id' => 'wishlist-page-checkout-button-color',
			'type' => 'color',
			'title' => __( 'Button Checkout Color', 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-page-checkout-button-font-color',
			'type' => 'color',
			'title' => __( 'Button Checkout Font Color', 'shopglut' ),
			'default' => '#fff',
		),

	),
) );
// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Product Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-product-button-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Color", 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-product-button-font-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Font Color", 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-product-wishlist-button-width',
			'type' => 'dimensions',
			'title' => __( 'Wishlist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '125',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-button-padding',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-button-margin',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-icon-color',
			'type' => 'color',
			'title' => __( "Wishlist Icon Color", 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-product-move-button-color',
			'type' => 'color',
			'title' => __( 'Move List Button Color', 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-product-move-button-font-color',
			'type' => 'color',
			'title' => __( 'Move List Button Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-product-movelist-button-width',
			'type' => 'dimensions',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Movelist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '125',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-move-button-padding',
			'type' => 'spacing',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( "Move List Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-product-move-button-margin',
			'type' => 'spacing',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( "Move List Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => '0',
			),
		),

	),
) );
// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Shop Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-shop-button-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Color", 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-shop-button-text-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Font Color", 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-shop-wishlist-button-width',
			'type' => 'dimensions',
			'title' => __( 'Wishlist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '125',
				'unit' => 'px',
			),
		),

		array(
			'id' => 'wishlist-shop-button-padding',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-shop-button-margin',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-shop-icon-color',
			'type' => 'color',
			'title' => __( "Wishlist Icon Color", 'shopglut' ),
			'default' => '#fff',
		),
		array(
			'id' => 'wishlist-shop-move-button-color',
			'type' => 'color',
			'title' => __( 'Move Button Color', 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-shop-move-button-font-color',
			'type' => 'color',
			'title' => __( 'Move Button Font Color', 'shopglut' ),
			'default' => '#fff',
		),
		array(
			'id' => 'wishlist-shop-movelist-button-width',
			'type' => 'dimensions',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Movelist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '125',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-shop-move-button-padding',
			'type' => 'spacing',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( "Move List Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-shop-move-button-margin',
			'type' => 'spacing',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( "Move List Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),
	),
) );

// // Create a sub-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'secondry_tab', // The slug id of the parent section
	'title' => __( 'Archive Page Style', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-archive-button-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Color", 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-archive-button-text-color',
			'type' => 'color',
			'title' => __( "Wishlist Button Font Color", 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-archive-wishlist-button-width',
			'type' => 'dimensions',
			'title' => __( 'Wishlist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '125',
				'unit' => 'px',
			),
		),


		array(
			'id' => 'wishlist-archive-button-padding',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-archive-button-margin',
			'type' => 'spacing',
			'title' => __( "Wishlist Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-archive-icon-color',
			'type' => 'color',
			'title' => __( "Wishlist Icon Color", 'shopglut' ),
			'default' => '#fff',
		),
		array(
			'id' => 'wishlist-archive-move-button-color',
			'type' => 'color',
			'title' => __( 'Move Button Color', 'shopglut' ),
			'default' => '#0073aa',
		),
		array(
			'id' => 'wishlist-archive-move-button-font-color',
			'type' => 'color',
			'title' => __( 'Move Button Font Color', 'shopglut' ),
			'default' => '#fff',
		),

		array(
			'id' => 'wishlist-archive-movelist-button-width',
			'type' => 'dimensions',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Movelist Button Width', 'shopglut' ),
			'height' => false,
			'default' => array(
				'width' => '125',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-archive-move-button-padding',
			'type' => 'spacing',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( "Move List Button Padding", 'shopglut' ),
			'default' => array(
				'top' => '15',
				'right' => '20',
				'bottom' => '15',
				'left' => '20',
				'unit' => 'px',
			),
		),
		array(
			'id' => 'wishlist-archive-move-button-margin',
			'type' => 'spacing',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( "Move List Button Margin", 'shopglut' ),
			'default' => array(
				'top' => '0',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
			),
		),
	),
) );

// Create a top-tab
AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'id' => 'third_tab', // Set a unique slug-like ID
	'icon' => 'fa-solid fa-envelope', // Set a unique slug-like ID
	'title' => __( 'Email Marketing', 'shopglut' ),
) );

AGSHOPGLUT::createSection( $AGSHOPGLUT_WISHLIST_OPTIONS, array(
	'parent' => 'third_tab', // The slug id of the parent section
	'title' => __( 'Manage Emailing', 'shopglut' ),
	'fields' => array(

		array(
			'id' => 'wishlist-email-from-name',
			'type' => 'text',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'From Name', 'shopglut' ),
			'default' => __( 'your name', 'shopglut' ),
		),
		array(
			'id' => 'wishlist-email-from-address',
			'type' => 'text',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'From Address', 'shopglut' ),
			'desc' => __( 'It may sometime not work due to SMTP server settings', 'shopglut' ),
			'validate' => 'agl_validate_email',
			'default' => __( 'email@email.com', 'shopglut' ),
		),
		array(
			'id' => 'wishlist-email-body',
			'type' => 'wp_editor',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Email Body', 'shopglut' ),
		),
		array(
			'id' => 'wishlist-email-mail',
			'type' => 'wishlistMail',
			'pro' => 'https://www.appglut.com/plugin/shopglut',
			'title' => __( 'Wishlist Mail', 'shopglut' ),
		),

	),
) );