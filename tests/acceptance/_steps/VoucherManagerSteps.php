<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class VoucherManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class VoucherManagerSteps extends AdminManagerSteps
{
	/**
	 * Function to Add a new Voucher
	 *
	 * @return void
	 */
	public function addVoucher()
	{
		$I = $this;
		$I->amOnPage(\VoucherManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager New');
		$I->click('Cancel');
	}
}
