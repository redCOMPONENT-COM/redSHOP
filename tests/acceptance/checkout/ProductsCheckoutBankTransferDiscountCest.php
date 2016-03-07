<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutBankTransferDiscountCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutBankTransferDiscountCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testBankTransferDiscountPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Bank Transfer Discount Payments for redSHOP Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_banktransfer_discount/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"customerID" => "87654321",
			"debitCardNumber" => "4444333322221111",
			"cvv" => "1234",
			"cardExpiryMonth" => '2',
			"cardExpiryYear" => '2016',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('Bank Transfer Discount Payments for redSHOP');
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

		$this->checkoutProductWithBankTransferDiscountPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
		$I->doAdministratorLogin();
		$I->uninstallExtension('Bank Transfer Discount Payments for redSHOP', true);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Bank Transfer Discount Payment Plugin
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
	private function checkoutProductWithBankTransferDiscountPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='rs_payment_banktransfer_discount']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->see('Order Receipt', "//div[@class='componentheading']");
	}
}
