<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Administrator\plugins\PluginPaymentManagerJoomla;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Frontend\payment\CheckoutWithEANTransferPayment;
use AcceptanceTester\AdminManagerJoomla3Steps;

/**
 * Class ProductsCheckoutWithEANPaymentCest
 * @since 2.1.4
 */
class ProductCheckoutWithEANPaymentCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.4
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $productNumber;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $minimumQuantity;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $maximumQuantity;

	/**
	 * @var array
	 * @since 2.1.4
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $extensionURL;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $pluginName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $pluginURL;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public $package;

	/**
	 * @var array
	 * @since 2.1.4
	 */
	public $cartSetting;

	/**
	 * ProductCheckoutWithEANPaymentCest constructor.
	 * @since 2.1.4
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('CategoryName ?###?');
		$this->productName      = $this->faker->bothify('Product Name ?###?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = 100;
		$this->minimumQuantity  = 1;
		$this->maximumQuantity  = $this->faker->numberBetween(11, 100);

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addCart"            => 'product',
			"allowPreOrder"      => 'yes',
			"cartTimeOut"        => $this->faker->numberBetween(100, 10000),
			"enabledAjax"        => 'no',
			"defaultCart"        => null,
			"buttonCartLead"     => 'Back to current view',
			"onePage"            => 'yes',
			"showShippingCart"   => 'no',
			"attributeImage"     => 'no',
			"quantityChange"     => 'no',
			"quantityInCart"     => 0,
			"minimumOrder"       => 0,
			"enableQuotation"    => 'no'
		);

		$this->customerInformation = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "5000",
			"city"       => "HCM",
			"country"    => "Denmark",
			"state"      => "Karnataka",
			"phone"      => "8787878787"
		);

		$this->extensionURL   = 'extension url';
		$this->pluginName     = 'ean transfer Payments';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->package        = 'plg_redshop_payment_rs_payment_eantransfer.zip';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install EAN Transfer Payment Plugin");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->enablePlugin($this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function testCheckoutWithEANTransferPayment(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I->wantTo('Checkout with Authorize payment');
		$I = new CheckoutWithEANTransferPayment($scenario);
		$I->checkoutProductWithEANTransferPayment($this->productName, $this->categoryName, $this->customerInformation);

		$I->wantTo('Check Order');
		$I = new ConfigurationSteps($scenario);
		$I->checkPriceTotal($this->productPrice, $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"], $this->productName, $this->categoryName, $this->pluginName);

		$I->comment('Delete data has created');
		$I->wantTo('Deletion of Order in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->customerInformation['firstName']);
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
		$I->wantTo('Disable Plugin');
		$I->disablePlugin($this->pluginName);
	}
}
