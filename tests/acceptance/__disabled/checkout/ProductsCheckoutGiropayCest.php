<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutGiropayCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutGiropayCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testGiropayPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Giro Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_giropay/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"merchatID" => "3615728",
			"projectID" => "16949",
			"projectPassphrase" => "6NxTcPNMj3DK",
			"bankCode" => "12345679",
			"BIC" => "TESTDETT421",
			"loginName" => "sepatest1",
			"pin" => "12345",
			"tan" => "123456",
		);
		$I->enablePlugin('GiroPay Payment');
		$this->updateGiropayPlugin($I, $checkoutAccountInformation['merchatID'], $checkoutAccountInformation['projectID'], $checkoutAccountInformation['projectPassphrase']);
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

		$this->checkoutProductWithGiropayPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);

	}

	/**
	 * Function to Update the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I                 Actor Class Object
	 * @param   String            $merchantId        Id of API
	 * @param   String            $projectId         Project ID
	 * @param   String            $secretPassphrase  secret pass phrase for the plugin
	 *
	 * @return void
	 */
	private function updateGiropayPlugin(AcceptanceTester $I, $merchantId, $projectId, $secretPassphrase)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('GiroPay Payment');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('GiroPay Payment'), 30);
		$I->checkExistenceOf('GiroPay Payment');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_merchant_id"], 30);
		$I->fillField(['id' => "jform_params_merchant_id"], $merchantId);
		$I->fillField(['id' => "jform_params_project_id"], $projectId);
		$I->fillField(['id' => "jform_params_secret_password"], $secretPassphrase);

		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Giropay Payment Plugin
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
	private function checkoutProductWithGiropayPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='rs_payment_giropay']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(['id' => "termscondition"]);
		$I->click(['id' => "checkout_final"]);
		$I->waitForElement(['id' => "edit-bankcode"], 30);
		$I->fillField(['id' => "edit-bankcode"], $checkoutAccountDetail['bankCode']);
		$I->click(['id' => "edit-submit"]);

		$I->waitForElement(['xpath' => "//input[@name='account/addition[@name=benutzerkennung]']"], 30);
		$I->fillField(['xpath' => "//input[@name='account/addition[@name=benutzerkennung]']"], $checkoutAccountDetail['loginName']);
		$I->fillField(['xpath' => "//input[@name='ticket/pin']"], $checkoutAccountDetail['pin']);
		$I->click(['xpath' => "//input[@value='Sicher anmelden']"]);

		$I->waitForElement(['xpath' => "//input[@value='Weiter']"], 30);
		$I->click(['xpath' => "//input[@value='Weiter']"]);

		$I->waitForElement(['xpath' => "//input[@name='ticket/tan']"], 30);
		$I->fillField(['xpath' => "//input[@name='ticket/tan']"], $checkoutAccountDetail['tan']);
		$I->click(['xpath' => "//input[@value='Jetzt bezahlen']"]);

		$I->waitForText('Die giropay-Zahlung wurde erfolgreich durchgeführt.', 30, ['xpath' => "//span[@class='sf-text']"]);
		$I->see('Die giropay-Zahlung wurde erfolgreich durchgeführt.', ['xpath' => "//span[@class='sf-text']"]);
		$I->click(['xpath' => "//div[@class='buttonWrap hideOnMobile']//input[@name='back2MerchantButton']"]);
	}
}
