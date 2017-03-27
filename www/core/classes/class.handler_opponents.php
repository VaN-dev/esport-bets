<?php
class Handler_Opponents {

	public function __construct() {
		
	}
	
	public function get_list($order_by = 'id') {
		$data = Sql::get_objects_from_query("SELECT OPPONENTS.*
		FROM " . TABLES__OPPONENTS . " OPPONENTS
		ORDER BY ".$order_by, 'Opponent');
		
		return $data;
	}

}

?>