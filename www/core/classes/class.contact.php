<?php
class Contact Extends Object_Model
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
			$item = Sql::get_row_from_query(sprintf("SELECT CONTACTS.*
			FROM " . TABLES__CONTACTS . " CONTACTS 
			WHERE id = '%d'", mysql_real_escape_string($object_or_array_or_id)));
		}

		if (!empty($item))
		{
			$this->hydrate($item);
		}
	}

	public function hydrate($array)
	{
		foreach($array as $key => $value)
		{
			$this->$key = $value;
		}
	}

	public function __get($property)
	{
		if(isset($this->$property))
		{
			return $this->$property;
		}
	}
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

	public function prenom_nom()
	{
		return stripslashes($this->prenom . ' ' . $this->nom);
	}

	public function create()
	{
		Sql::sql_query(sprintf("INSERT INTO " . TABLES__CONTACTS . " (datetime, nom, prenom, telephone, mail, message) VALUES (NOW(), '%s', '%s', '%s', '%s', '%s')", 
		mysql_real_escape_string($this->nom), mysql_real_escape_string($this->prenom), mysql_real_escape_string($this->telephone), mysql_real_escape_string($this->mail), mysql_real_escape_string($this->message)));
		
		$this->id = mysql_insert_id();
	}

	public function delete()
	{
		Sql::sql_query(sprintf("DELETE FROM " . TABLES__CONTACTS . " WHERE id = '%d'", $this->id));
	}
}
?>