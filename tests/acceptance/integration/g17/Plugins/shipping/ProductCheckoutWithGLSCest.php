<?php
/**
 * @package     redSHOP
 * @subpackage  Cest ProductCheckoutWithGLS
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;
use Frontend\shipping\CheckoutWithShippingGLSSteps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class ProductCheckoutWithGLSCest
 * @since 2.1.3
 */
class ProductCheckoutWithGLSCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $productNumber;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $extensionURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $pluginName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $pluginURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $package;

	/**
	 * @var
	 * @since 2.1.3
	 */
	public $total;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	public $cartSetting;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	public $shipping;

	/**
	 * ProductCheckoutWithGLSCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('Category Name Demo ?###?');
		$this->productName      = $this->faker->bothify('Product Name ?###?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = 100;

		$this->extensionURL     = 'extension url';
		$this->pluginName       = 'default GLS';
		$this->pluginURL        = 'paid-extensions/tests/releases/plugins/';
		$this->package          = 'plg_redshop_shipping_default_shipping_gls.zip';

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

		$this->shipping       = array(
			'shippingName' => $this->faker->bothify('Testing Shipping GLS ?##?'),
			'shippingRate' => 10
		);

		$this->customerInformation = array(
			"username"          => $this->faker->userName,
			"email"             => $this->faker->email,
			"firstName"         => $this->faker->firstName,
			"lastName"          => $this->faker->lastName,
			"address"           => $this->faker->address,
			"postalCode"        => "5000",
			"city"              => $this->faker->city,
			"country"           => "Denmark",
			"state"             => "Karnataka",
			"phone"             => "0123456789",
			"shopperGroup"      => 'Default Private',
		);
		$this->group          = 'Registered';
		$this->total = $this->productPrice + $this->shipping['shippingRate'];
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since    2.1.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @throws Exception
	 * @since    2.1.3
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install plugin payment Bank Transfer Discount");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page:: $messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin Bank Transfer Discount Payments in Administrator');
		$I->enablePlugin($this->pluginName);

		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configShippingGLSPlugin($this->pluginName);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function testShippingGLSPlugin(ConfigurationSteps $I, $scenario)
	{
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I->wantToTest("Create shipping rate");
		$I = new ShippingSteps($scenario);
		$I->createShippingRateStandard($this->pluginName, $this->shipping, 'save');

		$I->wantToTest("Create user");
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser(
			$this->customerInformation["username"], $this->customerInformation["username"], $this->customerInformation["email"], $this->group, $this->customerInformation["shopperGroup"],
			$this->customerInformation["firstName"], $this->customerInformation["lastName"], 'saveclose'
		);
		$I->editAddShipping($this->customerInformation['firstName'], $this->customerInformation['lastName'], $this->customerInformation['address'], $this->customerInformation['city'], $this->customerInformation['phone'], $this->customerInformation['postalCode']);

		$I->wantTo('checkout with '.$this->pluginName);
		$I = new CheckoutWithShippingGLSSteps($scenario);
		$I->checkoutWithShippingGLS($this->categoryName, $this->productName, $this->customerInformation['username'], $this->customerInformation['username'], $this->shipping['shippingName'], $this->shipping['shippingRate'], $this->total);

		$I->wantTo('Check Order');
		$I = new ConfigurationSteps($scenario);
		$I->checkPriceTotal($this->total, $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"],
			$this->productName, $this->categoryName, $this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.3
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Delete shipping rate');
		$I = new ShippingSteps($scenario);
		$I->deleteShippingRate($this->pluginName, $this->shipping['shippingName']);

		$I->wantToTest('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation['firstName']);

		$I->wantTo("Disable Plugin");
		$I->disablePlugin($this->pluginName);
	}
}