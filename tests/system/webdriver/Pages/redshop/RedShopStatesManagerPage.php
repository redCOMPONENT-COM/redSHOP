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
 * Page class for the back-end States Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopStatesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'States')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=state';

	/**
	 * Function to Add a State
	 *
	 * @param   string  $stateName    Name of the New State
	 * @param   string  $codeThree    Three Code of the State
	 * @param   string  $codeTwo      Two code of the State
	 * @param   string  $countryName  Name of the Country
	 *
	 * @return RedShopStatesManagerPage
	 */
	public function addState($stateName = 'Sample', $codeThree = '123', $codeTwo = '33', $countryName = 'Sample Country')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='state_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='state_name']"));
		$nameField->clear();
		$nameField->sendKeys($stateName);
		$codeThreeField = $elementObject->findElement(By::xPath("//input[@id='state_3_code']"));
		$codeThreeField->clear();
		$codeThreeField->sendKeys($codeThree);
		$codeTwoField = $elementObject->findElement(By::xPath("//input[@id='state_2_code']"));
		$codeTwoField->clear();
		$codeTwoField->sendKeys($codeTwo);
		$elementObject->findElement(By::xPath("//option[contains(text(),'" . $countryName . "')]"))->click();
		sleep(1);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='country_main_filter']"), 10);
	}
}
