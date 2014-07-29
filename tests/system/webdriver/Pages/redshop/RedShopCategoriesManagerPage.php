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
	protected $waitForXpath = "//h2[text() = 'Category Management']";

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
		$this->checkNoticesForEditView(get_class($this));
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='category_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='category_name']"));
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
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->clear();
		sleep(4);
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $categoryName . "']"), 10);
		$row = $this->getRowNumber($categoryName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$this->checkNoticesForEditView(get_class($this));
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

	/**
	 * Function to delete a Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 *
	 * @return RedShopCategoriesManagerPage
	 */
	public function deleteCategory($categoryName)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->clear();
		sleep(3);
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $categoryName . "']"), 10);
		$row = $this->getRowNumber($categoryName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
		$elementObject->switchTo()->getAlert()->accept();
	}

	/**
	 * Function to Search for a Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 * @param   string  $functionName  Name of the function after which Search is getting called
	 *
	 * @return bool True or False Based on the Value
	 */
	public function searchCategory($categoryName, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->clear();
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		sleep(5);
		$row = $this->getRowNumber($categoryName) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[text() = '" . $categoryName . "']"));

		if (count($arrayElement))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to Get State of a Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 *
	 * @return string Based on the value
	 */
	public function getState($categoryName)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->clear();
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		sleep(5);
		$row = $this->getRowNumber($categoryName);
		$text = $this->driver->findElement(By::xPath("//tbody/tr[" . $row . "]/td[7]//a"))->getAttribute(@onclick);

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		return $result;
	}

	/**
	 * Function to Change State of a Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 * @param   string  $state         State for the Category
	 *
	 * @return RedShopCategoriesManagerPage
	 */
	public function changeCategoryState($categoryName, $state = 'published')
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $categoryName . "']"), 10);
		$row = $this->getRowNumber($categoryName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();

		if (strtolower($state) == 'published')
		{
			$elementObject->findElement(By::xPath("//li[@id='toolbar-publish']/a"))->click();
			$this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));
		}
		elseif (strtolower($state) == 'unpublished')
		{
			$elementObject->findElement(By::xPath("//li[@id='toolbar-unpublish']/a"))->click();
			$this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));
		}
	}

	/**
	 * Function to Copy Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 *
	 * @return RedShopCategoriesManagerPage
	 */
	public function copyCategory($categoryName)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='category_main_filter']"));
		$searchField->sendKeys($categoryName);
		$elementObject->findElement(By::xPath("//button[@onclick=\"document.adminForm.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $categoryName . "']"), 10);
		$row = $this->getRowNumber($categoryName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-copy']/a"))->click();
		sleep(4);
	}
}
