<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageVoucherAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageVoucherAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->randomVoucherCode = $this->faker->bothify('ManageVoucherAdministratorCest ?##?');
		$this->updatedRandomVoucherCode = 'Updating ' . $this->randomVoucherCode;
		$this->voucherAmount = $this->faker->numberBetween(9, 99);
		$this->voucherCount = $this->faker->numberBetween(99, 999);
	}

	/**
	 * Function to Test Voucher Creation in Backend
	 *
	 */
	public function createVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Voucher creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->addVoucher($this->randomVoucherCode, $this->voucherAmount, $this->voucherCount);
	}

	/**
	 * Function to Test Voucher Update in the Administrator
	 *
	 * @depends createVoucher
	 */
	public function updateVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Voucher gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->editVoucher($this->randomVoucherCode, $this->updatedRandomVoucherCode);
	}

	/**
	 * Test for State Change in Voucher Administrator
	 *
	 * @depends updateVoucher
	 */
	public function changeVoucherState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Voucher gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->changeVoucherState($this->updatedRandomVoucherCode, 'unpublish');
		$I->verifyState('unpublished', $I->getVoucherState($this->updatedRandomVoucherCode), "Voucher State Must be Unpublished");
		$I->changeVoucherState($this->updatedRandomVoucherCode, 'publish');
	}

	/**
	 * Function to Test Voucher Deletion
	 *
	 * @depends changeVoucherState
	 */
	public function deleteVoucher(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Voucher in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\VoucherManagerJoomla3Steps($scenario);
		$I->deleteVoucher($this->updatedRandomVoucherCode);
	}
}
