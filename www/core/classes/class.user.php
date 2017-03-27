<?php
class User Extends Object_Model
{
    private $sql;

	public function __construct($object_or_array_or_id = '')
	{
        global $sql;
        $this->sql = $sql;
        
		global $lang;

		if (is_object($object_or_array_or_id))
		{
			$item = $object_or_array_or_id;
		}
		if (is_array($object_or_array_or_id))
		{
			$item = $object_or_array_or_id;
		}
		elseif (is_numeric($object_or_array_or_id))
		{
			$item = $this->sql->get_row_from_query(sprintf("SELECT USERS.*
			FROM " . TABLES__USERS . " USERS 
			WHERE USERS.id = '%d'", $this->sql->escape($object_or_array_or_id)));
		}

		if (!empty($item)) {
			$this->hydrate($item);
		}
	}
	
	public function hydrate($array)
	{
		foreach ($array as $key => $value)
		{
			$this->$key = $value;
		}
	}
	
	public function __get($property)
	{
		if (isset($this->$property))
		{
			return $this->$property;
		}
	}
	public function __set($property, $value)
	{
		$this->$property = $value;
	}
	
	public function is_unique()
	{
		$count = $this->sql->get_value_from_query(sprintf("SELECT COUNT(id) FROM " . TABLES__USERS . " WHERE mail = '%s' AND id != %d", $this->sql->escape($this->mail), $this->id));
		if ($count > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function create()
	{
		$this->sql->sql_query(sprintf("INSERT INTO " . TABLES__USERS . " (datetime, mail, password, activation_key, firstname, birthdate, points, statut) 
		VALUES(NOW(), '%s', '%s', '%s', '%s', '%s', '%s', %d)",
		$this->sql->escape($this->mail), $this->sql->escape(sha1($this->password)), $this->sql->escape($this->activation_key), $this->sql->escape($this->firstname), $this->sql->escape($this->birthdate), $this->sql->escape($this->points), $this->sql->escape($this->statut)));
	}

	public function edit()
	{
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__USERS . " SET mail = '%s', firstname = '%s', birthdate = '%s' WHERE id = %d", 
		$this->sql->escape($this->mail), $this->sql->escape($this->firstname), $this->sql->escape($this->birthdate), $this->sql->escape($this->id)));

		if (!empty($this->password))
		{
			$this->sql->sql_query(sprintf("UPDATE " . TABLES__USERS . " SET password = '%s' WHERE id = %d", $this->sql->escape(sha1($this->password)), $this->id));
		}
	}

	public function delete()
	{
		$this->sql->sql_query(sprintf("DELETE FROM " . TABLES__ADMIN_USERS . " WHERE id = '%d'", $this->id));
	}

	public function switch_statut()
	{
		$this->statut == 1 ? $this->statut = 0 : $this->statut = 1;
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__USERS . " SET statut = %d WHERE id = %d", $this->sql->escape($this->statut), $this->sql->escape($this->id)));
	}
	
	public function attempt_activation() {
		$account_id = $this->sql->get_value_from_query(sprintf("SELECT id FROM " . TABLES__USERS . " WHERE mail = '%s' AND activation_key = '%s'", $this->sql->escape($this->mail), $this->sql->escape($this->activation_key)));
		if(!empty($account_id)) {
			$this->sql->sql_query(sprintf("UPDATE " . TABLES__USERS . " SET statut = 1 WHERE id = %d", $account_id));
			return true;
		}
		else {
			return false;
		}
	}
	
	public function update_points() 
	{
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__USERS . " SET points  = '%s' WHERE id = %d", $this->sql->escape($this->points), $this->sql->escape($this->id)));
	}
	
	public function open_bets() 
	{
		$bets = array();
		
		$dates = $this->sql->get_array_from_query(sprintf("SELECT DISTINCT(DATE_FORMAT(m.start, '%%Y-%%m-%%d')) AS date, DATE_FORMAT(m.start, '%%W %%d %%M %%Y') AS date_formatted
		FROM " . TABLES__MATCHES . " m, " . TABLES__BETS . " b 
		WHERE m.id = b.match_id AND b.user_id = %d AND m.statut = 1
		ORDER BY date", $this->sql->escape($this->id)));
		
		if(!empty($dates)) {
			foreach($dates as $date) {
				$bets[$date['date']] = $this->sql->get_objects_from_query(sprintf("SELECT b.*, DATE_FORMAT(m.start, '%%H:%%i') AS match_start_formatted, o.name AS opponent_name, odds.value AS odds_value 
				FROM " . TABLES__BETS . " b, " . TABLES__MATCHES . " m, " . TABLES__OPPONENTS . " o, " . TABLES__ODDS . " odds 
				WHERE b.user_id = %d AND b.statut = 1 AND b.match_id = m.id AND b.opponent_id = o.id AND b.odds_id = odds.id AND DATE_FORMAT(m.start, '%%Y-%%m-%%d') = '%s'
				ORDER BY m.start", $this->sql->escape($this->id), $this->sql->escape($date['date'])), 'Bet');
			}
		}
		return $bets;
	}
	
	public function completed_unnoticed_bets() 
	{
		$bets = array();
		
		$dates = $this->sql->get_array_from_query(sprintf("SELECT DISTINCT(DATE_FORMAT(m.start, '%%Y-%%m-%%d')) AS date, DATE_FORMAT(m.start, '%%W %%d %%M %%Y') AS date_formatted
		FROM " . TABLES__MATCHES . " m, " . TABLES__BETS . " b 
		WHERE m.id = b.match_id AND b.user_id = %d AND m.statut = 2 AND b.noticed = 0
		ORDER BY date", $this->sql->escape($this->id)));
		
		if(!empty($dates)) {
			foreach($dates as $date) {
				$data = $this->sql->get_objects_from_query(sprintf("SELECT b.*, DATE_FORMAT(m.end, '%%H:%%i') AS match_end_formatted, o.name AS opponent_name, odds.value AS odds_value, m.winner_id, w.name AS winner_name 
				FROM " . TABLES__BETS . " b, " . TABLES__MATCHES . " m, " . TABLES__OPPONENTS . " o, " . TABLES__ODDS . " odds, " . TABLES__OPPONENTS . " w
				WHERE b.user_id = %d AND b.statut = 2 AND b.match_id = m.id AND b.opponent_id = o.id AND b.odds_id = odds.id AND DATE_FORMAT(m.start, '%%Y-%%m-%%d') = '%s' AND b.noticed = 0 AND m.winner_id = w.id
				ORDER BY m.start", $this->sql->escape($this->id), $this->sql->escape($date['date'])), 'Bet');
				
				if(!empty($data)) {
					foreach($data as $row) {
						$row->set_noticed();
					}
				}
				
				$bets[$date['date']] = $data;
			}
		}
		return $bets;
	}
	
	public function all_bets() 
	{
		$bets = array();
		
		$dates = $this->sql->get_array_from_query(sprintf("SELECT DISTINCT(DATE_FORMAT(m.start, '%%Y-%%m-%%d')) AS date, DATE_FORMAT(m.start, '%%W %%d %%M %%Y') AS date_formatted
		FROM " . TABLES__MATCHES . " m, " . TABLES__BETS . " b 
		WHERE m.id = b.match_id AND b.user_id = %d
		ORDER BY date", $this->sql->escape($this->id)));
		
		if(!empty($dates)) {
			foreach($dates as $date) {
				$data = $this->sql->get_objects_from_query(sprintf("SELECT b.*, DATE_FORMAT(m.start, '%%H:%%i') AS match_start_formatted, o.name AS opponent_name, odds.value AS odds_value, m.winner_id, w.name AS winner_name 
				FROM " . TABLES__BETS . " b, " . TABLES__OPPONENTS . " o, " . TABLES__ODDS . " odds, " . TABLES__MATCHES . " m 
				LEFT JOIN " . TABLES__OPPONENTS . " w ON m.winner_id = w.id
				WHERE b.user_id = %d AND b.statut = 2 AND b.match_id = m.id AND b.opponent_id = o.id AND b.odds_id = odds.id AND DATE_FORMAT(m.start, '%%Y-%%m-%%d') = '%s'
				ORDER BY m.start", $this->sql->escape($this->id), $this->sql->escape($date['date'])), 'Bet');
				
				$bets[$date['date']] = $data;
			}
		}
		return $bets;
	}
}
?>