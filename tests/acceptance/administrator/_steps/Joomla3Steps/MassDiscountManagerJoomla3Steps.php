<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use MassDiscountManagerPage;

/**
 * Class MassDiscountManagerJoomla3Steps
 * @package AcceptanceTester
 */
class MassDiscountManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $massDiscountName
	 * @param $amountValue
	 * @param $discountStart
	 * @param $discountEnd
	 * @param $nameCategory
	 * @param $nameProduct
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscount($massDiscountName, $amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);

		$toDay = date('Y-m-d');

		$I->fillField(MassDiscountManagerPage::$name, $massDiscountName);
		$I->fillField(MassDiscountManagerPage::$valueAmount, $amountValue);
		$I->fillField(MassDiscountManagerPage::$fieldStartDate, $toDay);
		$I->fillField(MassDiscountManagerPage::$fieldEndDate, $toDay);
		$I->click(MassDiscountManagerPage::$buttonSave);

		$I->click(MassDiscountManagerPage::$categoryForm);
		$I->fillField(MassDiscountManagerPage::$categoryFormInput, $nameCategory);
		$useMassDiscountPage = new MassDiscountManagerPage();
		$I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
		$I->click($useMassDiscountPage->returnXpath($nameCategory));
		$I->click(MassDiscountManagerPage::$buttonSave);
		$I->see(MassDiscountManagerPage::$saveOneSuccess, MassDiscountManagerPage::$selectorSuccess);
	}

	/**
	 * @param $massDiscountName
	 * @param $amountValue
	 * @param $discountStart
	 * @param $discountEnd
	 * @param $nameCategory
	 * @param $nameProduct
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscountSaveClose($massDiscountName, $amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);
		$I->fillField(MassDiscountManagerPage::$name, $massDiscountName);
		$I->fillField(MassDiscountManagerPage::$valueAmount, $amountValue);

		$toDay = date('Y-m-d');
		$I->fillField(MassDiscountManagerPage::$fieldStartDate, $toDay);
		$I->fillField(MassDiscountManagerPage::$fieldEndDate, $toDay);
		$I->click(MassDiscountManagerPage::$buttonSave);

		$I->click(MassDiscountManagerPage::$categoryForm);
		$I->fillField(MassDiscountManagerPage::$categoryFormInput, $nameCategory);
		$useMassDiscountPage = new MassDiscountManagerPage();
		$I->waitForElement($useMassDiscountPage->returnXpath($nameCategory), 30);
		$I->click($useMassDiscountPage->returnXpath($nameCategory));

		$I->click(MassDiscountManagerPage::$buttonSaveClose);
		$I->waitForText(MassDiscountManagerPage::$saveOneSuccess, 30, MassDiscountManagerPage::$selectorSuccess);

		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
	}

	/**
	 * @param $massDiscountName
	 * @param $amountValue
	 * @param $nameCategory
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscountBeforeToday($massDiscountName, $amountValue, $nameCategory)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);

		$dateNow = date('Y-m-d');
		$date = date('Y-m-d', strtotime('-2 day', strtotime($dateNow)));
		$endDate = date('Y-m-d', strtotime('-1 day', strtotime($dateNow)));

		$I->fillField(MassDiscountManagerPage::$name, $massDiscountName);
		$I->fillField(MassDiscountManagerPage::$valueAmount, $amountValue);
		$I->fillField(MassDiscountManagerPage::$fieldStartDate, $date);
		$I->fillField(MassDiscountManagerPage::$fieldEndDate, $endDate);
		$I->click(MassDiscountManagerPage::$buttonSave);

		$I->click(MassDiscountManagerPage::$categoryForm);
		$I->fillField(MassDiscountManagerPage::$categoryFormInput, $nameCategory);
		$useMassDiscountPage = new MassDiscountManagerPage();
		$I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
		$I->click($useMassDiscountPage->returnXpath($nameCategory));
		$I->click(MassDiscountManagerPage::$buttonSave);
		$I->see(MassDiscountManagerPage::$saveOneSuccess, MassDiscountManagerPage::$selectorSuccess);
	}

	/**
	 * @param $massDiscountName
	 * @param $amountValue
	 * @param $discountStart
	 * @param $discountEnd
	 * @param $nameCategory
	 * @param $nameProduct
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscountStartThanEnd($massDiscountName, $amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);
		$I->fillField(MassDiscountManagerPage::$name, $massDiscountName);
		$I->fillField(MassDiscountManagerPage::$valueAmount, $amountValue);

		$I->waitForElement(MassDiscountManagerPage::$startDateIcon, 30);
		$I->click(MassDiscountManagerPage::$startDateIcon);
		$I->waitForElementVisible(MassDiscountManagerPage::$getToday);
		$I->click(MassDiscountManagerPage::$getToday);
		$I->wait(2);
		$I->click(MassDiscountManagerPage::$buttonSave);
		$I->wait(2);
		$I->click(MassDiscountManagerPage::$endDateIcon);
		$I->waitForElementVisible(MassDiscountManagerPage::$endDateIcon);
		$I->waitForElementVisible(MassDiscountManagerPage::$getToday);
		$I->click(MassDiscountManagerPage::$getToday);
		$I->wait(2);

		$useMassDiscountPage = new MassDiscountManagerPage();

		$I->click(MassDiscountManagerPage::$categoryForm);
		$I->fillField(MassDiscountManagerPage::$categoryFormInput, $nameCategory);
		$useMassDiscountPage->returnXpath($nameCategory);
		$I->click(MassDiscountManagerPage::$discountForm);
		$I->fillField(MassDiscountManagerPage::$discountFormInput, $nameProduct);
		$useMassDiscountPage->returnXpath($nameProduct);

		$I->click(MassDiscountManagerPage::$buttonSave);
		$I->waitForText(MassDiscountManagerPage::$messageError, 30, MassDiscountManagerPage::$selectorMissing);
	}

	/**
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscountMissingAllFields()
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);
		$I->click(MassDiscountManagerPage::$buttonSave);
		$I->waitForText(MassDiscountManagerPage::$fieldName, 30, MassDiscountManagerPage::$selectorMissing);
	}

	/**
	 * @param $amountValue
	 * @param $discountStart
	 * @param $discountEnd
	 * @param $nameCategory
	 * @param $nameProduct
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscountMissingName($amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);
		$I->fillField(MassDiscountManagerPage::$valueAmount, $amountValue);

		$I->fillField(MassDiscountManagerPage::$fieldStartDate, date('Y-m-d'));
		$I->click(MassDiscountManagerPage::$buttonSave);
		$I->waitForText(MassDiscountManagerPage::$fieldName, 30, MassDiscountManagerPage::$selectorMissing);
	}

	/**
	 * @param $massDiscountName
	 * @param $discountStart
	 * @param $discountEnd
	 * @param $nameCategory
	 * @param $nameProduct
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscountMissingAmount($massDiscountName, $discountStart, $discountEnd, $nameCategory, $nameProduct)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);
		$I->fillField(MassDiscountManagerPage::$name, $massDiscountName);

		$toDay = date('Y-m-d');
		$I->fillField(MassDiscountManagerPage::$fieldStartDate, $toDay);
		$I->fillField(MassDiscountManagerPage::$fieldEndDate, $toDay);
		$I->click(MassDiscountManagerPage::$buttonSave);

		$I->click(MassDiscountManagerPage::$categoryForm);
		$I->fillField(MassDiscountManagerPage::$categoryFormInput, $nameCategory);
		$useMassDiscountPage = new MassDiscountManagerPage();
		$I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
		$I->click($useMassDiscountPage->returnXpath($nameCategory));

		$I->click(MassDiscountManagerPage::$buttonSave);

		$I->waitForText(MassDiscountManagerPage::$messageError, 30, MassDiscountManagerPage::$selectorMissing);
	}

	/**
	 * @param $massDiscountName
	 * @param $amountValue
	 * @param $discountStart
	 * @param $discountEnd
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addMassDiscountMissingProducts($massDiscountName, $amountValue, $discountStart, $discountEnd)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);
		$I->fillField(MassDiscountManagerPage::$name, $massDiscountName);
		$I->fillField(MassDiscountManagerPage::$valueAmount, $amountValue);

		$toDay = date('Y-m-d');
		$I->fillField(MassDiscountManagerPage::$fieldStartDate, $toDay);
		$I->fillField(MassDiscountManagerPage::$fieldEndDate, $toDay);

		$I->click(MassDiscountManagerPage::$buttonSave);
		$I->waitForText(MassDiscountManagerPage::$saveError, 30, MassDiscountManagerPage::$selectorMissing);
	}

	/**
	 * @param $massDiscountName
	 * @param $massDiscountNameEdit
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editMassDiscount($massDiscountName, $massDiscountNameEdit)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->searchMassDiscount($massDiscountName);
		$I->click(['link' => $massDiscountName]);
		$I->waitForElement(MassDiscountManagerPage::$name, 30);
		$I->verifyNotices(false, $this->checkForNotices(), MassDiscountManagerPage::$pageEdit);
		$I->fillField(MassDiscountManagerPage::$name, $massDiscountNameEdit);
		$I->click(MassDiscountManagerPage::$buttonSaveClose);
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
	}

	/**
	 * @param $massDiscountName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function searchMassDiscount($massDiscountName)
	{
		$I = $this;
		$I->wantTo('Search the Mass Discount');
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
		$I->filterListBySearching($massDiscountName);
	}

	/**
	 * @param $massDiscountName
	 * @param $massDiscountNameEdit
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editMassDiscountSave($massDiscountName, $massDiscountNameEdit)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->searchMassDiscount($massDiscountName);
		$I->click(['link' => $massDiscountName]);
		$I->waitForElement(MassDiscountManagerPage::$name, 30);
		$I->verifyNotices(false, $this->checkForNotices(), MassDiscountManagerPage::$pageEdit);
		$I->fillField(MassDiscountManagerPage::$name, $massDiscountNameEdit);
		$I->click(MassDiscountManagerPage::$buttonSaveClose);
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
	}

	/**
	 * @param $massDiscountName
	 * @param $massDiscountNameEdit
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editButtonMassDiscountSave($massDiscountName, $massDiscountNameEdit)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->searchMassDiscount($massDiscountName);
		$I->checkAllResults();
		$I->click(MassDiscountManagerPage::$buttonCheckIn);
		$I->click(MassDiscountManagerPage::$checkFirstItems);
		$I->click(MassDiscountManagerPage::$buttonEdit);
		$I->waitForElement(MassDiscountManagerPage::$name, 30);
		$I->verifyNotices(false, $this->checkForNotices(), MassDiscountManagerPage::$pageEdit);
		$I->fillField(MassDiscountManagerPage::$name, $massDiscountNameEdit);
		$I->click(MassDiscountManagerPage::$buttonSaveClose);
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
	}

	/**
	 * @param $massDiscountName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkCloseButton($massDiscountName)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->searchMassDiscount($massDiscountName);
		$I->checkAllResults();
		$I->click(MassDiscountManagerPage::$buttonCheckIn);
		$I->click(['link' => $massDiscountName]);
		$I->waitForElement(MassDiscountManagerPage::$name, 30);
		$I->verifyNotices(false, $this->checkForNotices(), MassDiscountManagerPage::$pageEdit);

		$I->click(MassDiscountManagerPage::$buttonClose);
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
	}

	/**
	 * @param $massDiscountName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteMassDiscountCancel($massDiscountName)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->searchMassDiscount($massDiscountName);
		$I->click(MassDiscountManagerPage::$checkFirstItems);
		$I->click(MassDiscountManagerPage::$buttonDelete);
		$I->cancelPopup();
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
	}

	/**
	 * @param $massDiscountName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteMassDiscountOK($massDiscountName)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->searchMassDiscount($massDiscountName);
		$I->checkAllResults();
		$I->click(MassDiscountManagerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
		$I->fillField(MassDiscountManagerPage::$MassDiscountFilter, $massDiscountName);
		$I->pressKey(MassDiscountManagerPage::$MassDiscountFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($massDiscountName, MassDiscountManagerPage::$MassDicountResultRow);
	}

	/**
	 * @param $massDiscountName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteAllMassDiscountOK($massDiscountName)
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->checkAllResults();
		$I->click(MassDiscountManagerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
		$I->fillField(MassDiscountManagerPage::$MassDiscountFilter, $massDiscountName);
		$I->pressKey(MassDiscountManagerPage::$MassDiscountFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($massDiscountName, MassDiscountManagerPage::$MassDicountResultRow);
	}

	/**
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkCancelButton()
	{
		$I = $this;
		$I->amOnPage(MassDiscountManagerPage::$URL);
		$I->click(MassDiscountManagerPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings(MassDiscountManagerPage::$URLNew);
		$I->click(MassDiscountManagerPage::$buttonCancel);
		$I->waitForElement(MassDiscountManagerPage::$MassDiscountFilter, 30);
	}
}