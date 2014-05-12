<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Currency Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopCurrency0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopCurrenciesManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Currency';

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
	 * Function to Add a new Currency, test the CRUD Part
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createCurrency()
	{
		$rand = rand();
		$name = 'RedShopCurrency' . $rand;
		$code = $rand;
		$this->appTestPage->addCurrency($name, $code);
		$this->assertTrue($this->appTestPage->searchCurrency($name), 'Currency Must be Created');
		$this->appTestPage->deleteCurrency($name);
		$this->assertFalse($this->appTestPage->searchCurrency($name, 'Delete'), 'Currency Must be Deleted');
	}

	/**
	 * Function to test the Edit feature of Currency Add/Edit Screen
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateCurrency()
	{
		$rand = rand();
		$name = 'RedShopCurrency' . $rand;
		$newName = 'NewCurrency' . $rand;
		$code = $rand;
		$this->appTestPage->addCurrency($name, $code);
		$this->assertTrue($this->appTestPage->searchCurrency($name), 'Currency Must be Created');
		$this->appTestPage->editCurrency('Name', $newName, $name);
		$this->assertTrue($this->appTestPage->searchCurrency($newName), 'Currency Must be Updated');
		$this->appTestPage->deleteCurrency($newName);
		$this->assertFalse($this->appTestPage->searchCurrency($newName, 'Delete'), 'Currency Must be Deleted');
	}
}
