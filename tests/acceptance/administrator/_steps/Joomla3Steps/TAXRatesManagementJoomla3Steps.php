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
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
		$I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);
		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldCountry, $nameCountry);
		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldGroup, $VATGroupName);
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageSaveSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function addTAXRatesMissingNameSave($VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);

		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldCountry, $nameCountry);

//        $I->click(['xpath' => '//div[@id="s2id_jform_tax_state"]//a']);
//        $I->waitForElement(['id' => "s2id_autogen2_search"]);
//        $I->fillField(['id' => "s2id_autogen2_search"], $nameState);
//        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameState . "')]"], 60);
//        $I->click(['xpath' => "//span[contains(text(), '" . $nameState . "')]"]);

		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldGroup, $VATGroupName);

		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageError, \TAXRatesJoomla3Page::$selectorErrorHead);
	}

	public function addTAXRatesMissingGroupsSave($TAXRatesName, $TaxRatesValue)
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
		$I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageError, \TAXRatesJoomla3Page::$selectorErrorHead);
	}

	public function addTAXRatesMissingTaxValueSave($TAXRatesName, $VATGroupName)
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldGroup, $VATGroupName);
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageSaveSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function addTAXRatesValueAmountLessZeroSave($TAXRatesName, $VATGroupName, $TaxRatesValue)
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
		$I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);

		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldGroup, $VATGroupName);
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageError, \TAXRatesJoomla3Page::$selectorErrorHead);
	}

	public function addTAXRatesValueAmountStringSave($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
		$I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);
		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldCountry, $nameCountry);
		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldGroup, $VATGroupName);
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageSaveSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function addTAXRatesSaveClose($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesName);
		$I->fillField(\TAXRatesJoomla3Page::$TaxRatesValue, $TaxRatesValue);
		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldCountry, $nameCountry);
		$I->chooseOnSelect2(\TAXRatesJoomla3Page::$fieldGroup, $VATGroupName);
		$I->click(\TAXRatesJoomla3Page::$saveCloseButton);
		$I->see(\TAXRatesJoomla3Page::$messageSaveSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function editTAXRatesName($TAXRatesName, $TAXRatesNameEdit)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchTAXRates($TAXRatesName);
		$I->wait(3);
		$I->click(\TAXRatesJoomla3Page::$taxStateNamePath);
		$I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameEditPage);
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesNameEdit);
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageSaveSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function searchTAXRates($TAXRatesName)
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->waitForText(\TAXRatesJoomla3Page::$nameManagement, 30, \TAXRatesJoomla3Page::$headXPath);
		$I->filterListBySearching($TAXRatesName);
	}

	public function editButtonTAXRatesName($TAXRatesName, $TAXRatesNameEdit)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchTAXRates($TAXRatesName);
		$I->wait(3);
		$I->checkAllResults();
		$I->click(\TAXRatesJoomla3Page::$editButton);
		$I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameEditPage);
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, $TAXRatesNameEdit);
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageSaveSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function checkCancelTAXRates($TAXRatesName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchTAXRates($TAXRatesName);
		$I->wait(3);
		$I->checkAllResults();
		$I->click(\TAXRatesJoomla3Page::$editButton);
		$I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameEditPage);
		$I->click(\TAXRatesJoomla3Page::$cancelButton);
		$I->see(\TAXRatesJoomla3Page::$nameManagement, \TAXRatesJoomla3Page::$selectorNamePage);
	}

	public function editTAXRatesMissingName($TAXRatesName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchTAXRates($TAXRatesName);
		$I->wait(3);
		$I->click(\TAXRatesJoomla3Page::$taxStateNamePath);
		$I->waitForElement(\TAXRatesJoomla3Page::$TAXRatesName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameEditPage);
		$I->fillField(\TAXRatesJoomla3Page::$TAXRatesName, "");
		$I->click(\TAXRatesJoomla3Page::$saveButton);
		$I->see(\TAXRatesJoomla3Page::$messageError, \TAXRatesJoomla3Page::$selectorErrorHead);
	}

	public function deleteTAXRates($TAXRatesName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchTAXRates($TAXRatesName);
		$I->wait(3);
		$I->checkAllResults();
		$I->click(\TAXRatesJoomla3Page::$deleteButton);
		$I->see(\TAXRatesJoomla3Page::$messageSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function deleteTAXRatesOK($TAXRatesName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchTAXRates($TAXRatesName);
		$I->wait(3);
		$I->checkAllResults();
		$I->click(\TAXRatesJoomla3Page::$deleteButton);
		$I->acceptPopup();
		$I->see(\TAXRatesJoomla3Page::$messageSuccess, \TAXRatesJoomla3Page::$selectorSuccess);
	}

	public function deleteTAXRatesCancel($TAXRatesName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchTAXRates($TAXRatesName);
		$I->wait(3);
		$I->checkAllResults();
		$I->click(\TAXRatesJoomla3Page::$deleteButton);
		$I->cancelPopup();

	}

	public function checkCancel()
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$newButton);
		$I->verifyNotices(false, $this->checkForNotices(), \TAXRatesJoomla3Page::$nameNewPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\TAXRatesJoomla3Page::$cancelButton);
	}

	public function editButton()
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$editButton);
		$I->acceptPopup();
	}

	public function deleteButton()
	{
		$I = $this;
		$I->amOnPage(\TAXRatesJoomla3Page::$URL);
		$I->click(\TAXRatesJoomla3Page::$deleteButton);
		$I->acceptPopup();
	}
}