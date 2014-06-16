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
 * Page class for the back-end Products Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopProductsManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[text() = 'Product Management']";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=product';

	/**
	 * Function to Add a new Product
	 *
	 * @param   string  $name      Name of the Product
	 * @param   string  $number    Number of the Product
	 * @param   string  $price     Price of the Product
	 * @param   string  $category  Category of the Product
	 *
	 * @return RedShopProductsManagerPage
	 */
	public function addProduct($name = 'Sample', $number = '123455', $price = '10', $category = 'redCOMPONENT')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$this->checkNoticesForEditView(get_class($this));
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='product_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='product_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$numberField = $elementObject->findElement(By::xPath("//input[@id='product_number']"));
		$numberField->clear();
		$numberField->sendKeys($number);
		$priceField = $elementObject->findElement(By::xPath("//input[@id='product_price']"));
		$priceField->clear();
		$priceField->sendKeys($price);
		$elementObject->findElement(By::xPath("//select[@id='product_category']//option[contains(text(),'" . $category . "')]"))->click();
		sleep(2);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[text() = 'Product details saved']"), 10);
	}

}
