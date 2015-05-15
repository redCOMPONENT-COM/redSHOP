<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Discount Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\DiscountManagerJoomla3Steps';
$I = new $className($scenario);
$amount = rand(100, 1000);
$discountAmount = rand(10, 100);
$newAmount = rand(100, 1000);
$I->addDiscount($amount, $discountAmount);
$I->searchDiscount($amount);
$I->editDiscount($amount, $newAmount);
$I->searchDiscount($newAmount);
$I->changeDiscountState($newAmount);
$I->verifyState('unpublished', $I->getDiscountState($newAmount));
$I->deleteDiscount($newAmount);
$I->searchDiscount($newAmount, 'Delete');
