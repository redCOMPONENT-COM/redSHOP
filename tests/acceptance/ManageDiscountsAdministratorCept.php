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

$I->wantTo('Test Discount Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\DiscountManager' . $config['env'] . 'Steps';
$I = new $className($scenario);

if ($config['env'] == 'Joomla2')
{
	$I->addDiscount();
}
else
{
	$amount = rand(100, 1000);
	$discountAmount = rand(10, 100);
	$newAmount = rand(100, 1000);
	$I->addDiscount($amount, $discountAmount);
	$I->searchDiscount($amount);
	$I->editDiscount($amount, $newAmount);
	$I->searchDiscount($newAmount);
	$I->changeState($newAmount);
	$I->verifyState('unpublished', $I->getState($newAmount));
	$I->deleteDiscount($newAmount);
	$I->searchDiscount($newAmount, 'Delete');
}

