<?php
class Bet Extends Object_Model {

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
			$item = $this->sql->get_row_from_query(sprintf("SELECT *
			FROM " . TABLES__BETS . "  
			WHERE id = %d", $this->sql->ecape($object_or_array_or_id)));
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
		$this->sql->sql_query(sprintf("INSERT INTO " . TABLES__BETS . " (datetime, user_id, match_id, opponent_id, odds_id, stake, statut) VALUES(NOW(), %d, %d, %d, %d, '%s', %d)",
		$this->user_id, $this->match_id, $this->opponent_id, $this->odds_id, $this->stake, $this->statut));
	}
	
	public function edit() {
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__BETS . " SET statut = %d WHERE id = %d", 
		$this->statut, $this->id));
	}
	
	public function getLatestOddsIdFromMatchAndOpponent() {
		$odds_id = $this->sql->get_value_from_query(sprintf("SELECT id FROM " . TABLES__ODDS . " WHERE match_id = %d AND opponent_id = %d AND datetime < '%s'", $this->match_id, $this->opponent_id, $this->datetime));

		return $odds_id;
	}
	
	public function set_noticed() {
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__BETS . " SET noticed = 1 WHERE id = %d", $this->sql->ecape($this->id)));
	}
	
}
?>