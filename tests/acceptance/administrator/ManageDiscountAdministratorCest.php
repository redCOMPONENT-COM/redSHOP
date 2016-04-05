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
		$I->addDiscount($this->amount, $this->discountAmount);
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
		$I->editDiscount($this->amount, $this->newAmount);
	}

	/**
	 * Test for State Change in Discount Administrator
	 *
	 * @depends updateDiscount
	 */
	public function changeDiscountState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Discount gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\DiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Update Existing Discount');

		// @todo: we need a new way to manage publishing and unpublishing lists that have no search filter. Probably by adding a search in list
		$I->changeDiscountState($this->newAmount);
		$I->verifyState('unpublished', $I->getDiscountState($this->newAmount));
	}

	/**
	 * Function to Test Discount Deletion
	 */
	public function deleteDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\DiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Delete a Discount');
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->waitForText('Product Discount Management', 60, ['css' => 'h1']);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['xpath' => "//input[@name='checkall-toggle']"]);
		$I->click('Delete');
		$verifyAmount = '$ ' . $this->newAmount . ',00';

		$I->dontSeeElement(['link' => $verifyAmount]);

		$scenario->skip('@todo: once REDSHOP-2865 will be fixed');
		// $I->deleteDiscount($this->newAmount);
		// $I->searchDiscount($this->newAmount, 'Delete');
	}
}
