<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jennychan.dev
 * @since      1.0.0
 *
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/admin
 * @author     Jenny Chan <jenny@jennychan.dev>
 */
class Block_Woo_Orders_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Block_Woo_Orders_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Block_Woo_Orders_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/block-woo-orders-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Block_Woo_Orders_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Block_Woo_Orders_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/block-woo-orders-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_admin_menu() {
		add_menu_page(
			__( 'Block Woo Orders', 'block-woo-orders' ),
			'Block Woo Orders',
			'manage_options',
			'block-woo-orders',
			array( $this, 'admin_page_display' ),
			'dashicons-shield',
			60
		);

		add_submenu_page(
			'block-woo-orders',
			'Block Woo Orders',
			'Home',
			'manage_options',
			'block-woo-orders'
		);

		add_submenu_page(
			'block-woo-orders',
			'Block Woo Orders Settings',
			'Settings',
			'manage_options',
			'settings',
			array( $this, 'admin_page_display' )
		);
	}

	public function admin_page_display() {
		include 'partials/block-woo-orders-admin-display.php';
	}
}
