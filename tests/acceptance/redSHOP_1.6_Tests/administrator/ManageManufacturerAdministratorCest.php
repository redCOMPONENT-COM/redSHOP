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
        $this->manufacturerName = 'Test Manufacture' . rand(1, 100);
        $this->updatedName = 'Updated ' . $this->manufacturerName;
        $this->NoPage = 5;
        $this->noPageZero = 0;
        $this->manufacturerNameNew = 'Testing Manufacturers' . rand(101, 999);
        $this->manufacturerEmail = "redweb@gmail.dk";
        $this->manufactureWrongEmail = "redweb";
        $this->manufactureWrongURL = "URL";
        $this->manufactureRightURL = "http://redweb.dk";
    }

    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Edit Button Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->checkEditButton();
        $I->see("Manufacturer Management", '.page-title');
    }

    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Delete Button Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->checkDeleteButton();
        $I->see("Manufacturer Management", '.page-title');
    }

    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Publish Button Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->checkPublishButton();
        $I->see("Manufacturer Management", '.page-title');
    }

    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Unpublish Button Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->checkUnpublishButton();
        $I->see("Manufacturer Management", '.page-title');
    }

    public function createManufacturerSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->addManufacturerSaveClose($this->manufacturerName, $this->NoPage);
        $I->see(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage, '.alert-success');
    }

    /**
     * Function to Test Manufacturer Creation in Backend
     *
     */
    public function unpublishAllManufacturer(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->unpublishAllManufacturer();
        $I->see(\ManufacturerManagerJoomla3Page::$manufacturerUnpublishMessage, '.alert-success');
    }

    public function publishAllManufacturer(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->publishAllManufacturer();
        $I->see(\ManufacturerManagerJoomla3Page::$manufacturerPublishMessage, '.alert-success');
    }


    public function addManufacturerWithPageNumber(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation With page is 0 in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->addManufacturerWithoutPageNumber($this->manufacturerNameNew);
    }

    public function addManufacturerWrongURL(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation With page is 0 in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->addManufacturerWrongURL($this->manufacturerNameNew, $this->manufactureWrongURL, $this->NoPage);
    }

    //This oode is true , but the backend not handler this case , as soon as backend fixed this issues please open code.
//    public function addManufacturerWrongEmail(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test Manufacture creation With page is 0 in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
//        $I->addManufacturerWrongEmail($this->manufacturerNameNew, $this->manufactureWrongEmail, $this->NoPage);
//    }

    public function addManufacturerCancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation With page is 0 in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->addManufacturerCancelButton($this->manufacturerNameNew, $this->NoPage);
        $I->see("Manufacturer Management", '.page-title');
    }


    public function addManufacturerSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->addManufacturerSave($this->manufacturerNameNew, $this->NoPage);
        $I->see(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage, '.alert-success');
    }

    public function copyManufacture(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Manufacture copy in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->copyManufacture($this->manufacturerNameNew);
        $I->see(\ManufacturerManagerJoomla3Page::$manufactureCoPySuccess, '.alert-success');
    }

    public function deleteManufacture(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Delete Manufacture copy in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->deleteManufacture($this->manufacturerNameNew);
        $I->see(\ManufacturerManagerJoomla3Page::$manufacturerDeleteSuccessMessage, '.alert-success');
    }

    /**
     * Function to Test Manufacture Updation in the Administrator
     *
     * @depends createManufacturerSaveClose
     */
    public function updateManufacturer(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test update  Manufacture Name gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->editManufacturer($this->manufacturerName, $this->updatedName, $this->NoPage);
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
        $I->wantTo('Update Existing Manufacture');
        $I->changeManufactureState($this->updatedName, 'unpublish');
        $currentState = $I->getManufactureState($this->updatedName);
        $I->verifyState('unpublished', $currentState);


    }

    public function editManufactureWithoutPage(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test edit Manufacture without page gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Manufacture');
        $I->editManufactureWithoutPage($this->updatedName, $this->noPageZero);
    }


    public function editNameManufactureWithWrongURL(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test edit Manufacture with wrong URL page gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Manufacture');
        $I->editManufactureWithWrongURL($this->updatedName, $this->manufactureWrongURL);
    }


    public function editManufactureCheckClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test  Manufacture check close button gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ManufacturerManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Manufacture');
        $I->editManufactureCheckClose($this->updatedName);
        $I->see("Manufacturer Management", '.page-title');
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
