<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutAuthorizeDPMCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutAuthorizeDPMCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testAuthorizeDPMPaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Authorize DPM Net Payment Plugin');
		$I->doAdministratorLogin();
		$pluginName = 'Authorize Direct Post Method';
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_authorize_dpm/';
		$I->installExtensionFromDirectory($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"accessId" => "5rCF42xJ",
			"transactionId" => "336VyCe7R62LyjZZ",
			"secretQuestion" => "Simon",
			"md5Key" => "Simon",
			"password" => "Pull416!t",
			"debitCardNumber" => "4012888818888",
			"cvv" => "1234",
			"cardExpiryMonth" => '2',
			"cardExpiryYear" => '2016',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin($pluginName);
		$this->updateAuthorizeDPMPlugin($I, $checkoutAccountInformation['accessId'], $checkoutAccountInformation['transactionId'], $checkoutAccountInformation['md5Key']);
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
		$productName = 'redCOOKIE';
		$categoryName = 'Events and Forms';
		$this->checkoutProductWithAuthorizeDPMPayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
	}

	/**
	 * Function to Update the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I               Actor Class Object
	 * @param   String            $accessId        Access Id of API
	 * @param   String            $transactionKey  Transaction Key
	 * @param   String            $md5Key          MD5 Key for the Plugin
	 *
	 * @return void
	 */
	private function updateAuthorizeDPMPlugin(AcceptanceTester $I, $accessId, $transactionKey, $md5Key)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['xpath' => "//input[@id='filter_search']"], 'Authorize Direct Post Method');
		$I->click(['xpath' => "//button[@type='submit' and @data-original-title='Search']"]);
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Authorize Direct Post Method'), 30);
		$I->seeElement(['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->see('Authorize Direct Post Method', ['xpath' => "//form[@id='adminForm']/div/table/tbody/tr[1]"]);
		$I->click(['xpath' => "//input[@id='cb0']"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['xpath' => "//input[@id='jform_params_access_id']"], 30);
		$I->fillField(['xpath' => "//input[@id='jform_params_access_id']"], $accessId);
		$I->fillField(['xpath' => "//input[@id='jform_params_transaction_id']"], $transactionKey);
		$I->fillField(['xpath' => "//input[@id='jform_params_md5_key']"], $md5Key);
		$I->click(['xpath' => "//div[@id='jform_params_is_test_chzn']/a"]);

		// Choosing Test Mode to Yes
		$I->click(['xpath' => "//div[@id='jform_params_is_test_chzn']/div/ul/li[contains(text(), 'Yes')]"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Authorize DPM Payment Plugin
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
	private function checkoutProductWithAuthorizeDPMPayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
	{
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$alertMessageDiv);
		$I->waitForText(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, '.alert-success');
		$I->see(\FrontEndProductManagerJoomla3Page::$alertSuccessMessage, '.alert-success');
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$checkoutURL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$newCustomerSpan, 30);
		$I->click(\FrontEndProductManagerJoomla3Page::$newCustomerSpan);
		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->addressInformation($addressDetail);
		$I->shippingInformation($shipmentDetail);
		$I->click("Proceed");
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$billingFinal);
		$I->click(['xpath' => "//div[@id='rs_payment_authorize_dpm']//label//input"]);
		$I->click("Checkout");
		$I->waitForElement(['xpath' => "//input[@id='order_payment_name']"], 10);
		$I->fillField(['xpath' => "//input[@id='order_payment_name']"], $checkoutAccountDetail['customerName']);
		$I->fillField(['xpath' => "//input[@id='order_payment_number']"], $checkoutAccountDetail['debitCardNumber']);
		$I->fillField(['xpath' => "//input[@id='credit_card_code']"], $checkoutAccountDetail['cvv']);
		$I->click(['xpath' => "//input[@value='VISA']"]);
		$I->click(['xpath' => "//input[@value='Checkout: next step']"]);
		$I->waitForElement($productFrontEndManagerPage->product($productName), 30);
		$I->seeElement($productFrontEndManagerPage->product($productName));
		$I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText('This transaction has been approved.', 15, ['xpath' => "//td"]);
		$I->see('This transaction has been approved.', ['xpath' => "//td"]);
	}
}
