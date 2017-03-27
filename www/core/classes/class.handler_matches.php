<?php
class Handler_Matches {

    private $sql;

	public function __construct() {
        global $sql;
        $this->sql = $sql;
	}
	
	public function get_coming_matches($order_by = 'id') {
		$data = $this->sql->get_objects_from_query("SELECT MATCHES.*, DATE_FORMAT(MATCHES.start, '%Y-%m-%d') AS start_date_formatted, DATE_FORMAT(MATCHES.start, '%H:%i') AS start_time_formatted
		FROM " . TABLES__MATCHES . " MATCHES
		WHERE start >= NOW()
		ORDER BY ".$order_by, 'Match');
		
		return $data;
	}
	
	public function get_active_coming_matches($order_by = 'id') {
		$data = $this->sql->get_objects_from_query("SELECT MATCHES.*
		FROM " . TABLES__MATCHES . " MATCHES
		WHERE statut = 1 AND start >= NOW()
		ORDER BY ".$order_by, 'Match');
		
		return $data;
	}
	
	public function get_coming_dates() {
		$data = $this->sql->get_array_from_query("SELECT DISTINCT(DATE_FORMAT(MATCHES.start, '%Y-%m-%d')) AS date, DATE_FORMAT(MATCHES.start, '%W %d %M %Y') AS date_formatted
		FROM " . TABLES__MATCHES . " MATCHES 
		WHERE MATCHES.start >= NOW() AND MATCHES.statut = 1
		ORDER BY date");
		
		return $data;
	}
	
	public function get_coming_dates_from_game($game_id) {
		$data = $this->sql->get_array_from_query(sprintf("SELECT DISTINCT(DATE_FORMAT(m.start, '%%Y-%%m-%%d')) AS date, DATE_FORMAT(m.start, '%%W %%d %%M %%Y') AS date_formatted
		FROM " . TABLES__MATCHES . " m 
		WHERE m.start >= NOW() AND m.statut = 1 AND m.game_id = %d
		ORDER BY date", $this->sql->escape($game_id)));
		
		return $data;
	}
	
	public function get_matches_from_date($date) {
		$data = $this->sql->get_objects_from_query(sprintf("SELECT MATCHES.*, DATE_FORMAT(MATCHES.start, '%%H:%%i') AS start_formatted
		FROM " . TABLES__MATCHES . " MATCHES 
		WHERE DATE_FORMAT(MATCHES.start, '%%Y-%%m-%%d') >= '%s' AND MATCHES.start >= NOW() AND MATCHES.statut = 1
		ORDER BY start", $date), 'Match');
		
		return $data;
	}
	
	public function get_featured_matches() {
		$data = $this->sql->get_objects_from_query("SELECT m.*, DATE_FORMAT(m.start, '%%H:%%i') AS start_formatted
		FROM " . TABLES__MATCHES . " m 
		WHERE m.statut = 1 AND m.featured = 1
		ORDER BY start", 'Match');
		
		return $data;
	}

}

?>