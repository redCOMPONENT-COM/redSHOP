<?php
/**
 * @package    RedSHOP
 * @subpackage Page
 * @copyright  Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Page class for the back-end Manufacturer Redshop.
 *
 * @package    RedShop2.Test
 * @subpackage Webdriver
 * @since      1.0
 */
class RedShop2ManufacturersManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Manufacturer Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=manufacturer';

	/**
	 * Function to Add a Manufacturer
	 *
	 * @param   string  $name         Name of the Card
	 * @param   string  $price        Price of the card
	 * @param   string  $value        Value of the Card
	 * @param   string  $validity     Validity of the Card
	 * @param   string  $description  Description of the Card
	 *
	 * @return RedShopManufacturerManagerPage
	 */
	public function addManufacturer($name = 'Sample Manufacturer', $price = '100', $value = '10', $validity = '10')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='giftcard_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='giftcard_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$priceField = $elementObject->findElement(By::xPath("//input[@id='giftcard_price']"));
		$priceField->clear();
		$priceField->sendKeys($price);
		$valueField = $elementObject->findElement(By::xPath("//input[@id='giftcard_value']"));
		$valueField->clear();
		$valueField->sendKeys($value);
		$validityField = $elementObject->findElement(By::xPath("//input[@id='giftcard_validity']"));
		$validityField->clear();
		$validityField->sendKeys($validity);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Gift Card Saved')]"), 10);
	}

}