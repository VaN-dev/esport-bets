<?php
class Handler_Pages
{
	public function __construct()
	{
	}

	public function get_page($id)
	{
		return new Page($id);
	}

	public function get_list($order_by = 'id')
	{
		$data = Sql::get_objects_from_query("SELECT PAGES.*
		FROM " . TABLES__PAGES . " PAGES
		ORDER BY ".$order_by, 'Page');

		return $data;
	}

	public function get_list_with_exceptions($exceptions, $order_by = 'id')
	{
		if (!empty($exceptions))
		{
			$where = ' WHERE ';
			foreach ($exceptions as $key => $exception)
			{
				$where .= 'id != ' . mysql_real_escape_string($exception);
				$key + 1 < count($exceptions) ? $where .= ' AND ' : '';
			}
		}

		$data = Sql::get_objects_from_query("SELECT PAGES.*
		FROM " . TABLES__PAGES . " PAGES " .
		$where .
		" ORDER BY ".$order_by, 'Page');

		return $data;
	}

	public function get_list_actives($order_by = 'id')
	{
		$data = Sql::get_objects_from_query("SELECT PAGES.*
		FROM " . TABLES__PAGES . " PAGES
		WHERE statut = 1
		ORDER BY ".$order_by, 'Page');
		
		return $data;
	}

	public function get_page_from_rewrite($rewrite)
	{
		$data = Sql::get_objects_from_query(sprintf("SELECT PAGES.*
		FROM " . TABLES__PAGES . " PAGES
		WHERE titre_rewrite = '%s'", 
		mysql_real_escape_string($rewrite)), 'Page');

		if(isset($data[0]))
		{
			return $data[0];
		}
	}

	public function get_list_for_menu($parent_id = 0, $build_submenus = true)
	{
		$data = Sql::get_objects_from_query(sprintf("SELECT PAGES.*
		FROM " . TABLES__PAGES . " PAGES
		WHERE parent_id = %d AND statut = 1 AND menu_affichage = 1 ORDER BY menu_position", mysql_real_escape_string($parent_id)), 'Page');

		if($build_submenus === true)
		{
			if(!empty($data))
			{
				foreach($data as $key => $row)
				{
					$data[$key]->children = self::get_list_for_menu($row->id);
				}
			}
		}

		return $data;
	}

	public function get_list_for_menu_admin($parent_id = 0)
	{
		$data = Sql::get_objects_from_query(sprintf("SELECT PAGES.*
		FROM " . TABLES__PAGES . " PAGES
		WHERE parent_id = %d AND statut = 1 ORDER BY menu_position", mysql_real_escape_string($parent_id)), 'Page');

		if(!empty($data))
		{
			foreach($data as $key => $row)
			{
				$data[$key]->children = self::get_list_for_menu_admin($row->id);
			}
		}

		return $data;
	}
}
?>