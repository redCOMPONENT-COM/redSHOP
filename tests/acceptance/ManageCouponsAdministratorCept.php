<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla2');
$scenario->group('Joomla3');

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Test Coupon Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\CouponManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$randomCouponCode = 'Coupon Code' . rand(99, 999);
$updatedCouponCode = 'New ' . $randomCouponCode;
$couponValueIn = 'Total';
$couponValue = '100';
$couponType = 'Globally';
$couponLeft = '10';
$I->addCoupon($randomCouponCode, $couponValueIn, $couponValue, $couponType, $couponLeft);
$I->searchCoupon($randomCouponCode);
$I->editCoupon($randomCouponCode, $updatedCouponCode);
$I->searchCoupon($updatedCouponCode);
$I->deleteCoupon($updatedCouponCode);
$I->searchCoupon($updatedCouponCode, 'Delete');
