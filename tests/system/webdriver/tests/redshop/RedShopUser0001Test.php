<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the Users Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       2.0
 */
class RedShopUser0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopUsersManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_User';

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
	 * Function to Test User Creation
	 *
	 *
	 *
	 * @return void
	 */
	public function createUser()
	{
		$rand = rand();
		$userName = 'RedShopUser' . $rand;
		$email = 'RedShop' . $rand . '@fakeemail.com';
		$firstName = 'Raj' . $rand;
		$lastName = 'Red';
		$this->appTestPage->addUser($firstName, $lastName, $userName, $email);
		$this->assertTrue($this->appTestPage->searchUser($userName), 'User Must be Created');
		$this->appTestPage->deleteUser($userName);
		$this->assertFalse($this->appTestPage->searchUser($userName, 'Delete'), 'user must be deleted');
	}

	/**
	 * Function to Check Edit Feature
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateUser()
	{
		$rand = rand();
		$userName = 'RedShopUser' . $rand;
		$newUserName = 'NewUser' . $rand;
		$email = 'RedShop' . $rand . '@fakeemail.com';
		$firstName = 'Raj' . $rand;
		$lastName = 'Red';
		$this->appTestPage->addUser($firstName, $lastName, $userName, $email);
		$this->assertTrue($this->appTestPage->searchUser($userName), 'User Must be Created');
		$this->appTestPage->editUser('User Name', $newUserName, $userName);
		$this->assertTrue($this->appTestPage->searchUser($newUserName), 'user Name must be updated');
		$this->appTestPage->deleteUser($newUserName);
		$this->assertFalse($this->appTestPage->searchUser($newUserName, 'Delete'), 'user must be deleted');
	}
}
