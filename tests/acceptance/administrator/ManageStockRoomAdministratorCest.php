<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageStockRoomAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageStockRoomAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('ManageStockRoomAdministratorCest ?##?');
		$this->newName = 'Updated ' . $this->name;
		$this->amount = '100';
	}

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
	/**
	 * Function to Test Stock Room Creation in Backend
	 *
	 */
	public function createStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Stock Room creation in Administrator');
		$I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
		$I->addStockRoom($this->name, $this->amount);
		$I->searchStockRoom($this->name);
	}

	/**
	 * Function to Test Stock Room Updation in the Administrator
	 *
	 * @depends createStockRoom
	 */
	public function updateStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Stock Room gets updated in Administrator');
		$I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
		$I->editStockRoom($this->name, $this->newName);
		$I->searchStockRoom($this->newName);
	}

	/**
	 * Test for State Change in Stock Room Administrator
	 *
	 * @depends updateStockRoom
	 */
	public function changeStockRoomState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Stock Room gets Updated in Administrator');
		$I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
		$I->changeStockRoomState($this->newName);
		$I->verifyState('unpublished', $I->getStockRoomState($this->newName));
	}

	/**
	 * Function to Test Stock Room Deletion
	 *
	 * @depends changeStockRoomState
	 */
	public function deleteStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Stock Room in Administrator');
		$I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
		$I->deleteStockRoom($this->newName);
		$I->searchStockRoom($this->newName, 'Delete');
	}
}
