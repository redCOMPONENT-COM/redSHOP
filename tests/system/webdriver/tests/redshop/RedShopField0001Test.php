<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Field Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopField0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopFieldsManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Field';

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
	 * Function to Test Field Creation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createField()
	{
		$rand = rand();
		$type = 'Text area';
		$section = 'Category';
		$name = 'RedShop Field' . $rand;
		$title = 'RedShop Field' . $rand;
		$class = 'Sample Class' . $rand;
		$this->appTestPage->addField($type, $section, $name, $title, $class);
		$this->assertTrue($this->appTestPage->searchField($title), 'Field Must be Present');
		$this->assertEquals($this->appTestPage->getFieldType($title), $type, 'Both Must be Equal');
		$this->assertEquals($this->appTestPage->getFieldSection($title), $section, 'Both Must be Equal');
		$this->appTestPage->deleteField($title);
		$this->assertFalse($this->appTestPage->searchField($title, 'Delete'), 'Field Must be Deleted');
	}

	/**
	 * Function to Test Update Feature
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateField()
	{
		$rand = rand();
		$type = 'Text area';
		$section = 'Category';
		$name = 'RedShop Field' . $rand;
		$title = 'RedShop Field' . $rand;
		$newTitle = 'Updated Title' . $rand;
		$class = 'Sample Class' . $rand;
		$this->appTestPage->addField($type, $section, $name, $title, $class);
		$this->assertTrue($this->appTestPage->searchField($title), 'Field Must be Present');
		$this->assertEquals($this->appTestPage->getFieldType($title), $type, 'Both Must be Equal');
		$this->assertEquals($this->appTestPage->getFieldSection($title), $section, 'Both Must be Equal');
		$this->appTestPage->editField('Title', $newTitle, $title);
		$this->assertTrue($this->appTestPage->searchField($newTitle), 'Title Must be Updated');
		$this->appTestPage->deleteField($newTitle);
		$this->assertFalse($this->appTestPage->searchField($newTitle, 'Delete'), 'Field Must be Deleted');
	}

	/**
	 * Function to Test State Change Feature
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$type = 'Text area';
		$section = 'Category';
		$name = 'RedShop Field' . $rand;
		$title = 'RedShop Field' . $rand;
		$class = 'Sample Class' . $rand;
		$this->appTestPage->addField($type, $section, $name, $title, $class);
		$this->assertTrue($this->appTestPage->searchField($title), 'Field Must be Present');
		$this->assertEquals($this->appTestPage->getFieldType($title), $type, 'Both Must be Equal');
		$this->assertEquals($this->appTestPage->getFieldSection($title), $section, 'Both Must be Equal');
		$this->appTestPage->changeFieldState($title, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($title), 'unpublished', 'Field Must be Unpublished');
		$this->appTestPage->deleteField($title);
		$this->assertFalse($this->appTestPage->searchField($title, 'Delete'), 'Field Must be Deleted');
	}
}
