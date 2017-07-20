<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageSupplierAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageSupplierAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->supplierName = $this->faker->bothify('ManageSupplierAdministratorCest ?##?');
        $this->supplierUpdatedName = $this->faker->bothify('Supplier Updated Name ?##?');
        $this->supplierEmail = $this->faker->email();
    }

    /**
     * Function to Test Supplier Creation in Backend
     *
     */
    public function createSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->addSupplier($this->supplierName, $this->supplierEmail);

    }

    /**
     * Function to Test Supplier Update in Backend
     *
     */
    public function editSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier update in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->editSupplier($this->supplierName, $this->supplierUpdatedName);
    }

    /**
     * Function to Test Supplier Update in Backend
     *
     */
    public function deleteSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier Deletion in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->deleteSupplier($this->supplierUpdatedName);
    }

}
