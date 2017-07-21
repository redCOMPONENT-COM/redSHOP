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
        $this->supplierNameSave = $this->faker->bothify('ManageSupplierSaveAdministratorCest ?##?');
        $this->supplierUpdatedName = $this->faker->bothify('Supplier Updated Name ?##?');
        $this->supplierEmail = $this->faker->email();
        $this->supplierEmailSave = $this->faker->email();

    }

    /**
     *
     * Function check delete button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Delete button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->checkDeleteButton();
    }

    /**
     *
     * Function ccheck publish button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Publish button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->checkPublishButton();
    }

    /**
     *
     * Function check unpublish button
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Unpublish button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->checkUnpublishButton();
    }

    /**
     *
     * Function check checkin button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCheckinButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test CheckiIn button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->checkCheckinButton();
    }

    /**
     *
     * Function add supplier when missing name
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addSupplierSaveMissingName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier Missing Name creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->addSupplierSaveMissingName($this->supplierEmail);

    }

//    /*
//     * This case will not run because at code pass this issues .
//     */
//    public function addSupplierWrongEmail(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test Supplier email with wrong email creation in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
//        $I->addSupplierWrongEmail($this->supplierName,$this->supplierName);
//    }

    /**
     * Function to Test Supplier Creation in Backend
     * Create supplier with save and close button
     *
     */
    public function createSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier creation with save and close button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->addSupplier($this->supplierName, $this->supplierEmail);

    }

    /**
     * Function create supplier with save button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createSupplierSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier creation with save in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->addSupplierSave($this->supplierNameSave, $this->supplierEmailSave);
    }

    /**
     * Function check cancel button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier check cancel button inside in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->checkCancelButton();
        $I->see(\SupplierManagerPage::$namePage, \SupplierManagerPage::$selectorPageTitle);
    }


    //unpublish
    public function unpublishAllSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test unpublish all supplier Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->unpublishAllSupplier();
        $I->see(\SupplierManagerPage::$namePage, \SupplierManagerPage::$selectorPageTitle);
    }


    //publish
    public function publishAllSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test publish all supplier Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->publishAllSupplier();
        $I->see(\SupplierManagerPage::$namePage, \SupplierManagerPage::$selectorPageTitle);
    }

    //unpublish
    public function checkinSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test check in all supplier Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->checkinSupplier();
        $I->see(\SupplierManagerPage::$namePage, \SupplierManagerPage::$selectorPageTitle);
    }


    public function changeSupplierStateUnpublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if update status to unpublish of Supplier gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->wantTo(' update status of Supplier');
        $I->changeSupplierState($this->supplierNameSave, 'unpublish');
        $currentState = $I->getSupplierState($this->supplierNameSave);
        $I->verifyState('unpublished', $currentState);
    }

    public function changeSupplierStatePublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test change publish Supplier gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->wantTo(' update status of Supplier');
        $I->changeSupplierStatePublish($this->supplierNameSave, 'publish');
        $currentState = $I->getSupplierState($this->supplierNameSave);
        $I->verifyState('published', $currentState);
    }


    /**
     * Function to Test Supplier Update in Backend
     *
     */
    public function editSupplier(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier update name  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->editSupplier($this->supplierName, $this->supplierUpdatedName);
    }


    /**
     * Function to Test Supplier Update in Backend
     *
     */
    public function editSupplierMissingName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier update missing name in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->editSupplierMissingName($this->supplierUpdatedName);
    }


    public function editSupplierCheckCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier check Close button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->editSupplierCheckCloseButton($this->supplierUpdatedName);
    }

    /**
     * Function to Test Supplier Update in Backend
     *
     */
    public function deleteSupplierCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Supplier Deletion Then clicks cancel button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\SupplierManagerJoomla3Steps($scenario);
        $I->deleteSupplierCancel($this->supplierUpdatedName);
        $I->see(\SupplierManagerPage::$namePage, \SupplierManagerPage::$selectorPageTitle);

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
