<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CouponCheckoutProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutPaypalCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester $I        Actor Class Object
	 * @param   String           $scenario Scenario Variable
	 *
	 * @return void
	 */
	public function testPaypalCheckoutFrontEnd(AcceptanceTester $I, $scenario)
	{
		$sillyLogic = rand(99, 999);
		$I->wantTo('Test Product Checkout on Front End with PayPal Payment Plugin');
		$I->doAdministratorLogin();
		$pluginName = 'redSHOP - Paypal Payments';
		$payPalInformation = array(
			"username" => "alexis@redcomponent.com",
			"password" => "I10v3redK0mpont#",
			"email" => "alexis-buyer@redcomponent.com",
			"email2" => "alexis-facilitator@redcomponent.com"
		);
		$payPalInformation2 = array(
			"username" => "alexis@redcomponent.com",
			"password" => "I10v3redK0mpont#",
			"email" => "alexis-buyer@redcomponent.com",
			"email2" => "alexis-facilitator@redcomponent.com"
		);
		$I = new AcceptanceTester\PayPalPluginManagerJoomla3Steps($scenario);
		$I->enablePlugin($pluginName);
		$I->updatePayPalPlugin($payPalInformation["email2"]);
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

		if ($sillyLogic % 2 == 0)
		{
			$I->checkoutProductWithPayPalPayment($customerInformation, $customerInformation, $payPalInformation, $productName, $categoryName);
		}
		else
		{
			$I->checkoutProductWithPayPalPayment($customerInformation, $customerInformation, $payPalInformation2, $productName, $categoryName);
		}
	}
}