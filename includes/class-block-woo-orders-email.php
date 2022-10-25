<?php

require_once BLOCK_WOO_ORDERS_BASE_DIR . "includes/abstracts/class-block-woo-orders-abstract-entry.php";

class Block_Woo_Orders_Email extends Block_Woo_Orders_Abstract_Entry {

	protected $id;

	protected $name;

	protected $flag;

	protected $type;

	protected $created_at;

	protected $updated_at;

	public function __construct( $id = null ) {

		// if id is not null, then find the object in the database?
		if ($id) {
			$this->id = $id;
			$this->search_by_id($id);
		}

		parent::__construct( $id, "email" );
	}

	public function search_by_id($id) {
		global $wpdb;
		$table_name = $wpdb->prefix . "bwo_emails";
		$sql = $wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $id);
		$result = $wpdb->get_row($sql, 'ARRAY_A');
		if (!empty($result)) {
			$this->name = $result['email'];
			$this->flag = $result['flag'];
			$this->created_at = $result['created_at'];
			$this->updated_at = $result['updated_at'];
		}
	}

	public function save() {
		if ( $this->get_id() ) {
			return $this->update();
		} else {
			$id = $this->create();
			if ($id > 0) {
				$this->set_id($id);
			}
			return $id;
		}
	}

	public function create() {
		// sql to insert the entry
		global $wpdb;
		$sql = $wpdb->prepare(
		"INSERT INTO " . $this->get_table_name(). "(email, flag, notes, created_at, updated_at) VALUES (%s, %s, %s, NOW(), NOW())",
		$this->get_name(), $this->get_flag(), $this->get_notes());
		return $wpdb->query($sql);
	}

	public function update() {
		global $wpdb;
		$sql = $wpdb->prepare(
			"UPDATE ". $this->get_table_name() . " SET 
			email = %s,
			flag = %s,
			notes = %s,
			updated_at = NOW()
			WHERE id = %d",
			$this->get_name(), $this->get_flag(), $this->get_notes(), $this->get_id());
		return $wpdb->query($sql);
	}
}