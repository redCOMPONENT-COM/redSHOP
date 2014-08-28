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
 * Page class for the back-end States Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopStatesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[text() = 'States']";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=state';

	/**
	 * Function to Add a State
	 *
	 * @param   string  $stateName    Name of the New State
	 * @param   string  $codeThree    Three Code of the State
	 * @param   string  $codeTwo      Two code of the State
	 * @param   string  $countryName  Name of the Country
	 *
	 * @return RedShopStatesManagerPage
	 */
	public function addState($stateName = 'Sample', $codeThree = '123', $codeTwo = '33', $countryName = 'Sample Country')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$this->checkNoticesForEditView(get_class($this));
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='state_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='state_name']"));
		$nameField->clear();
		$nameField->sendKeys($stateName);
		$codeThreeField = $elementObject->findElement(By::xPath("//input[@id='state_3_code']"));
		$codeThreeField->clear();
		$codeThreeField->sendKeys($codeThree);
		$codeTwoField = $elementObject->findElement(By::xPath("//input[@id='state_2_code']"));
		$codeTwoField->clear();
		$codeTwoField->sendKeys($codeTwo);
		$elementObject->findElement(By::xPath("//option[text() = '" . $countryName . "']"))->click();
		sleep(1);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='country_main_filter']"), 30);
	}

	/**
	 * Function to Update a State
	 *
	 * @param   string  $field     Field which is to be updated
	 * @param   stirng  $newValue  New value of the Field
	 * @param   string  $name      Name of the State which is to be updated
	 *
	 * @return RedShopStatesManagerPage
	 */
	public function editState($field, $newValue, $name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $name . "']"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$this->checkNoticesForEditView(get_class($this));
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='state_name']"), 30);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='state_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "3-Code":
				$codeThreeField = $elementObject->findElement(By::xPath("//input[@id='state_3_code']"));
				$codeThreeField->clear();
				$codeThreeField->sendKeys($newValue);
				break;
			case "2-Code":
				$codeTwoField = $elementObject->findElement(By::xPath("//input[@id='state_2_code']"));
				$codeTwoField->clear();
				$codeTwoField->sendKeys($newValue);
				break;
			case "Country":
				$elementObject->findElement(By::xPath("//option[text() = '" . $newValue . "']"))->click();
				sleep(1);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='country_main_filter']"), 30);
	}

	/**
	 * Function to get Row Number
	 *
	 * @param   string  $name  Name of the State
	 *
	 * @return bool|int|mixed
	 */
	public function getRowNumber($name)
	{
		$result = false;
		$tableElements = $this->driver->findElements(By::xPath("//form/table[@class='adminlist']/tbody"));
		$noOfElements = count($tableElements);

		if (isset($tableElements[0]))
		{
			$rowElements = $this->driver->findElement(By::xPath("//form/table[@class='adminlist']/tbody"))->findElements(By::tagName('tr'));
			$count = count($rowElements);

			for ($i = 0; $i < $count; $i ++)
			{
				$rowText = $rowElements[$i]->getText();

				if (strpos(strtolower($rowText), strtolower($name)) !== false)
				{
					$result = $i + 1;
					break;
				}
			}
		}

		return $result;
	}

	/**
	 * Function to Delete a State
	 *
	 * @param   string  $name  Name of the State
	 *
	 * @return RedShopStatesManagerPage
	 */
	public function deleteState($name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[text() = '" . $name . "']"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 * Function to Search for a State
	 *
	 * @param   string  $name          Name of the State
	 * @param   string  $functionName  Name of the Function after which search is being called
	 *
	 * @return bool
	 */
	public function searchState($name, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$row = $this->getRowNumber($name) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[text() = '" . $name . "']"));

		if (count($arrayElement))
		{
			return true;
		}

		return false;
	}

	/**
	 * Function to get the three digit code for a state
	 *
	 * @param   string  $name  Name of the State
	 *
	 * @return String Value of the Code
	 */
	public function getThreeCode($name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$row = $this->getRowNumber($name);
		$fieldValue = $elementObject->findElement(By::xPath("//tbody/tr[" . $row . "]/td[5]"))->getText();

		return $fieldValue;
	}

	/**
	 * Function to get the two digit code
	 *
	 * @param   string  $name  Name of the State
	 *
	 * @return String Value of the two digit code
	 */
	public function getTwoCode($name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$row = $this->getRowNumber($name);
		$fieldValue = $elementObject->findElement(By::xPath("//tbody/tr[" . $row . "]/td[6]"))->getText();

		return $fieldValue;
	}

	/**
	 * Function to get the country Name for the State
	 *
	 * @param   string  $name  Name of the State
	 *
	 * @return String Name of the COuntry
	 */
	public function getCountry($name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->clear();
		$searchField = $elementObject->findElement(By::xPath("//input[@id='country_main_filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//input[@type='submit']"))->click();
		sleep(2);
		$row = $this->getRowNumber($name);
		$fieldValue = $elementObject->findElement(By::xPath("//tbody/tr[" . $row . "]/td[4]"))->getText();

		return $fieldValue;
	}
}
