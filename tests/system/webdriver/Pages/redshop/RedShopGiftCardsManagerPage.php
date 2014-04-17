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
 * Page class for the back-end Categories Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopGiftCardsManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Gift Card Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=giftcard';

	/**
	 * Function to Add a Card
	 *
	 * @param   string  $name         Name of the Card
	 * @param   string  $price        Price of the card
	 * @param   string  $value        Value of the Card
	 * @param   string  $validity     Validity of the Card
	 * @param   string  $description  Description of the Card
	 *
	 * @return RedShopGiftCardsManagerPage
	 */
	public function addCard($name = 'Sample Card', $price = '100', $value = '10', $validity = '10', $description = 'Sample Gift Card')
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
		$elementObject->findElement(By::xPath("//a[@title='Toggle editor']"))->click();
		sleep(4);
		$descriptionField = $elementObject->findElement(By::xPath("//textarea[@id='jform_giftcard_desc']"));
		$descriptionField->clear();
		$descriptionField->sendKeys($description);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Gift Card Saved')]"), 10);
	}
}
