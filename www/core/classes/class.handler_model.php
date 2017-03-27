<?php
class Handler_Anecdotes {

	public function __construct() {
		
	}
	
	public function get_list($order_by = 'id') {
		$data = Sql::get_objects_from_query("SELECT ANECDOTES.*
		FROM " . TABLES__ANECDOTES . " ANECDOTES
		ORDER BY ".$order_by, 'Anecdote');
		
		return $data;
	}
	
	public function get_list_actives($order_by = 'id') {
		$data = Sql::get_objects_from_query("SELECT ANECDOTES.*
		FROM " . TABLES__ANECDOTES . " ANECDOTES
		WHERE statut = 1
		ORDER BY ".$order_by, 'Anecdote');
		
		return $data;
	}

}

?>