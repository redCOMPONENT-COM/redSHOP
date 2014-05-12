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
 * Page class for the back-end Currency Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopCurrenciesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Currency Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=currency';

	/**
	 * Function to Add a New Currency
	 *
	 * @param   string  $name  Name of the Currency
	 * @param   string  $code  Code of the Currency
	 *
	 * @return RedShopCurrenciesManagerPage
	 */
	public function addCurrency($name = '1RedShop', $code = '123')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='jform_currency_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='currency_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$codeField = $elementObject->findElement(By::xPath("//input[@id='currency_code']"));
		$codeField->clear();
		$codeField->sendKeys($code);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Currency Detail Saved')]"), 10
	}
}
