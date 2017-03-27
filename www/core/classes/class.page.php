<?php
class Page Extends Object_Model
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
			$item = Sql::get_row_from_query(sprintf("SELECT PAGES.*
			FROM " . TABLES__PAGES . " PAGES 
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
		if (isset($this->$property))
		{
			return $this->$property;
		}
	}
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

	public function rewrited_url()
	{
		return $this->id . '-' . $this->titre_rewrite . '.html';
	}

	public function create()
	{
		Sql::sql_query(sprintf("INSERT INTO " . TABLES__PAGES . " (parent_id, titre, titre_rewrite, introduction, contenu, menu_affichage, menu_position, statut) VALUES (%d, '%s', '%s', '%s', '%s', %d, %d, %d)", 
		mysql_real_escape_string($this->parent_id), mysql_real_escape_string($this->titre), mysql_real_escape_string($this->titre_rewrite), mysql_real_escape_string($this->introduction), mysql_real_escape_string($this->contenu), mysql_real_escape_string($this->menu_affichage), mysql_real_escape_string($this->menu_position), mysql_real_escape_string($this->statut)));
		
		$this->id = mysql_insert_id();
	}

	public function edit()
	{
		Sql::sql_query(sprintf("UPDATE " . TABLES__PAGES . " SET parent_id = %d, titre = '%s', titre_rewrite = '%s', introduction = '%s', contenu = '%s', menu_affichage = %d, menu_position = %d, statut = %d WHERE id = '%d'", 
		mysql_real_escape_string($this->parent_id), mysql_real_escape_string($this->titre), mysql_real_escape_string($this->titre_rewrite), mysql_real_escape_string($this->introduction), mysql_real_escape_string($this->contenu), mysql_real_escape_string($this->menu_affichage), mysql_real_escape_string($this->menu_position), mysql_real_escape_string($this->statut), $this->id));
	}

	public function delete()
	{
		Sql::sql_query(sprintf("DELETE FROM " . TABLES__PAGES . " WHERE id = '%d'", $this->id));
	}

	public function switch_statut()
	{
		$this->statut == 1 ? $this->statut = 0 : $this->statut = 1;
		Sql::sql_query(sprintf("UPDATE " . TABLES__PAGES . " SET statut = %d WHERE id = %d", mysql_real_escape_string($this->statut), mysql_real_escape_string($this->id)));
	}
}
?>