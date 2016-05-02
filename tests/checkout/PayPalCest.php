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

		$I->amOnPage('/index.php?option=com_redshop');
		$I->waitForElement("//div[@id='redshopcomponent']",30);
		$I->checkForPhpNoticesOrWarnings();

		$I->click(['link' => $categoryName]);
		$I->waitForElement(['id' => 'redcatproducts'],30);
		$I->click(['link' => $productName]);
		$I->waitForElement("//div[@id='add_to_cart_all']//form//span[text() = 'Add to cart']", 30);
		$I->click("//div[@id='add_to_cart_all']//form//span[text() = 'Add to cart']");
		$I->waitForText("Product has been added to your cart.", 30, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement("//span[text() = 'New customer? Please Provide Your Billing Information']",30);
		$I->click("//span[text() = 'New customer? Please Provide Your Billing Information']");

		$I->waitForElementVisible("#email1");
		$I->fillField("#email1", $customerInformation['email']);
		$I->fillField("//input[@id='firstname']", $customerInformation['firstName']);
		$I->fillField("//input[@id='lastname']", $customerInformation['lastName']);
		$I->fillField("//input[@id='address']", $customerInformation['address']);
		$I->fillField("//input[@id='zipcode']", $customerInformation['postalCode']);
		$I->fillField("//input[@id='city']", $customerInformation['city']);
		$I->selectOption("//select[@id='country_code']", $customerInformation['country']);
		$I->waitForElement("//select[@id='state_code']", 20);
		$I->selectOption("//select[@id='state_code']", $customerInformation['state']);
		$I->fillField("//input[@id='phone']", $customerInformation['phone']);

		$I->waitForElement("//input[@id='firstname_ST']", 30);
		$I->fillField("//input[@id='firstname_ST']", $customerInformation['firstName']);
		$I->fillField("//input[@id='lastname_ST']", $customerInformation['lastName']);
		$I->fillField("//input[@id='address_ST']", $customerInformation['address']);
		$I->fillField("//input[@id='zipcode_ST']", $customerInformation['postalCode']);
		$I->fillField("//input[@id='city_ST']", $customerInformation['city']);
		$I->selectOption("//select[@id='country_code_ST']", $customerInformation['country']);
		$I->waitForElement("//select[@id='state_code_ST']", 20);
		$I->selectOption("//select[@id='state_code_ST']", $customerInformation['state']);
		$I->fillField("//input[@id='phone_ST']", $customerInformation['phone']);

		$I->click("Proceed");
		$I->waitForElement("//legend[text() = 'Bill to information']");
		$I->click("//input[@id='rs_payment_paypal1']");
		$I->click("Checkout");
		$I->waitForElement(['link' => $productName],30);
		$I->seeElement(['link' => $productName]);
		$I->click("//input[@id='termscondition']");
		$I->click("//input[@id='checkout_final']");
		$I->click("//input[@id='loadLogin']");
		$I->waitForElement("//input[@id='login_password']",30);
		$I->fillField("//input[@id='login_email']", $payPalAccountDetail["email"]);
		$I->fillField("//input[@id='login_password']", $payPalAccountDetail["password"]);
		$I->click("//input[@id='privateDeviceCheckbox']");
		$I->click("//input[@id='submitLogin']");
		$I->waitForElement("//input[@id='continue_abovefold']",30);
		$I->seeElement("//input[@id='continue_abovefold']");
		$I->click("//input[@id='continue_abovefold']");
		$I->waitForElement("//span[@title='You just completed your payment.']",30);
		$I->seeElement("//span[@title='You just completed your payment.']");
	}
}