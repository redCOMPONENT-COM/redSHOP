<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\StockImageSteps;
use AcceptanceTester\ConfigurationSteps;
/**
 * Class ManageStockImageAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class StockImageCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('Name Stock Image ?##?');
		$this->newImageTooltip = 'Updated ' . $this->name;
		$this->quantity = '100';
		$this->randomStockName = $this->faker->bothify('Name Stock Image ?##?');
		$this->updatedRandomStockName = 'New ' . $this->randomStockName;
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function enableStock(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Enable StockRoom in Administrator page');
		$I = new ConfigurationSteps($scenario);
		$I -> featureUsedStockRoom();

		/**
		* Function to Create a Stock Image List
		*/
		$I->wantTo('Create Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->addStockImage($this->name);

        /**
         * Function to Update a Stock Image List
         */
		$I->wantTo('Update Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->updateStockImage($this->name);
	}

	public function disableStock(AcceptanceTester $I, $scenario)
	{

		/**
		 * Function to Delete a Stock Image List
		 */
		$I->wantTo('Delete Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->deleteStockImage($this->name);


		/**
		 * Function to Disable a Stock Image List
		 */
		$I->wantTo('Disable StockRoom in Administrator page');
		$I = new ConfigurationSteps($scenario);
		$I -> featureOffStockRoom();
	}
}
