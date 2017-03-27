<?php
class Handler_Comments {

    private $sql;

	public function __construct() {
        global $sql;
        $this->sql = $sql;
	}
	
	public function get_list($order_by = 'id') {
		$data = $this->sql->get_objects_from_query("SELECT c.*, DATE_FORMAT(c.datetime, '%M %D %Y %l:%i%p') AS datetime_formatted
		FROM " . TABLES__COMMENTS . " c
		ORDER BY ".$order_by, 'Comment');
		
		return $data;
	}
	
	public function get_list_active_from_match($match_id, $order_by = 'id') {
		$data = $this->sql->get_objects_from_query(sprintf("SELECT c.*, DATE_FORMAT(c.datetime, '%%M %%D %%Y %%l:%%i%%p') AS datetime_formatted
		FROM " . TABLES__COMMENTS . " c
		WHERE statut = 1 AND match_id = %d
		ORDER BY ".$order_by, $match_id), 'Comment');
		
		return $data;
	}

}

?>