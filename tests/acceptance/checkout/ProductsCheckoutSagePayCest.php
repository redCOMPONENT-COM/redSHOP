<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutSagePayCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutSagePayCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testSagePayPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with SagePay Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_sagepay/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"vendorName" => "redcomponent",
			"vendorEmail" => "alexis@redcomponent.com",
			"encryptPassword" => "17b394e392b80f05",
			"debitCardNumber" => "4929 0000 0000 6",
			"cvv" => "123",
			"cardExpiryMonth" => '05',
			"cardExpiryYear" => '18',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);

		// @todo: commenting this part until REDSHOP-2659 is fixed
		// $I->enablePlugin('SagePay Payments');
		$this->updateSagePayPaymentPlugin($I, $checkoutAccountInformation['vendorName'], $checkoutAccountInformation['vendorEmail'], $checkoutAccountInformation['encryptPassword']);

		/*
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

		$this->checkoutProductWithSagePayPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
		*/
	}

	/**
	 * Function to Update the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I            Acceptance Tester Object
	 * @param   String            $vendorName   Merchant ID
	 * @param   String            $vendorEmail  User ID
	 * @param   String            $password     Password for the Merchant Account
	 *
	 * @return void
	 */
	private function updateSagePayPaymentPlugin(AcceptanceTester $I, $vendorName, $vendorEmail, $password)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('SagePay Payments');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('SagePay Payments'), 30);
		$I->checkExistenceOf('SagePay Payments');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_sagepay_vendorname"], 30);
		$I->fillField(['id' => "jform_params_sagepay_vendorname"], $vendorName);
		$I->fillField(['id' => "jform_params_sagepay_vendoremail"], $vendorEmail);
		$I->fillField(['id' => "jform_params_sagepay_encryptpass"], $password);
		$I->click(['xpath' => "//div[@id='jform_params_payment_method_chzn']/a"]);
		$I->waitForElement(['xpath' => "//li[contains(text(), 'Test')]"],10);
		$I->click(['xpath' => "//li[contains(text(), 'Test')]"]);

		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=configuration');
		$I->waitForElement(["xpath" => "//a[text()='Price']"], 10);
		$I->click(["xpath" => "//a[text()='Price']"]);
		$I->waitForElement(["id" => "currency_code"], 10);
		$I->click(['xpath' => "//div[@id='currency_code_chzn']/a/div/b"]);
		$I->click(['xpath' => "//div[@id='currency_code_chzn']//li[text()='British Pound']"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('Configuration Saved', ['id' => 'system-message-container']);
		// @todo: review following lines when REDSHOP-2659 is fixed
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=configuration');
		$I->waitForElement(["xpath" => "//a[text()='Price']"], 10);
		$I->click(["xpath" => "//a[text()='Price']"]);
		$I->waitForElement(["id" => "currency_code"], 10);
		$I->click(['xpath' => "//div[@id='currency_code_chzn']/a/div/b"]);
		$I->click(['xpath' => "//div[@id='currency_code_chzn']//li[text()='US Dollar']"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('Configuration Saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the SagePay Payment Plugin
	 *
	 * @param   AcceptanceTester  $I                      Actor Class Object
	 * @param   String            $scenario               Scenario Variable
	 * @param   Array             $addressDetail          Address Detail
	 * @param   Array             $shipmentDetail         Shipping Address Detail
	 * @param   Array             $checkoutAccountDetail  Account Detail
	 * @param   string            $productName            Name of the Product
	 * @param   string            $categoryName           Name of the Category
	 *
	 * @return void
	 */
	private function checkoutProductWithSagePayPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I->amOnPage('/index.php?option=com_redshop');
		$I->waitForElement(['id' => "redshopcomponent"], 30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(['xpath' => "//div[@id='add_to_cart_all']//form//span[text() = 'Add to cart']"]);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(['xpath' => "//span[text() = 'New customer? Please Provide Your Billing Information']"], 30);
		$I->click(['xpath' => "//span[text() = 'New customer? Please Provide Your Billing Information']"]);
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->addressInformation($addressDetail);
		$I->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(['xpath' => "//legend[text() = 'Bill to information']"]);
		$I->click(['xpath' => "//div[@id='rs_payment_sagepay']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->waitForElement(['xpath' => "//button[@value='VISA']"], 60);
		$I->click(['xpath' => "//button[@value='VISA']"]);
		$I->waitForElement(['xpath' => "//input[@name='cardnumber']"],30);
		$I->fillField(['xpath' => "//input[@name='cardnumber']"], $checkoutAccountDetail['debitCardNumber']);
		$I->fillField(['xpath' => "//input[@name='securitycode']"], $checkoutAccountDetail['cvv']);
		$I->fillField(['id' => "form-card_details.field-expiry_mm"], $checkoutAccountDetail['cardExpiryMonth']);
		$I->fillField(['id' => "form-card_details.field-expiry_yy"], $checkoutAccountDetail['cardExpiryYear']);
		$I->click(['xpath' => "//button[@value='proceed']"]);
		$I->waitForElement(['xpath' => "//button[@value='proceed']"],10);
		$I->click(['xpath' => "//button[@value='proceed']"]);
		$I->waitForElement(['xpath' => "//table[@class='cart_calculations']//tbody//tr[6]//td//p[text()='Paid ']"],30);
		$I->waitForText('0000 : The Authorisation was Successful.', 15, ['xpath' => "//div[@class='alert alert-success']"]);
		$I->see('0000 : The Authorisation was Successful.', "//div[@class='alert alert-success']");
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=configuration');
		$I->waitForElement(["xpath" => "//a[text()='Price']"], 10);
		$I->click(["xpath" => "//a[text()='Price']"]);
		$I->waitForElement(["id" => "currency_code"], 10);
		$I->click(['xpath' => "//div[@id='currency_code_chzn']/a/div/b"]);
		$I->click(['xpath' => "//div[@id='currency_code_chzn']//li[text()='US Dollar']"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('Configuration Saved', ['id' => 'system-message-container']);
	}
}
