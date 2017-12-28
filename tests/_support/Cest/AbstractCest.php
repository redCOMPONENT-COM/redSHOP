<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cest;

use Faker\Factory;

/**
 * Class Abstract cest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.0
 */
class AbstractCest
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
		$this->faker           = Factory::create();
		$this->countryName     = $this->faker->bothify('Testing Country ?##?');
		$this->newCountryName  = 'New ' . $this->countryName;
		$this->randomTwoCode   = $this->faker->numberBetween(10, 99);
		$this->randomThreeCode = $this->faker->numberBetween(99, 999);
		$this->randomCountry   = $this->faker->bothify('Country ?##?');
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
}
