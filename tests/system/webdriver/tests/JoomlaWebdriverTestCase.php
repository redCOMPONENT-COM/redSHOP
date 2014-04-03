<?php
/**
 * @package     RedShopb.Test
 * @subpackage  Webdriver
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once '../bootstrap.php';
require_once '../../servers/configdef.php';

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\Http\HttpFactory;
use SeleniumClient\Http\HttpClient;
use Pages\AdminLoginPage;
use Pages\ControlPanelPage;

/**
 * This class is base class for Testing application
 *
 * @package     RedShopb.Test
 * @subpackage  Webdriver
 * @since       3.0
 */
class JoomlaWebdriverTestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * SeleniumConfig so tests can get at the fields
	 *
	 * @var object
	 */
	public $cfg;

	/**
	 * Selenium WebDriver
	 *
	 * @var object
	 */
	protected $driver = null;

	/**
	 * URL from configuration file
	 *
	 * @var string
	 */
	protected $testUrl = null;

	/**
	 * The page class being tested.
	 *
	 * @var     object
	 * @since   3.0
	 */
	protected $appTestPage = null;

	/**
	 * The menu group being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = null;

	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = null;

	/**
	 * Setup Web object for testing
	 *
	 * @return void
	 *
	 * @since   3.0
	 */
	public function setUp()
	{
		$this->cfg = new SeleniumConfig();
		$this->testUrl = $this->cfg->host . $this->cfg->path;
		switch ($this->cfg->browser)
		{
			case '*chrome':
				$browser = 'firefox';
				break;
			case '*googlechrome':
			default:
				$browser = 'chrome';
				break;
		}
		$desiredCapabilities = new DesiredCapabilities($browser);
		$this->driver = new WebDriver($desiredCapabilities);

		if (isset($this->cfg->windowSize) && is_array($this->cfg->windowSize))
		{
			$this->driver->setCurrentWindowSize($this->cfg->windowSize[0], $this->cfg->windowSize[1]);
		}
		else
		{
			$this->driver->setCurrentWindowSize(1280, 1024);
		}
	}

	/**
	 * Logout and close test.
	 *
	 * @return void
	 *
	 * @since   3.0
	 */
	public function tearDown()
	{
		if ($this->driver != null)
		{
			$this->driver->clearCurrentCookies();
			$this->driver->quit();
		}
	}

	/**
	 * Page object for manipulating in test case
	 *
	 * @param   string  $type             Class name for object to create.
	 * @param   bool    $checkForNotices  If true, check for notices after page load
	 * @param   string  $url              Optional URL to load
	 *
	 * @return object
	 */
	public function getPageObject($type, $checkForNotices = true, $url = null)
	{
		$pageObject = new $type($this->driver, $this, $url);

		if ($checkForNotices)
		{
			$this->assertFalse($pageObject->checkForNotices(), 'PHP Notice found on page ' . $pageObject);
		}

		return $pageObject;
	}

	/**
	 * Login to administration of the application.
	 *
	 * @return object
	 *
	 * @since   3.0
	 */
	public function doAdminLogin()
	{
		$d = $this->driver;
		$d->clearCurrentCookies();
		$url = $this->cfg->host . $this->cfg->path . 'administrator/index.php';
		$loginPage = $this->getPageObject('AdminLoginPage', true, $url);

		// We are doing checks only on english version
		$arrayElement = $this->driver->findElements(By::xPath("//div[@id='lang_chzn']/a"));
		if(count($arrayElement))
		{
			$d->findElement(By::xPath("//div[@id='lang_chzn']/a"))->click();
			$d->findElement(By::xPath("//div[@id='lang_chzn']/div/div/input"))->sendKeys($this->cfg->language);
			$d->findElement(By::xPath("//div[@id='lang_chzn']//ul[@class='chzn-results']/li[contains(.,'" . $this->cfg->language . "')]"))->click();
			$cpPage = $loginPage->loginValidUser($this->cfg->username, $this->cfg->password);
			$this->assertTrue(is_a($cpPage, 'GenericAdminPage'));
		}
		else
		{
			$cpPage = $loginPage->loginValidUser($this->cfg->username, $this->cfg->password);
			$this->assertTrue(is_a($cpPage, 'GenericAdminPage'));
		}
		return $cpPage;
	}

	/**
	 * Logout of the application administration.
	 *
	 * @return object
	 *
	 * @since   3.0
	 */
	public function doAdminLogout()
	{
		// Clear cookies to force logout
		$this->driver->clearCurrentCookies();
		$url = $this->cfg->host . $this->cfg->path . 'administrator/index.php';
		$loginPage = $this->getPageObject('AdminloginPage', true, $url);
		$this->assertTrue(is_a($loginPage, 'AdminloginPage'));

		return $loginPage;
	}

	/**
	 * Login to frontend of the application.
	 *
	 * @return object
	 *
	 * @since   3.0
	 */
	public function doSiteLogin()
	{
		$username = $this->cfg->username;
		$password = $this->cfg->password;
		$d = $this->driver;
		$d->clearCurrentCookies();
		$url = $this->cfg->host . $this->cfg->path . 'index.php/login';
		$this->driver->get($url);
		$loginPage = $this->getPageObject('SiteLoginPage', true, $url);
		$loginPage->SiteLoginUser($username, $password);
		$loginPage = $this->getPageObject('SiteLoginPage', true, $url);
		$urlHome = $this->cfg->host . $this->cfg->path . 'index.php';
		$this->driver->get($urlHome);
		$homePage = $this->getPageObject('SiteContentFeaturedPage', true, $urlHome);

		return $homePage;
	}

	/**
	 * Logout of the application frontend.
	 *
	 * @return object
	 *
	 * @since   3.0
	 */
	public function doSiteLogout()
	{
		$url = $this->cfg->host . $this->cfg->path . 'index.php/login';
		$this->driver->get($url);
		$loginPage = $this->getPageObject('SiteLoginPage');
		$loginPage->SiteLogoutUser();
		$loginPage = $this->getPageObject('SiteLoginPage', true, $url);
		$urlHome = $this->cfg->host . $this->cfg->path . 'index.php';
		$this->driver->get($urlHome);
		$homePage = $this->getPageObject('SiteContentFeaturedPage', true, $urlHome);

		return $homePage;
	}

	/**
	 * Get Fields from Elements
	 *
	 * @param   object  $testElements  List of object
	 *
	 * @return object
	 */
	public function getActualFieldsFromElements($testElements)
	{
		$actualFields = array();

		foreach ($testElements as $el)
		{
			$el->labelText = (substr($el->labelText, -2) == ' *') ? substr($el->labelText, 0, -2) : $el->labelText;

			if (isset($el->group))
			{
				$actualFields[] = array('label' => $el->labelText, 'id' => $el->id, 'type' => $el->tag, 'tab' => $el->tab, 'group' => $el->group);
			}
			else
			{
				$actualFields[] = array('label' => $el->labelText, 'id' => $el->id, 'type' => $el->tag, 'tab' => $el->tab);
			}
		}

		return $actualFields;
	}

	/**
	 * Takes screenshot of current screen, saves it in specified default directory or as specified in parameter
	 *
	 * @param   String  $fileName  File Name
	 * @param   String  $folder    File folder
	 *
	 * @throws Exception
	 * @return string
	 */
	public function createScreenShot($fileName, $folder = null)
	{
		$this->driver->setCurrentWindowSize(1280, 1024);
		$screenshotsDirectory = null;

		if (isset($folder))
		{
			$screenshotsDirectory = $folder;
		}
		elseif ($this->driver->getScreenShotsDirectory())
		{
			$screenshotsDirectory = $this->driver->getScreenShotsDirectory();
		}
		else
		{
			throw new \Exception("Must Specify Screenshot Directory");
		}

		$command = "screenshot";
		$urlHubFormatted = $this->driver->getHubUrl() . "/session/{$this->driver->getSessionId()}/{$command}";

		$httpClient = HttpFactory::getClient($this->driver->getEnvironment());
		$results = $httpClient->setUrl($urlHubFormatted)->setHttpMethod(HttpClient::GET)->execute();

		if (isset($results["value"]) && trim($results["value"]) != "")
		{
			if (!file_exists($screenshotsDirectory))
			{
				mkdir($screenshotsDirectory, 0755, true);
			}

			$filePath = $screenshotsDirectory . "/" . $fileName;

			file_put_contents($filePath, base64_decode($results["value"]));

			return $filePath;
		}

		return null;
	}
}
