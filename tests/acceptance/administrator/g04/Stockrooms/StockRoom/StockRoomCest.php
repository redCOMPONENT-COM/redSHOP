<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\StockRoomManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class StockRoomCest
 * @since 1.4.0
 */
class StockRoomCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $name;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $newName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $amount;

	/**
	 * StockRoomCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker   = Faker\Factory::create();
		$this->name    = $this->faker->bothify('StockRoomCest ?##?');
		$this->newName = 'Updated ' . $this->name;
		$this->amount  = $this->faker->numberBetween(1,100);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Function to Test Stock Room Creation in Backend
	 *
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function createUpdateStockRoom(AcceptanceTester $I, $scenario)
	{
		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Test use Stockroom in Administrator');
		$I->featureUsedStockRoom();

		$I->wantTo('Delete all Stock Room in Administrator');
		$I = new StockRoomManagerJoomla3Steps($scenario);
		$I->deleteAllStockRoom();

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
	 *
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function changeStockRoomState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Stock Room gets Updated in Administrator');
		$I = new StockRoomManagerJoomla3Steps($scenario);
		$I->changeStockRoomState();
		$I->waitForElement(AdminJ3Page::$resetButton, 30);
		$I->click(AdminJ3Page::$resetButton);
		$I->filterListBySearching($this->newName, StockRoomManagerJoomla3Page::$searchField);
		$I->verifyState('unpublished', $I->getStockRoomState($this->newName));
	}

	/**
	 * Function to Test Stock Room Deletion
	 *
	 * @depends changeStockRoomState
	 *
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function deleteStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Stock Room in Administrator');
		$I = new StockRoomManagerJoomla3Steps($scenario);
		$I->deleteAllStockRoom();

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Test off Stockroom in Administrator');
		$I->featureOffStockRoom();
	}
}
