<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutIngenicoPaymentCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutIngenicoPaymentCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testIngenicoPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Ingenico Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_ingenico/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"PSID" => "RivaSono",
			"UserID" => "RivaSono",
			"ShaIn" => "ur3op%weltwehlgigbcpqj4wdvwf",
			"ShaOut" => "7ljbe3ru#Fv4pJ8pj*dwnbo2ih",
			"debitCardNumber" => "4929 0000 0000 6",
			"cvv" => "123",
			"cardExpiryMonth" => '12',
			"cardExpiryYear" => '2015',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('Ingenico Payment');
		$this->updateIngenicoPaymentPlugin($I, $checkoutAccountInformation['PSID'], $checkoutAccountInformation['UserID'], $checkoutAccountInformation['ShaIn'], $checkoutAccountInformation['ShaOut']);
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

		$this->checkoutProductWithIngenicoPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to Update the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I       Actor Class Object
	 * @param   String            $psid    PSID Detail
	 * @param   String            $userid  UserId for the Account
	 * @param   String            $shain   Shain Pass
	 * @param   String            $shaout  Shaout Pass
	 *
	 * @return void
	 */
	private function updateIngenicoPaymentPlugin(AcceptanceTester $I, $psid, $userid, $shain, $shaout)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('Ingenico Payment');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Ingenico Payment'), 30);
		$I->checkExistenceOf('Ingenico Payment');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_ingenico_pspid"], 30);
		$I->fillField(['id' => "jform_params_ingenico_pspid"], $psid);
		$I->fillField(['id' => "jform_params_ingenico_userid"], $userid);
		$I->fillField(['id' => "jform_params_sha_in_pass_phrase"], $shain);
		$I->fillField(['id' => "jform_params_sha_out_pass_phrase"], $shaout);

		$I->click(["xpath" => "//div[@id='jform_params_currency_chzn']/a/div/b"]);
		$I->click(["xpath" => "//div[@id='jform_params_currency_chzn']//li[text()='Euro (EUR)']"]);
		$I->click(["xpath" => "//div[@id='jform_params_language_chzn']/a/div/b"]);
		$I->click(["xpath" => "//div[@id='jform_params_language_chzn']//li[text()='English (UK)']"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Ingenico Payment Plugin
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
	private function checkoutProductWithIngenicoPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='rs_payment_ingenico']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->waitForElement(["xpath" => "//input[@title='VISA']"], 30);
		$I->click(["xpath" => "//input[@title='VISA']"]);
		$I->waitForElement(["id" => "Ecom_Payment_Card_Number"], 30);
		$I->fillField(["id" => "Ecom_Payment_Card_Number"], $checkoutAccountDetail['debitCardNumber']);
		$I->click(["xpath" => "//select[@id='Ecom_Payment_Card_ExpDate_Month']//option[@value='01']"]);
		$I->click(["xpath" => "//select[@id='Ecom_Payment_Card_ExpDate_Year']//option[@value='2017']"]);
		$I->fillField(["id" => "Ecom_Payment_Card_Verification"], $checkoutAccountDetail['cvv']);
		$I->click(["xpath" => "//input[@value='Yes, I confirm my payment']"]);
		$I->waitForText('Order placed', 30, ['xpath' => "//div[@class='alert alert-message']"]);
		$I->see('Order placed', "//div[@class='alert alert-message']");
	}
}