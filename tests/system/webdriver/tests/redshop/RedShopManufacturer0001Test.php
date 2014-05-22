<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Manufacturer Add/Edit.
 *
 * @package     RedShop2.Test
 * @subpackage  Webdriver
 * @since       2.0
 */
class RedShopManufacturer0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopManufacturersManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Manufacturer';

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
	 * Function to test Creation of Manufacturers
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createManufacturer()
	{
		$rand = rand();
		$name = 'RedShopManufacturer' . $rand;
		$template = 14;
		$email = $rand . '@' . $rand . '.com';
		$url = 'http://www.redcomponent.com';
		$productPerPage = 10;
		$this->appTestPage->addManufacturer($name, $template, $email, $url, $productPerPage);
		$this->assertTrue($this->appTestPage->existManufacturer($name), 'Field Must be Present');
		$this->appTestPage->deleteManufacturer($name);
		$this->assertFalse($this->appTestPage->existManufacturer($name), 'Field Must not Exist');
	}

	/**
	 * Function to test the edition of a Manufacturer
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateManufacturer()
	{
		$rand = rand();
		$name = 'RedShopManufacturer' . $rand;
		$template = 14;
		$email = $rand . '@' . $rand . '.com';
		$url = 'http://www.redcomponent.com';
		$productPerPage = 10;
		$this->appTestPage->addManufacturer($name, $template, $email, $url, $productPerPage);
		$this->assertTrue($this->appTestPage->existManufacturer($name), 'Field Must be Present');

		$newName = 'NewManufacturer' . $rand;
		$this->appTestPage->editManufacturer($name, $newName);
		$this->assertTrue($this->appTestPage->existManufacturer($newName), 'Field Must be Present');

		$this->appTestPage->deleteManufacturer($newName);
		$this->assertFalse($this->appTestPage->existManufacturer($newName), 'Field Must not Exist');
	}
}