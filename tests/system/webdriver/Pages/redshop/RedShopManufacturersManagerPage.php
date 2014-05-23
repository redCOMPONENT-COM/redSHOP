<?php
/**
 * @package     RedSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use SeleniumClient\By;

/**
 * Page class for the back-end Manufacturer Redshop.
 *
 * @package     RedShop2.Test
 * @subpackage  Webdriver
 * @since       1.0
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

		$elementObject->findElement(By::xPath("//select[@id='template_id']//option[@value='{$template}']"))->click();

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

	/**
	 * Function to Modify an existing Manufacturer name
	 *
	 * @param   string  $originalName  Name of the Manufacturer before being edited
	 * @param   string  $name          The new name for the Macturer
	 *
	 * @return void
	 */
	public function editManufacturer($originalName, $name = 'Sample Manufacturer')
	{
		// Search the name of the Manufacturer using search filter
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($originalName);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();

		sleep(2);

		// Select the right manufacturer to edit in the Manufacturers list
		$elementObject->waitForElementUntilIsPresent(By::xPath("//a[contains(text(),'{$originalName}')]"), 10);
		$elementObject->findElement(By::xPath("//a[contains(text(),'{$originalName}')]"))->click();

		$nameField = $elementObject->findElement(By::xPath("//input[@id='manufacturer_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Manufacturer Detail Saved')]"), 10);
	}

	/**
	 * Function to delete a Manufacturer
	 *
	 * @param   string  $name  Name of the State
	 *
	 * @return RedShopStatesManagerPage
	 */
	public function deleteManufacturer($name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();

		sleep(2);

		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$elementObject->findElement(By::xPath("//a[contains(text(),'{$name}')]/../../td[2]/input"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 * Function to check the existence of a specific Manufacturer searching it by name
	 *
	 * @param   string  $name  Name of the Manufacturer
	 *
	 * @return bool
	 */
	public function searchManufacturer($name)
	{
		// Search the name of the Manufacturer using search filter
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();

		sleep(2);

		$existManufacturer = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"));

		if (count($existManufacturer))
		{
			return true;
		}

		return false;
	}
}