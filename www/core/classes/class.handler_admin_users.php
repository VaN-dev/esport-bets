<?php
class Handler_Admin_Users
{
	public function __construct()
	{
	}

	public function get_list($order_by = 'id')
	{
		$data = Sql::get_objects_from_query("SELECT ADMIN_USERS.*, name AS level_name 
		FROM " . TABLES__ADMIN_USERS . " ADMIN_USERS
		LEFT JOIN " . TABLES__ADMIN_USERS_LEVELS . " LEVELS ON ADMIN_USERS.level = LEVELS.id ORDER BY ".$order_by, 'Admin_User');

		return $data;
	}

	public function get_list_without_adveris($order_by = 'id')
	{
		$data = Sql::get_objects_from_query("SELECT ADMIN_USERS.*, name AS level_name 
		FROM " . TABLES__ADMIN_USERS . " ADMIN_USERS
		LEFT JOIN " . TABLES__ADMIN_USERS_LEVELS . " LEVELS ON ADMIN_USERS.level = LEVELS.id 
		WHERE ADMIN_USERS.id != 1
		ORDER BY ".$order_by, 'Admin_User');

		return $data;
	}
}
?>