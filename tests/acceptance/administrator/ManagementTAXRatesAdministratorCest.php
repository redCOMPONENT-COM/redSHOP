<?php

/**
 * Codeception for VAT/TAX rates
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

    /** Create VAT Group with
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createVATGroupSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test VAT Groups  Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\VATGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Create VAT groups  Save button');
        $I->addVATGroupsSave($this->VATGroupName);
    }

    /**
     * Create VAT Tax Rates
     * @param AcceptanceTester $I
     * @param $scenario
     * @depends createVATGroupSave
     */
    public function createTaxRatesSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesSave($this->TAXRatesName, $this->VATGroupName, $this->TaxRatesValue, $this->NameCountry, $this->NameState);
    }

    /**
     * Create TAX Rates missing name
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addTAXRatesMissingNameSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates Save missing name creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->addTAXRatesMissingNameSave($this->VATGroupName, $this->TaxRatesValue, $this->NameCountry, $this->NameState);
    }

    /**
     * Create Tax Rates missing groups
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addTAXRatesMissingGroupsSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates missing groups save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->addTAXRatesMissingGroupsSave($this->TAXRatesName, $this->TaxRatesValue);
    }

    /**
     * Create Tax Rates missing tax value
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addTAXRatesMissingTaxValueSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX missing tax value Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->addTAXRatesMissingTaxValueSave($this->TAXRatesName, $this->VATGroupName);
    }

    /**
     * Create Tax Rates with value amount less zero
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addTAXRatesValueAmountLessZeroSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX amount less zero Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->addTAXRatesValueAmountLessZeroSave($this->TAXRatesName, $this->VATGroupName, $this->TaxRatesValueLessZero);
    }

    /**
     * Creat TAX Rates with amount is string
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addTAXRatesValueAmountStringSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates with amount is string  Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->addTAXRatesValueAmountStringSave($this->TaxRatesValueString, $this->VATGroupName, $this->TaxRatesValueString, $this->NameCountry, $this->NameState);
    }

    /**
     * Edit Tax Rates name try to clicks on name of TAX Rates
     * @param AcceptanceTester $I
     * @param $scenario
     * @depends createTaxRatesSave
     */
    public function editTAXRatesName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->editTAXRatesName($this->TAXRatesName, $this->TAXRatesNameEdit);
    }

    /**
     * Edit Tax Rates name by Edit button
     * @param AcceptanceTester $I
     * @param $scenario
     * @depends editTAXRatesName
     */
    public function editButtonTAXRatesName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test TAX Rates edit with Edit button Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\TAXRatesManagementJoomla3Steps($scenario);
        $I->wantTo('Create VAT/Tax Rates  Save button');
        $I->editTAXRatesName($this->TAXRatesNameEdit, $this->TAXRatesName);
    }

    /**
     * Edit Tax Rates missing name
     * @param AcceptanceTester $I
     * @param $scenario
     * @depends editButtonTAXRatesName
     */
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
        $I->see(\TAXRatesJoomla3Page::$nameManagement, \TAXRatesJoomla3Page::$selectorNamePage);
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
        $I->see(\TAXRatesJoomla3Page::$nameManagement, \TAXRatesJoomla3Page::$selectorNamePage);
    }

// codeception use for show dialog delete VAT/TAX rates .
//    public function deleteTAXRatesCancel(AcceptanceTester $I, $scenario)
//    {f
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