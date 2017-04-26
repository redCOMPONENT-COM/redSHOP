<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutPaysonCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutPaysonCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testPaysonPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Payson Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/payson/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"agentID" => "4",
			"md5Key" => "2acab30d-fe50-426f-90d7-8c60a7eb31d4",
			"debitCardNumber" => "4242424242424242",
			"cvv" => "123",
			"cardExpiryMonth" => '05',
			"cardExpiryYear" => '2018',
			"email" => "testagent-1@payson.se",
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('redSHOP Payment - Payson');
		$this->updatePaysonPaymentPlugin($I, $checkoutAccountInformation['agentID'], $checkoutAccountInformation['md5Key'], $checkoutAccountInformation['email']);
		$I->doAdministratorLogout();

		$customerInformation = array(
			"email" => "test-shopper@payson.se",
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

		$this->checkoutProductWithPaysonPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
		$I->doAdministratorLogin();
		$I->uninstallExtension('redSHOP Payment - Payson');
	}

	/**
	 * Function to Update the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I        Acceptance Tester Object
	 * @param   String            $agentID  Agent ID
	 * @param   String            $md5Key   MD5 Key
	 * @param   String            $email    email for the Reciever account
	 *
	 * @return void
	 */
	private function updatePaysonPaymentPlugin(AcceptanceTester $I, $agentID, $md5Key, $email)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('redSHOP Payment - Payson');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('redSHOP Payment - Payson'), 30);
		$I->checkExistenceOf('redSHOP Payment - Payson');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_md5Key"], 30);
		$I->fillField(['id' => "jform_params_md5Key"], $md5Key);
		$I->fillField(['id' => "jform_params_agentID"], $agentID);
		$I->fillField(['id' => "jform_params_receiverEmail"], $email);
		$I->click(['xpath' => "//div[@id='jform_params_localeCode_chzn']/a"]);
		$I->waitForElement(['xpath' => "//li[contains(text(), 'English')]"],10);
		$I->click(['xpath' => "//li[contains(text(), 'English')]"]);

		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Payson Payment Plugin
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
	private function checkoutProductWithPaysonPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I->amOnPage('/index.php?option=com_redshop');
		$I->waitForElement(['id' => "redshopcomponent"], 30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(['xpath' => "//div[@id='add_to_cart_all']//form//span[text() = 'Add to cart']"]);
		$I->waitForElement(['xpath' => "//div[@class='alert alert-success']"]);
		$I->waitForText("Product has been added to your cart.", 10, '.alert-success');
		$I->see("Product has been added to your cart.", '.alert-success');
		$I->amOnPage('/index.php?option=com_redshop&view=checkout');
		$I->waitForElement(['xpath' => "//span[text() = 'New customer? Please Provide Your Billing Information']"], 30);
		$I->click(['xpath' => "//span[text() = 'New customer? Please Provide Your Billing Information']"]);
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->addressInformation($addressDetail);
		$I->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(['xpath' => "//legend[text() = 'Bill to information']"]);
		$I->click(['xpath' => "//div[@id='payson']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->waitForElement(['xpath' => "//h1[text() = 'Test Payson']"],60);
		$I->click(['xpath' => "//div[@id='CreditCard']"]);
		$cardNumber = str_split($checkoutAccountDetail['debitCardNumber']);
		foreach ($cardNumber as $number)
		{
			$I->pressKey(['id' => "cc-number"], $number);
		}
		$I->click(['id' => "valid-month"]);
		$I->waitForElement(['xpath' => "//option[@value='7']"],10);
		$I->click(['xpath' => "//option[@value='7']"]);
		$I->click(['id' => "valid-year"]);
		$I->waitForElement(['xpath' => "//option[@value='2019']"],10);
		$I->click(['xpath' => "//option[@value='2019']"]);
		$cvcnumber = str_split($checkoutAccountDetail['cvv']);
		foreach ($cvcnumber as $number)
		{
			$I->pressKey(['id' => "cvc-number"], $number);
		}
		$I->click(['id' => "sums"]);
		$I->click(['id' => "valid-month"]);
		$I->waitForElement(['xpath' => "//option[@value='7']"],10);
		$I->click(['xpath' => "//option[@value='7']"]);
		$I->click(['id' => "valid-year"]);
		$I->waitForElement(['xpath' => "//option[@value='2019']"],10);
		$I->click(['xpath' => "//option[@value='2019']"]);
		$I->click(['id' => "sums"]);
		$I->click(['id' => "credit-card-pay-button"]);
		$I->waitForElement(['id' => "acceptButton"],60);
		$I->click(['id' => "acceptButton"]);
		$I->waitForElement(['xpath' => "//span[text()='Thank you for the transaction!']"],30);
		$I->see('Thank you for the transaction!', "//span");
	}
}
