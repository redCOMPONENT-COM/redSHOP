<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
 * @since 1.4.0
 */
class WrapperCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $name;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $price;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $category;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $newName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $priceinvalid;

	/**
	 * WrapperCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('Manage Wrapper ?##?');
		$this->price = $this->faker->numberBetween(100, 1000);
		$this->category = $this->faker->bothify('Category Demo Wrapping ?##?');
		$this->newName = 'Updated ' . $this->name;
		$this->priceinvalid = $this->faker->bothify("##??");
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
	 * Function to Test Wrapper Creation in Backend
	 * @param CategoryManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function createUpdateDeleteWrapper(CategoryManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo('Create category');
		$I->addCategorySave($this->category);
		$I = new WrapperSteps($scenario);
		$I->createWrapper
		(
			$this->name,
			$this->category,
			$this->price
		);

		$I->wantTo('Test if Wrapper gets Updation in Administrator');
		$I = new WrapperSteps($scenario);
		$I-> updateWrapper($this->name);

		$I->wantTo('Test if Wrapper gets Change Wrapper State in Administrator');
		$I = new WrapperSteps($scenario);
		$I-> changeWrapperState($this->name);

		$I->wantTo('Test Wrapper when user delete in Administrator');
		$I = new WrapperSteps($scenario);
		$I->deleteWrapper($this->name);
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete category');
		$I->deleteCategory($this->category);
	}

	/**
	 * @param WrapperSteps $I
	 * @throws Exception
	 * @since 2.1.2.2
	 */
	public function CheckInvalidName(WrapperSteps $I)
	{
		$I->wantTo("I want to test invalid price of Wrapping");
		$I->checkWrapperInvalidField();
	}
}
