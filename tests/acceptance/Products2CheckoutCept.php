<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
$I->doAdministratorLogin();
$pluginName = '2Checkout';
$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_2checkout/';
$I->installExtensionFromDirectory($pathToPlugin, 'Plugin');

$checkoutAccountInformation = array(
	"vendorID" => "901261371",
	"secretWord" => "tango",
	"debitCardNumber" => "4000000000000002",
	"cvv" => "290",
	"cardExpiryMonth" => '2',
	"cardExpiryYear" => '2016',
	"shippingAddress" => "some place on earth"
);
$I = new AcceptanceTester\Payment2CheckoutPluginManagerJoomla3Steps($scenario);
$I->enablePlugin($pluginName);
$I->update2CheckoutPlugin($checkoutAccountInformation['vendorID'], $checkoutAccountInformation['secretWord']);
$I->doAdministratorLogout();
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

$I->checkoutProductWith2CheckoutPayment($customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
