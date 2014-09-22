<?php
/**
 * @package     RedSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the back-end control panel screen.
 *
 * @since  1.4
 *
 */
class InstallationPage extends AdminPage
{
	/**
	 * Xpath for the Installation Page
	 *
	 * @var string
	 */
	protected $waitForXpath = "//h1[contains(.,'Installation')]";

	/**
	 * URL for Installation Page
	 *
	 * @var string
	 */
	protected $url = 'installation/';

	/**
	 * Function to Click Next Button
	 *
	 * @param   string  $step  Step No. During the Process of Installation
	 *
	 * @return void
	 */
	public function clickNextButton($step)
	{
		sleep(2);
		$this->driver->findElement(By::linkText('Next'))->click();
		sleep(3);
	}

	/**
	 * Function to set Old Database
	 *
	 * @param   string  $option  Option Value
	 *
	 * @return void
	 */
	public function setOldDatabaseProcess($option = 'Backup')
	{
		$this->driver->findElement(By::xPath("//input[@value = '" . strtolower($option) . "']"))->click();
	}

	/**
	 * Function to Install Sample Data
	 *
	 * @return void
	 */
	public function installSampleData()
	{
		$this->driver->findElement(By::xPath("//label[contains(., '" . $this->cfg->sample_data_file . "')]"))->click();
	}

	/**
	 * Function to do the Installation
	 *
	 * @param   Configuration  $cfg  Object for Configuration
	 *
	 * @return void
	 */
	public function install($cfg)
	{
		$this->clickNextButton('2');
		$this->clickNextButton('3');
		$this->clickNextButton('4');

		$this->setDatabaseType($cfg->db_type);
		$this->setField('Host Name', $cfg->db_host);
		$this->setField('Username', $cfg->db_user);
		$this->setField('Password', $cfg->db_pass);
		$this->setField('Database Name', $cfg->db_name);
		$this->setField('Table Prefix', $cfg->db_prefix);

		$this->clickNextButton('5');
		$this->clickNextButton('6');
		$this->setField('Site Name', $cfg->site_name);
		$this->setField('Your Email', $cfg->admin_email);
		$this->setField('Admin Username', $cfg->username);
		$this->setField('Admin Password', $cfg->password);
		$this->setField('Confirm Admin Password', $cfg->password);

		if ($cfg->sample_data && isset($cfg->sample_data_file))
		{
			$this->setSampleData($cfg->sample_data_file);
		}
		else
		{
			$this->setSampleData('Default English');
		}

		$this->driver->findElement(By::xPath("//input[@value='Install Sample Data']"))->click();
		sleep(5);
		$this->clickNextButton('7');
	}

	/**
	 * Function to set Field Values
	 *
	 * @param   string  $label  Label of the Field
	 * @param   string  $value  Value for the Field
	 *
	 * @return void
	 */
	public function setField($label, $value)
	{
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

		$this->driver->findElement(By::id($id))->clear();
		$this->driver->findElement(By::id($id))->sendKeys($value);
	}

	/**
	 * Function to set DB Type
	 *
	 * @param   string  $value  Value of the DB
	 *
	 * @return void
	 */
	public function setDatabaseType($value)
	{
		$this->driver->findElement(By::xPath("//select[@id='jform_db_type']"))->click();
		$this->driver->findElement(By::xPath("//select[@id='jform_db_type']//option[@value='" . strtolower($value) . "']"))->click();
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
		$this->driver->findElement(By::xPath("//label[contains(., '" . $option . "')]"))->click();
	}

	/**
	 * Function to wait for installation
	 *
	 * @param   string  $stepNumber  Step Number being Passed
	 *
	 * @return void
	 */
	protected function waitForStepNumber($stepNumber)
	{
		$xPath = $this->waitForXpath;

		switch ($stepNumber)
		{
			case 2:
				$xPath = "//h2[Contains(text(),'Pre-Installation Check')]";
				break;
			case 3:
				$xPath = "//h2[Contains(text(),'License')]";
				break;
			case 4:
				$xPath = "//h2[Contains(text(),'Database Configuration')]";
				break;
			case 5:
				$xPath = "//h2[Contains(text(),'FTP Configuration')]";
				break;
			case 6:
				$xPath = "//h2[Contains(text(),'Main Configuration')]";
				break;
			case 7:
				$xPath = "//h2[Contains(text(),'Finish')]";
				break;
			default:
				break;
		}

		$this->driver->waitForElementUntilIsPresent(By::xPath($xPath));
	}
}
