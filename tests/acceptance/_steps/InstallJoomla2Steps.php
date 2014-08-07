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
 */
class InstallJoomla2Steps extends \AcceptanceTester
{
	// Include url of current page
	public static $URL = '/installation/index.php';

	/**
	 * @var AcceptanceTester;
	 */
	protected $acceptanceTester;

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 *
	 * @return  void
	 */
	public static function route($param = "")
	{
		return static::$URL . $param;
	}

	/**
	 * Function to Install Joomla
	 *
	 * @return void
	 */
	public function installJoomla2()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage($this->route());
		$cfg = $I->getConfig();
		$this->clickNextButton('2');
		$this->clickNextButton('3');
		$this->clickNextButton('4');

		$this->setDatabaseType($cfg['db_type']);
		$this->setField('Host Name', $cfg['db_host']);
		$this->setField('Username', $cfg['db_user']);
		$this->setField('Password', $cfg['db_pass']);
		$this->setField('Database Name', $cfg['db_name']);
		$this->setField('Table Prefix', $cfg['db_prefix']);

		$this->clickNextButton('5');
		$this->clickNextButton('6');
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

		$I->click("//input[@value='Install Sample Data']");
		sleep(5);
		$this->clickNextButton('7');

		return $this;
	}

	/**
	 * Function to Click Next Button
	 *
	 * @return void
	 */
	private function clickNextButton()
	{
		$I = $this->acceptanceTester;
		$I->click('Next');
		sleep(3);
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
		$I->selectOption("#jform_db_type", strtolower($value));
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
				$id = 'jform_db_host';
				break;
			case 'Username':
				$id = 'jform_db_user';
				break;
			case 'Password':
				$id = 'jform_db_pass';
				break;
			case 'Database Name':
				$id = 'jform_db_name';
				break;
			case 'Table Prefix':
				$id = 'jform_db_prefix';
				break;
			case 'Site Name':
				$id = 'jform_site_name';
				break;
			case 'Your Email':
				$id = 'jform_admin_email';
				break;
			case 'Admin Username':
				$id = 'jform_admin_user';
				break;
			case 'Admin Password':
				$id = 'jform_admin_password';
				break;
			case 'Confirm Admin Password':
				$id = 'jform_admin_password2';
				break;
		}

		$I->fillField("#" . $id . "", $value);
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
