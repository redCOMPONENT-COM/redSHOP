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
use Administrator\plugins\PluginPaymentManagerJoomla;
use Frontend\payment\CheckoutWithAuthorizeDPMPayment;

/**
 * Class ProductsCheckoutAuthorizeDPMCest
 * @package  AcceptanceTester
 * @since    2.1.2
 */
class ProductsCheckoutAuthorizeDPMCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.2
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productNumber;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $minimumQuantity;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $maximumQuantity;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $checkoutAccountInformation;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $group;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $extensionURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $pluginName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $pluginURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public $package;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public $cartSetting;

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
			"addCart"            => 'product',
			"allowPreOrder"      => 'yes',
			"cartTimeOut"        => $this->faker->numberBetween(100, 10000),
			"enabledAjax"         => 'no',
			"defaultCart"        => null,
			"buttonCartLead"     => 'Back to current view',
			"onePage"            => 'yes',
			"showShippingCart"   => 'no',
			"attributeImage"     => 'no',
			"quantityChange"     => 'no',
			"quantityInCart"     => 0,
			"minimumOrder"       => 0,
			"enableQuotation"      => 'no'
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
		$this->pluginName     = 'Authorize Direct Post Method';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->package         = 'plg_redshop_payment_rs_payment_authorize_dpm.zip';

		$this->checkoutAccountInformation = array(
			"accessId"          => "5rCF42xJ",
			"transactionId"     => "336VyCe7R62LyjZZ",
			"secretQuestion"    => "Simon",
			"md5Key"            => "Simon",
			"password"          => "Pull416!t",
			"debitCardNumber"   => "4012888818888",
			"cvv"               => "123",
			"cardExpiryMonth"   => '12',
			"cardExpiryYear"    => '2025',
			"shippingAddress"   => "some place on earth",
			"customerName"      => 'Your name'
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
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install plugin payment 2Checkout ");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->enablePlugin($this->pluginName);
		$I->wantTo('Enable Plugin 2Checkout Payments in Administrator');
		$I->configAuthorizeDPMPlugin($this->pluginName, $this->checkoutAccountInformation['accessId'], $this->checkoutAccountInformation['transactionId'], $this->checkoutAccountInformation['md5Key']);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function testAuthorizeDPMPaymentPlugin(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I = new CheckoutWithAuthorizeDPMPayment($scenario);
		$I->wantTo('One Steps checkout with payment');
		$I->checkoutProductWithAuthorizeDPMPayment($this->checkoutAccountInformation, $this->productName, $this->categoryName, $this->customerInformation);

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
		$I->wantTo('Disable Plugin');
		$I->disablePlugin($this->pluginName);
	}
}