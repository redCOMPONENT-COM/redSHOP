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
 * Page class for the back-end Country Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopCountriesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Country Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=country';

	public function addCountry($name = 'Sample Country', $codeThree = '123', $codeTwo = '22', $country = 'Test')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='country_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='country_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$codeThreeField = $elementObject->findElement(By::xPath("//input[@id='country_3_code']"));
		$codeThreeField->clear();
		$codeThreeField->sendKeys($codeThree);
		$codeTwoField = $elementObject->findElement(By::xPath("//input[@id='country_2_code']"));
		$codeTwoField->clear();
		$codeTwoField->sendKeys($codeTwo);
		$countryField = $elementObject->findElement(By::xPath("//input[@id='country_jtext']"));
		$countryField->clear();
		$countryField->sendKeys($country);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Country detail saved')]"), 10);
	}
}

