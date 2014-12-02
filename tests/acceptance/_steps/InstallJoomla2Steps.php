<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallJoomla2Steps extends \AcceptanceTester
{
	/**
	 * Function to Select DB Type
	 *
	 * @param   String  $value  Value of the DB Type
	 *
	 * @return void
	 */
	public function setDatabaseType($value)
	{
		$I = $this;
		$I->selectOption(\InstallJoomla2ManagerPage::$dbType, strtolower($value));
	}

	/**
	 * Function to Fill all the Fields
	 *
	 * @param   String  $label  Label of the input Field
	 * @param   String  $value  Value to be inserted
	 *
	 * @return void
	 */
	public function setField($label, $value)
	{
		$I = $this;

		switch ($label)
		{
			case 'Host Name':
				$id = \InstallJoomla2ManagerPage::$dbHost;
				break;
			case 'Username':
				$id = \InstallJoomla2ManagerPage::$dbUsername;
				break;
			case 'Password':
				$id = \InstallJoomla2ManagerPage::$dbPassword;
				break;
			case 'Database Name':
				$id = \InstallJoomla2ManagerPage::$dbName;
				break;
			case 'Table Prefix':
				$id = \InstallJoomla2ManagerPage::$dbPrefix;
				break;
			case 'Site Name':
				$id = \InstallJoomla2ManagerPage::$siteName;
				break;
			case 'Your Email':
				$id = \InstallJoomla2ManagerPage::$adminEmail;
				break;
			case 'Admin Username':
				$id = \InstallJoomla2ManagerPage::$adminUser;
				break;
			case 'Admin Password':
				$id = \InstallJoomla2ManagerPage::$adminPassword;
				break;
			case 'Confirm Admin Password':
				$id = \InstallJoomla2ManagerPage::$adminPasswordConfirm;
				break;
		}

		$I->fillField($id, $value);
	}

	/**
	 * Function to set Installation Data
	 *
	 * @param   string  $option  Option for Sample Data
	 *
	 * @return void
	 */
	public function setSampleData($option = 'Default')
	{
		$I = $this;
		$I->selectOption(\InstallJoomla2ManagerPage::$sampleFile, $option);
	}
}
