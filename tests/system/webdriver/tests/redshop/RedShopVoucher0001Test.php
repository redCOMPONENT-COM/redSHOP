<?php
/**
 * @package     RedShop
 * @subpackage  Test
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'JoomlaWebdriverTestCase.php';

/**
 * This class tests the  Voucher Add/Edit.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.2
 */
class RedShopVoucher0001Test extends JoomlaWebdriverTestCase
{
	/**
	 * The menu name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuLinkName = 'RedShopVouchersManagerPage';

	/**
	 * The menu group name being tested.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $appMenuGroupName = 'RedSHOP_Voucher';

	/**
	 * Function to login to the Application
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$cpPage = $this->doAdminLogin();
		$this->appTestPage = $cpPage->clickMenu($this->appMenuGroupName, $this->appMenuLinkName);
	}

	/**
	 * Logout and close test.
	 *
	 * @return void
	 *
	 * @since   3.0
	 */
	public function tearDown()
	{
		$this->doAdminLogout();
		parent::tearDown();
	}

	/**
	 * Function to test voucher creation
	 *
	 * @test
	 *
	 * @return void
	 */
	public function createVoucher()
	{
		$rand = rand();
		$voucherCode = 'RedShop Code' . $rand;
		$voucherAmount = '100';
		$voucherVerifyingAmount = '$ ' . $voucherAmount . ',00';
		$voucherLeft = '10';
		$this->appTestPage->addVoucher($voucherCode, $voucherAmount, $voucherLeft);
		$this->assertTrue($this->appTestPage->searchVoucher($voucherCode), 'Voucher Must be Created');
		$this->assertEquals($this->appTestPage->getVoucherAmount($voucherCode), $voucherVerifyingAmount, 'Both Must be Equal');
		$this->assertEquals($this->appTestPage->getVoucherLeft($voucherCode), $voucherLeft, 'Both Must be Equal');
		$this->appTestPage->deleteVoucher($voucherCode);
		$this->assertFalse($this->appTestPage->searchVoucher($voucherCode), 'Voucher Must be Deleted');
	}

	/**
	 * Function to test Voucher Update
	 *
	 * @test
	 *
	 * @return void
	 */
	public function updateVoucher()
	{
		$rand = rand();
		$voucherCode = 'RedShop Code' . $rand;
		$voucherNewCode = 'Updated Code' . $rand;
		$voucherAmount = '100';
		$verifyVoucherAmount = '$ ' . $voucherAmount . ',00';
		$voucherLeft = '10';
		$this->appTestPage->addVoucher($voucherCode, $voucherAmount, $voucherLeft);
		$this->assertTrue($this->appTestPage->searchVoucher($voucherCode), 'Voucher Must be Created');
		$this->assertEquals($this->appTestPage->getVoucherAmount($voucherCode), $verifyVoucherAmount, 'Both Must be Equal');
		$this->assertEquals($this->appTestPage->getVoucherLeft($voucherCode), $voucherLeft, 'Both Must be Equal');
		$this->appTestPage->editVoucher('Voucher Code', $voucherNewCode, $voucherCode);
		$this->assertTrue($this->appTestPage->searchVoucher($voucherNewCode), 'Voucher Must be Updated');
		$this->appTestPage->deleteVoucher($voucherNewCode);
		$this->assertFalse($this->appTestPage->searchVoucher($voucherNewCode), 'Voucher Must be Deleted');
	}

	/**
	 * Function to test State Change
	 *
	 * @test
	 *
	 * @return void
	 */
	public function changeState()
	{
		$rand = rand();
		$voucherCode = 'RedShop Code' . $rand;
		$voucherAmount = '100';
		$voucherVerifyingAmount = '$ ' . $voucherAmount . ',00';
		$voucherLeft = '10';
		$this->appTestPage->addVoucher($voucherCode, $voucherAmount, $voucherLeft);
		$this->assertTrue($this->appTestPage->searchVoucher($voucherCode), 'Voucher Must be Created');
		$this->assertEquals($this->appTestPage->getVoucherAmount($voucherCode), $voucherVerifyingAmount, 'Both Must be Equal');
		$this->assertEquals($this->appTestPage->getVoucherLeft($voucherCode), $voucherLeft, 'Both Must be Equal');
		$this->appTestPage->changeVoucherState($voucherCode, 'unpublished');
		$this->assertEquals($this->appTestPage->getState($voucherCode), 'unpublished', 'State Must be Changed');
		$this->appTestPage->deleteVoucher($voucherCode);
		$this->assertFalse($this->appTestPage->searchVoucher($voucherCode), 'Voucher Must be Deleted');
	}
}
