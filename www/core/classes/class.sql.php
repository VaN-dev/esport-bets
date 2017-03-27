<?php
/*
=======================================================================
SCRIPT:			Class : Sql
PROJECT:		Brunchbox
AUTHOR:			David Micheau
=======================================================================
Methods:		- hydrate()
				- [M001] SQL 		methods
				- [M002] HTML 		methods
				- [M003] String 	methods
				- [M004] Date 		methods
				- [M005] Number 	methods
				- [M006] File 		methods
				- [M007] Notice 	methods
				- [M008] XML 		methods		
=======================================================================
Description : 	Set a lot of functions used in others classes
=======================================================================
*/ 

class Sql
{
    private $connection;

	public function __construct($bdd_host = BDD_HOST, $bdd_user = BDD_USER, $bdd_pass = BDD_PASS)
	{
	    $this->connection($bdd_host, $bdd_user, $bdd_pass);
	}

	public function connection($bdd_host, $bdd_user, $bdd_pass)
	{
		$this->connection = mysqli_connect($bdd_host, $bdd_user, $bdd_pass);
	}
	public function select_db($bdd_name = BDD_NAME)
	{
		mysqli_select_db($this->connection, $bdd_name);
	}

	##################################################################
	##																##
	## [F001]	SQL METHODS											##
	##																##
	##################################################################
	// Soumet une requete
	public function sql_query($sql, $link_identifier = null, $debug = false)
	{
		if ($debug === true)
		{
			echo "<p>".$sql."<p>";
		}

		$query = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));
		return $query;
	}

	// Retourne un tableau de données
	public function get_array_from_query($sql, $link_identifier = null, $debug = false)
	{
		if ($debug === true)
		{
			echo "<p>".$sql."<p>";
		}

		$query = $this->sql_query($sql, $this->connection) or die(mysqli_error($this->connection));
		$data = array();
		while ($result = mysqli_fetch_assoc($query))
		{
			$data[] = $result;
		}
		return $data;
	}

	// Retourne une liste d'objets
	public function get_objects_from_query($sql, $class_name, $link_identifier = null, $debug = false)
	{
		if ($debug === true)
		{
			echo "<p>".$sql."<p>";
		}

		$query = $this->sql_query($sql, $this->connection) or die(mysqli_error($this->connection));
		$data = array();
		while ($result = mysqli_fetch_object($query, $class_name))
		{
			$data[] = $result;
		}
		return $data;
	}

	// Retourne une ligne de donnees
	public function get_row_from_query($sql, $link_identifier = null, $debug = false)
	{
		if ($debug === true)
		{
			echo "<p>".$sql."<p>";
		}

		$query = $this->sql_query($sql, $this->connection) or die(mysqli_error($this->connection));
		$data = array();
		while ($result = mysqli_fetch_assoc($query))
		{
			$data[] = $result;
		}

		if (!empty($data[0]))
		{
			return $data[0];
		}
		else
		{
			return array();
		}
	}

	// Retourne une valeur unique
	public function get_value_from_query($sql, $link_identifier = null, $debug = false)
	{
		
		if ($debug === true)
		{
			echo "<p>".$sql."<p>";
		}
		
		$query = $this->sql_query($sql, $this->connection) or die(mysqli_error($this->connection));
		$result = mysqli_fetch_array($query);
		return $result[0];
	}

	// Retourne un tableau d'une ligne
	public function get_field_array_from_query($sql, $link_identifier = null, $debug = false)
	{
		
		if($debug === true)
		{
			echo "<p>".$sql."<p>";
		}
		$query = $this->sql_query($sql, $this->connection) or die(mysqli_error($this->connection));
		$results = array();
		while($result = mysqli_fetch_array($query))
		{
			if(!empty($result))
			{
				$results[] = $result[0];
			}
		}
		return $results;
	}

	public function last_insert_id()
    {
        return mysqli_insert_id($this->connection);
    }

	public function escape($var)
    {
        return mysqli_escape_string($this->connection, $var);
    }
}
?>