<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use TaxRatePage;
use TaxGroupPage;

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
	public function addTAXRatesSave($taxRateName, $taxGroupName, $taxRateValue, $nameCountry, $stateName)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $taxRateName);
		$client->fillField(\TaxRatePage::$fieldValue, $taxRateValue);
		$client->wait(0.5);
		$client->chooseOnSelect2(\TaxRatePage::$fieldCountry, $nameCountry);
		$client->wait(0.5);
		$client->waitForElement(\TaxRatePage::$stateDropdown, 30);
		if (isset($stateName))
		{
			$client->click(\TaxRatePage::$stateDropdown);
			$client->chooseOnSelect2(\TaxRatePage::$fieldState, $stateName);
		}
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $taxGroupName);
		$client->click(\TaxRatePage::$buttonSave);
		$client->waitForElement(\TaxRatePage::$selectorSuccess,30);
		$client->see(\TaxRatePage::$messageItemSaveSuccess, \TaxRatePage::$selectorSuccess);
		$client->click(\TaxRatePage::$buttonClose);
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
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);

		$client->click(\TaxRatePage::$buttonSave);
		try
		{
			$client->waitForElement(\TaxGroupPage::$selectorMissing,10);

		}catch (\Exception $e)
		{
			$client->click(\TaxRatePage::$buttonSaveClose);
			$client->waitForElement(\TaxGroupPage::$selectorMissing,10);
		}

		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorMissing);
		$client->click(\TaxRatePage::$buttonCancel);
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
		$client->waitForElement(\TaxGroupPage::$selectorMissing,30);
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorMissing);
		$client->click(\TaxRatePage::$buttonCancel);
	}

	public function addTAXRatesMissingTaxValueSave($TAXRatesName, $VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorMissing);
		$client->click(\TaxRatePage::$buttonCancel);
	}

	/**
	 * @param $VATGroupName
	 * @param $TaxRatesValue
	 * @param $nameCountry
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function addTAXRatesMissingNameSaveCloseAndSaveNew($VATGroupName, $TaxRatesValue, $nameCountry)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);

		$client->chooseOnSelect2(TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);

		$client->click(TaxRatePage::$buttonSaveClose);

		try
		{
			$client->waitForElement(TaxGroupPage::$selectorMissing, 30);
		}catch (\Exception $e)
		{
			$client->click(TaxRatePage::$buttonSaveClose);
			$client->waitForElement(TaxGroupPage::$selectorMissing, 30);
		}

		$client->click(TaxRatePage::$buttonSaveNew);

		try
		{
			$client->waitForElement(TaxGroupPage::$selectorMissing, 30);
		}catch (\Exception $e)
		{
			$client->click(TaxRatePage::$buttonSaveNew);
			$client->waitForElement(TaxGroupPage::$selectorMissing, 30);
		}

		$taxRateMessage = new TaxRatePage();
		$client->waitForText($taxRateMessage->messageMissing('Name'), 30, TaxRatePage::$selectorMissing);
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
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorMissing);
		$client->click(\TaxRatePage::$buttonCancel);
	}

	/**
	 * @param $TAXRatesName
	 * @param $TaxRatesValue
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function addTAXRatesMissingGroupsSaveCloseAndSaveNew($TAXRatesName, $TaxRatesValue)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);

		$client->click(TaxRatePage::$buttonSaveClose);
		$client->waitForElement(TaxGroupPage::$selectorMissing, 30);

		$client->click(TaxRatePage::$buttonSaveNew);
		$client->waitForElement(TaxGroupPage::$selectorMissing, 30);

		$taxRateMessage = new TaxRatePage();
		$client->waitForText($taxRateMessage->messageMissing('Group'), 30, TaxRatePage::$selectorMissing);
	}

	/**
	 * @param $TAXRatesName
	 * @param $VATGroupName
	 * @since 2.1.3
	 */
	public function addTAXRatesMissingTaxValueSaveCloseAndSaveNew($TAXRatesName, $VATGroupName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);

		$client->click(TaxRatePage::$buttonSaveClose);
		$client->see(TaxRatePage::$messageError, TaxRatePage::$selectorMissing);

		$client->click(TaxRatePage::$buttonSaveNew);
		$client->see(TaxRatePage::$messageError, TaxRatePage::$selectorMissing);

		$client->click(TaxRatePage::$buttonCancel);
	}

	/**
	 * @param $TAXRatesName
	 * @param $VATGroupName
	 * @param $TaxRatesValue
	 * @since 2.1.3
	 */
	public function addTAXRatesValueAmountLessZeroSaveCloseAndSaveNew($TAXRatesName, $VATGroupName, $TaxRatesValue)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);

		$client->click(TaxRatePage::$buttonSaveClose);
		$client->waitForText(TaxRatePage::$messageInvalid, 30, TaxRatePage::$selectorMissing);

		$client->click(TaxRatePage::$buttonSaveNew);
		$client->waitForText(TaxRatePage::$messageInvalid, 30, TaxRatePage::$selectorMissing);
	}

	/**
	 * @param $TAXRatesName
	 * @param $VATGroupName
	 * @param $TaxRatesValue
	 * @param $nameCountry
	 * @param $nameState
	 * @param string $buttonName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function addTAXRatesValueAmountString($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState, $buttonName = "SaveClose")
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);
		$client->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$client->wait(0.5);

		switch ($buttonName)
		{
			case 'Save':
				$client->click(TaxRatePage::$buttonSave);
				break;
			case 'SaveNew':
				$client->click(TaxRatePage::$buttonSaveNew);
				break;
			case 'SaveClose':
				$client->click(TaxRatePage::$buttonSaveClose);
				break;
			default:
				break;
		}

		$client->waitForElement(TaxRatePage::$selectorSuccess, 60);
		$client->waitForText(TaxRatePage::$messageItemSaveSuccess, 30, TaxRatePage::$selectorSuccess);
		$client->see(TaxRatePage::$messageItemSaveSuccess, TaxRatePage::$selectorSuccess);
	}

	public function addTAXRatesSaveClose($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->waitForElementVisible(\TaxRatePage::$fieldName, 30);
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesName);
		$client->waitForElementVisible(\TaxRatePage::$fieldValue, 30);
		$client->fillField(\TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(\TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(\TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(\TaxRatePage::$buttonSaveClose);
		$client->waitForText(\TaxRatePage::$messageItemSaveSuccess, 30, \TaxRatePage::$selectorSuccess);
	}

	public function editTAXRatesName($TAXRatesName, $TAXRatesNameEdit)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->checkAllResults();
		$client->click(\TaxRatePage::$buttonCheckIn);
		$client->click($TAXRatesName);
		$client->waitForElement(\TaxRatePage::$fieldName, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxRatePage::$fieldName, $TAXRatesNameEdit);
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageItemSaveSuccess, \TaxRatePage::$selectorSuccess);
		$client->click(\TaxRatePage::$buttonClose);
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
		$client->click($TAXRatesName);
		$client->waitForElement(\TaxRatePage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxRatePage::$nameEditPage);
		$client->fillField(\TaxRatePage::$fieldName, "");
		$client->click(\TaxRatePage::$buttonSave);
		$client->see(\TaxRatePage::$messageError, \TaxRatePage::$selectorMissing);
		$client->click(\TaxGroupPage::$buttonClose);
	}

	public function deleteTAXRatesOK($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->checkAllResults();
		$client->click(\TaxRatePage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForJS("return window.jQuery && jQuery.active == 0;", 30);

		try
		{
			$client->waitForText(\TaxRatePage::$messageDeleteSuccess, 5, \TaxRatePage::$selectorSuccess);
			$client->see(\TaxRatePage::$messageDeleteSuccess, \TaxRatePage::$selectorSuccess);
		} catch (\Exception $e)
		{
			$client->waitForText(\TaxRatePage::$messageNoItemOnTable, 10, \TaxRatePage::$selectorAlert);
			$client->see(\TaxRatePage::$messageNoItemOnTable, \TaxRatePage::$selectorAlert);
		}
	}

	public function deleteTAXRatesCancel($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchTAXRates($TAXRatesName);
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

	/**
	 * @param $name
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkEdit($taxRateName)
	{
		$I =  $this;
		$I->amOnPage(TaxRatePage::$url);
		$I->searchTAXRates($taxRateName);
		$I->click($taxRateName);
		$I->waitForText(TaxRatePage::$editTitle, 5, TaxRatePage::$selectorPageTitle);
	}

	public function deleteButton()
	{
		$client = $this;
		$client->amOnPage(\TaxRatePage::$url);
		$client->click(\TaxRatePage::$buttonDelete);
		$client->acceptPopup();
	}

	/**
	 * @param $taxRateName1
	 * @param $taxRateName2
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkResetButton($taxRateName1, $taxRateName2)
	{
		$I =  $this;
		$I->amOnPage(TaxRatePage::$url);
		$I->searchTAXRates($taxRateName1);
		$I->click(TaxRatePage::$buttonReset);
		$I->waitForText($taxRateName2, 5, TaxRatePage::$tableTaxRate);
	}

	/**
	 * @param $taxRateName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkSearchToolsEUCountry()
	{
		$I =  $this;
		$I->amOnPage(TaxRatePage::$url);
		$I->waitForElementVisible(TaxRatePage::$searchTools, 30);
		$I->wantTo("Check Search tools button");
		$I->click(TaxRatePage::$searchTools);
		$I->waitForElementVisible(TaxRatePage::$eUCountry, 30);

		$I->wantTo("Check EU Country");
		$I->click(TaxRatePage::$eUCountry);
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForText(TaxRatePage::$labelEU, 30, TaxRatePage::$selectorEUCountry);
	}

	/**
	 * @param $taxRateName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkInButton($taxRateName)
	{
		$I =  $this;
		$I->amOnPage(TaxRatePage::$url);
		$I->searchTAXRates($taxRateName);
		$I->checkAllResults();
		$I->click(TaxRatePage::$buttonCheckIn);
		$I->waitForText(TaxRatePage::$messageCheckInSuccess, 30, TaxRatePage::$selectorSuccess);
	}
}
