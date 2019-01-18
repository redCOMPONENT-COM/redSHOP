<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutDotpayCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutDotpayCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testDotpayPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Dotpay Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/dotpay/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			'customerId' => '751042',
			'password' => 'hdMtwXA8APUrPMRldqSNqncsv9KMRsrP',
		);
		$I->enablePlugin('DotPay Payments');
		$this->updateDotpayPaymentPlugin($I, $checkoutAccountInformation['customerId'], $checkoutAccountInformation['password']);
		$I->doAdministratorLogout();

		$customerInformation = array(
			"email" => "gunjan@redcomponent.com",
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

		$this->checkoutProductWithDotpayPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to Update the Plugin
	 *
	 * @param   AcceptanceTester  $I           Actor Class Object
	 * @param   String            $customerId  Customer Id key
	 * @param   String            $password    Customer password
	 *
	 * @return void
	 */
	private function updateDotpayPaymentPlugin(AcceptanceTester $I, $customerId, $password)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('DotPay Payments');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('DotPay Payments'), 30);
		$I->checkExistenceOf('DotPay Payments');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_customerId"], 30);
		$I->fillField(['id' => "jform_params_customerId"], $customerId);
		$I->fillField(['id' => "jform_params_pin"], $password);

		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->waitForElement(['id' => 'system-message-container'], 30);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Dotpay Payment Plugin
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
	private function checkoutProductWithDotpayPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='dotpay']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		// @todo this is a temporal hotfix until we are able to test properly. see issue REDSHOP-2750
		/*
		$I->switchToIFrame("stripe_checkout_app");
		$I->waitForElementVisible(['class' => "bodyView"],30);
		$I->waitForElement(['id' => "card_number"],30);

		$I->comment('I have to fill the card number one by one due to a JS validation in the field');
		$cardNumber = str_split($checkoutAccountDetail['debitCardNumber']);
		foreach ($cardNumber as $number)
		{
			$I->pressKey(['id' => "card_number"], $number);
		}
		$I->fillField(['id' => "cc-csc"], $checkoutAccountDetail['cvv']);

		$I->comment('I have to fill the expiry date one by one due to a JS validation in the field');
		$expiryDate = str_split(str_replace('/', '', $checkoutAccountDetail['expiryDate']));
		foreach ($expiryDate as $number)
		{
			$I->pressKey(['id' => "cc-exp"], $number);
		}

		$I->click(['id' => "submitButton"]);
		$I->waitForElement(['xpath' => "//table[@class='cart_calculations']//tbody//tr[6]//td//p[text()='Paid ']"],30);
		$I->seeElement(['xpath' => "//table[@class='cart_calculations']//tbody//tr[6]//td//p[text()='Paid ']"]);
		*/
		$I->doAdministratorLogin();
		$I->uninstallExtension('DotPay Payments');
	}
}
