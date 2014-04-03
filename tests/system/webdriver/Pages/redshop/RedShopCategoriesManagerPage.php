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
class RedShopCategoriesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Category Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=category';

	/**
	 * Function to add a new Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 * @param   string  $noOfProducts  No of Products Per Page
	 *
	 * @return RedShopCategoriesManagerPage
	 */
	public function addCategory($categoryName = 'Sample Category', $noOfProducts = '5')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='category_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='jform_category_name']"));
		$nameField->clear();
		$nameField->sendKeys($categoryName);
		$noOfProductsField = $elementObject->findElement(By::xPath("//input[@id='products_per_page']"));
		$noOfProductsField->clear();
		$noOfProductsField->sendKeys($noOfProducts);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='category_main_filter']"), 10);
	}

	/**
	 * Function to Update a Category Info
	 *
	 * @param   string  $field         Field Name
	 * @param   string  $newValue      New value for the Field
	 * @param   string  $categoryName  Name of the Category Which is to be Updated
	 *
	 * @return RedShopCategoriesManagerPage
	 */
	public function editCategory($field, $newValue, $categoryName)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("category_main_filter"));
		$searchField->clear();
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $categoryName . "')]"), 10);
		$row = $this->getRowNumber($categoryName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='category_name']"), 10);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='category_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "Products":
				$noOfProductsField = $elementObject->findElement(By::xPath("//input[@id='jform_products_per_page']"));
				$noOfProductsField->clear();
				$noOfProductsField->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='category_main_filter']"), 10);
	}

	public function deleteCategory($categoryName)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->clear();
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $categoryName . "')]"), 10);
		$row = $this->getRowNumber($categoryName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}
}
