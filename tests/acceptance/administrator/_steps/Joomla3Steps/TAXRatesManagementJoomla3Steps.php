<?php
/**
 */

namespace AcceptanceTester;


class TAXRatesManagementJoomla3Steps extends AdminManagerJoomla3Steps
{
    public function addTAXRatesSave($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry)
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
        $I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_country"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $nameCountry);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_group_id"]//a']);
        $I->waitForElement(['id' => "s2id_autogen3_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $VATGroupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"]);
        $I->click("Save");
        $I->see("item successfully saved", '.alert-success');
    }

    public function addTAXRatesMissingNameSave( $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_country"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $nameCountry);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"]);

//        $I->click(['xpath' => '//div[@id="s2id_jform_tax_state"]//a']);
//        $I->waitForElement(['id' => "s2id_autogen2_search"]);
//        $I->fillField(['id' => "s2id_autogen2_search"], $nameState);
//        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameState . "')]"], 60);
//        $I->click(['xpath' => "//span[contains(text(), '" . $nameState . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_group_id"]//a']);
        $I->waitForElement(['id' => "s2id_autogen3_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $VATGroupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"]);
        $I->click("Save");
        $I->see("Error", '.alert-heading');
    }

    public function addTAXRatesMissingGroupsSave($TAXRatesName, $TaxRatesValue)
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
        $I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);
        $I->click("Save");
        $I->see("Error", '.alert-heading');
    }

    public function addTAXRatesMissingTaxValueSave($TAXRatesName, $VATGroupName)
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_group_id"]//a']);
        $I->waitForElement(['id' => "s2id_autogen3_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $VATGroupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"]);
        $I->click("Save");
        $I->acceptPopup();
    }

    public function addTAXRatesValueAmountLessZeroSave($TAXRatesName, $VATGroupName, $TaxRatesValue)
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
        $I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_group_id"]//a']);
        $I->waitForElement(['id' => "s2id_autogen3_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $VATGroupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"]);
        $I->click("Save");
        $I->acceptPopup();
    }

    public function addTAXRatesValueAmountStringSave($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
        $I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_country"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $nameCountry);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_group_id"]//a']);
        $I->waitForElement(['id' => "s2id_autogen3_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $VATGroupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"]);
        $I->click("Save");
        $I->acceptPopup();
    }

    public function addTAXRatesSaveClose($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
        $I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_country"]//a']);
        $I->waitForElement(['id' => "s2id_autogen1_search"]);
        $I->fillField(['id' => "s2id_autogen1_search"], $nameCountry);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $nameCountry . "')]"]);

        $I->click(['xpath' => '//div[@id="s2id_jform_tax_group_id"]//a']);
        $I->waitForElement(['id' => "s2id_autogen3_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $VATGroupName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $VATGroupName . "')]"]);

        $I->click("Save & Close");
        $I->see("item successfully saved", '.alert-success');
    }

    public function editTAXRatesName($TAXRatesName, $TAXRatesNameEdit)
    {
        $I = $this;
        $I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
        $I->searchTAXRates($TAXRatesName);
        $I->wait(3);
        $I->click(\TAXRatesJoomla3Page::$taxStateNamePath);
        $I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT Rates: [ Edit ]');
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesNameEdit);
        $I->click("Save");
        $I->see("item successfully saved", '.alert-success');
    }

    public function editButtonTAXRatesName($TAXRatesName, $TAXRatesNameEdit)
    {
        $I = $this;
        $I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
        $I->searchTAXRates($TAXRatesName);
        $I->wait(3);
        $I->click(\TAXRatesJoomla3Page::$CheckAllTAXRates);
        $I->click('Edit');
        $I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT Rates: [ Edit ]');
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesNameEdit);
        $I->click("Save");
        $I->see("item successfully saved", '.alert-success');
    }
    public function checkCancelTAXRates($TAXRatesName)
    {
        $I = $this;
        $I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
        $I->searchTAXRates($TAXRatesName);
        $I->wait(3);
        $I->click(\TAXRatesJoomla3Page::$CheckAllTAXRates);
        $I->click('Edit');
        $I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT Rates: [ Edit ]');
        $I->click("Cancel");
        $I->see("VAT Rates", '.page-title');
    }

    public function editTAXRatesMissingName($TAXRatesName)
    {
        $I = $this;
        $I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
        $I->searchTAXRates($TAXRatesName);
        $I->wait(3);
        $I->click(\TAXRatesJoomla3Page::$taxStateNamePath);
        $I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT Rates: [ Edit ]');
        $I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, "");
        $I->click("Save");
        $I->see("Error", '.alert-heading');
    }

    public function deleteTAXRates($TAXRatesName)
    {
        $I = $this;
        $I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
        $I->searchTAXRates($TAXRatesName);
        $I->wait(3);
        $I->click(\TAXRatesJoomla3Page::$CheckAllTAXRates);
        $I->click('Delete');
        $I->see("Message", '.alert-success');
    }

    public function deleteTAXRatesOK($TAXRatesName)
    {
        $I = $this;
        $I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
        $I->searchTAXRates($TAXRatesName);
        $I->wait(3);
        $I->click(\TAXRatesJoomla3Page::$CheckAllTAXRates);
        $I->click('Delete');
        $I->acceptPopup();
        $I->see("Message", '.alert-success');
    }

    public function deleteTAXRatesCancel($TAXRatesName)
    {
        $I = $this;
        $I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
        $I->searchTAXRates($TAXRatesName);
        $I->wait(3);
        $I->click(\TAXRatesJoomla3Page::$CheckAllTAXRates);
        $I->click('Delete');
        $I->cancelPopup();

    }

    public function checkCancel()
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Rates Management New');
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Cancel");
    }

    public function editButton()
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("Edit");
        $I->acceptPopup();
    }

    public function deleteButton()
    {
        $I = $this;
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->click("Delete");
        $I->acceptPopup();
    }

    public function searchTAXRates($TAXRatesName)
    {
        $I = $this;
        $I->wantTo('Search the VAT/Tax Groups ');
        $I->amOnPage(\TAXRatesJoomla3Page::$URL);
        $I->waitForText('VAT Rates', 30, ['xpath' => "//h1"]);
        $I->filterListBySearching($TAXRatesName);
    }

}