<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ManageStockImageAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageStockImageAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->tip = $this->faker->bothify('ManageStockImageAdministratorCest ?##?');
		$this->newTip = 'Updated ' . $this->tip;
		$this->quantity = '100';
		$this->amount = 'Higher than';
	}

	/**
	 * Function to Test Stock Images Creation in Backend
	 *
	 */
	public function createStockImage(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Stock Image creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\StockImageManagerJoomla3Steps($scenario);
		$I->addStockImage($this->tip, $this->amount, $this->quantity);
		$I->searchStockImage($this->tip);
	}

	/**
	 * Function to Test Stock Image Updation in the Administrator
	 *
	 * @depends createStockImage
	 */
	public function updateStockImage(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Stock Image gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\StockImageManagerJoomla3Steps($scenario);
		$I->editStockImage($this->tip, $this->newTip);
		$I->searchStockImage($this->newTip);
	}

	/**
	 * Function to Test Stock Image Deletion
	 *
	 * @depends updateStockImage
	 */
	public function deleteStockImage(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Stock Images in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\StockImageManagerJoomla3Steps($scenario);
		$I->deleteStockImage($this->newTip);
		$I->searchStockImage($this->newTip, 'Delete');
	}
}
