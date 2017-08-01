<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class Products2CheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class Products2CheckoutCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testProductsCheckoutFrontEnd(AcceptanceTester $I, $scenario)
	{
		$I = new AcceptanceTester($scenario);

		$scenario->skip('@todo to be removed once REDSHOP-2731 gets fixed');

		$I->wantTo('Test Product Checkout on Front End with 2 Checkout Payment Plugin');
		$I->doAdministratorLogin();
		$pluginName = '2Checkout';
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_2checkout/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"vendorID" => "901261371",
			"secretWord" => "tango",
			"debitCardNumber" => "4000000000000002",
			"cvv" => "290",
			"cardExpiryMonth" => '2',
			"cardExpiryYear" => '2016',
			"shippingAddress" => "some place on earth"
		);
		$I->enablePlugin($pluginName);
		$this->update2CheckoutPlugin($I, $scenario, $checkoutAccountInformation['vendorID'], $checkoutAccountInformation['secretWord']);
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

		$this->checkoutProductWith2CheckoutPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to Update Checkout Plugin Information
	 *
	 * @param   AcceptanceTester  $I           Actor Class Object
	 * @param   String            $scenario    Scenario Variable
	 * @param   String            $vendorID    Vendor ID for the Account
	 * @param   String            $secretWord  Secret Word for the Account
	 *
	 * @return void
	 */
	private function update2CheckoutPlugin(AcceptanceTester $I, $scenario, $vendorID, $secretWord)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('2Checkout');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('2Checkout'),30);
		$I->seeElement(['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->see('2Checkout', ['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->click(['xpath' => "//input[@id='cb0']"]);
		$I->clickToolbarButton('Edit');
		$I->waitForElement(['xpath' => "//input[@id='jform_params_vendor_id']"],30);
		$I->fillField(['xpath' => "//input[@id='jform_params_vendor_id']"], $vendorID);
		$I->fillField(['xpath' => "//input[@id='jform_params_secret_words']"], $secretWord);
		$I->clickToolbarButton('Save & Close');
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the 2Checkout Payment Plugin
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
	private function checkoutProductWith2CheckoutPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='rs_payment_2checkout']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName),30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText('Secure Checkout', 20, ['xpath' => '//h1']);
		$I->see('Secure Checkout', ['xpath' => '//h1']);
		$I->click(['xpath' => "//section[@id='review-cart']/button"]);
		$I->fillField(['xpath' => "//input[@id='shipping-address-1']"], $checkoutAccountDetail['shippingAddress']);
		$I->click(['xpath' => "//section[@id='shipping-information']/button"]);
		$I->click(['xpath' => "//input[@id='same-as-shipping']"]);
		$I->click(['xpath' => "//section[@id='billing-information']/button"]);
		$I->waitForElement(['xpath' => "//input[@id='card-number']"], 30);
		$I->fillField(['xpath' => "//input[@id='card-number']"], $checkoutAccountDetail['debitCardNumber']);
		$I->click(['xpath' => "//section[@id='payment-method']/div[2]/button"]);
		$I->waitForText('Your payment has been processed', 10, '//h1');
		$I->see('Your payment has been processed', '//h1');
	}
}
