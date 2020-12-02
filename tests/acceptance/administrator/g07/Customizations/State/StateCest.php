<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\StateSteps;
use Codeception\Scenario;

/**
 * Class StateCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class StateCest
{
	/**
	 * @var  string
	 * @since 1.4.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $manufacturerName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $randomCountryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $randomStateName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $updatedRandomStateName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $randomTwoCode;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $randomThreeCode;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $randomCountry;

	/**
	 * ManageStateAdministratorCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->manufacturerName       = $this->faker->bothify('ManageManufacturerAdministratorCest ?##?');
		$this->randomCountryName      = $this->faker->bothify('ManageStateAdministratorCest ?##?');
		$this->randomStateName        = $this->faker->bothify('ManageStateAdministratorCest State ?##?');
		$this->updatedRandomStateName = 'New ' . $this->randomStateName;
		$this->randomTwoCode          = $this->faker->numberBetween(10, 99);
		$this->randomThreeCode        = $this->faker->numberBetween(100, 999);
		$this->randomCountry          = 'Country ' . $this->faker->numberBetween(99, 999);
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
	 * Function to Test State Creation in Backend
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   Scenario         $scenario Scenario object.
	 *
	 * @return  void
	 * @since 1.4.0
	 */
	public function createState(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test State creation in Administrator');

		$client = new CountrySteps($scenario);
		$client->addNewItem(
			array(
				'country_name'   => $this->randomCountryName,
				'country_2_code' => $this->randomTwoCode,
				'country_3_code' => $this->randomThreeCode,
				'country_jtext'  => $this->randomCountry
			)
		);
		$client = new StateSteps($scenario);
		$client->addState($this->randomCountryName, $this->randomStateName, $this->randomTwoCode, $this->randomThreeCode);
	}

	/**
	 * Function to Test State Update in the Administrator
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   Scenario         $scenario Scenario object.
	 *
	 * @return  void
	 *
	 * @depends createState
	 * @since 1.4.0
	 */
	public function updateState(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test if State gets updated in Administrator');
		$client = new StateSteps($scenario);
		$client->updateState($this->randomStateName, $this->updatedRandomStateName);
	}

	/**
	 * Function to Test State Deletion
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   Scenario         $scenario Scenario object.
	 *
	 * @return  void
	 *
	 * @depends updateState
	 * @since 1.4.0
	 */
	public function deleteState(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Deletion of State in Administrator');
		$client = new StateSteps($scenario);
		$client->deleteState($this->updatedRandomStateName);
	}
}
