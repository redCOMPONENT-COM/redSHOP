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
 * Page class for the back-end Fields Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopFieldsManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Custom Field Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=fields';

	/**
	 * Function to create a new Field
	 *
	 * @param   string  $type     Type of Field
	 *
	 * @param   string  $section  Section for which the Field is to be created
	 *
	 * @param   string  $name     Name of the field.
	 *
	 * @param   string  $title    Title for the Field
	 *
	 * @param   string  $class    Class for the Field
	 *
	 * @return RedShopFieldsManagerPage
	 */
	public function addField($type = 'Input', $section = 'category', $name = 'Sample Field', $title = 'sample Title', $class = 'Sample Class')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='field_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='field_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$titleField = $elementObject->findElement(By::xPath("//input[@id='field_title']"));
		$titleField->clear();
		$titleField->sendKeys($title);
		$classField = $elementObject->findElement(By::xPath("//input[@id='field_class']"));
		$classField->clear();
		$classField->sendKeys($class);
		$elementObject->findElement(By::xPath("//option[contains(text(),'" . $type . "')]"))->click();
		sleep(2);
		$elementObject->findElement(By::xPath("//option[contains(text(),'" . $section . "')]"))->click();
		sleep(2);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Field details saved')]"), 10);
	}

	/**
	 * Function to edit a Field
	 *
	 * @param   string  $field       Name of the Field
	 *
	 * @param   string  $newValue    New value for that field
	 *
	 * @param   string  $fieldTitle  Field which are going to edit
	 *
	 * @return RedShopFieldsManagerPage
	 */
	public function editField($field, $newValue, $fieldTitle)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($fieldTitle);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();
		sleep(2);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $fieldTitle . "')]"), 10);
		$row = $this->getRowNumber($fieldTitle) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='field_name']"), 10);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='field_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "Type":
				$elementObject->findElement(By::xPath("//option[contains(text(),'" . $newValue . "')]"))->click();
				sleep(2);
				break;
			case "Section":
				$elementObject->findElement(By::xPath("//option[contains(text(),'" . $newValue . "')]"))->click();
				sleep(2);
				break;
			case "Class":
				$classField = $elementObject->findElement(By::xPath("//input[@id='field_class']"));
				$classField->clear();
				$classField->sendKeys($newValue);
				break;
			case "Title":
				$titleField = $elementObject->findElement(By::xPath("//input[@id='field_title']"));
				$titleField->clear();
				$titleField->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Field details saved')]"), 10);
	}

	/**
	 * Function to delete a Field
	 *
	 * @param   string  $fieldTitle  Field which we are going to delete
	 *
	 * @return RedShopFieldsManagerPage
	 */
	public function deleteField($fieldTitle)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($fieldTitle);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();
		sleep(2);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $fieldTitle . "')]"), 10);
		$row = $this->getRowNumber($fieldTitle) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 * Function  to search for a Field
	 *
	 * @param   string  $fieldTitle    Title of the field which we are going to search
	 *
	 * @param   string  $functionName  Name of the function after which the Search function is getting called
	 *
	 * @return bool True or False depending on the value
	 */
	public function searchField($fieldTitle, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($fieldTitle);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();
		sleep(2);
		$row = $this->getRowNumber($fieldTitle) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $fieldTitle . "')]"));

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
	 * Function to change a Field State
	 *
	 * @param   string  $fieldTitle  title of the field for which the state is to be changed
	 *
	 * @param   string  $state       New state for the Field
	 *
	 * @return RedShopFieldsManagerPage
	 */
	public function changeFieldState($fieldTitle, $state = 'published')
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($fieldTitle);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();
		sleep(2);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $fieldTitle . "')]"), 10);
		$row = $this->getRowNumber($fieldTitle) - 1;
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
	 * Function to get state
	 *
	 * @param   string  $fieldTitle  Title of the Field for which we need the State
	 *
	 * @return string Value of the State
	 */
	public function getState($fieldTitle)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($fieldTitle);
		$elementObject->findElement(By::xPath("//button[@onclick='this.form.submit();']"))->click();
		sleep(2);
		$row = $this->getRowNumber($fieldTitle);
		$text = $this->driver->findElement(By::xPath("//tbody/tr[" . $row . "]/td[8]//a"))->getAttribute(@onclick);

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
}
