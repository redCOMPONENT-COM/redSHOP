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
}
