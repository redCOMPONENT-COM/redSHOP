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
class RedShopManufacturersManagerPage extends AdminManagerPage
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
	 * @param   string  $name            Name of the Manufacturer
	 * @param   int     $template        Template to use for Manufacturer frontend page
	 * @param   string  $email           Contact e-mail for manufacturer
	 * @param   string  $url             Manufacturer website
	 * @param   int     $productPerPage  Description of the Card
	 *
	 * @return RedShopManufacturerManagerPage
	 */
	public function addManufacturer($name = 'Sample Manufacturer', $template = 0, $email = '', $url = '', $productPerPage = 10)
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='manufacturer_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='manufacturer_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);

		// @Todo: we can valuate using SeleniumClient\SelectElement;
		$elementObject->findElement(By::xPath("//select[@id='template_id']//option[@value='{$template}']"))->click();
		sleep(5);

		$emailField = $elementObject->findElement(By::xPath("//input[@id='manufacturer_email']"));
		$emailField->clear();
		$emailField->sendKeys($email);
		$urlField = $elementObject->findElement(By::xPath("//input[@id='manufacturer_url']"));
		$urlField->clear();
		$urlField->sendKeys($url);
		$productPerPageField = $elementObject->findElement(By::xPath("//input[@id='product_per_page']"));
		$productPerPageField->clear();
		$productPerPageField->sendKeys($productPerPage);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Manufacturer Detail Saved')]"), 10);
	}
}