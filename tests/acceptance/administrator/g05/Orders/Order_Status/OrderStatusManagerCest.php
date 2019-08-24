<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\OrderStatusManagerSteps;

/**
 * Class OrderStatusManagerCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4
 */

class OrderStatusManagerCest
{

	public function __construct()
	{
		//create user for quotation
		$this->faker           = Faker\Factory::create();
		$this->orderStatusName = $this->faker->bothify('ManageNameStatus ?##?');
		$this->orderStatusCode = $this->faker->bothify('ManageCodeStatus ?##?');
		$this->changeName      = $this->faker->bothify('ManageChangeNameStatus ?##?');
	}
	/**
	 * @param AcceptanceTester $I
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 *
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function createEditDeleteOrderStatus(AcceptanceTester $I, $scenario)
	{
		$I->wantTo("I want create Order Status");
		$I = new OrderStatusManagerSteps($scenario);
		$I->createOrderStatus($this->orderStatusName, $this->orderStatusCode);
		$I->createOrderStatusMissingName($this->orderStatusCode);
		$I->createOrderStatusMissingCode($this->orderStatusName);

		$I->wantTo("I want edit Order Status");
		$I = new OrderStatusManagerSteps($scenario);
		$I->editOrderStatus($this->orderStatusName, $this->changeName);

		$I->wantTo("I want edit Order Status");
		$I = new OrderStatusManagerSteps($scenario);
		$I->changeStatusUnpublish($this->changeName);

		$I->wantTo("I want edit Order Status");
		$I = new OrderStatusManagerSteps($scenario);
		$I->changeStatusPublish($this->changeName);

		$I->wantTo("I want to delete Order Status");
		$I = new OrderStatusManagerSteps($scenario);
		$I->deleteOrderStatus($this->changeName);
	}
}