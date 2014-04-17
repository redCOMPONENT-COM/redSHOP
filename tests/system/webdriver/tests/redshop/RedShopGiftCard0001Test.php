<?php
/**
 * @package     RedShop
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Gift Cards Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       2.0
 */
class RedShopGiftCard0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopGiftCardsManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_GiftCard';

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
	 * Function to Test Creation of Cards
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createCard()
	{
		$rand = rand();
		$name = 'RedShopGift' . $rand;
		$price = '100';
		$value = '50';
		$validity = '10';
		$description = 'This is Test Description' . $rand;
		$this->appTestPage->addCard($name, $price, $value, $validity, $description);
		$this->assertTrue($this->appTestPage->searchCard($name), 'Gift Card Must be Created');
		$this->appTestPage->deleteCard($name);
		$this->assertFalse($this->appTestPage->searchCard($name, 'Delete'), 'Gift Card Must be Deleted');
	}

	/**
	 * Function to Test the Updating Feature
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateCard()
	{
		$rand = rand();
		$name = 'RedShopGift' . $rand;
		$newName = 'RedShopNewGift' . $rand;
		$price = '100';
		$value = '50';
		$validity = '10';
		$description = 'This is Test Description' . $rand;
		$this->appTestPage->addCard($name, $price, $value, $validity, $description);
		$this->assertTrue($this->appTestPage->searchCard($name), 'Gift Card Must be Created');
		$this->appTestPage->editCard('Name', $newName, $name);
		$this->assertTrue($this->appTestPage->searchCard($newName), 'Gift Card Must be Updated');
		$this->appTestPage->deleteCard($newName);
		$this->assertFalse($this->appTestPage->searchCard($newName, 'Delete'), 'Gift Card Must be Deleted');
	}

	/**
	 * Function to Test Change of State for a gift card
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$name = 'RedShopGift' . $rand;
		$price = '100';
		$value = '50';
		$validity = '10';
		$description = 'This is Test Description' . $rand;
		$this->appTestPage->addCard($name, $price, $value, $validity, $description);
		$this->assertTrue($this->appTestPage->searchCard($name), 'Gift Card Must be Created');
		$this->appTestPage->changeCardState($name, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($name), 'unpublished', 'Gift card state Must be changed to unpublished now');
		$this->appTestPage->deleteCard($name);
		$this->assertFalse($this->appTestPage->searchCard($name, 'Delete'), 'Gift Card Must be Deleted');
	}

	/**
	 * Function to Test functionality of Copy Function
	 *
	 * @test
	 *
	 * @return void
	 */
	public function checkCopy()
	{
		$rand = rand();
		$name = 'RedShopGift' . $rand;
		$copyCardName = 'Copy of ' . $name;
		$price = '100';
		$value = '50';
		$validity = '10';
		$description = 'This is Test Description' . $rand;
		$this->appTestPage->addCard($name, $price, $value, $validity, $description);
		$this->assertTrue($this->appTestPage->searchCard($name), 'Gift Card Must be Created');
		$this->appTestPage->copyCard($name);
		$this->assertTrue($this->appTestPage->searchCard($copyCardName), 'A Copy Gift Card must be created');
		$this->appTestPage->deleteCard($copyCardName);
		$this->appTestPage->deleteCard($name);
		$this->assertFalse($this->appTestPage->searchCard($name, 'Delete'), 'Gift Card Must be Deleted');
		$this->assertFalse($this->appTestPage->searchCard($copyCardName, 'Delete'), 'Gift Card Must be Deleted');
	}
}
