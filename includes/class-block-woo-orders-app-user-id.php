<?php

require_once BLOCK_WOO_ORDERS_BASE_DIR . "includes/abstracts/class-block-woo-orders-abstract-entry.php";

class Block_Woo_Orders_App_User_Id extends Block_Woo_Orders_Abstract_Entry {

	public function __construct( $id = null ) {

		parent::__construct( $id, "app_user_id" );

		// if id is not null, then find the object in the database?
		if ( ! empty( $id ) ) {
			$this->search_by_id( $id );
			$this->id = $id;
		}
	}
}
