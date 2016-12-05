<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
	 * @param   string  $code    Code for the Voucher
	 * @param   string  $amount  Amount of the Voucher
	 * @param   string  $count   Count of the Vouchers
	 *
	 * @return void
	 */
	public function addVoucher($code = 'Testing123', $amount = '100', $count = '10')
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click('New');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\VoucherManagerPage::$voucherCode, $code);
		$I->fillField(\VoucherManagerPage::$voucherAmount, $amount);

		// @todo: we need a generic function to select options in a Select2 multiple option field
		$I->fillField(['id' => 's2id_autogen1'], 'redCORE');
		$I->waitForElement(['css' => 'span.select2-match'], 60);
		$I->click(['css' => 'span.select2-match']);
		// end of select2

		$I->fillField(\VoucherManagerPage::$voucherLeft, $count);
		$I->click('Save & Close');
		$I->waitForElement(['id' => 'system-message-container'], 60);
		$I->scrollTo(['css' => '.alert-success']);
		$I->see("Voucher details saved", '.alert-success');
		$I->seeElement(['link' => $code]);
	}

	/**
	 * Function to edit a Voucher Code
	 *
	 * @param   String  $voucherCode     Code for the Current Voucher
	 * @param   String  $voucherNewCode  New Code for the Voucher
	 *
	 * @return void
	 */
	public function editVoucher($voucherCode, $voucherNewCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->waitForElement(['link' => $voucherCode], 60);
		$I->click(\VoucherManagerPage::$voucherCheck);
		$I->click("Edit");
		$I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager Edit');
		$I->fillField(\VoucherManagerPage::$voucherCode, $voucherNewCode);
		$I->click('Save & Close');
		$I->waitForElement(['id' => 'system-message-container'], 60);
		$I->see("Voucher details saved", '.alert-success');
		$I->seeElement(['link' => $voucherNewCode]);
	}

	/**
	 * Function to Delete a Voucher
	 *
	 * @param   String  $voucherCode  Code of the voucher which is to be deleted
	 *
	 * @return void
	 */
	public function deleteVoucher($voucherCode)
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->waitForElement(\VoucherManagerPage::$voucherResultRow, 30);
		$I->fillField(\VoucherManagerPage::$voucherSearchField, $voucherCode);
		$I->pressKey(\VoucherManagerPage::$voucherSearchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $voucherCode]);
		$I->click(\VoucherManagerPage::$voucherCheck);
		$I->click('Delete');
		$I->dontSeeElement(['link' => $voucherCode]);
	}

	/**
	 * Function to Change Voucher State
	 *
	 * @param   String  $voucherCode  Code of the voucher for which the state is to be changed
	 * @param   String  $state        State to which we want it to be changed to
	 *
	 * @return void
	 */
	public function changeVoucherState($voucherCode, $state = 'unpublish')
	{
		$I = $this;
		$I->changeState(new \VoucherManagerPage, $voucherCode, $state, \VoucherManagerPage::$voucherResultRow, \VoucherManagerPage::$voucherCheck);
	}

	/**
	 * Function to return the Result of the State of a Voucher
	 *
	 * @param   String  $voucherCode  Code of the Voucher for which State is tobe Determined
	 *
	 * @return string  State of the Voucher
	 */
	public function getVoucherState($voucherCode)
	{
		$result = $this->getState(new \VoucherManagerPage, $voucherCode, \VoucherManagerPage::$voucherResultRow, \VoucherManagerPage::$voucherStatePath);

		return $result;
	}
}
