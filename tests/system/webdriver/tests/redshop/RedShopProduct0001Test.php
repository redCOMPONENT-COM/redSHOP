<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Product Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopProduct0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopProductsManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Product';

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
	 * Function to Test Product Creation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createProduct()
	{
		$rand = rand();
		$name = $rand . 'RedShop Product';
		$number = $rand;
		$price = 10;
		$category = 'redCOMPONENT';
		$this->appTestPage->addProduct($name, $number, $price, $category);
		$this->assertTrue($this->appTestPage->searchProduct($name), 'Product Must be Present');
		$this->appTestPage->deleteProduct($name);
		$this->assertFalse($this->appTestPage->searchProduct($name, 'Delete'), 'Product Must be Deleted');
	}

	/**
	 * Function to Test Product Updation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateProduct()
	{
		$rand = rand();
		$name = $rand . 'RedShop Product';
		$newName = $rand . 'New RedShop Product';
		$number = $rand;
		$price = 10;
		$category = 'redCOMPONENT';
		$this->appTestPage->addProduct($name, $number, $price, $category);
		$this->assertTrue($this->appTestPage->searchProduct($name), 'Product Must be Present');
		$this->appTestPage->editProduct("Name", $newName, $name);
		$this->assertTrue($this->appTestPage->searchProduct($newName), 'Product Must be Updated');
		$this->appTestPage->deleteProduct($newName);
		$this->assertFalse($this->appTestPage->searchProduct($newName, 'Delete'), 'Product Must be Deleted');
	}

	/**
	 * Function to test State Change of  Product
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$name = $rand . 'RedShop Product';
		$number = $rand;
		$price = 10;
		$category = 'redCOMPONENT';
		$this->appTestPage->addProduct($name, $number, $price, $category);
		$this->assertTrue($this->appTestPage->searchProduct($name), 'Product Must be Present');
		$this->appTestPage->changeProductState($name, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($name), 'unpublished', 'Product must be Unpublished');
		$this->appTestPage->deleteProduct($name);
		$this->assertFalse($this->appTestPage->searchProduct($name, 'Delete'), 'Product Must be Deleted');
	}
}
