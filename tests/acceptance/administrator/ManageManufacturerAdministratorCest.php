<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageManufacturerAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageManufacturerAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->manufacturerName = $this->faker->bothify('ManageManufacturerAdministratorCest ?##?');
        $this->updatedName = 'Updated ' . $this->manufacturerName;
        $this->productPerPage = $this->faker->numberBetween(1, 100);
    }

    /**
     * Function to Test Manufacturer Creation in Backend
     *
     */
    public function createManufacturer(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->addManufacturer($this->manufacturerName, $this->productPerPage);
        $I->searchManufacturer($this->manufacturerName);
    }

    /**
     * Function to Test Manufacture Updation in the Administrator
     *
     * @depends createManufacturer
     */
    public function updateManufacturer(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Manufacture gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->editManufacturer($this->manufacturerName, $this->updatedName);
        $I->searchManufacturer($this->updatedName);
    }

    /**
     * Test for State Change in Manufacturer Administrator
     *
     * @depends updateManufacturer
     */
    public function changeManufacturerState(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State of a Manufacture gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->changeManufacturerState($this->updatedName);
        $I->verifyState('unpublished', $I->getManufacturerState($this->updatedName));

    }

    /**
     * Function to Test Manufacturer Deletion
     *
     * @depends changeManufacturerState
     */
    public function deleteManufacturer(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of Manufacture in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->deleteManufacturer($this->updatedName);
    }
}
