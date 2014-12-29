<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla2');
$scenario->group('Joomla3');

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Test Voucher Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\VoucherManager' . $config['env'] . 'Steps';
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
