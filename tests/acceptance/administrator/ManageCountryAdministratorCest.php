<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageCountryAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageCountryAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->countryName = $this->faker->bothify('Testing Country ?##?');
		$this->newCountryName = 'New ' . $this->countryName;
		$this->randomTwoCode = $this->faker->numberBetween(10, 99);
		$this->randomThreeCode = $this->faker->numberBetween(99, 999);
		$this->randomCountry = $this->faker->bothify('Country ?##?');
	}

	/**
	 * Function to Test Country Creation in Backend
	 *
	 */
	public function createCountry(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Country creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CountryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Country');
		$I->addCountry($this->countryName, $this->randomThreeCode, $this->randomTwoCode, $this->randomCountry);

		$I->searchCountry($this->countryName);
	}

	/**
	 * Function to Test Country Updation in the Administrator
	 *
	 * @depends createCountry
	 */
	public function updateCountry(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Country gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CountryManagerJoomla3Steps($scenario);
		$I->wantTo('Update Existing Country');
		$I->editCountry($this->countryName, $this->newCountryName);
		$I->searchCountry($this->newCountryName);
	}

	/**
	 * Function to Test Country Deletion
	 *
	 * @depends updateCountry
	 */
	public function deleteCountry(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Country in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CountryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete a Country');
		$I->deleteCountry($this->newCountryName);
	}
}
