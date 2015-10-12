<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Voucher Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\VoucherManagerJoomla3Steps';
$I = new $className($scenario);
$randomVoucherCode = 'Testing Voucher ' . rand(99, 999);
$UpdatedRandomVoucherCode = 'Updating Voucher Code' . rand(99, 999);
$voucherAmount = rand(9, 99);
$voucherCount = rand(99, 999);
$I->addVoucher($randomVoucherCode, $voucherAmount, $voucherCount);
$I->changeVoucherState($randomVoucherCode, 'unpublish');
$I->verifyState('unpublished', $I->getVoucherState($randomVoucherCode), "Voucher State Must be Unpublished");
$I->changeVoucherState($randomVoucherCode, 'publish');
$I->editVoucher($randomVoucherCode, $UpdatedRandomVoucherCode);
$I->deleteVoucher($UpdatedRandomVoucherCode);
