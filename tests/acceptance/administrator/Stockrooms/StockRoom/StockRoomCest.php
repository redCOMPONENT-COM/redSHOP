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
class StockRoomCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('StockRoomCest ?##?');
		$this->newName = 'Updated ' . $this->name;
		$this->amount = $this->faker->numberBetween(1,100);
	}

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
    /**
     * Function to Test Stock Room Creation in Backend
     *
     */
    public function createUpdateStockRoom(AcceptanceTester $I, $scenario)
    {
        $I = new AcceptanceTester\ConfigurationSteps($scenario);
        $I->wantTo('Test use Stockroom in Administrator');
        $I->featureUsedStockRoom();

        $I->wantTo('Test Stock Room creation in Administrator');
        $I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
        $I->addStockRoom($this->name, $this->amount);
        $I->searchStockRoom($this->name);

        $I->wantTo('Test if Stock Room gets updated in Administrator');
        $I->editStockRoom($this->name, $this->newName);
        $I->searchStockRoom($this->newName);
    }

    /**
     * Test for State Change in Stock Room Administrator
     *
     * @depends createUpdateStockRoom
     */
    public function changeStockRoomState(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State of a Stock Room gets Updated in Administrator');
        $I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
        $I->changeStockRoomState($this->newName);
        $I->waitForElement(\AdminJ3Page::$resetButton, 30);
        $I->click(\AdminJ3Page::$resetButton);
        $I->filterListBySearching($this->newName, $searchField = ['id' => 'filter']);
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

        $I = new AcceptanceTester\ConfigurationSteps($scenario);
        $I->wantTo('Test off Stockroom in Administrator');
        $I->featureOffStockRoom();
    }
}
