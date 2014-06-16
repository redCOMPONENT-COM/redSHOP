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

	/**
	 * Function to Edit a Product
	 *
	 * @param   string  $field        Field which we are going to Update
	 * @param   string  $newValue     New value of the Field
	 * @param   string  $productName  Name of the Product which we are going to edit
	 *
	 * @return RedShopProductsManagerPage
	 */
	public function editProduct($field, $newValue, $productName)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@name='keyword']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@name='keyword']"));
		$searchField->sendKeys($productName);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $productName . "']"), 10);
		$row = $this->getRowNumber($productName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$this->checkNoticesForEditView(get_class($this));
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='product_name']"), 10);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='product_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "Price":
				$priceField = $elementObject->findElement(By::xPath("//input[@id='product_price']"));
				$priceField->clear();
				$priceField->sendKeys($newValue);
				break;
			case "Number":
				$numberField = $elementObject->findElement(By::xPath("//input[@id='product_number']"));
				$numberField->clear();
				$numberField->sendKeys($newValue);
				break;
			case "Category":
				$elementObject->findElement(By::xPath("//select[@id='product_category']//option[contains(text(),'" . $newValue . "')]"))->click();
				sleep(2);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[text() = 'Product details saved']"), 10);
	}

	/**
	 * Function to Delete a Product
	 *
	 * @param   string  $productName  Name of the Product which is to be deleted
	 *
	 * @return RedShopProductsManagerPage
	 */
	public function deleteProduct($productName)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@name='keyword']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@name='keyword']"));
		$searchField->sendKeys($productName);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $productName . "']"), 10);
		$row = $this->getRowNumber($productName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//a[@onclick=\"if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ Joomla.submitbutton('remove')}\"]"))->click();
	}

	/**
	 * Function to Search for a Product
	 *
	 * @param   string  $productName   Name of the Product for which we are going to Search
	 * @param   string  $functionName  Name of the function after which Search is getting called
	 *
	 * @return bool
	 */
	public function searchProduct($productName, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@name='keyword']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@name='keyword']"));
		$searchField->sendKeys($productName);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$row = $this->getRowNumber($productName) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[text() = '" . $productName . "']"));

		if (count($arrayElement))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
