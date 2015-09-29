<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ProductsCheckoutAuthorizeCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ProductsCheckoutAuthorizeCest
{
	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testAuthorizePaymentPlugin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Authorize Net Payment Plugin');
		$I->doAdministratorLogin();
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_authorize/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"accessId" => "62qpC9xN9nN4",
			"transactionId" => "97sRY6pGTea3E48d",
			"password" => "Pull416!t",
			"debitCardNumber" => "4012888818888",
			"cvv" => "1234",
			"cardExpiryMonth" => '2',
			"cardExpiryYear" => '2016',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('Authorize Payments');
		$this->updateAuthorizePlugin($I, $checkoutAccountInformation['accessId'], $checkoutAccountInformation['transactionId']);
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
		$this->checkoutProductWithAuthorizePayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $productName, $categoryName);
		$I->doAdministratorLogin();
		$I->uninstallExtension('Authorize Payments');
	}

	/**
	 * Test to Verify the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I         Actor Class Object
	 * @param   String            $scenario  Scenario Variable
	 *
	 * @return void
	 */
	public function testAuthorizePaymentPluginWithProducts(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Checkout on Front End with Authorize Net Payment Plugin');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$randomCategoryName = 'TestingCategory' . rand(99, 999);
		$randomProductName = 'Testing Products' . rand(99, 999);
		$randomProductNumber = rand(99, 999);
		$randomProductPrice = rand(99, 199);

		$I->wantTo('Create a Category');
		$I->addCategory($randomCategoryName);
		$I->see($randomCategoryName);

		$I->wantTo('Create a Product');
		$I->amOnPage('administrator/index.php?option=com_redshop&view=product');
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
		$I->click("New");
		$I->waitForElement(['id' => "product_name"], 30);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['id' => "product_name"], $randomProductName);
		$I->fillField(['id' => "product_number"], $randomProductNumber);
		$I->fillField(['id' => "product_price"], $randomProductPrice);
		$I->fillField(['xpath' => "//div[@id='product_category_chzn']//ul/li//input"], $randomCategoryName);
		$I->waitForElement(['xpath' => "//em[contains(text(), " . $randomCategoryName . ")]"], 30);
		$I->click(['xpath' => "//em[contains(text(), " . $randomCategoryName . ")]"]);
		$I->click("Save & Close");
		$I->waitForText('Product details saved', 30, ['class' => 'alert-message']);
		$I->see('Product details saved', ['class' => 'alert-message']);
		$pathToPlugin = $I->getConfig('repo folder') . 'plugins/redshop_payment/rs_payment_authorize/';
		$I->installExtensionFromFolder($pathToPlugin, 'Plugin');

		$checkoutAccountInformation = array(
			"accessId" => "62qpC9xN9nN4",
			"transactionId" => "97sRY6pGTea3E48d",
			"password" => "Pull416!t",
			"debitCardNumber" => "4012888818888",
			"cvv" => "1234",
			"cardExpiryMonth" => '2',
			"cardExpiryYear" => '2016',
			"shippingAddress" => "some place on earth",
			"customerName" => 'Testing Customer'
		);
		$I->enablePlugin('Authorize Payments');
		$this->updateAuthorizePlugin($I, $checkoutAccountInformation['accessId'], $checkoutAccountInformation['transactionId']);
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
		$this->checkoutProductWithAuthorizePayment($I, $scenario, $customerInformation, $customerInformation, $checkoutAccountInformation, $randomProductName, $randomCategoryName);
		$I->doAdministratorLogin();
		$I->wantTo('Delete the created Product');
		$I->amOnPage('administrator/index.php?option=com_redshop&view=product');
		$I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
		$I->fillField(['xpath' => "//div[@class='filterItem']//div//input[@name='keyword']"], $randomProductName);
		$I->click(['xpath' => "//div[@class='filterItem']//div//input[@value='Search']"]);
		$I->see($randomProductName, ['xpath' => "//div[@id='editcell']/table/tbody/tr[1]"]);
		$I->checkAllResults();
		$I->click("Delete");
		$I->waitForText('Product deleted successfully', 30, ['class' => 'alert-success']);
		$I->dontSee($randomProductName);
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($randomCategoryName);
		$I->searchCategory($randomCategoryName, 'Delete');
		$I->dontSee($randomCategoryName);
		$I->uninstallExtension('Authorize Payments');
	}

	/**
	 * Function to Update the Payment Plugin
	 *
	 * @param   AcceptanceTester  $I               Actor Class Object
	 * @param   String            $accessId        Access Id of API
	 * @param   String            $transactionKey  Transaction Key
	 *
	 * @return void
	 */
	private function updateAuthorizePlugin(AcceptanceTester $I, $accessId, $transactionKey)
	{
		$I->amOnPage('/administrator/index.php?option=com_plugins');
		$I->checkForPhpNoticesOrWarnings();
		$I->searchForItem('Authorize Payments');
		$pluginManagerPage = new \PluginManagerJoomla3Page;
		$I->waitForElement($pluginManagerPage->searchResultPluginName('Authorize Payments'), 30);
		$I->checkExistenceOf('Authorize Payments');
		$I->click(['id' => "cb0"]);
		$I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
		$I->waitForElement(['id' => "jform_params_access_id"], 30);
		$I->fillField(['id' => "jform_params_access_id"], $accessId);
		$I->fillField(['id' => "jform_params_transaction_id"], $transactionKey);
		$I->click(['xpath' => "//div[@id='jform_params_is_test_chzn']/a"]);

		// Choosing Test Mode to Yes
		$I->click(['xpath' => "//div[@id='jform_params_is_test_chzn']/div/ul/li[contains(text(), 'Yes')]"]);
		$I->click(['link' => 'Advanced']);
		$I->click(['xpath' => "//li//label[text()='Visa']"]);
		$I->click(['xpath' => "//li//label[text()='MasterCard']"]);
		$I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
		$I->see('successfully saved', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Checkout Process of a Product using the Braintree Payment Plugin
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
	private function checkoutProductWithAuthorizePayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms')
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
		$I->click(['xpath' => "//div[@id='rs_payment_authorize']//label//input"]);
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
