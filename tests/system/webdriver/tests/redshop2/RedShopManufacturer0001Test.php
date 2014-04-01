<?php
/**
 * @package    RedCore
 * @subpackage Model
 * @copyright  Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
	protected $appMenuLinkName = 'RedShop2ManufacturersManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP2_Manufacturer';

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
	 * @since   3.0
	 */
	public function tearDown()
	{
		$this->doAdminLogout();
		parent::tearDown();
	}

	/**
	 * Function to Test Creation of a Manufacturer
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createManufacturer()
	{
		$rand = rand();
		$name = 'RedShop2Manufacturer' . $rand;
		$email = 'test' . $rand . '@test.com';
		$this->appTestPage->addManufacturer($name, $email);
		$this->assertTrue($this->appTestPage->searchManufacturer($name), 'Manufacturer Must be Created');
		$this->appTestPage->deleteManufacturer($name);
		$this->assertFalse($this->appTestPage->searchManufacturer($name, 'Delete'), 'Manufacturer Must be Deleted');
	}

	/**
	 * Function to Test Editing Functionality
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateManufacturer()
	{
		$rand = rand();
		$name = 'RedShop2Manufacturer' . $rand;
		$newName = 'RedShop2NewManufacturer' . $rand;
		$email = 'test' . $rand . '@test.com';
		$this->appTestPage->addManufacturer($name, $email);
		$this->assertTrue($this->appTestPage->searchManufacturer($name), 'Manufacturer Must be Created');
		$this->appTestPage->editManufacturer('Name', $newName, $name);
		$this->assertTrue($this->appTestPage->searchManufacturer($newName), 'Manufacturer Must be Updated');
		$this->appTestPage->deleteManufacturer($newName);
		$this->assertFalse($this->appTestPage->searchManufacturer($newName, 'Delete'), 'Manufacturer Must be Deleted');
	}

	/**
	 * Function to Change State of a Manufacturer
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$name = 'RedShop2Manufacturer' . $rand;
		$email = 'test' . $rand . '@test.com';
		$this->appTestPage->addManufacturer($name, $email);
		$this->assertTrue($this->appTestPage->searchManufacturer($name), 'Manufacturer Must be Created');
		$this->appTestPage->changeManufacturerState($name, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($name), 'unpublished', 'Manufacturer state must be Unpublished Now');
		$this->appTestPage->deleteManufacturer($name);
		$this->assertFalse($this->appTestPage->searchManufacturer($name, 'Delete'), 'Manufacturer Must be Deleted');
	}
}