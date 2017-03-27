<?php
class Bet Extends Object_Model {
	
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
			FROM " . TABLES__BETS . "  
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
		Sql::sql_query(sprintf("INSERT INTO " . TABLES__BETS . " (datetime, user_id, match_id, opponent_id, opponent_odds, stake) VALUES(NOW(), %d, %d, %d, '%s', '%s')",
		$this->user_id, $this->match_id, $this->opponent_id, $this->opponent_odds, $this->stake, $this->statut));
	}
	
	public function edit() {
		Sql::sql_query(sprintf("UPDATE " . TABLES__BETS . " SET statut = %d WHERE id = %d", 
		$this->statut, $this->id));
	}
	
}
?>