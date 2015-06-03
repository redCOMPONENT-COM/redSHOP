<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Product Checkout on Front End with Braintree Payment Plugin');
$I->doAdministratorLogin();
$pluginName = 'Braintree';
$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_braintree/';
$I->installExtensionFromDirectory($pathToPlugin, 'Plugin');

$checkoutAccountInformation = array(
	"merchantID" => "2xvzzjy89sx3m2md",
	"publicKey" => "ddr5rsh4bgpjx559",
	"privateKey" => "c99015005b70baa2263c287bf00d349e",
	"debitCardNumber" => "4012888888881881",
	"cvv" => "123",
	"cardExpiryMonth" => '2',
	"cardExpiryYear" => '2016',
	"shippingAddress" => "some place on earth",
	"customerName" => 'Testing Customer'
);
$I = new AcceptanceTester\PaymentBraintreePluginManagerJoomla3Steps($scenario);
$I->enablePlugin($pluginName);
$I->updateBraintreePlugin($checkoutAccountInformation['merchantID'], $checkoutAccountInformation['publicKey'], $checkoutAccountInformation['privateKey']);
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

$I->checkoutProductWithBraintreePayment($customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
