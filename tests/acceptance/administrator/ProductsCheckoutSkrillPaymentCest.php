<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutSkrillPaymentCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutSkrillPaymentCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function SkrillPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Money Booker Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_moneybooker/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"merchantEmail" => "javier@redcomponent.com",
			"debitCardNumber" => "4000001234567890",
			"password"	=> "gunjan105",
			"cvv" => "123",
			"cardExpiryMonth" => '02',
			"cardExpiryYear" => '18',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('Money Booker Payments');
		$this->updateSkrillPlugin($I, $checkoutAccountInformation['merchantEmail'], $checkoutAccountInformation['password']);
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

		$this->checkoutProductWithSkrillPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to update Skrill Payment Plugin

	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $emailID   Store ID for API
	 * @param   String            $password  ApiToken for Plugin
	 *
	 * @return void
	 */
	private function updateSkrillPlugin(AcceptanceTester $I, $emailID, $password)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('Money Booker Payments');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Money Booker Payments'), 30);
		$I->checkExistenceOf('Money Booker Payments');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_pay_to_email"], 30);
		$I->fillField(['id' => "jform_params_pay_to_email"], $emailID);
		$I->fillField(['id' => "jform_params_pay_to_password"], $password);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Skrill Payment Plugin
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
	private function checkoutProductWithSkrillPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->amOnPage('/index.php?option=com_redshop&view=checkout');
		$I->waitForElement(['xpath' => "//span[text() = 'New customer? Please Provide Your Billing Information']"], 30);
		$I->click(['xpath' => "//span[text() = 'New customer? Please Provide Your Billing Information']"]);
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->addressInformation($addressDetail);
		$I->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(['xpath' => "//legend[text() = 'Bill to information']"]);
		$I->click(['xpath' => "//div[@id='rs_payment_moneybooker']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->waitForElement(['id' => "card_number"], 30);
		$I->fillField(['id' => "card_number"], $checkoutAccountDetail['debitCardNumber']);
		$expiryMonth = str_split($checkoutAccountDetail['cardExpiryMonth']);

		foreach ($expiryMonth as $number)
		{
			$I->pressKey(['id' => "expiry_month"], $number);
		}

		$expiryYear = str_split($checkoutAccountDetail['cardExpiryYear']);

		foreach ($expiryYear as $number)
		{
			$I->pressKey(['id' => "expiry_year"], $number);
		}

		$I->fillField(['id' => "cvv_code"], $checkoutAccountDetail['cvv']);
		$I->click(['id' => "pay_button"]);
		$I->waitForText('Order Receipt', 30, ['class' => "componentheading"]);
		$I->see('Order Receipt', ['class' => "componentheading"]);
		$I->doAdministratorLogin();
		$I->uninstallExtension('Money Booker Payments');
	}
}
