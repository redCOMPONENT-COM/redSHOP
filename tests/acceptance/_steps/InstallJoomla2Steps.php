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
	 * Function to Install Joomla
	 *
	 * @return void
	 */
	public function installJoomla2()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage(\InstallJoomla2ManagerPage::$URL);
		$cfg = $I->getConfig();
		$I->click('Next');
		$I->click('Next');
		$I->click('Next');
		sleep(3);

		$this->setDatabaseType($cfg['db_type']);
		$this->setField('Host Name', $cfg['db_host']);
		$this->setField('Username', $cfg['db_user']);
		$this->setField('Password', $cfg['db_pass']);
		$this->setField('Database Name', $cfg['db_name']);
		$this->setField('Table Prefix', $cfg['db_prefix']);

		$I->click('Next');
		$I->click('Next');
		sleep(3);
		$this->setField('Site Name', $cfg['site_name']);
		$this->setField('Your Email', $cfg['admin_email']);
		$this->setField('Admin Username', $cfg['username']);
		$this->setField('Admin Password', $cfg['password']);
		$this->setField('Confirm Admin Password', $cfg['password']);

		if (strtolower($cfg['sample_data']) == "yes")
		{
			$this->setSampleData($cfg['sample_data_file']);
		}
		else
		{
			$this->setSampleData('Default English');
		}

		$I->click(\InstallJoomla2ManagerPage::$installSampleData);
		sleep(5);
		$I->click('Next');
		sleep(3);

		return $this;
	}

	/**
	 * Function to Select DB Type
	 *
	 * @param   String  $value  Value of the DB Type
	 *
	 * @return void
	 */
	private function setDatabaseType($value)
	{
		$I = $this->acceptanceTester;
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
	private function setField($label, $value)
	{
		$I = $this->acceptanceTester;

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
	private function setSampleData($option = 'Default')
	{
		$I = $this->acceptanceTester;
		$I->click("//label[contains(., '" . $option . "')]");
	}
}
