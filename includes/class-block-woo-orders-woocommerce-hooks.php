<?php

/**
 * All the functions related to Woocommerce hooks
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/admin
 * @author     Jenny Chan <jenny@jennychan.dev>
 */
class Block_Woo_Orders_Woocommerce_Hooks {

	public function scan_orders_for_fraud( $order_id, $posted_data, $order ) {
		global $wpdb;

		// fetch data
		$app_user_id_table = $wpdb->prefix . 'bwo_app_user_ids';
		$email_table       = $wpdb->prefix . 'bwo_emails';

		$app_user_id = "1234";
		$email       = $order->get_billing_email();

		$app_user_id_query = $wpdb->prepare( "SELECT * FROM $app_user_id_table WHERE app_user_id = %s", $app_user_id );
		$app_user_id_row   = $wpdb->get_row( $app_user_id_query, ARRAY_A );

		$email_query = $wpdb->prepare( "SELECT * FROM $email_table WHERE email = %s", $email );
		$email_row   = $wpdb->get_row( $email_query, ARRAY_A );

		$is_verified     = false;
		$is_blocked      = false;
		$review_required = false;

		$rows_to_check = array(
			'app_user_id' => $app_user_id_row,
			'email'       => $email_row
		);

		$scan_result = [];

		foreach ( $rows_to_check as $key => $row ) {
			if ( ! empty( $row ) ) {
				if ( $row['flag'] === "verified" ) {
					$is_verified = true;
				} else if ( $row['flag'] === "review" ) {
					$review_required = true;
				} else if ( $row['flag'] === "blocked" ) {
					$is_blocked = true;
				}

				$scan_result[ $key ] = [
					'flag'  => $row['flag'],
					'notes' => $row['notes']
				];
			}
		}

		if ( $is_blocked ) {
			$order->update_status( 'blocked' );
		} else if ( $review_required && ! $is_verified ) {
			$order->update_status( 'review-required' );
		} else if ( $is_verified ) {
			$order->update_status( 'verified' );
		}

		if ( ! empty( $scan_result ) ) {
			update_post_meta( $order_id, 'scan_result', json_encode( $scan_result ) );
		}
	}
}