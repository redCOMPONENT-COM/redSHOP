<?php
/**
 * @package     RedShop
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
    public function createOrderStatusWithSaveButton(AcceptanceTester $I, $scenario)
    {
        $I = $this;
        $I->wantTo("I want create Order Status");
        $I->createOrderStatus($this->orderStatusName, $this->orderStatusCode);
        $I->createOrderStatusMissingName($this->orderStatusCode);
        $I->createOrderStatusMissingCode($this->orderStatusName);

    }

    public function editOrderStatus(AcceptanceTester $I, $scenario)
    {
        $I = $this;
        $I->wantTo("I want edit Order Status");
        $I->editOrderStatus($this->orderStatusName);
    }

    public function deleteOrderStatus(AcceptanceTester $I, $scenario)
    {
        $I = $this;
        $I->wantTo("I want to delete Order Status");
        $I->deleteOrderStatus($this->orderStatusName);
    }
}