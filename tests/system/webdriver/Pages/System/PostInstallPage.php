<?php
use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the back-end Post Installation Screen.
 */
class PostInstallPage extends AdminPage
{
	/*
	 * Unique Path for the Page
	 */
	protected $waitForXpath = "//h2[contains(text(),'Post-installation and upgrade messages')]";

	/*
	 * Url for the Page
	 */
	protected $url = 'administrator/index.php?option=com_postinstall';
}