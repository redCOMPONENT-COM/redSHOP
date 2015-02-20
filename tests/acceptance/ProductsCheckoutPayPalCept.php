<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla3');
$I = new AcceptanceTester($scenario);
$config = $I->getConfig();
$className = 'AcceptanceTester\Login' . $config['env'] . 'Steps';
$I = new $className($scenario);

$I->wantTo('Test Product Checkout on Front End with PayPal Payment Plugin');
$I->doAdminLogin();
$config = $I->getConfig();
$pluginName = 'Paypal';
$payPalInformation = array(
	"username" => "alexis@redcomponent.com",
	"password" => "I10v3redK0mpont#",
	"email2" => "alexis-buyer@redcomponent.com",
	"email" => "alexis-facilitator@redcomponent.com"
);
$I = new AcceptanceTester\PayPalPluginManagerJoomla3Steps($scenario);
$I->enablePlugin($pluginName);
$I->updatePayPalPlugin($payPalInformation["email2"]);
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

$I->checkoutProductWithPayPalPayment($customerInformation, $customerInformation, $payPalInformation, $productName, $categoryName);
