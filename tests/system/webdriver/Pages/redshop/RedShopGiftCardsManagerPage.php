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
class RedShopGiftCardsManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Gift Card Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=giftcard';

	/**
	 * Function to Add a Card
	 *
	 * @param   string  $name         Name of the Card
	 * @param   string  $price        Price of the card
	 * @param   string  $value        Value of the Card
	 * @param   string  $validity     Validity of the Card
	 * @param   string  $description  Description of the Card
	 *
	 * @return RedShopGiftCardsManagerPage
	 */
	public function addCard($name = 'Sample Card', $price = '100', $value = '10', $validity = '10', $description = 'Sample Gift Card')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='giftcard_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='giftcard_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$priceField = $elementObject->findElement(By::xPath("//input[@id='giftcard_price']"));
		$priceField->clear();
		$priceField->sendKeys($price);
		$valueField = $elementObject->findElement(By::xPath("//input[@id='giftcard_value']"));
		$valueField->clear();
		$valueField->sendKeys($value);
		$validityField = $elementObject->findElement(By::xPath("//input[@id='giftcard_validity']"));
		$validityField->clear();
		$validityField->sendKeys($validity);
		$elementObject->findElement(By::xPath("//a[@title='Toggle editor']"))->click();
		sleep(4);
		$descriptionField = $elementObject->findElement(By::xPath("//textarea[@id='jform_giftcard_desc']"));
		$descriptionField->clear();
		$descriptionField->sendKeys($description);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Gift Card Saved')]"), 10);
	}

	/**
	 * Function to Edit a Gift Card
	 *
	 * @param   string  $field     Field which is to be Updated
	 * @param   string  $newValue  New Value of the Field
	 * @param   string  $name      Name of the Card
	 *
	 * @return RedShopGiftCardsManagerPage
	 */
	public function editCard($field, $newValue, $name)
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='giftcard_name']"), 10);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='giftcard_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "Price":
				$priceField = $elementObject->findElement(By::xPath("//input[@id='giftcard_price']"));
				$priceField->clear();
				$priceField->sendKeys($newValue);
				break;
			case "Value":
				$valueField = $elementObject->findElement(By::xPath("//input[@id='giftcard_value']"));
				$valueField->clear();
				$valueField->sendKeys($newValue);
				break;
			case "Validity":
				$validityField = $elementObject->findElement(By::xPath("//input[@id='giftcard_validity']"));
				$validityField->clear();
				$validityField->sendKeys($newValue);
				break;
			case "Description":
				$elementObject->findElement(By::xPath("//a[@title='Toggle editor']"))->click();
				sleep(4);
				$descriptionField = $elementObject->findElement(By::xPath("//textarea[@id='giftcard_desc']"));
				$descriptionField->clear();
				$descriptionField->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Gift Card Saved')]"), 10);
	}

	/**
	 * Function to Delete a giftCard
	 *
	 * @param   string  $name  Name of the Card
	 *
	 * @return RedShopGiftCardsManagerpage
	 */
	public function deleteCard($name)
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 * Function to get state of a gift card
	 *
	 * @param   string  $name  Name of the Gift Card
	 *
	 * @return string   Depending on the Value
	 */
	public function getState($name)
	{
		$elementObject = $this->driver;
		$row = $this->getRowNumber($name);
		$text = $elementObject->findElement(By::xPath("//tbody/tr[" . $row . "]/td[9]//a"))->getAttribute(@onclick);

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
	 * Function to change State of a gift card
	 *
	 * @param   string  $name   Name of the Card
	 * @param   string  $state  New State of the Card
	 *
	 * @return RedShopGiftCardsManagerPage
	 */
	public function changeCardState($name, $state = 'published')
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
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
	 * Function to Copy a Card
	 *
	 * @param   string  $name  Name of the Gift Card
	 *
	 * @return RedShopGiftCardsManagerPage
	 */
	public function copyCard($name)
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-copy']/a"))->click();
		sleep(4);
	}

	/**
	 * Function to search for a Gift Card
	 *
	 * @param   string  $name          Name of the Gift Card
	 * @param   string  $functionName  Function after which the search function is getting called
	 *
	 * @return bool True or False Depending on the Value
	 */
	public function searchCard($name, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$row = $this->getRowNumber($name) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"));

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
