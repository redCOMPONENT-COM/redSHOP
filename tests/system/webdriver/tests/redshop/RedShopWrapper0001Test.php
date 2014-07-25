<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Wrapper Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Wrapper
 * @since       1.4
 */
class RedShopWrapper0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopWrappersManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Wrapper';

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
	 * Function to Test Wrapper Creation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createWrapper()
	{
		$rand = rand();
		$wrapperName = 'RedShop Wrapper' . $rand;
		$wrapperPrice = '100';
		$displayPrice = '$ ' . $wrapperPrice . ',00';
		$this->appTestPage->addWrapper($wrapperName, $wrapperPrice);
		$this->assertTrue($this->appTestPage->searchWrapper($wrapperName), 'Wrapper Must be Created');
		$this->assertEquals($this->appTestPage->getPrice($wrapperName), $displayPrice, 'Both Must be Equal');
		$this->appTestPage->deleteWrapper($wrapperName);
		$this->assertFalse($this->appTestPage->searchWrapper($wrapperName, 'Delete'), 'Wrapper Must be Deleted');
	}

	/**
	 * Function to Check Update Feature
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateWrapper()
	{
		$rand = rand();
		$wrapperName = 'RedShop Wrapper' . $rand;
		$wrapperPrice = '100';
		$displayPrice = '$ ' . $wrapperPrice . ',00';
		$wrapperNewName = 'Updated Name' . $rand;
		$this->appTestPage->addWrapper($wrapperName, $wrapperPrice);
		$this->assertTrue($this->appTestPage->searchWrapper($wrapperName), 'Wrapper Must be Created');
		$this->assertEquals($this->appTestPage->getPrice($wrapperName), $displayPrice, 'Must be Equal');
		$this->appTestPage->editWrapper('Name', $wrapperNewName, $wrapperName);
		$this->assertTrue($this->appTestPage->searchWrapper($wrapperNewName), 'Wrapper Must be Updated');
		$this->appTestPage->deleteWrapper($wrapperNewName);
		$this->assertFalse($this->appTestPage->searchWrapper($wrapperNewName, 'Delete'), 'Wrapper Must be Deleted');
	}

	/**
	 * Function to verify State Change Feature
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$wrapperName = 'RedShop Wrapper' . $rand;
		$wrapperPrice = '100';
		$displayPrice = '$ ' . $wrapperPrice . ',00';
		$this->appTestPage->addWrapper($wrapperName, $wrapperPrice);
		$this->assertTrue($this->appTestPage->searchWrapper($wrapperName), 'Wrapper Must be Created');
		$this->assertEquals($this->appTestPage->getPrice($wrapperName), $displayPrice, 'Must be Equal');
		$this->appTestPage->changeWrapperState($wrapperName, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($wrapperName), 'unpublished', 'State Must be Changed');
		$this->appTestPage->deleteWrapper($wrapperName);
		$this->assertFalse($this->appTestPage->searchWrapper($wrapperName, 'Delete'), 'Wrapper Must be Deleted');
	}
}
