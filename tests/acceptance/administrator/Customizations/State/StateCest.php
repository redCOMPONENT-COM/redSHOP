<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $manufacturerName;

	/**
	 * @var string
	 */
	public $randomCountryName;

	/**
	 * @var string
	 */
	public $randomStateName;

	/**
	 * @var string
	 */
	public $updatedRandomStateName;

	/**
	 * @var string
	 */
	public $randomTwoCode;

	/**
	 * @var string
	 */
	public $randomThreeCode;

	/**
	 * @var string
	 */
	public $randomCountry;

	/**
	 * ManageStateAdministratorCest constructor.
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
     *
     * @throws Exception
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
	 *
	 * @throws Exception
	 */
	public function createState(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test State creation in Administrator');

		/** @var CountrySteps $client */
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
	 */
	public function deleteState(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Deletion of State in Administrator');
		$client = new StateSteps($scenario);
		$client->deleteState($this->updatedRandomStateName);
		/**
		 * @TODO: Why delete country here?
		 */
		/*$client = new AcceptanceTester\CountryManagerJoomla3Steps($scenario);
		$client->deleteCountry($this->randomCountryName);
		$client->searchCountry($this->randomCountryName, 'Delete');*/
	}
}
