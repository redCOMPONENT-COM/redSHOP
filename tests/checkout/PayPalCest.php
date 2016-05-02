<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class Products2CheckoutCest
 *
 * @package  CheckoutTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class PaypalCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   CheckoutTester $I        Actor Class Object
	 * @param   String         $scenario Scenario Variable
	 *
	 * @return void
	 */
	public function checkout(CheckoutTester $I, $scenario)
	{
		$sillyLogic = rand(99, 999);
		$I->wantTo('Test Product Checkout on Front End with PayPal Payment Plugin');
		$I->doAdministratorLogin();
		$payPalInformation = array(
			"username" => "alexis@redcomponent.com",
			"password" => "I10v3redK0mpont#",
			"email" => "alexis-buyer@redcomponent.com",
			"email2" => "alexis-facilitator@redcomponent.com"
		);
		$payPalInformation2 = array(
			"username" => "alexis@redcomponent.com",
//"password" => "reddieSTUFF11",
//"email" => "jacobo@redcomponent.com",
			"password" => "I10v3redK0mpont#",
			"email" => "alexis-buyer@redcomponent.com",
			"email2" => "alexis-facilitator@redcomponent.com"
		);

		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField("//input[@id='filter_search']", 'redSHOP - Paypal Payments');
		$I->click("//button[@type='submit' and @data-original-title='Search']");

		$I->waitForElement(['link' => 'redSHOP - Paypal Payments'], 30);
		$I->seeElement(['link' => 'redSHOP - Paypal Payments']);
		$I->click("//input[@id='cb0']");
		$I->click('Enable');
		$I->see("Plugin successfully enabled", '.alert-success');

		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField("//input[@id='filter_search']", 'redSHOP - Paypal Payments');
		$I->click("//button[@type='submit' and @data-original-title='Search']");

		$I->waitForElement(['link' => 'redSHOP - Paypal Payments'], 30);
		$I->seeElement(['link' => 'redSHOP - Paypal Payments']);
		$I->click(['link' => 'redSHOP - Paypal Payments']);

		$I->waitForElement("//input[@id='jform_params_merchant_email']",30);
		$I->fillField("//input[@id='jform_params_merchant_email']", $payPalInformation["email2"]);
		$I->click("//fieldset[@id='jform_params_sandbox']/label[2]");
		$I->click("Save & Close");
		$I->see("Plugin successfully saved",'.alert-success');

		$I->doAdministratorLogout();

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