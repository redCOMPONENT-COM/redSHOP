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
 * @since  1.3
 */
class ExtensionManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//input[@id='install_directory']";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_installer';

	/**
	 * Function to Install Redcore
	 *
	 * @param   Configuration  $cfg  Object for the Configuration file
	 *
	 * @return ExtensionManagerPage
	 */
	public function installRedCore($cfg)
	{
		$redCorePath = $cfg->folder . 'redCORE/';
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[contains(text(),'Install from Directory')]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='install_directory']"), 50);
		$installDirectory = $elementObject->findElement(By::xPath("//input[@id='install_directory']"));
		$installDirectory->clear();
		$installDirectory->sendKeys($redCorePath);
		$elementObject->findElement(By::xPath("//input[contains(@onclick,'Joomla.submitbutton3()')]"))->click();
		sleep(10);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//div[@class='alert alert-success']"), 30);
	}

	/**
	 * Function to Install RedShop Extension
	 *
	 * @param   Configuration  $cfg         Configuration Object
	 * @param   string         $sampleData  Sample Data to be installed for the component
	 *
	 * @return ExtensionManagerPage
	 */
	public function installRedShop($cfg, $sampleData = 'Sample Data')
	{
		$elementObject = $this->driver;
		$elementObject->get($cfg->host . $cfg->path . $this->url);
		$installDirectory = $elementObject->findElement(By::xPath("//input[@id='install_directory']"));
		$installDirectory->clear();
		$installDirectory->sendKeys($cfg->folder);
		$elementObject->findElement(By::xPath("//input[contains(@onclick,'Joomla.submitbutton3()')]"))->click();
		sleep(5);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Installing component was successful')]"), 30);

		if ($sampleData == 'Sample Data')
		{
			$elementObject->findElement(By::xPath("//input[@onclick=\"submitWizard('content');\" and @value='install Demo Content']"))->click();
			$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Sample Data Installed Successfully')]"), 30);
		}
	}

	/**
	 * Function to Verify if the extension was installed or not
	 *
	 * @param   Configuration  $cfg            Configuration Object
	 * @param   string         $extensionName  Name of the Extension
	 *
	 * @return bool
	 */
	public function verifyInstallation($cfg, $extensionName = 'redShop')
	{
		$elementObject = $this->driver;
		$componentURl = 'administrator/index.php?option=com_redshop';
		$elementObject->get($cfg->host . $cfg->path . $componentURl);
		$arrayElement = $elementObject->findElements(By::xPath("//a//span[contains(text(),'Products')]"));

		if (count($arrayElement))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
