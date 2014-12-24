<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class VoucherManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class VoucherManagerJoomla2Steps extends AdminManagerJoomla2Steps
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
		$I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager Page');
		$I->click("ID");
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager New');
		$I->fillField(\VoucherManagerPage::$voucherCode, $code);
		$I->fillField(\VoucherManagerPage::$voucherAmount, $amount);
		$I->fillField(\VoucherManagerPage::$voucherLeft, $count);
		$I->click('Save & Close');
		$I->see("Voucher details saved");
		$I->click("ID");
		$I->see($code, \VoucherManagerPage::$voucherResultRow);
		$I->click("ID");
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
		$I->click("ID");
		$I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
		$I->click(\VoucherManagerPage::$voucherCheck);
		$I->click("Edit");
		$I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager Edit');
		$I->fillField(\VoucherManagerPage::$voucherCode, $voucherNewCode);
		$I->click('Save & Close');
		$I->see("Voucher details saved");
		$I->see($voucherNewCode, \VoucherManagerPage::$voucherResultRow);
		$I->click("ID");
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
		$I->click('ID');
		$I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
		$I->click(\VoucherManagerPage::$voucherCheck);
		$I->click('Delete');
		$I->see('Voucher detail deleted successfully');
		$I->dontSee($voucherCode, \VoucherManagerPage::$voucherResultRow);
		$I->click('ID');
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
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->click("ID");
		$I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
		$I->click(\VoucherManagerPage::$voucherCheck);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
			$I->see("Voucher detail unpublished successfully");
		}
		else
		{
			$I->click("Publish");
			$I->see("Voucher detail published successfully");
		}

		$I->click("ID");
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
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->click("ID");
		$I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
		$text = $I->grabAttributeFrom(\VoucherManagerPage::$voucherStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		$I->click("ID");

		return $result;
	}
}
