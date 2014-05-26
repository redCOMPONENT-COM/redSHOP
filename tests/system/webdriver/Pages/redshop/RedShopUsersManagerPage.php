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
 * Page class for the back-end Users Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopUsersManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'User Management')]";

	// We need to change this, and update it to the header of the View

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=user';

	/**
	 * Function to Create a New User
	 *
	 * @param   string  $firstName     First Name of the User
	 * @param   string  $lastName      Last Name
	 * @param   string  $userName      User Name
	 * @param   string  $email         Email of the User
	 * @param   string  $password      Password of the User
	 * @param   string  $shopperGroup  Name of the Group
	 *
	 * @return RedShopUsersManagerPage
	 */
	public function addUser($firstName = 'Testing', $lastName = 'Name', $userName = 'User', $email = 'red@op.com', $password = '5121', $shopperGroup = 'Default Private')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$userNameField = $elementObject->findElement(By::xPath("//input[@id='username']"));
		$userNameField->clear();
		$userNameField->sendKeys($userName);
		$passwordField = $elementObject->findElement(By::xPath("//input[@id='password']"));
		$passwordField->clear();
		$passwordField->sendKeys($password);
		$confirmPassword = $elementObject->findElement(By::xPath("//input[@id='password2']"));
		$confirmPassword->clear();
		$confirmPassword->sendKeys($password);
		$emailField = $elementObject->findElement(By::xPath("//input[@id='email']"));
		$emailField->clear();
		$emailField->sendKeys($email);
		$elementObject->findElement(By::xPath("//select[@id='shopper_group_id']/option[contains(text(),'" . $shopperGroup . "')]"))->click();
		$elementObject->findElement(By::xPath("//li//input[@id='1group_1']"))->click();
		$elementObject->findElement(By::xPath("//dl[@id='pane']/dt[2]/span[contains(text(),'Billing Information')]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='firstname']"), 10);
		$firstNameField = $elementObject->findElement(By::xPath("//input[@id='firstname']"));
		$firstNameField->clear();
		$firstNameField->sendKeys($firstName);
		$lastNameField = $elementObject->findElement(By::xPath("//input[@id='lastname']"));
		$lastNameField->clear();
		$lastNameField->sendKeys($lastName);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'User detail saved')]"), 10);
	}

	/**
	 * Function to Edit a User Details
	 *
	 * @param   string  $field     Field which we are going to update
	 * @param   string  $newValue  New Value of the Field
	 * @param   string  $name      User Name of the User which is to be updated
	 *
	 * @return RedShopUsersManagerPage
	 */
	public function editUser($field, $newValue, $name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		sleep(4);
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//button[@onclick=\"this.form.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[6][contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->findElement(By::xPath("//dl[@id='pane']/dt[1]/span[contains(text(),'General User Information')]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='username']"), 10);

		switch ($field)
		{
			case "First Name":
				$elementObject->findElement(By::xPath("//dl[@id='pane']/dt[2]/span[contains(text(),'Billing Information')]"))->click();
				$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='firstname']"), 10);
				$firstNameField = $elementObject->findElement(By::xPath("//input[@id='firstname']"));
				$firstNameField->clear();
				$firstNameField->sendKeys($newValue);
				break;
			case "Last Name":
				$elementObject->findElement(By::xPath("//dl[@id='pane']/dt[2]/span[contains(text(),'Billing Information')]"))->click();
				$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='firstname']"), 10);
				$lastNameField = $elementObject->findElement(By::xPath("//input[@id='lastname']"));
				$lastNameField->clear();
				$lastNameField->sendKeys($newValue);
				break;
			case "User Name":
				$userNameField = $elementObject->findElement(By::xPath("//input[@id='username']"));
				$userNameField->clear();
				$userNameField->sendKeys($newValue);
				break;
			case "Email":
				$emailField = $elementObject->findElement(By::xPath("//input[@id='email']"));
				$emailField->clear();
				$emailField->sendKeys($newValue);
				break;
			case "Password":
				$passwordField = $elementObject->findElement(By::xPath("//input[@id='password']"));
				$passwordField->clear();
				$passwordField->sendKeys($newValue);
				$confirmPassword = $elementObject->findElement(By::xPath("//input[@id='password2']"));
				$confirmPassword->clear();
				$confirmPassword->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='filter']"), 10);
	}

	/**
	 * Function to Delete a User
	 *
	 * @param   string  $name  User Name of the User
	 *
	 * @return RedShopUsersManagerPage
	 */
	public function deleteUser($name)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		sleep(3);
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//button[@onclick=\"this.form.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[6][contains(text(),'" . $name . "')]"), 10);
		$row = $this->getRowNumber($name) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 * Function to Search for a User
	 *
	 * @param   string  $name          User Name of the user for which search is getting called
	 * @param   string  $functionName  Name of the Function after which Search is being called
	 *
	 * @return bool True or False Depending on the Value
	 */
	public function searchUser($name, $functionName = 'Search')
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField->sendKeys($name);
		$elementObject->findElement(By::xPath("//button[@onclick=\"this.form.submit();\"]"))->click();
		sleep(5);
		$row = $this->getRowNumber($name) - 1;

		if ($functionName == 'Search')
		{
			$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		}

		$arrayElement = $elementObject->findElements(By::xPath("//tbody/tr/td[6][contains(text(),'" . $name . "')]"));

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

