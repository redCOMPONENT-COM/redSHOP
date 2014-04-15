<?php
/**
 * @package     RedCore
 * @subpackage  Model
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
 * Page class for the back-end Wrapper Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Wrappers
 * @since       1.4
 */
class RedShopWrappersManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Wrapping Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=wrapper';

	/**
	 * Function to Add a Wrapper
	 *
	 * @param   string  $wrapperName   Name of the Wrapper
	 * @param   string  $wrapperPrice  Price of the Wrapper
	 *
	 * @return RedShopWrappersManagerPage
	 */
	public function addWrapper($wrapperName = 'Sample', $wrapperPrice = '100')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='wrapper_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='wrapper_name']"));
		$nameField->clear();
		$nameField->sendKeys($wrapperName);
		$priceField = $elementObject->findElement(By::xPath("//input[@id='wrapper_price']"));
		$priceField->clear();
		$priceField->sendKeys($wrapperPrice);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Wrapping detail saved')]"), 10);
	}

	/**
	 * Function to edit a Wrapper
	 *
	 * @param   string  $field        Name of the Field which is to be updated
	 * @param   string  $newValue     New Value of the Field
	 * @param   string  $wrapperName  Name of the Wrapper
	 *
	 * @return RedShopWrappersManagerPage
	 */
	public function editWrapper($field, $newValue, $wrapperName)
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $wrapperName . "')]"), 10);
		$row = $this->getRowNumber($wrapperName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='wrapper_name']"), 10);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='wrapper_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "Price":
				$priceField = $elementObject->findElement(By::xPath("//input[@id='wrapper_price']"));
				$priceField->clear();
				$priceField->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Wrapping detail saved')]"), 10);
	}

	/**
	 * Function to delete a Wrapper
	 *
	 * @param   string  $wrapperName  Name of the Wrapper
	 *
	 * @return RedShopWrappersManagerPage
	 */
	public function deleteWrapper($wrapperName)
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $wrapperName . "')]"), 10);
		$row = $this->getRowNumber($wrapperName) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 *
	 * Function to Search for a Wrapper
	 *
	 * @param   string  $wrapperName   Name of the Wrapper which we want to search
	 * @param   string  $functionName  Name of the function after which Search is getting called
	 *
	 * @return bool True or False Depending on the Value
	 */
	public function searchWrapper($wrapperName, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$row = $this->getRowNumber($wrapperName) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $wrapperName . "')]"));

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
