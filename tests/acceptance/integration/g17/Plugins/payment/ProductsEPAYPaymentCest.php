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
use Faker\Factory;
use Frontend\payment\CheckoutWith2Payment;
use Administrator\plugins\PluginPaymentManagerJoomla;

/**
 * Class ProductsEPAYPaymentCest
 * @package  AcceptanceTester
 * @since    2.1.2
 */
class ProductsEPAYPaymentCest
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
		$this->addcart          = 'product';
		$this->allowPreOrder    = 'yes';
		$this->cartTimeOut      = $this->faker->numberBetween(100, 10000);
		$this->enabldAjax       = 'no';
		$this->defaultCart      = null;
		$this->buttonCartLead   = 'Back to current view';
		$this->onePage          = 'yes';
		$this->showShippingCart = 'no';
		$this->attributeImage   = 'no';
		$this->quantityChange   = 'no';
		$this->quantityInCart   = 0;
		$this->minimunOrder     = 0;
		$this->enableQuation    = 'no';
		$this->onePageNo        = 'no';
		$this->onePageYes       = 'yes';
		$this->merchantID       = '8882887';
		$this->keyMD5           = 'bW5cPIHb5i2MdEWLJuPc5bLWS';

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
		$this->pluginName     = 'E-Pay-new Payments Windows';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->pakage         = 'plg_redshop_payment_rs_payment_epayv2.zip';
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
		$I->waitForText(AdminJ3Page:: $messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin EPay Payments in Administrator');
		$I->enablePlugin($this->pluginName);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configEPayPlugin($this->pluginName, $this->merchantID, $this->keyMD5);
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
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead,
			$this->onePageYes, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);

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
		$I->CheckoutWithEPAYPayment($this->customerInformation["userName"], $this->customerInformation["password"],$this->productName, $this->categoryName );

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
	}
}