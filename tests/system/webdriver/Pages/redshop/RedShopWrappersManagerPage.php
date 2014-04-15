<?php
/**
 * @package     RedCore
 * @subpackage  Model
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
 * Page class for the back-end Wrapper Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Wrappers
 * @since       1.4
 */
class RedShopWrappersManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Wrapping Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=wrapper';

	/**
	 * Function to Add a Wrapper
	 *
	 * @param   string  $wrapperName   Name of the Wrapper
	 * @param   string  $wrapperPrice  Price of the Wrapper
	 *
	 * @return RedShopWrappersManagerPage
	 */
	public function addWrapper($wrapperName = 'Sample', $wrapperPrice = '100')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='wrapper_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='wrapper_name']"));
		$nameField->clear();
		$nameField->sendKeys($wrapperName);
		$priceField = $elementObject->findElement(By::xPath("//input[@id='wrapper_price']"));
		$priceField->clear();
		$priceField->sendKeys($wrapperPrice);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Wrapping detail saved')]"), 10);
	}
}
