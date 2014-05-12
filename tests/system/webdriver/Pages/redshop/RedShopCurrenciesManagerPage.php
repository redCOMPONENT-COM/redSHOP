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
 * Page class for the back-end Currency Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopCurrenciesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Currency Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=currency';

	/**
	 * Function to Add a New Currency
	 *
	 * @param   string  $name  Name of the Currency
	 * @param   string  $code  Code of the Currency
	 *
	 * @return RedShopCurrenciesManagerPage
	 */
	public function addCurrency($name = '1RedShop', $code = '123')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='currency_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='currency_name']"));
		$nameField->clear();
		$nameField->sendKeys($name);
		$codeField = $elementObject->findElement(By::xPath("//input[@id='currency_code']"));
		$codeField->clear();
		$codeField->sendKeys($code);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Currency Detail Saved')]"), 10);
	}

	/**
	 * Function to Edit a Currency
	 *
	 * @param   string  $field     Field which is to be Updated
	 * @param   string  $newValue  New value of the Field
	 * @param   string  $name      Name of the Currency
	 *
	 * @return RedShopCurrenciesManagerPage
	 */
	public function editCurrency($field, $newValue, $name)
	{
		$elementObject = $this->driver;
		$this->sortData();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name);
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='currency_name']"));

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='currency_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "Code":
				$codeField = $elementObject->findElement(By::xPath("//input[@id='currency_code']"));
				$codeField->clear();
				$codeField->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Currency Detail Saved')]"), 10);
		$this->sortData();
	}

	/**
	 * Function to Toggle the Data and sort it
	 *
	 * @return RedShopCurrenciesManagerPage
	 */
	public function sortData()
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[contains(text(),'ID')]"))->click();
		sleep(2);
	}

	/**
	 * Function to Delete a Currency
	 *
	 * @param   string  $name  Name of the Currency which is to be Deleted
	 *
	 * @return RedShopCurrenciesManagerPage
	 */
	public function deleteCurrency($name)
	{
		$elementObject = $this->driver;
		$this->sortData();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
		$this->sortData();
	}

	/**
	 * Function to Search for a Currency
	 *
	 * @param   string  $name          Name of the Currency which we are searching for
	 * @param   string  $functionName  Name of the Function after which the search function is being called
	 *
	 * @return bool True or Flase Depending on the Value
	 */
	public function searchCurrency($name, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$this->sortData();
		$row = $this->getRowNumber($name) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $name . "')]"));

		if (count($arrayElement))
		{
			$this->sortData();

			return true;
		}
		else
		{
			$this->sortData();

			return false;
		}
	}

	public function getRowNumber($name)
	{
		$result = false;
		$tableElements = $this->driver->findElements(By::xPath("//form//table[@class='adminlist']/tbody"));
		$noOfElements = count($tableElements);


		if (isset($tableElements[0]))
		{
			$rowElements = $this->driver->findElement(By::xPath("//form//table[@class='adminlist']/tbody"))->findElements(By::tagName('tr'));
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
}
