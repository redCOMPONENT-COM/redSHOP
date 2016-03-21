<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageWrapperAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageWrapperAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('ManageWrapperAdministratorCest ?##?');
		$this->price = $this->faker->numberBetween(100, 1000);
		$this->category = 'Events and Forms';
		$this->newName = 'Updated ' . $this->name;
	}

	/**
	 * Function to Test Wrapper Creation in Backend
	 *
	 */
	public function createWrapper(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Wrapper creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\WrapperManagerJoomla3Steps($scenario);
		$I->addWrapper($this->name, $this->price, $this->category);
		$I->searchWrapper($this->name);
	}

	/**
	 * Function to Test Wrapper Update in the Administrator
	 *
	 * @depends createWrapper
	 */
	public function updateWrapper(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Wrapper gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\WrapperManagerJoomla3Steps($scenario);
		$I->editWrapper($this->name, $this->newName);
		$I->searchWrapper($this->newName);

	}

	/**
	 * Test for State Change in Wrapper Administrator
	 *
	 * @depends updateWrapper
	 */
	public function changeWrapperState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Wrapper gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\WrapperManagerJoomla3Steps($scenario);
		$I->changeWrapperState($this->newName);
		$I->verifyState('unpublished', $I->getWrapperState($this->newName));

	}

	/**
	 * Function to Test Wrapper Deletion
	 *
	 * @depends changeWrapperState
	 */
	public function deleteWrapper(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Wrapper in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\WrapperManagerJoomla3Steps($scenario);
		$I->deleteWrapper($this->newName);
		$I->searchWrapper($this->newName, 'Delete');
	}
}
