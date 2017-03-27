<?php
class Match Extends Object_Model {

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
			$item = $this->sql->get_row_from_query(sprintf("SELECT *, DATE_FORMAT(start, '%%M %%D %%Y %%h:%%m%%p') AS start_formatted, DATE_FORMAT(end, '%%M %%D %%Y %%h:%%m%%p') AS end_formatted 
			FROM " . TABLES__MATCHES . "  
			WHERE id = %d", $this->sql->escape($object_or_array_or_id)));
		}
		
		if(!empty($item)) {
			$this->hydrate($item);
		}
		
		if(isset($this->id)) {
			$this->opponents = array();
			
			$opponent_1 = $this->sql->get_objects_from_query(sprintf("SELECT ODDS.id AS odds_id, ODDS.datetime, ODDS.opponent_id, ROUND(ODDS.value, 2) AS value, o.name, o.image
			FROM " . TABLES__OPPONENTS . " o, " . TABLES__ODDS . " ODDS
			WHERE ODDS.match_id = %d AND ODDS.opponent_id = o.id 
			ORDER BY datetime DESC LIMIT 1", $this->id), 'Opponent');
			if(!empty($opponent_1)) {
				$this->opponents[0] = $opponent_1[0];
				
				$opponent_2 = $this->sql->get_objects_from_query(sprintf("SELECT ODDS.id AS odds_id, ODDS.datetime, ODDS.opponent_id, ROUND(ODDS.value, 2) AS value, o.name, o.image
				FROM " . TABLES__OPPONENTS . " o, " . TABLES__ODDS . " ODDS
				WHERE ODDS.match_id = %d AND ODDS.opponent_id = o.id AND ODDS.opponent_id != %d 
				ORDER BY datetime DESC LIMIT 1", $this->id, $opponent_1[0]->opponent_id), 'Opponent');
				if(!empty($opponent_2)) {
					$this->opponents[1] = $opponent_2[0];
				}
			}
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
		$this->sql->sql_query(sprintf("INSERT INTO " . TABLES__MATCHES . " (start, end, game_id, featured, statut) VALUES('%s', '%s', %d, %d, %d)",
		$this->start, $this->end, $this->game_id, $this->featured, $this->statut));
		
		$this->id = mysql_insert_id();
	}
	
	public function edit() {
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__MATCHES . " SET start = '%s', end = '%s', game_id = %d, featured = %d, statut = %d WHERE id = '%d'", 
		$this->sql->escape($this->start), $this->sql->escape($this->end), $this->sql->escape($this->game_id), $this->sql->escape($this->featured), $this->sql->escape($this->statut), $this->sql->escape($this->id)));
	}
	
	public function close() {
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__MATCHES . " SET winner_id = %d, statut = 2 WHERE id =%d", $this->sql->escape($this->winner_id), $this->sql->escape($this->id)));
	}
	
	public function reopen() {
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__MATCHES . " SET statut = 1 WHERE id =%d", $this->sql->escape($this->id)));
	}
	
	public function get_all_odds() {
		$data = $this->sql->get_objects_from_query(sprintf("SELECT o.datetime,
		ROUND(MAX(case when opponent_id = 1 then o.value end), 2) AS odds_1,
		ROUND(MAX(case when opponent_id = 2 then o.value end), 2) AS odds_2 
		FROM ebets_odds o
		WHERE match_id = %d
		GROUP BY o.datetime
		ORDER BY o.datetime DESC", $this->id), 'Odds');
		
		return $data;
	}
	
	public function close_bets() {
		$this->sql->sql_query(sprintf("UPDATE " . TABLES__BETS . " SET statut = 2 WHERE match_id = %d", $this->sql->escape($this->id)));
	}
	
	public function reward_betters() {
		$bets = $this->sql->get_objects_from_query(sprintf("SELECT BETS.* FROM " . TABLES__BETS . " BETS WHERE match_id = %d AND opponent_id = %d", $this->sql->escape($this->id), $this->sql->escape($this->winner_id)), 'Bet');
		Object_Model::print_r_pre($bets);
		
		if(!empty($bets)) {
			foreach($bets as $bet) {
				$odd = new Odds($this->sql->escape($bet->odds_id));
				$reward = $bet->stake * $odd->value;
				
				$this->sql->sql_query(sprintf("UPDATE " . TABLES__USERS . " USERS SET points = points + '%s' WHERE id = %d", $reward, $this->sql->escape($bet->user_id)));
			}
		}
	}
	
}
?>