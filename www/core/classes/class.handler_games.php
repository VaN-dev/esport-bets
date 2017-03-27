<?php
class Handler_Games {

    private $sql;

	public function __construct() {
        global $sql;
        $this->sql = $sql;
	}
	
	public function get_list($order_by = 'id') {
		$data = $this->sql->get_objects_from_query("SELECT GAMES.*
		FROM " . TABLES__GAMES . " GAMES
		ORDER BY ".$order_by, 'Game');
		
		return $data;
	}

}

?>