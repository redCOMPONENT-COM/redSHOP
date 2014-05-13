<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  State Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopState0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopStatesManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_State';

	/**
	 * Function to login to the Application
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$cpPage = $this->doAdminLogin();
		$this->appTestPage = $cpPage->clickMenu($this->appMenuGroupName, $this->appMenuLinkName);
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
		$this->doAdminLogout();
		parent::tearDown();
	}

	/**
	 * Function to test creation of State
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createState()
	{
		$rand = rand();
		$elementObject = $this->driver;
		$config = new SeleniumConfig;
		$countryName = '01RedCountry' . $rand;
		$countryThreeDigit = rand(100, 999);
		$countryTwoDigit = rand(10, 99);
		$country = '01Sample' . $rand;
		$stateName = 'RedState' . $rand;
		$stateThreeDigit = rand(100, 999);
		$stateTwoDigit = rand(10, 99);
		$countryPage = 'administrator/index.php?option=com_redshop&view=country';
		$countryUrl = $config->host . $config->path . $countryPage;
		$elementObject->get($countryUrl);
		$this->redShopCountriesManagerPage = $this->getPageObject("RedShopCountriesManagerPage");
		$this->redShopCountriesManagerPage->addCountry($countryName, $countryThreeDigit, $countryTwoDigit, $country);
		$this->assertTrue($this->redShopCountriesManagerPage->searchCountry($countryName), 'Country Must be Created');
		$statePage = 'administrator/index.php?option=com_redshop&view=state';
		$stateUrl = $config->host . $config->path . $statePage;
		$elementObject->get($stateUrl);
		$this->redShopStatesManagerPage = $this->getPageObject("RedShopStatesManagerPage");
		$this->redShopStatesManagerPage->addState($stateName, $stateThreeDigit, $stateTwoDigit, $country);
		$this->assertTrue($this->redShopStatesManagerPage->searchState($stateName), 'State Must be Created');
		$this->assertEquals($this->redShopStatesManagerPage->getThreeCode($stateName), $stateThreeDigit, 'Three Digits Must be Equal');
		$this->assertEquals($this->redShopStatesManagerPage->getTwoCode($stateName), $stateTwoDigit, 'Two Digits Must be Equal');
		$this->assertEquals($this->redShopStatesManagerPage->getCountry($stateName), $countryName, 'Country Name must be Equal');
		$this->redShopStatesManagerPage->deleteState($stateName);
		$this->assertFalse($this->redShopStatesManagerPage->searchState($stateName, 'Delete'), 'State Must be Deleted');
		$elementObject->get($countryUrl);
		$this->redShopCountriesManagerPage = $this->getPageObject("RedShopCountriesManagerPage");
		$this->redShopCountriesManagerPage->deleteCountry($countryName);
	}

	/**
	 * Function to check for the update feature of State
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateState()
	{
		$rand = rand();
		$elementObject = $this->driver;
		$config = new SeleniumConfig;
		$countryName = '01RedCountry' . $rand;
		$countryThreeDigit = rand(100, 999);
		$countryTwoDigit = rand(10, 99);
		$country = '01Sample' . $rand;
		$stateName = 'RedState' . $rand;
		$stateNewName = 'NewRedState' . $rand;
		$stateThreeDigit = rand(100, 999);
		$stateTwoDigit = rand(10, 99);
		$countryPage = 'administrator/index.php?option=com_redshop&view=country';
		$countryUrl = $config->host . $config->path . $countryPage;
		$elementObject->get($countryUrl);
		$this->redShopCountriesManagerPage = $this->getPageObject("RedShopCountriesManagerPage");
		$this->redShopCountriesManagerPage->addCountry($countryName, $countryThreeDigit, $countryTwoDigit, $country);
		$this->assertTrue($this->redShopCountriesManagerPage->searchCountry($countryName), 'Country Must be Created');
		$statePage = 'administrator/index.php?option=com_redshop&view=state';
		$stateUrl = $config->host . $config->path . $statePage;
		$elementObject->get($stateUrl);
		$this->redShopStatesManagerPage = $this->getPageObject("RedShopStatesManagerPage");
		$this->redShopStatesManagerPage->addState($stateName, $stateThreeDigit, $stateTwoDigit, $country);
		$this->assertTrue($this->redShopStatesManagerPage->searchState($stateName), 'State Must be Created');
		$this->assertEquals($this->redShopStatesManagerPage->getThreeCode($stateName), $stateThreeDigit, 'Three Digits Must be Equal');
		$this->assertEquals($this->redShopStatesManagerPage->getTwoCode($stateName), $stateTwoDigit, 'Two Digits Must be Equal');
		$this->assertEquals($this->redShopStatesManagerPage->getCountry($stateName), $countryName, 'Country Name must be Equal');
		$this->redShopStatesManagerPage->editState('Name', $stateNewName, $stateName);
		$this->assertTrue($this->redShopStatesManagerPage->searchState($stateNewName), 'Name must be Updated');
		$this->redShopStatesManagerPage->deleteState($stateNewName);
		$this->assertFalse($this->redShopStatesManagerPage->searchState($stateNewName, 'Delete'), 'State Must be Deleted');
		$elementObject->get($countryUrl);
		$this->redShopCountriesManagerPage = $this->getPageObject("RedShopCountriesManagerPage");
		$this->redShopCountriesManagerPage->deleteCountry($countryName);
	}
}
