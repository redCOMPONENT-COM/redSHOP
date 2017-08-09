<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class SupplierManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class TaxRateSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Tax Rate
	 *
	 * @param   string  $taxRateName  Tax Rate name
	 * @param   string  $taxGroupName Tax Group name
	 * @param   integer $taxRateValue Tax Rate value
	 * @param   string  $nameCountry  Tax Rate name country
	 *
	 * @return void
	 */
	public function addTAXRatesSave($taxRateName, $taxGroupName, $taxRateValue, $nameCountry)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $taxRateName);
		$client->fillField(\TaxRatePage::$fieldValue, (int) $taxRateValue);
		$client->chooseOnSelect2(\TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $taxGroupName);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageItemSaveSuccess, \TaxRatePage::$selectorSuccess);
	}

	public function addTAXRatesMissingNameSave($VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldValue, $TaxRatesValue);

		$client->chooseOnSelect2(\TaxRatePage::$fieldCountry, $nameCountry);

//        $client->click(['xpath' => '//div[@id="s2id_jform_tax_state"]//a']);
//        $client->waitForElement(['id' => "s2id_autogen2_search"]);
//        $client->fillField(['id' => "s2id_autogen2_search"], $nameState);
//        $client->waitForElement(['xpath' => "//span[contains(text(), '" . $nameState . "')]"], 60);
//        $client->click(['xpath' => "//span[contains(text(), '" . $nameState . "')]"]);

		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);

		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorError);
	}

	public function addTAXRatesMissingGroupsSave($TAXRatesName, $TaxRatesValue)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(\TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorError);
	}

	public function addTAXRatesMissingTaxValueSave($TAXRatesName, $VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesName);
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageItemSaveSuccess, \TaxRatePage::$selectorSuccess);
	}

	public function addTAXRatesValueAmountLessZeroSave($TAXRatesName, $VATGroupName, $TaxRatesValue)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(\TaxRatePage::$fieldValue, $TaxRatesValue);

		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorError);
	}

	public function addTAXRatesValueAmountStringSave($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(\TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(\TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageItemSaveSuccess, \TaxRatePage::$selectorSuccess);
	}

	public function addTAXRatesSaveClose($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(\TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(\TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(\TaxRatePage::$buttonSaveClose);
		$client->see(\TaxRatePage::$messageItemSaveSuccess, \TaxRatePage::$selectorSuccess);
	}

	public function editTAXRatesName($TAXRatesName, $TAXRatesNameEdit)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->wait(3);
		$client->click($TAXRatesName);
		$client->waitForElement(\TaxRatePage::$fieldName, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesNameEdit);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageItemSaveSuccess, \TaxRatePage::$selectorSuccess);
	}

	public function searchTAXRates($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->waitForText(\TaxRatePage::$namePage, 30, \TaxRatePage::$headPage);
		$client->filterListBySearching($TAXRatesName);
	}

	public function checkCancelTAXRates($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->wait(3);
		$client->checkAllResults();
		$client->click($TAXRatesName);
		$client->waitForElement(\TaxRatePage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->click(\TaxRatePage::$buttonCancel);
		$client->see(\TaxRatePage::$namePage, \TaxRatePage::$selectorPageTitle);
	}

	public function editTAXRatesMissingName($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->wait(3);
		$client->click($TAXRatesName);
		$client->waitForElement(\TaxRatePage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->fillField(\TaxRatePage::$fieldName, "");
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorError);
	}

	public function deleteTAXRatesOK($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->wait(3);
		$client->checkAllResults();
		$client->click(\TaxRatePage::$buttonDelete);
		$client->acceptPopup();
		$client->see(\TaxRatePage::$messageSuccess, \TaxRatePage::$selectorSuccess);
	}

	public function deleteTAXRatesCancel($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->wait(3);
		$client->checkAllResults();
		$client->click(\TaxRatePage::$buttonDelete);
		$client->cancelPopup();
	}

	public function checkCancel()
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\TaxRatePage::$buttonCancel);
	}

	public function deleteButton()
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonDelete);
		$client->acceptPopup();
	}
}