<?php
class Comment Extends Object_Model {

    private $sql;

	public function __construct($object_or_array_or_id = '') {

        global $sql;
        $this->sql = $sql;

		global $lang;

		if(is_object($object_or_array_or_id)) {
			$item = $object_or_array_or_id;
		}
		if(is_array($object_or_array_or_id)) {
			$item = $object_or_array_or_id;
		}
		elseif(is_numeric($object_or_array_or_id)) {
			$item = $this->sql->get_row_from_query(sprintf("SELECT c.*, DATE_FORMAT(c.datetime, '%M %D %Y %l:%i%p') AS datetime_formatted
			FROM " . TABLES__COMMENTS . " c
			WHERE id = %d", $this->sql->escape($object_or_array_or_id)));
		}
		
		if(!empty($item)) {
			$this->hydrate($item);
		}
		
		if($this->user_id != 0) {
			$this->user = new User($this->user_id);
		}
	}
	
	public function hydrate($array) {
		foreach($array as $key => $value) {
			$this->$key = $value;
		}
	}
	
	public function __get($property) {
		if(isset($this->$property)) {
			return $this->$property;
		}
	}
	public function __set($property, $value) {
		$this->$property = $value;
	}
	
	public function create() {
		$this->sql->sql_query(sprintf("INSERT INTO " . TABLES__COMMENTS . " (datetime, match_id, user_id, content, statut) VALUES(NOW(), %d, %d, '%s', %d)",
		$this->match_id, $this->user_id, $this->content, $this->statut));
		
		$this->id = $this->sql->last_insert_id();
	}
	
	public function edit() {
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__COMMENTS . " SET content = '%s', statut = %d WHERE id = %d", 
		$this->content, $this->statut, $this->id));
	}
	
	public function delete() {
		$this->sql->sql_query(sprintf("DELETE FROM " . TABLES__COMMENTS . " WHERE id = %d", $this->id));
	}
	
}
?>