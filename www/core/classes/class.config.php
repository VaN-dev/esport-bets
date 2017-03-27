<?php
class Config Extends Object_Model
{
	public function __construct($object_or_array_or_id = '')
	{
		global $lang, $sql;

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
			$item = $sql->get_row_from_query(sprintf("SELECT CONFIG.*
			FROM " . TABLES__CONFIG . " CONFIG 
			WHERE CONFIG.id = %d", $sql->escape($object_or_array_or_id)));
		}

		if(!empty($item))
		{
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
		if(isset($this->$property))
		{
			return $this->$property;
		}
	}
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

	public function edit()
	{
		Sql::sql_query(sprintf("UPDATE " . TABLES__CONFIG . " SET 
		site_name = '%s', site_url = '%s', site_short_url = '%s', site_title = '%s', site_description = '%s', site_keywords = '%s', site_default_lang = '%s', site_build_xml = %d, site_maintenance = %d, site_maintenance_message = '%s', 
		contact_mail = '%s', contact_tel = '%s', contact_fax = '%s', 
		company_name = '%s', company_address = '%s', company_zipcode = '%s', company_city = '%s', company_country = '%s', company_legal_status = '%s', company_capital = '%s', company_siren = '%s', company_responsable = '%s', 
		analytics_key = '%s', map_key = '%s', map_latitude = '%s', map_longitude = '%s',
		social_facebook = '%s', social_twitter = '%s',
		layout_carousel_homepage_type = '%s', layout_carousel_homepage_id = %d, layout_carousel_galeries = '%s'
		WHERE id = %d", 
		$this->site_name, $this->site_url, $this->site_short_url, $this->site_title, $this->site_description, $this->site_keywords, $this->site_default_lang, $this->site_build_xml, $this->site_maintenance, $this->site_maintenance_message,
		$this->contact_mail, $this->contact_tel, $this->contact_fax, 
		$this->company_name, $this->company_address, $this->company_zipcode, $this->company_city, $this->company_country, $this->company_legal_status, $this->company_capital, $this->company_siren, $this->company_responsable,
		$this->analytics_key, $this->map_key, $this->map_latitude, $this->map_longitude,
		$this->social_facebook, $this->social_twitter,
		$this->layout_carousel_homepage_type, $this->layout_carousel_homepage_id, $this->carousel_galeries,
		$this->id
		));
	}
}
?>