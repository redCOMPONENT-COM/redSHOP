<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutPayFlowProCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutPayFlowProCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testPayFlowProPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with PayFlow Pro Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_payflowpro/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"merchantLogin" => "gunjan",
			"password" => "gunjan105",
			"user" => "gunjan",
			"debitCardNumber" => "4111111111111111",
			"cvv" => "123",
			"cardExpiryMonth" => '12',
			"cardExpiryYear" => '2015',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('Payflow Pro Payment');
		$this->updatePayFlowProPlugin($I, $checkoutAccountInformation['merchantLogin'], $checkoutAccountInformation['user'], $checkoutAccountInformation['password']);
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

		$this->checkoutProductWithPayFlowProPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to Update the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I           Acceptance Tester Object
	 * @param   String            $merchantId  Merchant ID
	 * @param   String            $user        User ID
	 * @param   String            $password    Password for the Merchant Account
	 *
	 * @return void
	 */
	private function updatePayFlowProPlugin(AcceptanceTester $I, $merchantId, $user, $password)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('Payflow Pro Payment');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Payflow Pro Payment'), 30);
		$I->checkExistenceOf('Payflow Pro Payment');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_merchant_id"], 30);
		$I->fillField(['id' => "jform_params_merchant_id"], $merchantId);
		$I->fillField(['id' => "jform_params_merchant_user"], $user);
		$I->fillField(['id' => "jform_params_merchant_password"], $password);

		$I->click(['link' => 'Advanced']);
		$I->wait(1);
		$I->comment('I check option: VISA');
		$I->checkOption(['id' => 'jform_params_accepted_credict_card0']);
		$I->comment('I check option: Mastercard');
		$I->checkOption(['id' => 'jform_params_accepted_credict_card1']);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->waitForElement(['id' => 'system-message-container'],60);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the PayFlow Pro Payment Plugin
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
	private function checkoutProductWithPayFlowProPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='rs_payment_payflowpro']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement(['id' => "order_payment_name"], 10);
		$I->fillField(['id' => "order_payment_name"], $checkoutAccountDetail['customerName']);
		$I->fillField(['id' => "order_payment_number"], $checkoutAccountDetail['debitCardNumber']);
		$I->fillField(['id' => "credit_card_code"], $checkoutAccountDetail['cvv']);
		$I->click(['xpath' => "//input[@value='VISA']"]);
		$I->click(['xpath' => "//input[@value='Checkout: next step']"]);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->waitForText('Order placed', 15, ['xpath' => "//div[@class='alert alert-success']"]);
		$I->see('Order placed', "//div[@class='alert alert-success']");
	}
}
