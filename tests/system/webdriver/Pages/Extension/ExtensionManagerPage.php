<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the back-end control panel screen.
 *
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
	protected $waitForXpath = "//input[contains(@onclick,'Joomla.submitbuttonInstallWebInstaller()')]";

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
	 * @param $cfg Object for the Configuration file
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
	 * Function to install the main Extension RedShop2
	 *
	 * @param $cfg Configuration File Object
	 *
	 * @return ExtensionManagerPage
	 */
	public function installRedShop2($cfg)
	{
		$elementObject = $this->driver;
		$elementObject->get($cfg->host . $cfg->path . $this->url);
		$elementObject->findElement(By::xPath("//a[contains(text(),'Install from Directory')]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='install_directory']"), 50);
		$installDirectory = $elementObject->findElement(By::xPath("//input[@id='install_directory']"));
		$installDirectory->clear();
		$installDirectory->sendKeys($cfg->folder);
		$elementObject->findElement(By::xPath("//input[contains(@onclick,'Joomla.submitbutton3()')]"))->click();
		sleep(50);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//div[@class='alert alert-success']"), 30);
	}

	/**
	 * Function to Verify if the extension was installed or not
	 *
	 * @param string $extensionName
	 *
	 * @return bool
	 */
	public function verifyInstallation($extensionName = 'RedShop2')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@href='index.php?option=com_installer&view=manage']"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='filter_search']"), 50);
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter_search']"));
		$searchField->clear();
		$searchField->sendKeys($extensionName);
		$elementObject->findElement(By::xPath("//button[@data-original-title='Search' or @title='Search']"))->click();
		sleep(5);
		$row = $this->getRowNumber($extensionName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[2]//span[contains(text(),'" . $extensionName . "')]"));
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