<?php

require_once BLOCK_WOO_ORDERS_BASE_DIR . "includes/abstracts/class-block-woo-orders-abstract-entry.php";

class Block_Woo_Orders_Email extends Block_Woo_Orders_Abstract_Entry {

	public function __construct( $id = null ) {

		parent::__construct( $id, "email" );

		// if id is not null, then find the object in the database?
		if ( ! empty( $id ) ) {
			$this->search_by_id( $id );
			$this->id = $id;
		}
	}

	public function set_name( $name ) {
		$email = filter_var( $name, FILTER_VALIDATE_EMAIL );
		if ( $email ) {
			$this->name = $email;
		} else {
			throw new Exception( "$name is not a valid email" );
		}
	}
}