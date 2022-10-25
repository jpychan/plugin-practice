<?php

defined( 'ABSPATH' ) || exit;

abstract class Block_Woo_Orders_Abstract_Entry {

	protected $id;

	protected $name;

	protected $flag;

	protected $notes;

	protected $type;

	protected $created_at;

	protected $updated_at;

	private $table_name;

	public function __construct( $id, $type ) {
		$this->id   = $id;
		$this->type = $type;
	}

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_flag() {
		return $this->flag;
	}

	public function get_notes() {
		return $this->notes;
	}

	public function get_type() {
		return $this->type;
	}

	public function get_created_at() {
		return $this->created_at;
	}

	public function get_updated_at() {
		return $this->updated_at;
	}

	protected function set_id( $id ) {
		$this->id = $id;
	}

	public function set_name( $name ) {
		$this->name = $name;
	}

	public function set_flag( $flag ) {
		$this->flag = $flag;
	}

	public function set_notes( $notes ) {
		$this->notes = $notes;
	}

	protected function get_table_name() {
		if ( empty( $this->table_name ) ) {
			$this->set_table_name();
		}

		return $this->table_name;
	}

	private function set_table_name() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . "bwo_" . $this->get_type() . "s";
	}

	public function save() {
		if ( $this->get_id() ) {
			return $this->update();
		} else {
			return $this->create();
		}
	}

	public function list() {

	}

	public function create() {
		// sql to insert the entry
	}

	public function update() {
		// sql to update the entry
	}

	public function delete() {
		if ( $this->get_id() ) {
			global $wpdb;

			return $wpdb->delete( $this->get_table_name(), array( 'id' => $this->get_id() ), array( '%d' ) );
		}
	}
}
