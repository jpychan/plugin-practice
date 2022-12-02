<?php
/**
 * Creates the listing page for the plugin.
 *
 * Provides the functionality necessary for rendering the page corresponding
 * to the submenu with which this page is associated.
 * @package Dd_Fraud_Prevention
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Block_Woo_Orders_Listing_Table extends WP_List_Table {

	/**
	 * Initialize the table.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'entry',
				'plural'   => 'entries',
				'ajax'     => false,
			)
		);
	}

	/**
	 * @return string
	 */
	private function get_entry_type() {
		$type = str_replace( 'bwo_', "", $_GET['page'] );

		return $type;
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$screen        = get_current_screen();
		$type          = $this->get_entry_type();
		$table_columns = array(
			'entry'      => __( ucfirst( $type ), 'block-woo-orders' ),
			'flag'       => __( 'Flag', 'block-woo-orders' ),
			'notes'      => __( 'Notes', 'block-woo-orders' ),
			'created_at' => __( 'Created At', 'block-woo-orders' ),
			'updated_at' => __( 'Updated At', 'block-woo-orders' ),
		);

		return $table_columns;
	}

	/**
	 * Default columns
	 * @param $item
	 * @param $column_name
	 *
	 * @return mixed|void
	 */
	public function column_default( $item, $column_name ) {
		$type = $this->get_entry_type();
		switch ( $column_name ) {
			case $type:
			case 'flag':
			case 'notes':
			case 'ID':
				return $item[ $column_name ];
			default:
				return $item[ $column_name ];
		}
	}

	/**
	 * Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
	 *
	 * @return void
	 */
	public function prepare_items() {

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
//		$this->column_headers = $this->get_column_info();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		// check and process any actions such as bulk actions.
		$this->handle_table_actions();

		// fetch the table data
		$data = $this->fetch_table_data();

		// start by assigning your data to the items variable
		$this->items = $data['results'];

		// code to handle pagination
		$entries_per_page = $this->get_items_per_page( 'entries_per_page' );

		// set the pagination arguments
		$this->set_pagination_args( array(
			'total_items' => $data['count'],
			'per_page'    => $entries_per_page,
			'total_pages' => ceil( $data['count'] / $entries_per_page )
		) );
	}

	/**
	 * Get data from database
	 *
	 * @return array
	 */
	private function fetch_table_data() {

		$type = $this->get_entry_type();
		$flag = ( isset( $_REQUEST['flag'] ) ? $_REQUEST['flag'] : 'all' );

		global $wpdb;

		$table_name = $wpdb->prefix . 'bwo_' . $type . "s";
		$orderby    = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'id';
		$order      = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';

		$current_page     = $this->get_pagenum();
		$entries_per_page = $this->get_items_per_page( 'entries_per_page' );

		if ( $current_page > 1 ) {
			$offset = $entries_per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		$search = '';

		if ( ! empty( $_REQUEST['s'] ) ) {
			$search = "AND $type LIKE '%" . esc_sql( $wpdb->esc_like( wc_clean( wp_unslash( $_REQUEST['s'] ) ) ) ) . "%' ";
		}

		$sql = "SELECT ID, $type, flag, notes, created_at, updated_at FROM $table_name WHERE ";
		if ( $flag !== "all" ) {
			$sql .= $wpdb->prepare( "flag = %s AND", $flag );
		}
		$sql .= " 1 = 1 {$search} ORDER BY $orderby $order";
		$sql .= $wpdb->prepare( " LIMIT %d OFFSET %d;", $entries_per_page, $offset );

		$query_results = $wpdb->get_results( $sql, ARRAY_A );

		$count_sql = "SELECT COUNT(id) FROM $table_name WHERE ";
		if ( $flag !== "all" ) {
			$count_sql .= $wpdb->prepare( "flag = %s AND", $flag );
		}

		$count_sql .= " 1 = 1 {$search};";
		$count     = $wpdb->get_var( $count_sql );

		$data = array( 'results' => $query_results, 'count' => $count );

		return $data;
	}

	/**
	 * Handle table functions (Edit Entry, Delete Entry)
	 *
	 * @return void
	 */
	public function handle_table_actions() {
		$the_table_action = $this->current_action();

		if ( 'delete_entry' === $the_table_action ) {
			$nonce = wp_unslash( $_REQUEST['_wpnonce'] );
			// verify the nonce.
			if ( ! wp_verify_nonce( $nonce, 'delete_entry' ) ) {
				$this->invalid_nonce_redirect();
				exit();
			} else {
				$deleted = $this->delete_entry( absint( $_REQUEST['id'] ), $_REQUEST['type'] );
				$url     = admin_url( 'admin.php?page=bwo_' . $_REQUEST['type'] );
				wp_safe_redirect( add_query_arg( 'deleted', $deleted, $url ) );
				exit();
			}
		}

		if ( 'edit_entry' === $the_table_action ) {
			$nonce = wp_unslash( $_REQUEST['_wpnonce'] );
			// verify the nonce.
			if ( ! wp_verify_nonce( $nonce, 'edit_entry' ) ) {
				$this->invalid_nonce_redirect();
				exit();
			} else {
				$this->page_view_edit_entry( absint( $_REQUEST['id'] ), $_REQUEST['type'] );
				exit();
			}
		}
	}

	/**
	 * When no items are found.
	 *
	 * @return void
	 */
	public function no_items() {
		_e( 'No entries found.', 'block-woo-orders' );
	}

	/**
	 * Define sortable columns
	 *
	 * @return array[]
	 */
	protected function get_sortable_columns() {
		$type             = $this->get_entry_type();
		$sortable_columns = array(
			'entry'      => array( $type, false ),
			'flag'       => array( 'flag', false ),
			'created_at' => array( 'created_at', false ),
			'updated_at' => array( 'updated_at', false ),
		);

		return $sortable_columns;
	}

	/**
	 * Column to display each email or app user id
	 * @param $item
	 *
	 * @return string
	 */
	protected function column_entry( $item ) {
		$type = $this->get_entry_type();

		$admin_page_url = admin_url( 'admin.php?page=bwo_' . $type );

		$query_args_edit_entry = array(
			'page'     => wp_unslash( $_REQUEST['page'] ),
			'action'   => 'edit_entry',
			'type'     => $type,
			'id'       => absint( $item['ID'] ),
			'_wpnonce' => wp_create_nonce( 'edit_entry' ),
		);

		$edit_entry_link = esc_url( add_query_arg( $query_args_edit_entry, $admin_page_url ) );

		$actions['edit_entry'] = '<a href="' . $edit_entry_link . '">' . __( 'Edit Entry', 'block-woo-orders' ) . '</a>';

		$query_args_delete_entry = array(
			'page'     => wp_unslash( $_REQUEST['page'] ),
			'action'   => 'delete_entry',
			'type'     => $type,
			'id'       => absint( $item['ID'] ),
			'_wpnonce' => wp_create_nonce( 'delete_entry' ),
		);

		$delete_entry_link       = esc_url( add_query_arg( $query_args_delete_entry, $admin_page_url ) );
		$actions['delete_entry'] = '<span class="trash"><a href="' . $delete_entry_link . '">' . __( 'Delete', 'block-woo-orders' ) . '</a></span>';

		$row_value = sanitize_text_field( $item[ $type ] );

		return $row_value . $this->row_actions( $actions );
	}

	/**
	 * @param $item
	 *
	 * @return string
	 */
	protected function column_notes( $item ) {
		$row_value = stripslashes( $item['notes'] );

		return $row_value;
	}

	/**
	 *
	 * @param $id
	 * @param $type
	 *
	 * @return bool|int|mysqli_result|resource|void|null
	 */
	private function delete_entry( $id, $type ) {
		if ( $type === "email" ) {
			$entry = new Block_Woo_Orders_Email( $id );

			return $entry->delete();
		} else if ( $type === "app_user_id" ) {
			$entry = new Block_Woo_Orders_App_User_Id( $id );

			return $entry->delete();
		}
	}

	/**
	 *
	 * @return void
	 */
	private function invalid_nonce_redirect() {
		wp_die( __( 'Invalid Nonce', 'block-woo-orders' ),
			__( 'Error', 'block-woo-orders' ),
			array(
				'response'  => 403,
				'back_link' => esc_url( add_query_arg( array( 'page' => wp_unslash( $_REQUEST['page'] ) ), admin_url( 'admin.php?page=bwo_' . $_REQUEST['type'] ) ) ),
			)
		);
	}

	/**
	 * @param $id
	 * @param $type
	 *
	 * @return void
	 */
	private function page_view_edit_entry( $id, $type ) {
		$entry = $this->get_entry( $id, $type );
		include_once( 'partials/block-woo-orders-add-entry-display.php' );
	}

	/**
	 * Get the entry by ID
	 * 
	 * @param $id
	 * @param $type
	 *
	 * @return Block_Woo_Orders_App_User_Id|Block_Woo_Orders_Email|void
	 */
	private function get_entry( $id, $type ) {
		if ( $type === "email" ) {
			$email = new Block_Woo_Orders_Email( $id );

			return $email;
		} else if ( $type === "app_user_id" ) {
			$app_user_id = new Block_Woo_Orders_App_User_Id( $id );

			return $app_user_id;
		}
	}

	/**
	 * Define status filters in the listing table
	 *
	 * @return array
	 */
	protected function get_views() {
		global $wpdb;

		$views   = array();
		$current = ( ! empty( $_REQUEST['flag'] ) ? $_REQUEST['flag'] : 'all' );

		$type  = $this->get_entry_type();
		$table = $wpdb->prefix . 'bwo_' . $type . "s";
		$total = 0;

		$flags = [
			'blocked'  => [
				'label' => 'Blocked',
				'count' => 0,
			],
			'review'   => [
				'label' => 'Review Required',
				'count' => 0,
			],
			'verified' => [
				'label' => 'Verified',
				'count' => 0,
			],
		];

		//All link
		$views['all'] = "";

		foreach ( $flags as $key => $flag_arr ) {
			$sql               = $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE flag = %s", $key );
			$flag_arr['count'] = $wpdb->get_var( $sql );
			$total             += $flag_arr['count'];
			$url               = add_query_arg( 'flag', $key );
			$class             = ( $current == $key ? ' class="current"' : '' );
			$views[ $key ]     = "<a href='{$url}' {$class} >{$flag_arr['label']}<span class='count'>({$flag_arr['count']})</span></a>";
		}

		$class        = ( $current == 'all' ? ' class="current"' : '' );
		$all_url      = remove_query_arg( 'flag' );
		$views['all'] = "<a href='{$all_url }' {$class} >All <span class='count'>($total)</span></a>";

		return $views;
	}
}