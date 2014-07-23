<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the back-end login screen
 *
 */
class AdminLoginPage extends AdminPage
{
	protected $waitForXpath =  "//input[@id='mod-login-username']";
	protected $url = 'administrator/index.php';

	public function loginValidUser($userName, $password)
	{
		$this->executeLogin($userName, $password);
		return $this->test->getPageObject('GenericAdminPage');
	}

	private function executeLogin($userName, $password)
	{
		$headerText = $this->driver->findElement(By::xPath("//a[@href='index.php']"));
		echo $headerText->getText();
		$webElement = $this->driver->findElement(By::xPath("//input[@id='mod-login-username']"));
		$webElement->clear();
		$webElement->sendKeys($this->cfg->username);
		$webElement = $this->driver->findElement(By::xPath("//input[@id='mod-login-password']"));
		$webElement->clear();
		$webElement->sendKeys($this->cfg->password);
		//access button
		$this->driver->findElement(By::xPath("//div[@class='button1']//a[contains(text(),'Log in')]"))->click();
	}

}
