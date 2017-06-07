<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/7/2017
 * Time: 11:06 AM
 */
class ManagementTAXRatesAdministratorCest
{
    public function __construct()
    {
        $this->TAXRatesName = 'Testing Tax Rates Groups' . rand(1, 199);
        $this->TAXRatesNameEdit = $this->TAXRatesName . 'Edit';
        $this->VATGroupName = 'Testing VAT Groups690';
        $this->TaxRatesValue = rand(0, 1);
        $this->NameCountry = 'United States';
        $this->NameState = 'Alabama';
        $this->TaxRatesValueLessZero = -1;
        $this->TaxRatesValueString = 'Test';
    }

    public function createVATGroupSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test VAT Groups  Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Create VAT groups  Save button');
        $I->addVATGroupsSave($this->VATGroupName);
        $I->see("item successfully saved", '.alert-success');
    }

    public function createTaxRatesSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesSave($this->TAXRatesName, $this->VATGroupName, $this->TaxRatesValue, $this->NameCountry, $this->NameState);
        $I->see("item successfully saved", '.alert-success');
    }


    public function addTAXRatesMissingNameSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates Save missing name creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->addTAXRatesMissingNameSave($this->VATGroupName, $this->TaxRatesValue, $this->NameCountry, $this->NameState);

    }

    public function addTAXRatesMissingGroupsSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates missing groups save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesMissingGroupsSave($this->TAXRatesName, $this->TaxRatesValue, $this->NameCountry, $this->NameState);

    }

    public function addTAXRatesMissingTaxValueSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX missing tax value Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesMissingTaxValueSave($this->TAXRatesName, $this->VATGroupName, $this->NameCountry, $this->NameState);

    }


    public function addTAXRatesValueAmountLessZeroSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX amount less zero Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesValueAmountLessZeroSave($this->TAXRatesName, $this->VATGroupName, $this->TaxRatesValueLessZero, $this->NameCountry, $this->NameState);

    }

    public function addTAXRatesValueAmountStringSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates with amount is string  Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesValueAmountStringSave($this->TaxRatesValueString, $this->VATGroupName, $this->TaxRatesValueString, $this->NameCountry, $this->NameState);

    }

    public function editTAXRatesName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->editTAXRatesName($this->TAXRatesName, $this->TAXRatesNameEdit);

    }

    public function editButtonTAXRatesName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates edit with Edit button Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->editTAXRatesName($this->TAXRatesNameEdit, $this->TAXRatesName);

    }

        public function editTAXRatesMissingName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Edit TAX missing name in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->editTAXRatesMissingName($this->TAXRatesName);

    }


    public function addTAXRatesSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates Save and Close creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesSaveClose($this->TAXRatesName, $this->VATGroupName, $this->TaxRatesValue, $this->NameCountry, $this->NameState);
        $I->see("VAT Rates", '.page-title');
        $I->searchTAXRates($this->TAXRatesName);
    }


    public function checkCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('check Cancel creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->checkCancel();
        $I->see("VAT Rates", '.page-title');
    }


    public function editButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test edit Button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Check Edit button');
        $I->editButton();
        $I->see("VAT Rates", '.page-title');
    }

    public function deleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test delete button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo(' Delete  button');
        $I->deleteButton();
        $I->see("VAT Rates", '.page-title');
    }

    public function deleteTAXRates(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test delete button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo(' Delete  button');
        $I->deleteTAXRates($this->TAXRatesName);
        $I->see("VAT Rates", '.page-title');
    }


// codeception use for show dialog delete VAT/TAX rates .
//    public function deleteTAXRatesCancel(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test delete button in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
//        $I->wantTo(' Delete  button');
//        $I->deleteTAXRatesCancel($this->TAXRatesName);
//        $I->see("VAT Rates", '.page-title');
//    }
//
//    public function deleteTAXRatesOK(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test delete button in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
//        $I->wantTo(' Delete  button');
//        $I->deleteTAXRatesOK($this->TAXRatesName);
//        $I->see("VAT Rates", '.page-title');
//    }




}