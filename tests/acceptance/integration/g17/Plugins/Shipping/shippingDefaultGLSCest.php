<?php
/**
 * @package     redSHOP
 * @subpackage  ProductsCheckoutStripePaymentCest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use Frontend\Shipping\shippingDefaultGLS;

/**
 * Class shippingDefaultGLSCest
 * @since 2.1.3
 */
class shippingDefaultGLSCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.3
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $extensionURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $pluginName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $pluginURL;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $package;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $shipping;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $categoryName;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $product;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $function;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $paymentMethod;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $cartSetting;

	/**
	 * shippingDefaultGLSCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker        = Faker\Factory::create();
		$this->categoryName = $this->faker->bothify("Category Demo ?##?");

		$this->product = array(
			"name"          => $this->faker->bothify("Product Demo ?##?"),
			"number"        => $this->faker->numberBetween(999,9999),
			"price"         => $this->faker->numberBetween(1,990)
		);

		$this->customerInformation = array(
			"userName"     => $this->faker->userName,
			"email"        => $this->faker->email,
			"firstName"    => $this->faker->firstName,
			"lastName"     => $this->faker->lastName,
			"address"      => $this->faker->address,
			"postalCode"   => "700000",
			"city"         => "HCM",
			"country"      => "Denmark",
			"state"        => "Karnataka",
			"phone"        => "0909909999",
			"shopperGroup" => 'Default Private',
			'group'        => 'Registered'
		);

		$this->cartSetting = array(
			"addCart"          => 'product',
			"allowPreOrder"    => 'yes',
			"cartTimeOut"      => $this->faker->numberBetween(100, 10000),
			"enabledAjax"      => 'no',
			"defaultCart"      => null,
			"buttonCartLead"   => 'Back to current view',
			"onePage"          => 'yes',
			"showShippingCart" => 'no',
			"attributeImage"   => 'no',
			"quantityChange"   => 'no',
			"quantityInCart"   => 0,
			"minimumOrder"     => 0,
			"enableQuotation"  => 'no'
		);

		$this->extensionURL = 'extension url';
		$this->pluginName   = 'default GLS';
		$this->pluginURL    = 'paid-extensions/tests/releases/plugins/';
		$this->package      = 'plg_redshop_shipping_default_shipping_gls.zip';

		// Shipping info
		$this->shipping = array(
			'shippingName' => 'Shipping Default GLS',
			'shippingRate' => 10
		);

		$this->paymentMethod = 'RedSHOP - Bank Transfer Payment';
		$this->function      = 'saveclose';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.3
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
	public function installPlugin(AdminManagerJoomla3Steps $I)
	{
		$I->wantTo("install plugin shipping default GLS");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable plugin shipping default GLS');
		$I->enablePlugin($this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingDefaultGLS(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Setting one page checkout');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I = new ShippingSteps($scenario);
		$I->wantTo('Check create new Shipping rate');
		$I->createShippingRateStandard($this->pluginName, $this->shipping, $this->function);

		$I->wantToTest('Create Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantToTest("Create Product");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->product['name'], $this->categoryName, $this->product['number'], $this->product['price']);

		$I->wantToTest('Check on Front-end');
		$I = new shippingDefaultGLS($scenario);
		$I->checkoutWithShippingDefaultGLS($this->categoryName, $this->product, $this->customerInformation, $this->shipping, $this->pluginName);

		$I->wantToTest('Check Order on Backend');
		$I = new ConfigurationSteps($scenario);
		$I->checkShippingTotal($this->shipping, $this->product, $this->customerInformation, $this->categoryName, $this->paymentMethod, $this->pluginName);
	}

	/**
	 * @param ProductManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function clearAll(ProductManagerJoomla3Steps $I, $scenario)
	{
		$I->wantToTest('Delete Product');
		$I->deleteProduct($this->product['name']);

		$I->wantToTest('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantToTest('Delete User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation['firstName']);

		$I->wantToTest('Delete Shipping Rate');
		$I = new ShippingSteps($scenario);
		$I->deleteShippingRate($this->pluginName, $this->shipping['shippingName']);

		$I->wantToTest('Delete Order');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->customerInformation['firstName']);

		$I->wantTo('Disable Plugin');
		$I->disablePlugin($this->pluginName);
	}
}
