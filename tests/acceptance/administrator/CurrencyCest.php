<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CurrencyCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CurrencyCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->currencyName = $this->faker->bothify('Testing Currency ?##?');
		$this->updateCurrencyName = 'New ' . $this->currencyName;
		$this->randomCurrencyCode = $this->faker->bothify('R##');
	}

	/**
	 * Function to Test Currency Creation in Backend
	 *
	 */
	public function createCurrency(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test currency creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CurrencySteps($scenario);
		$I->wantTo('Create a Currency');
		$I->addCurrency($this->currencyName, $this->randomCurrencyCode);
		$I->searchCurrency($this->currencyName);
	}

	/**
	 * Function to Test Currency Updation in the Administrator
	 *
	 * @depends createCurrency
	 */
	public function updateCurrency(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Currency gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CurrencySteps($scenario);
		$I->wantTo('Update Existing Currency');
		$I->editCurrency($this->currencyName, $this->updateCurrencyName);
		$I->searchCurrency($this->updateCurrencyName);
	}

	/**
	 * Function to Test Currency Deletion
	 *
	 * @depends updateCurrency
	 */
	public function deleteCurrency(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Currency in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CurrencySteps($scenario);
		$I->wantTo('Delete a Currency');
		$I->deleteCurrency($this->updateCurrencyName);
		$I->searchCurrency($this->updateCurrencyName, 'Delete');
	}
}
