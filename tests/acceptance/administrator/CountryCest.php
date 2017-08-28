<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CountrySteps;
use Codeception\Scenario;

/**
 * Class ManageCountryAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CountryCest
{
	/**
	 * @var  string
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $countryName = '';

	/**
	 * @var string
	 */
	public $newCountryName = '';

	/**
	 * @var string
	 */
	public $randomTwoCode = '';

	/**
	 * @var string
	 */
	public $randomThreeCode = '';

	/**
	 * @var string
	 */
	public $randomCountry = '';

	/**
	 * CountryCest constructor.
	 */
	public function __construct()
	{
		$this->faker           = Faker\Factory::create();
		$this->countryName     = $this->faker->bothify('Testing Country ?##?');
		$this->newCountryName  = 'New ' . $this->countryName;
		$this->randomTwoCode   = $this->faker->numberBetween(10, 99);
		$this->randomThreeCode = $this->faker->numberBetween(99, 999);
		$this->randomCountry   = $this->faker->bothify('Country ?##?');
	}

	public function deleteData($scenario)
	{
		$I= new RedshopSteps($scenario);
		$I->clearAllTables();
	}
	/**
	 * Function to Test Country Creation in Backend
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function createCountry(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Country creation in Administrator');
		$client->doAdministratorLogin();
		$client = new CountrySteps($scenario);
		$client->addCountry($this->countryName, $this->randomThreeCode, $this->randomTwoCode, $this->randomCountry);
	}

	/**
	 * Function to Test Country Update in the Administrator
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends createCountry
	 */
	public function updateCountry(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test if Country gets updated in Administrator');
		$client->doAdministratorLogin();
		$client = new CountrySteps($scenario);
		$client->editCountry($this->countryName, $this->newCountryName);
	}

	/**
	 * Function to Test Country Deletion
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends updateCountry
	 */
	public function deleteCountry(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Deletion of Country in Administrator');
		$client->doAdministratorLogin();
		$client = new CountrySteps($scenario);
		$client->deleteCountry($this->newCountryName);
	}
}
