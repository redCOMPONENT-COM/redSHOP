<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageStateAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageStateAdministratorCest
{
	/**
	 * ManageStateAdministratorCest constructor.
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->manufacturerName = $this->faker->bothify('ManageManufacturerAdministratorCest ?##?');
		$this->randomCountryName = $this->faker->bothify('ManageStateAdministratorCest ?##?');
		$this->randomStateName = $this->faker->bothify('ManageStateAdministratorCest State ?##?');
		$this->updatedRandomStateName = 'New ' . $this->randomStateName;
		$this->randomTwoCode = $this->faker->numberBetween(10, 99);
		$this->randomThreeCode = $this->faker->numberBetween(100, 999);
		$this->randomCountry = 'Country ' . $this->faker->numberBetween(99, 999);
	}

	/**
	 * Function to Test State Creation in Backend
	 *
	 * @param   AcceptanceTester  $I         Acceptance Tester case.
	 * @param   object            $scenario  Scenario object.
	 *
	 * @return  void
	 */
	public function createState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test State creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CountrySteps($scenario);
		$I->addCountry($this->randomCountryName, $this->randomThreeCode, $this->randomTwoCode, $this->randomCountry);
		$I = new AcceptanceTester\StateManagerJoomla3Steps($scenario);
		$I->wantTo('Add a new State');
		$I->addState($this->randomCountryName, $this->randomStateName, $this->randomTwoCode, $this->randomThreeCode);
	}

	/**
	 * Function to Test State Updation in the Administrator
	 *
	 * @param   AcceptanceTester  $I         Acceptance Tester case.
	 * @param   object            $scenario  Scenario object.
	 *
	 * @return  void
	 *
	 * @depends createState
	 */
	public function updateState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\StateManagerJoomla3Steps($scenario);
		$I->wantTo('Update state of an State');
		$I->updateState($this->randomStateName, $this->updatedRandomStateName);
	}

	/**
	 * Function to Test State Deletion
	 *
	 * @param   AcceptanceTester  $I         Acceptance Tester case.
	 * @param   object            $scenario  Scenario object.
	 *
	 * @return  void
	 *
	 * @depends updateState
	 */
	public function deleteState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of State in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\StateManagerJoomla3Steps($scenario);
		$I->wantTo('Delete an State');
		$I->deleteState($this->updatedRandomStateName);
		/**
		 * @TODO: Why delete country here?
		 */
		/*$I = new AcceptanceTester\CountryManagerJoomla3Steps($scenario);
		$I->deleteCountry($this->randomCountryName);
		$I->searchCountry($this->randomCountryName, 'Delete');*/
	}
}
