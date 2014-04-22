<?php
/**
 * @package     RedShop
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Text Library Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       2.0
 */
class RedShopTextLibrary0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopTextLibrariesManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_TextLibrary';

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
	 * Function to Test Library Creation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createLibrary()
	{
		$rand = rand();
		$tagName = 'RedShop Tag' . $rand;
		$tagDescription = 'RedShop Desc' . $rand;
		$this->appTestPage->addLibrary($tagName, $tagDescription);
		$this->assertTrue($this->appTestPage->searchLibrary($tagDescription), 'Library Must be created');
		$this->appTestPage->deleteLibrary($tagDescription);
		$this->assertFalse($this->appTestPage->searchLibrary($tagDescription, 'Delete'), 'Library Must be Deleted');
	}

	/**
	 * Function to Verify update Feature for Text Library
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateLibrary()
	{
		$rand = rand();
		$tagName = 'RedShop Tag' . $rand;
		$tagDescription = 'RedShop Desc' . $rand;
		$newTagDescription = 'New Desc' . $rand;
		$this->appTestPage->addLibrary($tagName, $tagDescription);
		$this->assertTrue($this->appTestPage->searchLibrary($tagDescription), 'Library Must be created');
		$this->appTestPage->editLibrary('Description', $newTagDescription, $tagDescription);
		$this->assertTrue($this->appTestPage->searchLibrary($newTagDescription), 'Tag Description must be Updated');
		$this->appTestPage->deleteLibrary($newTagDescription);
		$this->assertFalse($this->appTestPage->searchLibrary($newTagDescription, 'Delete'), 'Library Must be Deleted');
	}

	/**
	 * Function to verify State Change in Text Library
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$tagName = 'RedShop Tag' . $rand;
		$tagDescription = 'RedShop Desc' . $rand;
		$this->appTestPage->addLibrary($tagName, $tagDescription);
		$this->assertTrue($this->appTestPage->searchLibrary($tagDescription), 'Library Must be created');
		$this->appTestPage->changeLibraryState($tagDescription, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($tagDescription), 'unpublished', 'State Must be Changed');
		$this->appTestPage->deleteLibrary($tagDescription);
		$this->assertFalse($this->appTestPage->searchLibrary($tagDescription, 'Delete'), 'Library Must be Deleted');
	}
}
