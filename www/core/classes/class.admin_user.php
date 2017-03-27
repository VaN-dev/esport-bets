<?php
class Admin_User Extends Object_Model
{
	public function __construct($object_or_array_or_id = '')
	{
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
			$item = Sql::get_row_from_query(sprintf("SELECT ADMIN_USERS.*, LEVELS.name AS level_name 
			FROM " . TABLES__ADMIN_USERS . " ADMIN_USERS 
			LEFT JOIN " . TABLES__ADMIN_USERS_LEVELS . " LEVELS ON ADMIN_USERS.level = LEVELS.id
			WHERE ADMIN_USERS.id = '%d'", mysql_real_escape_string($object_or_array_or_id)));
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
	
	public function nom_prenom()
	{
		return $this->nom." ".$this->prenom;
	}
	public function prenom_nom()
	{
		return $this->prenom." ".$this->nom;
	}
	
	public function is_unique()
	{
		$count = Sql::get_value_from_query(sprintf("SELECT COUNT(id) FROM " . TABLES__ADMIN_USERS . " WHERE login = '%s' AND id != %d", mysql_real_escape_string($this->login), $this->id));
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
		Sql::sql_query(sprintf("INSERT INTO " . TABLES__ADMIN_USERS . " (id, login, password, datetime, level, prenom, nom, statut) 
		VALUES('', '%s', '%s', NOW(), '%d', '%s', '%s', '%d')", 
		mysql_real_escape_string($this->login), mysql_real_escape_string(md5($this->password)), mysql_real_escape_string($this->level), mysql_real_escape_string($this->prenom), mysql_real_escape_string($this->nom), mysql_real_escape_string($this->statut)));
	}

	public function edit()
	{
		Sql::sql_query(sprintf("UPDATE " . TABLES__ADMIN_USERS . " SET login = '%s', level = '%d', prenom = '%s', nom = '%s', statut = '%d' WHERE id = '%d'", 
		mysql_real_escape_string($this->login), mysql_real_escape_string($this->level), mysql_real_escape_string($this->prenom), mysql_real_escape_string($this->nom), mysql_real_escape_string($this->statut), mysql_real_escape_string($this->id)));

		if (!empty($this->password))
		{
			Sql::sql_query(sprintf("UPDATE " . TABLES__ADMIN_USERS . " SET password = '%s' WHERE id = '%d'", mysql_real_escape_string(md5($this->password)), $this->id));
		}
	}

	public function delete()
	{
		Sql::sql_query(sprintf("DELETE FROM " . TABLES__ADMIN_USERS . " WHERE id = '%d'", $this->id));
	}

	public function switch_statut()
	{
		$this->statut == 1 ? $this->statut = 0 : $this->statut = 1;
		Sql::sql_query(sprintf("UPDATE " . TABLES__ADMIN_USERS . " SET statut = %d WHERE id = %d", mysql_real_escape_string($this->statut), mysql_real_escape_string($this->id)));
	}
}
?>