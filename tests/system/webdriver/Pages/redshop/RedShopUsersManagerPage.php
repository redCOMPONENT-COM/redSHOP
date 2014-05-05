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
	protected $waitForXpath = "//div[@id='toolbar-box']";

	// We need to change this, and update it to the header of the View

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=user';

	public function addUser($firstName = 'Testing', $lastName = 'LastName', $userName = 'User', $password = '1234', $email = 'redshop@redshop.com', $shopperGroup = 'Default Private')
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
}
