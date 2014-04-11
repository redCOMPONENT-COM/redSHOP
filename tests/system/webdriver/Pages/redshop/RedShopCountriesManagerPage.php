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
 * Page class for the back-end Country Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopCountriesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Country Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=country';

	/**
	 * Function to add a new Country to RedShop
	 *
	 * @param   string  $name       Country Name
	 * @param   string  $codeThree  Value for three Code
	 * @param   string  $codeTwo    Value for Two Code
	 * @param   string  $country    Name of the COuntry
	 *
	 * @return RedShopCountriesManagerPage
	 */
	public function addCountry($name = 'Sample Country', $codeThree = '123', $codeTwo = '22', $country = 'Test')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='country_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='country_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$codeThreeField = $elementObject->findElement(By::xPath("//input[@id='country_3_code']"));
		$codeThreeField->clear();
		$codeThreeField->sendKeys($codeThree);
		$codeTwoField = $elementObject->findElement(By::xPath("//input[@id='country_2_code']"));
		$codeTwoField->clear();
		$codeTwoField->sendKeys($codeTwo);
		$countryField = $elementObject->findElement(By::xPath("//input[@id='country_jtext']"));
		$countryField->clear();
		$countryField->sendKeys($country);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Country detail saved')]"), 10);
	}

	/**
	 * Function to Edit a Country Detail
	 *
	 * @param   string  $field     Name of the Field
	 * @param   string  $newValue  New value for the Field
	 * @param   string  $name      Name of the Country
	 *
	 * @return RedShopCountriesManagerPage
	 */
	public function editCountry($field, $newValue, $name)
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[contains(.,'Country Name')]"))->click();
		sleep(1);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='country_name']"), 10);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='country_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "3-Code":
				$codeThreeField = $elementObject->findElement(By::xPath("//input[@id='country_3_code']"));
				$codeThreeField->clear();
				$codeThreeField->sendKeys($newValue);
				break;
			case "2-Code":
				$codeTwoField = $elementObject->findElement(By::xPath("//input[@id='country_2_code']"));
				$codeTwoField->clear();
				$codeTwoField->sendKeys($newValue);
				break;
			case "Country":
				$countryField = $elementObject->findElement(By::xPath("//input[@id='country_jtext']"));
				$countryField->clear();
				$countryField->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Country detail saved')]"), 10);
	}

	/**
	 * Function to Delete a Country
	 *
	 * @param   string  $name  Name of the Country
	 *
	 * @return RedShopCountriesManagerPage
	 */
	public function deleteCountry($name)
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[contains(.,'Country Name')]"))->click();
		sleep(1);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 * Function to Search for a Country
	 *
	 * @param   string  $name          Name of the Country
	 * @param   string  $functionName  Name of the Function after Which Search is getting Called
	 *
	 * @return bool True or False Depending on the Value
	 */
	public function searchCountry($name, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[contains(.,'Country Name')]"))->click();
		sleep(1);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
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

	public function getTwoCode($name)
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[contains(.,'Country Name')]"))->click();
		sleep(1);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name);
		$fieldValue = $elementObject->findElement(By::xPath("//tbody/tr[" . $row . "]/td[5]"))->getText();

		return $fieldValue;
	}
}
