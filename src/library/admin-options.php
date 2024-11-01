<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Set a unique slug-like ID
$AGSHOPGLUT_WOO_OPTIONS = 'agshopglut_woo_options';

//
// Create Woo options
AGSHOPGLUT::createOptions( $AGSHOPGLUT_WOO_OPTIONS, array(
	// menu settings
	'menu_title' => esc_html__( 'Settings', 'shopglut' ),
	'menu_slug' => 'shopglut_settings',
	'menu_parent' => 'shopglut_layoutss',
	'menu_type' => 'submenu',
	'menu_capability' => 'manage_options',
	'framework_title' => esc_html__( 'Shopglut Settings', 'shopglut' ),
	'framework_class' => 'shopglut_settings', // The type of the database save options. `serialize` or `unserialize`
) );

// Create a section
AGSHOPGLUT::createSection( $AGSHOPGLUT_WOO_OPTIONS, array(
	'fields' => array(

		array(
			'id' => 'show_search_field_filter',
			'type' => 'checkbox',
			'title' => __( 'Show Search in Filter', 'shopglut' ),
			'label' => __( 'Yes', 'shopglut' ),
			'default' => true, // or false
		),

		array(
			'id' => 'notification_stock_back',
			'type' => 'checkbox',
			'title' => __( 'Notification for Back in Stock and On Sale', 'shopglut' ),
			'label' => __( 'Yes', 'shopglut' ),
			'default' => true, // or false
		),

	) ) );