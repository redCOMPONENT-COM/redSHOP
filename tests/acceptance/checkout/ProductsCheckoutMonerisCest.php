<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutMonerisCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutMonerisCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function MonerisPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Moneris Payments Plugin');

		$scenario->skip('@todo to be removed once REDSHOP-2732 gets fixed');

		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_moneris/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"storeID" => "store1",
			"debitCardNumber" => "5454545454545454",
			"apiToken"	=> "yesguy",
			"cvv" => "1234",
			"cardExpiryMonth" => '2',
			"cardExpiryYear" => '2016',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('Moneris Payments');
		$this->updateMonerisPlugin($I, $checkoutAccountInformation['storeID'], $checkoutAccountInformation['apiToken']);
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

		$this->checkoutProductWithMonerisPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to update Moneris Payment Plugin

	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $storeId   Store ID for API
	 * @param   String            $apiToken  ApiToken for Plugin
	 *
	 * @return void
	 */
	private function updateMonerisPlugin(AcceptanceTester $I, $storeId, $apiToken)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('Moneris Payments');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Moneris Payments'), 30);
		$I->checkExistenceOf('Moneris Payments');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['xpath' => "//li//label[text()='Visa']"]);
		$I->click(['xpath' => "//li//label[text()='Visa']"]);
		$I->click(['xpath' => "//li//label[text()='MasterCard']"]);
		$I->fillField(['id' => "jform_params_moneris_store_id"], $storeId);
		$I->fillField(['id' => "jform_params_moneris_test_store_id"], $storeId);
		$I->fillField(['id' => "jform_params_moneris_api_token"], $apiToken);
		$I->fillField(['id' => "jform_params_moneris_test_api_token"], $apiToken);
		$I->click(['xpath' => "//div[@id='jform_params_moneris_check_creditcard_code_chzn']/a"]);
		$I->waitForElement(['xpath' => "//li[contains(text(), 'No')]"],10);
		$I->click(['xpath' => "//li[contains(text(), 'No')]"]);
		$I->click(['xpath' => "//div[@id='jform_params_moneris_check_avs_chzn']/a"]);
		$I->waitForElement(['xpath' => "//div[@id='jform_params_moneris_check_avs_chzn']/div/ul//li[contains(text(), 'No')]"],10);
		$I->click(['xpath' => "//div[@id='jform_params_moneris_check_avs_chzn']/div/ul//li[contains(text(), 'No')]"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Moneris Payment Plugin
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
	private function checkoutProductWithMonerisPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='rs_payment_moneris']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement(['id' => "order_payment_name"], 10);
		$I->fillField(['id' => "order_payment_name"], $checkoutAccountDetail['customerName']);
		$I->fillField(['id' => "order_payment_number"], $checkoutAccountDetail['debitCardNumber']);
		$I->fillField(['id' => "credit_card_code"], $checkoutAccountDetail['cvv']);
		$I->click(['xpath' => "//input[@value='MC']"]);
		$I->click(['xpath' => "//input[@value='Checkout: next step']"]);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->waitForText('Order placed', 15, ['xpath' => "//div[@class='alert alert-success']"]);
		$I->see('Order placed', "//div[@class='alert alert-success']");
	}
}
