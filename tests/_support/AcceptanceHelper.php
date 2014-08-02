<?php
namespace Codeception\Module;

// Here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptanceHelper extends \Codeception\Module
{
	public function getConfig()
	{
		$configuration = [
		"username" => "puneet",
		"password" => "1234",
		"folder" => "/home/puneet/Projects/redSHOP/",
		"db_host" => "localhost",
		"db_user" => "root",
		"db_pass" => "1234",
		"db_name" => "joomla-db-3-1",
		"db_type" => "MySQLi",
		"db_prefix" => "jml31_",
		"sample_data_file" => "Default English",
		"site_name" => "Joomla",
		"admin_email" => "admin@mydomain.com",
		"language" => "English (United Kingdom)",
		"sample_data" => "yes"
		];

		return $configuration;
	}
}
