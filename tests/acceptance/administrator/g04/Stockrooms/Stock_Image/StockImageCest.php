<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\StockImageSteps;
use Configuration\ConfigurationSteps;
/**
 * Class ManageStockImageAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4
 */

class StockImageCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->nameStockImage = $this->faker->bothify('Stock Image ?##?');
		$this->nameStockRoom = $this->faker->bothify('Stock Room ?##?');
		$this->newImageTooltip = 'Updated ' . $this->nameStockImage;
		$this->quantity = $this->faker->numberBetween(1,100);
		$this->quantityNew = $this->faker->numberBetween(1,100);;
		$this->amountStockRoom = $this->faker->numberBetween(1,100);
		$this->amountStockImage = 'Higher than';
	}

	/**
	 * @param AcceptanceTester $I
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws Exception
	 */
	public function enableStock(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Enable StockRoom in Administrator page');
		$I = new ConfigurationSteps($scenario);
		$I -> featureUsedStockRoom();

		$I->wantTo('Test Stock Room creation in Administrator');
		$I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
		$I->addStockRoom($this->nameStockRoom, $this->amountStockRoom);
		/**
		 * Function to Create a Stock Image List
		 */
		$I->wantTo('Create Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->addStockImage($this->nameStockImage, $this->nameStockRoom, $this->amountStockImage, $this->quantity);

		/**
		 * Function to Update a Stock Image List
		 */
		$I->wantTo('Update Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->updateStockImage($this->nameStockImage, $this->newImageTooltip, $this->quantityNew);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws Exception
	 */
	public function disableStock(AcceptanceTester $I, $scenario)
	{
		/**
		 * Function to Delete a Stock Image List
		 */
		$I->wantTo('Deletion Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->deleteAllStockImage();

		$I->wantTo('Deletion of Stock Room in Administrator');
		$I = new AcceptanceTester\StockRoomManagerJoomla3Steps($scenario);
		$I->deleteAllStockRoom();
		/**
		 * Function to Disable a Stock Image List
		 */
		$I->wantTo('Disable StockRoom in Administrator page');
		$I = new ConfigurationSteps($scenario);
		$I->featureOffStockRoom();
	}
}
