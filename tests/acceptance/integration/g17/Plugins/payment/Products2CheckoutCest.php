<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Frontend\payment\CheckoutWith2Payment;
use Administrator\plugins\PluginPaymentManagerJoomla;

/**
 * Class Products2CheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class Products2CheckoutCest
{
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('CategoryName ?###?');
		$this->productName      = $this->faker->bothify('Testing Product ??####?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = 100;
		$this->minimumQuantity  = 1;
		$this->maximumQuantity  = $this->faker->numberBetween(11, 100);

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->faker->numberBetween(100, 10000),
			"enabledAjax"       => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'yes',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'no',
			"quantityInCart"    => 0,
			"minimumOrder"      => 0,
			"enableQuotation"   => 'no'
		);

		$this->customerInformation = array(
			"userName"      => $this->faker->bothify('UserName ?####?'),
			"password"      => $this->faker->bothify('Password ?##?'),
			"email"         => $this->faker->email,
			"firstName"     => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"      => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"       => "Some Place in the World",
			"postalCode"    => "23456",
			"city"          => "HCM",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => "8787878787",
			"shopperGroup"  => 'Default Private',
		);
		$this->group          = 'Registered';

		$this->extensionURL   = 'extension url';
		$this->pluginName     = '2Checkout Payments';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->pakage         = 'plg_redshop_payment_rs_payment_2checkout.zip';

		$this->checkoutAccountInformation = array(
			"vendorID"        => "901261371",
			"secretWord"      => "tango",
			"debitCardNumber" => "4000000000000002",
			"cvv"             => "123",
			"cardExpiryMonth" => '5',
			"cardExpiryYear"  => '2022',
			"shippingAddress" => "some place on earth"
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install plugin payment 2Checkout ");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->pakage);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin 2Checkout Payments in Administrator');
		$I->enablePlugin($this->pluginName);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->config2CheckoutPlugin($this->pluginName, $this->checkoutAccountInformation['vendorID'], $this->checkoutAccountInformation['secretWord']);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function testProductsCheckoutFrontEnd(AcceptanceTester $I, $scenario)
	{
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I->wantTo('Create user for checkout');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser(
			$this->customerInformation["userName"], $this->customerInformation["password"], $this->customerInformation["email"], $this->group, $this->customerInformation["shopperGroup"],
			$this->customerInformation["firstName"], $this->customerInformation["lastName"], 'saveclose'
		);

		$I = new CheckoutWith2Payment($scenario);
		$I->checkoutProductWith2Checkout($this->customerInformation["userName"], $this->customerInformation["password"], $this->checkoutAccountInformation,$this->productName, $this->categoryName, $this->customerInformation["city"]);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->productPrice, $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"], $this->productName, $this->categoryName, $this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Order in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->customerInformation['firstName']);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation["firstName"]);

		$I->wantTo('Disable Plugin');
		$I->disablePlugin($this->pluginName);
	}
}