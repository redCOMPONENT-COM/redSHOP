<?php
/**
 * @package     RedCore
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Category Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopCategory0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopCategoriesManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Category';

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
	 * Function to Test Category Creation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createCategory()
	{
		$rand = rand();
		$name = 'Red Category' . $rand;
		$noOfProducts = rand(10, 99);
		$this->appTestPage->addCategory($name, $noOfProducts);
		$this->assertTrue($this->appTestPage->searchCategory($name), 'Category Must be Created');
		$this->appTestPage->deleteCategory($name);
		$this->assertFalse($this->appTestPage->searchCategory($name, 'Delete'), 'Category Must be Deleted');
	}

	/**
	 * Function to verify Update Feature
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateCategory()
	{
		$rand = rand();
		$name = 'Red Category' . $rand;
		$newName = 'New Red Category' . $rand;
		$noOfProducts = rand(10, 99);
		$this->appTestPage->addCategory($name, $noOfProducts);
		$this->assertTrue($this->appTestPage->searchCategory($name), 'Category Must be Created');
		$this->appTestPage->editCategory('Name', $newName, $name);
		$this->assertTrue($this->appTestPage->searchCategory($newName), 'Category Must be Updated');
		$this->appTestPage->deleteCategory($newName);
		$this->assertFalse($this->appTestPage->searchCategory($newName, 'Delete'), 'Category Must be Deleted');
	}

	/**
	 * Function to verify state change
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$name = 'Red Category' . $rand;
		$noOfProducts = rand(10, 99);
		$this->appTestPage->addCategory($name, $noOfProducts);
		$this->assertTrue($this->appTestPage->searchCategory($name), 'Category Must be Created');
		$this->appTestPage->changeCategoryState($name, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($name), 'unpublished', 'Category state must be Unpublished Now');
		$this->appTestPage->deleteCategory($name);
		$this->assertFalse($this->appTestPage->searchCategory($name, 'Delete'), 'Category Must be Deleted');
	}
}
