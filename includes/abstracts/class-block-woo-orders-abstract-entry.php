<?php

defined( 'ABSPATH' ) || exit;

abstract class Block_Woo_Orders_Abstract_Entry {

	/**
	 * @var      int $id The ID of the entry
	 */
	protected $id;

	/**
	 * The name of the entry
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $name The name of the entry
	 */
	protected $name;

	/**
	 * The flag of the entry (blocked, verified, review)
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $flag The flag of the entry
	 */
	protected $flag;

	/**
	 * The notes of the entry
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $notes The notes of the entry
	 */
	protected $notes;

	/**
	 * The type of the entry
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $type The type of the entry (email or app_user_id)
	 */
	protected $type;

	/**
	 * The time of creation for the entry
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      datetime $type The time of creation for the entry
	 */
	protected $created_at;

	/**
	 * The time of last update for the entry
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      datetime $type The time of last update for the entry
	 */
	protected $updated_at;

	/**
	 * The database table name associated with the entry type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $type The database table name associated with the entry type
	 */
	private $table_name;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @param int $id The name of the entry.
	 * @param type $type The version of the entry.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $id, $type ) {
		$this->id   = $id;
		$this->type = $type;
	}

	/**
	 * Get the ID
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get the flag
	 *
	 * @return string
	 */
	public function get_flag() {
		return $this->flag;
	}

	/**
	 * Get the notes
	 *
	 * @return string
	 */
	public function get_notes() {
		return $this->notes;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Get creation time
	 *
	 * @return datetime
	 */
	public function get_created_at() {
		return $this->created_at;
	}

	/**
	 * Get last updated time
	 *
	 * @return datetime
	 */
	public function get_updated_at() {
		return $this->updated_at;
	}

	/**
	 * Get database table name
	 *
	 * @return string
	 */
	public function get_table_name() {
		if ( empty( $this->table_name ) ) {
			$this->set_table_name();
		}

		return $this->table_name;
	}

	/**
	 * Set the ID
	 *
	 * @param int $id
	 */
	protected function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * Set the name
	 *
	 * @param string $name
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Set the flag
	 *
	 * @param string $flag
	 */
	public function set_flag( $flag ) {
		$this->flag = $flag;
	}

	/**
	 * Set the notes
	 *
	 * @param string $notes
	 */
	public function set_notes( $notes ) {
		$this->notes = $notes;
	}

	/**
	 * Set table name based on type
	 */
	public function set_table_name() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . "bwo_" . $this->get_type() . "s";
	}

	/**
	 * Save the entry in the database
	 * @return bool|int|mysqli_result|resource|null
	 */
	public function save() {
		if ( $this->get_id() ) {
			return $this->update();
		} else {
			$id = $this->create();
			if ( $id > 0 ) {
				$this->set_id( $id );
			}

			return $id;
		}
	}

	/**
	 * Create the entry in the database.
	 *
	 * @return bool|int|mysqli_result|resource|null
	 */
	public function create() {
		// sql to insert the entry
		global $wpdb;
		$sql = $wpdb->prepare(
			"INSERT INTO " . $this->get_table_name() . "({$this->get_type()}, flag, notes, created_at, updated_at) VALUES (%s, %s, %s, NOW(), NOW())",
			$this->get_name(), $this->get_flag(), $this->get_notes() );

		return $wpdb->query( $sql );
	}

	/**
	 * Update the entry in the database
	 *
	 * @return bool|int|mysqli_result|resource|null
	 */
	public function update() {
		global $wpdb;
		$sql = $wpdb->prepare(
			"UPDATE " . $this->get_table_name() . " SET 
			{$this->get_type()} = %s,
			flag = %s,
			notes = %s,
			updated_at = NOW()
			WHERE id = %d",
			$this->get_name(), $this->get_flag(), $this->get_notes(), $this->get_id() );

		return $wpdb->query( $sql );
	}

	/**
	 * Delete the entry from the database
	 *
	 * @return bool|int|mysqli_result|resource|void|null
	 */
	public function delete() {
		if ( $this->get_id() ) {
			global $wpdb;

			return $wpdb->delete( $this->get_table_name(), array( 'id' => $this->get_id() ), array( '%d' ) );
		}
	}

	/**
	 * Search the entry by ID and set the other properties if found
	 *
	 * @param $id
	 */
	protected function search_by_id( $id ) {
		global $wpdb;
		$sql    = $wpdb->prepare( "SELECT * FROM {$this->get_table_name()} WHERE id = %d", $id );
		$result = $wpdb->get_row( $sql, 'ARRAY_A' );
		if ( ! empty( $result ) ) {
			$this->name       = $result[ $this->get_type() ];
			$this->flag       = $result['flag'];
			$this->created_at = $result['created_at'];
			$this->updated_at = $result['updated_at'];
		}
	}
}
