<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Product Checkout on Front End with Beanstream Payment Plugin');
$I->doAdministratorLogin();
$pluginName = 'BeanStream';
$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_beanstream/';
$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

$checkoutAccountInformation = array(
	"merchantID" => "300210236",
	"username" => "alexisrodriguez",
	"password" => "Pull416!t",
	"debitCardNumber" => "4012888888881881",
	"cvv" => "123",
	"cardExpiryMonth" => '2',
	"cardExpiryYear" => '2016',
	"shippingAddress" => "some place on earth",
	"customerName" => 'Testing Customer'
);
$I = new AcceptanceTester\PaymentBeanStreamPluginManagerJoomla3Steps($scenario);
$I->enablePlugin($pluginName);
$I->updateBeanStreamPlugin($checkoutAccountInformation['merchantID'], $checkoutAccountInformation['username'], $checkoutAccountInformation['password']);
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
$randomNumber = rand(10, 1000);

if (($randomNumber % 2) == 1)
{
	$productRandomizer = rand(10, 1000);

	if (($productRandomizer % 2) == 1)
	{
		$productName = 'redSLIDER';
	}
	else
	{
		$productName = 'redCOOKIE';
	}

	$categoryName = 'Events and Forms';

}
else
{
	$productRandomizer = rand(10, 1000);

	if (($productRandomizer % 2) == 1)
	{
		$productName = 'redSHOP';
	}
	else
	{
		$productName = 'redITEM';
	}

	$categoryName = 'CCK and e-Commerce';

}

// $I->checkoutProductWithBeanStreamPayment($customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
