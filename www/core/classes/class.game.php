<?php
class Game Extends Object_Model {

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
			FROM " . TABLES__GAMES . "  
			WHERE id = %d", $this->sql->escape($object_or_array_or_id)));
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
	
}
?>