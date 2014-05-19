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
}
