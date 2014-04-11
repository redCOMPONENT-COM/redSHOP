<?php
/**
 * @package     RedCore
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Country Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.4
 */
class RedShopCountry0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopCountriesManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Country';

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

	public function createCountry()
	{
		$rand = rand();
		$name = '01RedShopCountry' . $rand;
		$threeCode = rand(100, 999);
		$twoCode = rand(10, 99);
		$country = '01RedCountry' . $rand;
		$this->appTestPage->addCountry($name, $threeCode, $twoCode, $country);
		$this->assertTrue($this->appTestPage->searchCountry($name), 'Country Must be Created');
		$this->assertEquals($this->appTestPage->getThreeCode($name), $threeCode, 'Both Three Codes Must be Equal');
		$this->assertEquals($this->appTestPage->getTwoCode($name), $twoCode, 'Both Two Codes Must be Equal');
		$this->assertEquals($this->appTestPage->getCountry($name), $country, 'Both Country Must be Equal');
		$this->appTestPage->deleteCountry($name);
		$this->assertFalse($this->appTestPage->searchCountry($name, 'Delete'), 'Country Must be Deleted');
	}
}
