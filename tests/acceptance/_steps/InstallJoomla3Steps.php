<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
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
	// Include url of current page
	public static $URL = '/installation/index.php';

	/**
	 * @var AcceptanceTester;
	 */
	protected $acceptanceTester;

	/**
	 * Function to redirect to the Installation Page
	 *
	 * @param   string  $param  URL Value
	 *
	 * @return string
	 */
	public static function route($param = "")
	{
		return static::$URL . $param;
	}

	/**
	 * Function to Install Joomla3.x
	 *
	 * @return void
	 */
	public function installJoomla3()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage($this->route());
		$cfg = $I->getConfig();
		$this->setField('Site Name', $cfg['site_name']);
		$this->setField('Your Email', $cfg['admin_email']);
		$this->setField('Admin Username', $cfg['username']);
		$this->setField('Admin Password', $cfg['password']);
		$this->setField('Confirm Admin Password', $cfg['password']);
		$I->click("//li[@id='database']/a");
		$I->waitForElement("//li[@id='database'][@class='step active']", 30);

		$this->setDatabaseType($cfg['db_type']);
		$this->setField('Host Name', $cfg['db_host']);
		$this->setField('Username', $cfg['db_user']);
		$this->setField('Password', $cfg['db_pass']);
		$this->setField('Database Name', $cfg['db_name']);
		$this->setField('Table Prefix', $cfg['db_prefix']);

		$I->click("//label[@for='jform_db_old1']");

		$I->click("//li[@id='summary']/a");

		if (strtolower($cfg['sample_data']) == "yes")
		{
			$this->setSampleData($cfg['sample_data_file']);
		}
		else
		{
			$this->setSampleData('None');
		}

		$I->click("//a[@title='Install']");
		$I->waitForElement("//input[contains(@onclick, 'Install.removeFolder')]", 60);
	}

	/**
	 * Function to Populate Values
	 *
	 * @param   String  $label  Label of the Field
	 * @param   String  $value  Value of the Field
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

		$I->fillField(['id' => $id], $value);
	}

	/**
	 * Function to set the Database Type
	 *
	 * @param   String  $value  Value of DB
	 *
	 * @return void
	 */
	private function setDatabaseType($value)
	{
		$I = $this->acceptanceTester;
		$I->click("//div[@id='jform_db_type_chzn']/a/div/b");
		$I->click("//div[@id='jform_db_type_chzn']//ul[@class='chzn-results']/li[contains(translate(.,'" . strtoupper($value) . "', '" . strtolower($value) . "'), '" . strtolower($value) . "')]");
	}

	/**
	 * Function to set Sample Data for Installation
	 *
	 * @param   string  $option  Option Value
	 *
	 * @return void
	 */
	private function setSampleData($option = 'Default')
	{
		$I = $this->acceptanceTester;
		$I->click("//label[contains(., '" . $option . "')]");
	}

	/**
	 * Function to Click on the Next Button
	 *
	 * @param   String  $step  Step No.
	 *
	 * @return void
	 */
	private function clickNextButton($step)
	{
		$I = $this->acceptanceTester;
		$I->click('Next');
		$this->waitForStepNumber($step);
	}

	/**
	 * Function to wait for the Xpath to be Present on the Page
	 *
	 * @param   String  $stepNumber  No of Step
	 *
	 * @return void
	 */
	private function waitForStepNumber($stepNumber)
	{
		$I = $this->acceptanceTester;
		$xPath = $this->waitForXpath;

		switch ($stepNumber)
		{
			case 2:
				$xPath = "//div[@id='preinstall'][@class='step active']";
				break;
			case 3:
				$xPath = "//div[@id='license'][@class='step active']";
				break;
			case 4:
				$xPath = "//div[@id='database'][@class='step active']";
				break;
			case 5:
				$xPath = "//div[@id='filesystem'][@class='step active']";
				break;
			case 6:
				$xPath = "//div[@id='site'][@class='step active']";
				break;
			case 7:
				$xPath = "//div[@id='complete'][@class='step active']";
				break;
			default:
				break;
		}

		$I->waitForElement($xPath, 30);
	}

	/**
	 * Function to Keep Backup of Old Data Base while Doing a Reinstallation
	 *
	 * @param   string  $option  Name of the Option
	 *
	 * @return void
	 */
	private function setOldDatabaseProcess($option = 'Backup')
	{
		$I = $this->acceptanceTester;
		$I->click("//input[@value = '" . strtolower($option) . "']");
	}

	/**
	 * Function to Install Sample Data
	 *
	 * @return void
	 */
	private function installSampleData()
	{
		$I = $this->acceptanceTester;
		$I->click("//label[contains(., '" . $this->cfg->sample_data_file . "')]");
	}
}
