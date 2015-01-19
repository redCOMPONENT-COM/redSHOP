<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class InstallJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallJoomla3Steps extends \AcceptanceTester
{
	/**
	 * Function to Populate Values
	 *
	 * @param   String  $label  Label of the Field
	 * @param   String  $value  Value of the Field
	 *
	 * @return void
	 */
	public function setField($label, $value)
	{
		$I = $this;

		switch ($label)
		{
			case 'Host Name':
				$id = \InstallJoomla3ManagerPage::$dbHost;
				break;
			case 'Username':
				$id = \InstallJoomla3ManagerPage::$dbUsername;
				break;
			case 'Password':
				$id = \InstallJoomla3ManagerPage::$dbPassword;
				break;
			case 'Database Name':
				$id = \InstallJoomla3ManagerPage::$dbName;
				break;
			case 'Table Prefix':
				$id = \InstallJoomla3ManagerPage::$dbPrefix;
				break;
			case 'Site Name':
				$id = \InstallJoomla3ManagerPage::$siteName;
				break;
			case 'Your Email':
				$id = \InstallJoomla3ManagerPage::$adminEmail;
				break;
			case 'Admin Username':
				$id = \InstallJoomla3ManagerPage::$adminUser;
				break;
			case 'Admin Password':
				$id = \InstallJoomla3ManagerPage::$adminPassword;
				break;
			case 'Confirm Admin Password':
				$id = \InstallJoomla3ManagerPage::$adminPasswordConfirm;
				break;
		}

		$I->fillField($id, $value);
	}

	/**
	 * Function to set the Database Type
	 *
	 * @param   String  $value  Value of DB
	 *
	 * @return void
	 */
	public function setDatabaseType($value)
	{
		$I = $this;
		$I->selectOption(\InstallJoomla3ManagerPage::$dbType, strtolower($value));
	}

	/**
	 * Function to set Sample Data for Installation
	 *
	 * @param   string  $option  Option Value
	 *
	 * @return void
	 */
	public function setSampleData($option = 'Default')
	{
		$I = $this;
		$I->selectOption(\InstallJoomla3ManagerPage::$sampleFile, $option);
	}
}
