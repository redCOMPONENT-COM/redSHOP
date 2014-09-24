<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Mail Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopMail0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopMailsManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Mail';

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
	 * Function to Test Basic Mail Creation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createMail()
	{
		$rand = rand();
		$name = 'RedMail Sample' . $rand;
		$subject = 'RedSubject Sample' . $rand;
		$section = 'Ask question about product';
		$this->appTestPage->addMail($name, $subject, $section);
		$this->assertTrue($this->appTestPage->searchMail($name), "Email Must be Created");
		$this->appTestPage->deleteMail($name);
		$this->assertFalse($this->appTestPage->searchMail($name, 'Delete'), "Email Must be Deleted");
	}

	/**
	 * Function to test basic Editing Functionality
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateMail()
	{
		$rand = rand();
		$name = 'RedMail Sample' . $rand;
		$newName = 'New Red Name' . $rand;
		$subject = 'RedSubject Sample' . $rand;
		$section = 'Ask question about product';
		$this->appTestPage->addMail($name, $subject, $section);
		$this->assertTrue($this->appTestPage->searchMail($name), "Email Must be Created");
		$this->appTestPage->editMail("Mail Name", $newName, $name);
		$this->assertTrue($this->appTestPage->searchMail($newName), "Mail Name must be Updated");
		$this->appTestPage->deleteMail($newName);
		$this->assertFalse($this->appTestPage->searchMail($newName, 'Delete'), "Email Must be Deleted");
	}

	/**
	 * Function to Test State Changing Functionality
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$name = 'RedMail Sample' . $rand;
		$subject = 'RedSubject Sample' . $rand;
		$section = 'Ask question about product';
		$this->appTestPage->addMail($name, $subject, $section);
		$this->assertTrue($this->appTestPage->searchMail($name), "Email Must be Created");
		$this->appTestPage->changeMailState($name, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($name), 'unpublished', 'Mail State Must be Changed');
		$this->appTestPage->deleteMail($name);
		$this->assertFalse($this->appTestPage->searchMail($name, 'Delete'), "Email Must be Deleted");
	}
}
