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
 * Page class for the Front End Products Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopFrontEndManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//p[text() = 'redSHOP Categories']";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'index.php?option=com_redshop';

	/**
	 * Function to Checkout a Product from Frontend
	 *
	 * @param   string  $productName   Name of the Product
	 * @param   string  $category      Category of the Product
	 * @param   Array   $customerInfo  Information related to the Customer
	 *
	 * @return bool
	 */
	public function checkOutProduct($productName, $category, $customerInfo)
	{
		$cfg = new SeleniumConfig;
		$checkoutUrl = 'index.php?option=com_redshop&view=checkout';
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[text() = '" . $category . "']"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//select[@id='order_by']//option[@value='p.product_id DESC']"), 15);
		$elementObject->findElement(By::xPath("//select[@id='order_by']//option[@value='p.product_id DESC']"))->click();
		sleep(2);
		$elementObject->findElement(By::xPath("//a[text() = '" . $productName . "']"))->click();
		$elementObject->findElement(By::xPath("//div[@id='add_to_cart_all']//form//span[text() = 'Add to cart']"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[text() = 'Product has been added to your cart.']"));
		$elementObject->get($cfg->host . $cfg->path . $checkoutUrl);
		$elementObject->findElement(By::xPath("//input[@id = 'mytogglermycheckerregister']"))->click();
		$emailField = $elementObject->findElement(By::xPath("//input[@id='email1']"));
		$emailField->clear();
		$emailField->sendKeys($customerInfo['email']);
		$firstNameField = $elementObject->findElement(By::xPath("//input[@id='firstname']"));
		$firstNameField->clear();
		$firstNameField->sendKeys($customerInfo['firstname']);
		$lastNameField = $elementObject->findElement(By::xPath("//input[@id='lastname']"));
		$lastNameField->clear();
		$lastNameField->sendKeys($customerInfo['lastname']);
		$addressField = $elementObject->findElement(By::xPath("//input[@id='address']"));
		$addressField->clear();
		$addressField->sendKeys($customerInfo['address']);
		$postalField = $elementObject->findElement(By::xPath("//input[@id='zipcode']"));
		$postalField->clear();
		$postalField->sendKeys($customerInfo['postalcode']);
		$cityField = $elementObject->findElement(By::xPath("//input[@id='city']"));
		$cityField->clear();
		$cityField->sendKeys($customerInfo['city']);
		$elementObject->findElement(By::xPath("//select[@id='state_code']//option[text()='" . $customerInfo['state'] . "']"))->click();
		$numberField = $elementObject->findElement(By::xPath("//input[@id='phone']"));
		$numberField->clear();
		$numberField->sendKeys($customerInfo['phone']);

		// Shipping Address same as Above
		$firstNameField = $elementObject->findElement(By::xPath("//input[@id='firstname_ST']"));
		$firstNameField->clear();
		$firstNameField->sendKeys($customerInfo['firstname']);
		$lastNameField = $elementObject->findElement(By::xPath("//input[@id='lastname_ST']"));
		$lastNameField->clear();
		$lastNameField->sendKeys($customerInfo['lastname']);
		$addressField = $elementObject->findElement(By::xPath("//input[@id='address_ST']"));
		$addressField->clear();
		$addressField->sendKeys($customerInfo['address']);
		$postalField = $elementObject->findElement(By::xPath("//input[@id='zipcode_ST']"));
		$postalField->clear();
		$postalField->sendKeys($customerInfo['postalcode']);
		$cityField = $elementObject->findElement(By::xPath("//input[@id='city_ST']"));
		$cityField->clear();
		$cityField->sendKeys($customerInfo['city']);
		$elementObject->findElement(By::xPath("//select[@id='state_code_ST']//option[text()='" . $customerInfo['state'] . "']"))->click();
		$numberField = $elementObject->findElement(By::xPath("//input[@id='phone_ST']"));
		$numberField->clear();
		$numberField->sendKeys($customerInfo['phone']);
		$elementObject->findElement(By::xPath("//input[@id='submitbtn']"))->click();
		sleep(2);
		$elementObject->findElement(By::xPath("//input[@name='checkoutnext']"))->click();
		sleep(1);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//a[text() = '" . $productName . "']"));
		$arrayElement = $elementObject->findElement(By::xPath("//a[text() = '" . $productName . "']"));

		if (count($arrayElement))
		{
			$elementObject->findElement(By::xPath("//input[@id='termscondition']"))->click();

			return true;
		}
		else
		{
			return false;
		}
	}
}
