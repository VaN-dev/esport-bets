<?php
class Handler_Contacts
{
	public function __construct()
	{
	}

	public function get_list($order_by = 'id DESC')
	{
		$data = Sql::get_objects_from_query("SELECT CONTACTS.*
		FROM " . TABLES__CONTACTS . " CONTACTS
		ORDER BY ".$order_by, 'Contact');

		return $data;
	}
}
?>