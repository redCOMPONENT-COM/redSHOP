<?php
/**
 * @package     RedShop
 * @subpackage  Helper Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Codeception\Module;

/* Here you can define custom actions
 all public methods declared in helper class will be available in $I */

/**
 * Class AcceptanceHelper
 *
 * @package  Codeception\Module
 *
 * @since    1.4
 */
class AcceptanceHelper extends \Codeception\Module
{
	/**
	 * Function to getConfiguration from the YML and return in the test
	 *
	 * @return array
	 */
	public function getConfig()
	{
		$configuration = [
		"username" => $this->config['username'],
		"password" => $this->config['password'],
		"folder" => $this->config['folder'],
		"db_host" => $this->config['db_host'],
		"db_user" => $this->config['db_user'],
		"db_pass" => $this->config['db_pass'],
		"db_name" => $this->config['db_name'],
		"db_type" => $this->config['db_type'],
		"db_prefix" => $this->config['db_prefix'],
		"sample_data_file" => $this->config['sample_data_file'],
		"site_name" => $this->config['site_name'],
		"admin_email" => $this->config['admin_email'],
		"language" => $this->config['language'],
		"sample_data" => $this->config['sample_data'],
		"host" => $this->config['host'],
		"extension_name" => $this->config['extension_name'],
		"install_extension_demo_data" => $this->config['install_extension_demo_data'],
		"env" => $this->config['env']
		];

		return $configuration;
	}

	/**
	 * Function to Verify State of an Object
	 *
	 * @param   String  $expected  Expected State
	 * @param   String  $actual    Actual State
	 *
	 * @return void
	 */
	public function verifyState($expected, $actual)
	{
		$this->assertEquals($expected, $actual, "Assert that the Actual State is equal to the state we Expect");
	}

	/**
	 * Function to VerifyNotices
	 *
	 * @param   string  $expected  Expected Value
	 * @param   string  $actual    Actual Value
	 * @param   string  $page      Page for which we are Verifying
	 *
	 * @return void
	 */
	public function verifyNotices($expected, $actual, $page)
	{
		$this->assertEquals($expected, $actual, "Page " . $page . " Contains PHP Notices and Warnings");
	}
}
