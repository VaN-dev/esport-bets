<?php
class Odds Extends Object_Model {
	
	public function __construct($object_or_array_or_id = '') {
		
		global $lang;

		if(is_object($object_or_array_or_id)) {
			$item = $object_or_array_or_id;
		}
		if(is_array($object_or_array_or_id)) {
			$item = $object_or_array_or_id;
		}
		elseif(is_numeric($object_or_array_or_id)) {
			$item = Sql::get_row_from_query(sprintf("SELECT *
			FROM " . TABLES__ODDS . "  
			WHERE id = %d", mysql_real_escape_string($object_or_array_or_id)));
		}
		
		if(!empty($item)) {
			$this->hydrate($item);
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
		Sql::sql_query(sprintf("INSERT INTO " . TABLES__ODDS . " (datetime, match_id, opponent_id, value) VALUES(NOW(), %d, %d, '%s')",
		$this->match_id, $this->opponent_id, $this->value));
	}
	
}
?>