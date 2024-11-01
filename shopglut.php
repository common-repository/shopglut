<?php

/*
 * Plugin Name: ShopGlut 
 * Description: Woocommerce customized options plugin
 * Version: 1.0.2
 * Author: AppGlut
 * Author URI: https://appglut.com
 * Plugin URI: https://wordpress.org/plugins/shopglut/
 * License: GPLv2 or later
 * Text Domain: shopglut
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

defined( 'ABSPATH' ) or die;

define( 'SHOPGLUT_NAME', 'Shopglut' );
define( 'SHOPGLUT_VERSION', '1.0.2' );
define( 'SHOPGLUT_BASENAME', plugin_basename( __FILE__ ) );
define( 'SHOPGLUT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHOPGLUT_URL', plugin_dir_url( __FILE__ ) );
define( 'SHOPGLUT_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'SHOPGLUT_SLUG', dirname( plugin_basename( __FILE__ ) ) );

require __DIR__ . '/autoloader.php';

Shopglut\Base::get_instance();