<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class VoucherManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class VoucherManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Create a new Voucher
	 *
	 * @param   string $code Code for the Voucher
	 * @param   string $amount Amount of the Voucher
	 * @param   string $count Count of the Vouchers
	 *
	 * @return void
	 */
	public function addVoucher($code, $amount, $voucherStartDate, $voucherEndDate, $count, $nameProduct, $nameFunction)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
		$I->click(\VoucherManagerPage::$newButton);
		switch ($nameFunction) {
			case 'save':
				$I->fillField(\VoucherManagerPage::$voucherCode, $code);
				$I->fillField(\VoucherManagerPage::$voucherAmount, $amount);

				// @todo: we need a generic function to select options in a Select2 multiple option field
				$I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
				$I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
				$I->click(\VoucherManagerPage::$waitProduct);
				// end of select2

				$I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherStartDate);
				$I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherEndDate);
				$I->fillField(\VoucherManagerPage::$voucherLeft, $count);
				$I->click(\VoucherManagerPage::$saveButton);
				$I->see(\VoucherManagerPage::$messageSaveSuccess, \VoucherManagerPage::$selectorSuccess);
				$I->click(\VoucherManagerPage::$closeButton);

				break;
			case 'saveclose':
				$I->fillField(\VoucherManagerPage::$voucherCode, $code);
				$I->fillField(\VoucherManagerPage::$voucherAmount, $amount);

				// @todo: we need a generic function to select options in a Select2 multiple option field
				$I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
				$I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
				$I->click(\VoucherManagerPage::$waitProduct);

				$I->fillField(\VoucherManagerPage::$voucherLeft, $count);
				$I->click(\VoucherManagerPage::$saveCloseButton);
				$I->waitForElement(\VoucherManagerPage::$messageContainer, 60);
				$I->see(\VoucherManagerPage::$messageSaveSuccess, \VoucherManagerPage::$selectorSuccess);
				$I->seeElement(['link' => $code]);
				break;
			case 'validday':
				$I->fillField(\VoucherManagerPage::$voucherCode, $code);
				$I->fillField(\VoucherManagerPage::$voucherAmount, $amount);
				$I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
				$I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
				$I->click(\VoucherManagerPage::$waitProduct);
				// end of select2
				$I->fillField(\VoucherManagerPage::$voucherLeft, $count);
				$I->click(\VoucherManagerPage::$saveButton);
				$I->see(\VoucherManagerPage::$messageSaveSuccess, \VoucherManagerPage::$selectorSuccess);
				break;

		}
	}

	public function addStartMoreThanEnd($code, $amount, $voucherStartDate, $voucherEndDate, $count, $nameProduct)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
		$I->click(\VoucherManagerPage::$newButton);
		$I->fillField(\VoucherManagerPage::$voucherCode, $code);
		$I->fillField(\VoucherManagerPage::$voucherAmount, $amount);

		// @todo: we need a generic function to select options in a Select2 multiple option field
		$I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
		$I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
		$I->click(\VoucherManagerPage::$waitProduct);
		// end of select2

		$I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherEndDate);
		$I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherStartDate);
		$I->fillField(\VoucherManagerPage::$voucherLeft, $count);
		$I->click(\VoucherManagerPage::$saveButton);
		$I->acceptPopup();
	}

	public function voucherMissingFieldValidValidation($code, $amount, $voucherStartDate, $voucherEndDate, $count, $nameProduct, $fieldName)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
		$I->click(\VoucherManagerPage::$newButton);

		switch ($fieldName) {
			case 'code':
				$I->fillField(\VoucherManagerPage::$voucherAmount, $amount);
				$I->fillField(\VoucherManagerPage::$voucherCode, "");
				// @todo: we need a generic function to select options in a Select2 multiple option field
				$I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
				$I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
				$I->click(\VoucherManagerPage::$waitProduct);
				// end of select2

				$I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherEndDate);
				$I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherStartDate);
				$I->fillField(\VoucherManagerPage::$voucherLeft, $count);
				$I->click(\VoucherManagerPage::$saveButton);
				$I->see(\VoucherManagerPage::$invalidCode, \VoucherManagerPage::$xPathInvalid);
				break;

			/* @TODO: Since vouchers can be set associated with all products if leave it empty. Keep this check for future consider.
			 * case 'product':
			 * $I->fillField(\VoucherManagerPage::$voucherCode, $code);
			 * $I->fillField(\VoucherManagerPage::$voucherAmount, $amount);
			 * $I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherEndDate);
			 * $I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherStartDate);
			 * $I->fillField(\VoucherManagerPage::$voucherLeft, $count);
			 * $I->click(\VoucherManagerPage::$saveButton);
			 * $I->see(\VoucherManagerPage::$invalidProduct, \VoucherManagerPage::$xPathInvalid);
			 * break;*/
		}
		$I->click(\VoucherManagerPage::$closeButton);
	}

	/**
	 * Function to edit a Voucher Code
	 *
	 * @param   String $voucherCode Code for the Current Voucher
	 * @param   String $voucherNewCode New Code for the Voucher
	 *
	 * @return void
	 */
	public function editVoucher($voucherCode, $voucherNewCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchVoucherCode($voucherCode);
		$I->checkAllResults();
		$I->click(\VoucherManagerPage::$checkInButton);
		$I->click($voucherCode);
		$I->waitForElement(\VoucherManagerPage::$voucherCode, 30);
		$I->checkForPhpNoticesOrWarnings();
		$I->wait(0.5);
		$I->fillField(\VoucherManagerPage::$voucherCode, $voucherNewCode);
		$I->click(\VoucherManagerPage::$saveCloseButton);
		$I->assertSystemMessageContains(\VoucherManagerPage::$messageSaveSuccess);
		$I->seeElement(['link' => $voucherNewCode]);
	}

	public function editVoucherMissingName($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchVoucherCode($voucherCode);
		$I->click($voucherCode);
		$I->waitForElement(\VoucherManagerPage::$voucherCode, 30);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\VoucherManagerPage::$voucherCode, "");
		$I->click(\VoucherManagerPage::$saveCloseButton);
		$I->assertSystemMessageContains(\VoucherManagerPage::$invalidCode);
		$I->click(\VoucherManagerPage::$closeButton);
	}

	public function checkCloseButton($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchVoucherCode($voucherCode);
		$I->click($voucherCode);
		$I->waitForElement(\VoucherManagerPage::$voucherCode, 30);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\VoucherManagerPage::$voucherCode, "");
		$I->click(\VoucherManagerPage::$closeButton);
		$I->searchVoucherCode($voucherCode);
	}

	public function checkButtons($buttonName)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->see(\VoucherManagerPage::$namePageManagement, \VoucherManagerPage::$selectorNamePage);
		switch ($buttonName)
		{
			case 'delete':
				$I->click(\VoucherManagerPage::$deleteButton);
				$I->acceptPopup();
				break;
			case 'publish':
				$I->click(\VoucherManagerPage::$publishButton);
				$I->acceptPopup();
				break;
			case 'unpublish':
				$I->click(\VoucherManagerPage::$unpublishButton);
				$I->acceptPopup();
				break;
			case 'cancel':
				$I->click(\VoucherManagerPage::$newButton);
				$I->waitForElementVisible(\VoucherManagerPage::$voucherCode, 30);
				$I->wait(0.5);
				$I->click(\VoucherManagerPage::$cancelButton);
				break;
			default:
				break;
		}

		$I->waitForText(\VoucherManagerPage::$namePageManagement, 30, \VoucherManagerPage::$selectorNamePage);
		$I->see(\VoucherManagerPage::$namePageManagement, \VoucherManagerPage::$selectorNamePage);
	}

	/**
	 * Function to Delete a Voucher
	 *
	 * @param   String $voucherCode Code of the voucher which is to be deleted
	 *
	 * @return void
	 */
	public function deleteVoucher($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchVoucherCode($voucherCode);
		$I->waitForElement(\VoucherManagerPage::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(\VoucherManagerPage::$deleteButton);
		$I->acceptPopup();
//		$I->waitForText(\VoucherManagerPage::$messageDeletedOneSuccess, 60, \VoucherManagerPage::$selectorSuccess);
//		$I->see(\VoucherManagerPage::$messageDeletedOneSuccess, \VoucherManagerPage::$selectorSuccess);
		$I->fillField(\VoucherManagerPage::$searchField, $voucherCode);
		$I->pressKey(\VoucherManagerPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($voucherCode, \VoucherManagerPage::$voucherResultRow);
	}

	/**
	 * Function to Change Voucher State
	 *
	 * @param   string $voucherCode Code of the voucher for which the state is to be changed
	 *
	 * @return  void
	 */
	public function changeVoucherState($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->searchVoucherCode($voucherCode);
		$I->waitForElement(\VoucherManagerPage::$voucherResultRow, 30);
		$I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
		$I->click(\VoucherManagerPage::$xPathStatus);
	}

	public function changeVoucherUnpublishButton($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->filterListBySearching($voucherCode);
		$I->waitForElement(\VoucherManagerPage::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(\VoucherManagerPage::$unpublishButton);
//		$I->see(\VoucherManagerPage::$messageUnpublishSuccess, \VoucherManagerPage::$selectorSuccess);
	}

	public function changeVoucherPublishButton($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->filterListBySearching($voucherCode);
		$I->waitForElement(\VoucherManagerPage::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(\VoucherManagerPage::$publishButton);
//		$I->see(\VoucherManagerPage::$messagePublishSuccess, \VoucherManagerPage::$selectorSuccess);
	}


	public function changeAllVoucherUnpublishButton()
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->waitForElement(\VoucherManagerPage::$searchField, 30);
		$I->waitForElement(\VoucherManagerPage::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(\VoucherManagerPage::$unpublishButton);
	}

	public function changeAllVoucherPublishButton()
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->waitForElement(\VoucherManagerPage::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(\VoucherManagerPage::$publishButton);
	}

	public function deleteAllVoucher($updatedRandomVoucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->click(\VoucherManagerPage::$resetButton);
		$I->waitForElement(\VoucherManagerPage::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(\VoucherManagerPage::$deleteButton);
		$I->acceptPopup();;
//		$I->see(\VoucherManagerPage::$messageSuccess, \VoucherManagerPage::$selectorSuccess);
		$I->dontSeeElement(['link' => $updatedRandomVoucherCode]);
	}

	/**
	 * Function to return the Result of the State of a Voucher
	 *
	 * @param   String $voucherCode Code of the Voucher for which State is tobe Determined
	 *
	 * @return string  State of the Voucher
	 */
	public function getVoucherState($voucherCode)
	{
		$result = $this->getState(new \VoucherManagerPage, $voucherCode, \VoucherManagerPage::$voucherResultRow, \VoucherManagerPage::$voucherStatePath);
		return $result;
	}

	public function searchVoucherCode($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->waitForText(\VoucherManagerPage::$namePageManagement, 30, \VoucherManagerPage::$headPageName);
		$I->filterListBySearching($voucherCode);
	}
}
