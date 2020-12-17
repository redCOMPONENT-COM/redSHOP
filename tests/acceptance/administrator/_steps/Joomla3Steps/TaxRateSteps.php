<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
	 * @param $taxRateName
	 * @param $taxGroupName
	 * @param $taxRateValue
	 * @param $nameCountry
	 * @param $stateName
	 * @param $shopperGroup
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesSave($taxRateName, $taxGroupName, $taxRateValue, $nameCountry, $stateName, $shopperGroup)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $taxRateName);
		$client->fillField(TaxRatePage::$fieldValue, $taxRateValue);
		$client->wait(0.5);
		$client->chooseOnSelect2(TaxRatePage::$fieldCountry, $nameCountry);
		$client->wait(0.5);
		$client->waitForElement(TaxRatePage::$stateDropdown, 30);

		if (isset($stateName))
		{
			$client->click(TaxRatePage::$stateDropdown);
			$client->chooseOnSelect2(TaxRatePage::$fieldState, $stateName);
		}

		if (isset($shopperGroup))
		{
			$client->waitForElementVisible(TaxRatePage::$fieldShopperGroup, 30);

			if($shopperGroup == "All")
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Private");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Company");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Tax Exempt");
			}else
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, $shopperGroup);
			}
		}

		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $taxGroupName);
		$client->click(TaxRatePage::$buttonSave);
		$client->waitForElement(TaxRatePage::$selectorSuccess,30);
		$client->see(TaxRatePage::$messageItemSaveSuccess, TaxRatePage::$selectorSuccess);
		$client->click(TaxRatePage::$buttonClose);
		$client->waitForText(TaxRatePage::$namePage, 30);
	}

	/**
	 * @param $VATGroupName
	 * @param $TaxRatesValue
	 * @param $nameCountry
	 * @param $nameState
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesMissingNameSave($VATGroupName, $TaxRatesValue, $nameCountry, $nameState)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);

		$client->chooseOnSelect2(TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);

		$client->click(TaxRatePage::$buttonSave);
		try
		{
			$client->waitForElement(TaxRatePage::$selectorMissing,10);

		}catch (\Exception $e)
		{
			$client->click(TaxRatePage::$buttonSaveClose);
			$client->waitForElement(TaxRatePage::$selectorMissing,10);
		}

		$client->see(TaxRatePage::$messageError, TaxRatePage::$selectorMissing);
		$client->click(TaxRatePage::$buttonCancel);
	}

	/**
	 * @param $TAXRatesName
	 * @param $TaxRatesValue
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesMissingGroupsSave($TAXRatesName, $TaxRatesValue)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->click(TaxRatePage::$buttonSave);
		$client->waitForElement(TaxRatePage::$selectorMissing,30);
		$client->see(TaxRatePage::$messageError, TaxRatePage::$selectorMissing);
		$client->click(TaxRatePage::$buttonCancel);
	}

	/**
	 * @param $TAXRatesName
	 * @param $VATGroupName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesMissingTaxValueSave($TAXRatesName, $VATGroupName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(TaxRatePage::$buttonSave);
		$client->see(TaxRatePage::$messageError, TaxRatePage::$selectorMissing);
		$client->click(TaxRatePage::$buttonCancel);
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

		$client->wait(0.5);
		$client->waitForElement(TaxGroupPage::$selectorMissing, 30);

		$client->click(TaxRatePage::$buttonSaveNew);
		$client->wait(0.5);
		$client->waitForElement(TaxGroupPage::$selectorMissing, 30);
		$taxRateMessage = new TaxRatePage();
		$client->waitForText($taxRateMessage->messageMissing('Name'), 30, TaxRatePage::$selectorMissing);
	}

	/**
	 * @param $TAXRatesName
	 * @param $VATGroupName
	 * @param $TaxRatesValue
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesValueAmountLessZeroSave($TAXRatesName, $VATGroupName, $TaxRatesValue)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);
		$client->click(TaxRatePage::$buttonSave);
		$client->see(TaxRatePage::$messageError, TaxRatePage::$selectorMissing);
		$client->click(TaxRatePage::$buttonCancel);
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

		$taxRateMessage = new TaxRatePage();
		$client->waitForText($taxRateMessage->messageMissing('Group'), 30, TaxRatePage::$selectorMissing);
	}

	/**
	 * @param $TAXRatesName
	 * @param $VATGroupName
	 * @throws \Exception
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
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function addTAXRatesValueAmountLessZeroSaveCloseAndSaveNew($TAXRatesName, $VATGroupName, $TaxRatesValue, $shopperGroup)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesName);
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);

		if (isset($shopperGroup))
		{
			$client->waitForElementVisible(TaxRatePage::$fieldShopperGroup, 30);

			if($shopperGroup == "All")
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Private");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Company");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Tax Exempt");
			}else
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, $shopperGroup);
			}
		}

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
	 * @param $shopperGroup
	 * @param string $buttonName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function addTAXRatesValueAmountString($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $shopperGroup, $buttonName = "SaveClose")
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

		if (isset($shopperGroup))
		{
			$client->waitForElementVisible(TaxRatePage::$fieldShopperGroup, 30);

			if($shopperGroup == "All")
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Private");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Company");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Tax Exempt");
			}else
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, $shopperGroup);
			}
		}

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

	/**
	 * @param $TAXRatesName
	 * @param $VATGroupName
	 * @param $TaxRatesValue
	 * @param $nameCountry
	 * @param $shopperGroup
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesSaveClose($TAXRatesName, $VATGroupName, $TaxRatesValue, $nameCountry, $shopperGroup)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->waitForElementVisible(TaxRatePage::$fieldName, 30);
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesName);
		$client->waitForElementVisible(TaxRatePage::$fieldValue, 30);
		$client->fillField(TaxRatePage::$fieldValue, $TaxRatesValue);
		$client->chooseOnSelect2(TaxRatePage::$fieldCountry, $nameCountry);
		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $VATGroupName);

		if (isset($shopperGroup))
		{
			$client->waitForElementVisible(TaxRatePage::$fieldShopperGroup, 30);

			if($shopperGroup == "All")
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Private");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Company");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Tax Exempt");
			}else
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, $shopperGroup);
			}
		}

		$client->click(TaxRatePage::$buttonSaveClose);
		$client->waitForText(TaxRatePage::$messageItemSaveSuccess, 30, TaxRatePage::$selectorSuccess);
	}

	/**
	 * @param $TAXRatesName
	 * @param $TAXRatesNameEdit
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editTAXRatesName($TAXRatesName, $TAXRatesNameEdit)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->waitForElementVisible(TaxRatePage::$checkAllXpath, 30);
		$client->click(TaxRatePage::$checkAllXpath);
		$client->click(TaxRatePage::$buttonCheckIn);
		$client->click($TAXRatesName);
		$client->waitForElement(TaxRatePage::$fieldName, 30);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $TAXRatesNameEdit);
		$client->click(TaxRatePage::$buttonSave);
		$client->see(TaxRatePage::$messageItemSaveSuccess, TaxRatePage::$selectorSuccess);
		$client->click(TaxRatePage::$buttonClose);
	}

	/**
	 * @param $TAXRatesName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function searchTAXRates($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->waitForText(TaxRatePage::$namePage, 30, TaxRatePage::$headPage);
		$client->filterListBySearching($TAXRatesName);
	}

	/**
	 * @param $TAXRatesName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkCancelTAXRates($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->checkAllResults();
		$client->click($TAXRatesName);
		$client->waitForElement(TaxRatePage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->click(TaxRatePage::$buttonCancel);
		$client->see(TaxRatePage::$namePage, TaxRatePage::$selectorPageTitle);
	}

	/**
	 * @param $TAXRatesName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editTAXRatesMissingName($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->click($TAXRatesName);
		$client->waitForElement(TaxRatePage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->fillField(TaxRatePage::$fieldName, "");
		$client->click(TaxRatePage::$buttonSave);
		$client->see(TaxRatePage::$messageError, TaxRatePage::$selectorMissing);
		$client->click(TaxRatePage::$buttonClose);
	}

	/**
	 * @param $TAXRatesName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteTAXRatesOK($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->waitForElementVisible(TaxRatePage::$checkAllXpath, 30);
		$client->click(TaxRatePage::$checkAllXpath);
		$client->click(TaxRatePage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForJS("return window.jQuery && jQuery.active == 0;", 30);

		try
		{
			$client->waitForText(TaxRatePage::$messageDeleteSuccess, 5, TaxRatePage::$selectorSuccess);
			$client->see(TaxRatePage::$messageDeleteSuccess, TaxRatePage::$selectorSuccess);
		} catch (\Exception $e)
		{
			$client->waitForText(TaxRatePage::$messageNoItemOnTable, 10, TaxRatePage::$selectorAlert);
			$client->see(TaxRatePage::$messageNoItemOnTable, TaxRatePage::$selectorAlert);
		}
	}

	public function deleteTAXRatesCancel($TAXRatesName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->searchTAXRates($TAXRatesName);
		$client->checkAllResults();
		$client->click(TaxRatePage::$buttonDelete);
		$client->cancelPopup();
	}

	public function checkCancel()
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(TaxRatePage::$buttonCancel);
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
		$I->waitForElementVisible(TaxRatePage::xpathLink($taxRateName), 30);
		$I->click(TaxRatePage::xpathLink($taxRateName));
		$I->waitForText(TaxRatePage::$editTitle, 5, TaxRatePage::$selectorPageTitle);
		$I->click(TaxRatePage::$buttonClose);
	}

	/**
	 * Function check delete button
	 * @since 1.4.0
	 */
	public function deleteButton()
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonDelete);
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
		$I->waitForElementVisible(TaxRatePage::$checkAllXpath, 30);
		$I->click(TaxRatePage::$checkAllXpath);
		$I->click(TaxRatePage::$buttonCheckIn);
		$I->waitForText(TaxRatePage::$messageCheckInSuccess, 30, TaxRatePage::$selectorSuccess);
	}

	/**
	 * @param $taxRate
	 * @param $taxGroupName
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function createVATRateEUMode($taxRate, $taxGroupName)
	{
		$client = $this;
		$client->amOnPage(TaxRatePage::$url);
		$client->click(TaxRatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), TaxRatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(TaxRatePage::$fieldName, $taxRate['name']);
		$client->fillField(TaxRatePage::$fieldValue, $taxRate['amount']);
		$client->waitForElement(TaxRatePage::$fieldCountry, 30);
		$client->chooseOnSelect2(TaxRatePage::$fieldCountry, $taxRate['country']);
		$client->waitForElementVisible(TaxRatePage::$stateDropdown, 30);

		if (isset($taxRate['shopperGroup']))
		{
			$client->waitForElementVisible(TaxRatePage::$fieldShopperGroup, 30);

			if($taxRate['shopperGroup'] == "All")
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Private");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Company");
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, "Default Tax Exempt");
			}else
			{
				$client->chooseOnSelect2(TaxRatePage::$fieldShopperGroup, $taxRate['shopperGroup']);
			}
		}

		$client->chooseOnSelect2(TaxRatePage::$fieldGroup, $taxGroupName);
		$client->waitForElementVisible(TaxRatePage::$countryEUYes, 30);
		$client->click(TaxRatePage::$countryEUYes);
		$client->waitForText(TaxRatePage::$buttonSave, 30);
		$client->click(TaxRatePage::$buttonSave);
		$client->waitForElement(TaxRatePage::$selectorSuccess,30);
		$client->see(TaxRatePage::$messageItemSaveSuccess, TaxRatePage::$selectorSuccess);
		$client->click(TaxRatePage::$buttonClose);
		$client->waitForText(TaxRatePage::$namePage, 30);
	}
}
