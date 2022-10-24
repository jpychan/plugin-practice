<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jennychan.dev
 * @since             1.0.0
 * @package           Block_Woo_Orders
 *
 * @wordpress-plugin
 * Plugin Name:       Block Woo Orders
 * Plugin URI:        https://jennychan.dev
 * Description:       Plugin to check billing emails to see if a Woocommerce order should be blocked or flagged for review
 * Version:           1.0.0
 * Author:            Jenny Chan
 * Author URI:        https://jennychan.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       block-woo-orders
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
defined( 'BLOCK_WOO_ORDERS_VERSION' ) or define( 'BLOCK_WOO_ORDERS_VERSION', '1.0.0' );

/**
 * Plugin Name
 * Start at version 1.0.0
 */
defined( 'BLOCK_WOO_ORDERS_PLUGIN_NAME' ) or define( 'BLOCK_WOO_ORDERS_PLUGIN_NAME', 'block-woo-orders' );

/**
 * Plugin Base FILE
 * Start at version 1.0.0
 */
defined( 'BLOCK_WOO_ORDERS_BASE_FILE' ) or define( 'BLOCK_WOO_ORDERS_BASE_FILE', __FILE__ );

/**
 * Plugin base dir path.
 * used to locate plugin resources primarily code files
 * Start at version 1.0.0
 */
defined( 'BLOCK_WOO_ORDERS_BASE_DIR' ) or define( 'BLOCK_WOO_ORDERS_BASE_DIR', plugin_dir_path( __FILE__ ) );


/**
 * Plugin url to access its resources through browser
 * used to access assets images/css/js files
 * Start at version 1.0.0
 */
defined( 'BLOCK_WOO_ORDERS_PLUGIN_URL' ) or define( 'BLOCK_WOO_ORDERS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-block-woo-orders-activator.php
 */
function activate_block_woo_orders() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-block-woo-orders-activator.php';
	Block_Woo_Orders_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-block-woo-orders-deactivator.php
 */
function deactivate_block_woo_orders() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-block-woo-orders-deactivator.php';
	Block_Woo_Orders_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_block_woo_orders' );
register_deactivation_hook( __FILE__, 'deactivate_block_woo_orders' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-block-woo-orders.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_block_woo_orders() {

	$plugin = new Block_Woo_Orders();
	$plugin->run();

}
run_block_woo_orders();
