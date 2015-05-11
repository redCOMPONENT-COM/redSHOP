<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Product Checkout on Front End with Bank Transfer');
$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
$customerInformation = array(
	"email" => "test@test" . rand() . ".com",
	"firstName" => "Tester",
	"lastName" => "User",
	"address" => "Some Place in the World",
	"postalCode" => "23456",
	"city" => "Bangalore",
	"country" => "India",
	"state" => "Karnataka",
	"phone" => "8787878787"
);
$productName = 'redCOOKIE';
$categoryName = 'Events and Forms';
$I->checkOutProductWithBankTransfer($customerInformation, $customerInformation, $productName, $categoryName);
