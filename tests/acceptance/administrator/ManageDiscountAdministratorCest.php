<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageDiscountAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageDiscountAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->discountName = $this->faker->bothify('ManageDiscountAdministratorCest discount ?##?');
		$this->amount = $this->faker->numberBetween(100, 999);
		$this->discountAmount = $this->faker->numberBetween(10, 100);
		$this->newAmount = $this->faker->numberBetween(100, 999);
	}

	/**
	 * Function to Test Discount Creation in Backend
	 *
	 */
	public function createDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\DiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscount($this->discountName, $this->amount, $this->discountAmount);
	}

	/**
	 * Function to Test Discount Updation in the Administrator
	 *
	 * @depends createDiscount
	 */
	public function updateDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Discount gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\DiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Update Existing Discount');
		$I->editDiscount($this->discountName, $this->amount, $this->newAmount);
	}

	/**
	 * Function to Test Discount Deletion
	 *
	 * @depends updateDiscount
	 */
	public function deleteDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\DiscountManagerJoomla3Steps($scenario);
		$I->deleteDiscount($this->discountName, $this->newAmount);
		$I->dontSeeElement(['link' => $this->discountName]);
	}
}
