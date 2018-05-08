<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\WrapperSteps;

/**
 * Class ManageWrapperAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class WrapperCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('ManageWrapperAdministratorCest ?##?');
		$this->price = $this->faker->numberBetween(100, 1000);
		$this->category = $this->faker->bothify('Category Demo Wrapping ?##?');
		$this->newName = 'Updated ' . $this->name;
	}

	/**
	 * Function to Test Login Backend
	 *
	 */

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Function to Test Wrapper Creation in Backend
	 *
	 */
	public function CreateWrapper(\AcceptanceTester\AdminManagerJoomla3Steps $I, $scenario)
	{
		/**
		 * Function to Test Wrapper Creation in Backend
		 *
		 */
		$I->wantTo('Test Wrapper Creation in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create category');
		$I->addCategorySave($this->category);

		$I = new WrapperSteps($scenario);
		$I->createWrapper
		(
			$this->name,
			$this->category,
			$this->price
		);

		/**
		 * Function to Test Wrapper Update in the Administrator
		 *
		 * @depends createWrapper
		 */
		$I->wantTo('Test if Wrapper gets Updation in Administrator');
		$I = new WrapperSteps($scenario);
		$I-> updateWrapper($this->name);

		/**
		 * Test for State Change in Wrapper Administrator
		 *
		 * @depends updateWrapper
		 */
		$I->wantTo('Test if Wrapper gets Change Wrapper State in Administrator');
		$I = new WrapperSteps($scenario);
		$I-> changeWrapperState($this->name);

		/**
		 * Function to Test Wrapper Deletion
		 *
		 * @depends changeWrapperState
		 */

		$I->wantTo('Test Wrapper when user delete in Administrator');
		$I = new WrapperSteps($scenario);
		$I-> deleteWrapper($this->name);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete category');
		$I->deleteCategory($this->category);
	}

}
