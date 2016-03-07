<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutBraintreeCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutBraintreeCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testProductsCheckoutBraintree(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);
		$I->wantTo('Test Product Checkout on Front End with Braintree Payment Plugin');
		$I->doAdministratorLogin();
		$pluginName = 'Braintree';
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_braintree/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

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
		$this->updateBraintreePlugin($I, $scenario, $checkoutAccountInformation['merchantID'], $checkoutAccountInformation['publicKey'], $checkoutAccountInformation['privateKey']);
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

		$this->checkoutProductWithBraintreePayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to Update the Configuration of Braintree payment plugin
	 *
	 * @param   AcceptanceTester  $I             Actor Class Object
	 * @param   String            $scenario      Scenario Variable
	 * @param   String            $merchantCode  Merchant Code
	 * @param   String            $publicKey     Username
	 * @param   String            $privateKey    Password for the API
	 *
	 * @return void
	 */
	private function updateBraintreePlugin(AcceptanceTester $I, $scenario, $merchantCode, $publicKey, $privateKey)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('Braintree');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Braintree'), 30);
		$I->seeElement(['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->see('Braintree', ['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->click(['xpath' => "//input[@id='cb0']"]);
		$I->clickToolbarButton('Edit');
		$I->waitForElement(['xpath' => "//input[@id='jform_params_merchant_id']"], 30);
		$I->fillField(['xpath' => "//input[@id='jform_params_merchant_id']"], $merchantCode);
		$I->fillField(['xpath' => "//input[@id='jform_params_public_key']"], $publicKey);
		$I->fillField(['xpath' => "//input[@id='jform_params_private_key']"], $privateKey);
		$I->click(['link' => 'Advanced']);
		$I->waitForElement(['xpath' => "//input[@value='VISA']"],30);
		$I->click(['xpath' => "//input[@value='VISA']"]);
		$I->click(['xpath' => "//input[@value='MC']"]);
		$I->clickToolbarButton('Save & Close');
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Braintree Payment Plugin
	 *
	 * @param   AcceptanceTester  $I                      Actor Class Object
	 * @param   String            $scenario               Scenario Variable
	 * @param   Array             $addressDetail          Address Detail
	 * @param   Array             $shipmentDetail         Shipping Address Detail
	 * @param   Array             $checkoutAccountDetail  2Checkout Account Detail
	 * @param   string            $productName            Name of the Product
	 * @param   string            $categoryName           Name of the Category
	 *
	 * @return void
	 */
	private function checkoutProductWithBraintreePayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv,30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList,30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-message');
		$I->see("Product has been added to your cart.", '.alert-message');
		$I->amOnPage('index.php?option=com_redshop&view=cart');
		$I->checkForPhpNoticesOrWarnings();
		$I->seeElement(['link' => $productName]);
		$I->click(['xpath' => "//input[@value='Checkout']"]);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$newCustomerSpan,30);
		$I->click(\FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->addressInformation($addressDetail);
		$I->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(['xpath' => "//div[@id='rs_payment_braintree']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName),30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElement(['xpath' => "//input[@id='order_payment_name']"],10);
		$I->fillField(['xpath' => "//input[@id='order_payment_name']"], $checkoutAccountDetail['customerName']);
		$I->fillField(['xpath' => "//input[@id='order_payment_number']"], $checkoutAccountDetail['debitCardNumber']);
		$I->fillField(['xpath' => "//input[@id='credit_card_code']"], $checkoutAccountDetail['cvv']);
		$I->click(['xpath' => "//input[@value='VISA']"]);
		$I->click(['xpath' => "//input[@value='Checkout: next step']"]);
		$I->waitForText('Order placed', 15, ['xpath' => "//div[@class='alert alert-success']"]);
		$I->see('Order placed', "//div[@class='alert alert-success']");
	}
}
