<?php

/**
 * Fired during plugin activation
 *
 * @link       https://jennychan.dev
 * @since      1.0.0
 *
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/includes
 * @author     Jenny Chan <jenny@jennychan.dev>
 */
class Block_Woo_Orders_Activator {

	/**
	 * Create custom tables to save app user ids and emails to check
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;

		$charset_collate   = $wpdb->get_charset_collate();
		$app_user_id_table = $wpdb->prefix . 'wbo_app_user_ids';

		$app_user_id_sql = "CREATE TABLE $app_user_id_table (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			app_user_id varchar(60) NOT NULL,
			flag varchar(10) NOT NULL,
			notes text,
			created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			updated_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY id (id),
			UNIQUE (app_user_id)
		) $charset_collate;";

		dbDelta( $app_user_id_sql );

		$email_table = $wpdb->prefix . 'wbo_emails';

		$email_sql = "CREATE TABLE $email_table (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			email varchar(100) NOT NULL,
			flag varchar(10) NOT NULL,
			notes text,
			created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY id (id),
			UNIQUE (email)
		) $charset_collate;";

		dbDelta( $email_sql );
	}

}
