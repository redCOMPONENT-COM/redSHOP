<?php
/**
 * @package     redSHOP
 * @subpackage  shippingGiaoHangNhanhCest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;
use Configuration\ConfigurationSteps;
use Frontend\Shipping\ShippingGiaoHangNhanh;

/**
 * Class shippingGiaoHangNhanhCest
 * @since 2.1.3
 */
class shippingGiaoHangNhanhCest
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
	 * @var mixed
	 * @since 2.1.3
	 */
	protected $total;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $paymentMethod;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $function;

	/**
	 * shippingGiaoHangNhanhCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();

		$this->extensionURL   = 'extension url';
		$this->pluginName     = 'redSHOP: Giao hàng nhanh';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->package        = 'plg_redshop_shipping_giaohangnhanh.zip';

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
			'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
			'shippingRate' => $this->faker->numberBetween(10,50)
		);

		$this->function = 'saveclose';

		$this->categoryName = $this->faker->bothify("Category Demo ?##?");

		$this->product = array(
			"name"          => $this->faker->bothify("Product Demo ?##?"),
			"number"        => $this->faker->numberBetween(999,9999),
			"price"         => $this->faker->numberBetween(50,200)
		);

		$this->customerInformation = array(
			"userName"      => $this->faker->userName,
			"email"         => $this->faker->email,
			"firstName"     => $this->faker->firstName,
			"lastName"      => $this->faker->lastName,
			"address"       => $this->faker->address,
			"postalCode"    => "700000",
			"city"          => "HCM",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => '0967692988',
			"shopperGroup"  => 'Default Private',
			'group'         => 'Registered'
		);

		$this->paymentMethod = 'RedSHOP - Bank Transfer Payment';

		$this->total = $this->product['price'] + $this->shipping['shippingRate'];
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
	 * @since 2.1.3
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I)
	{
		$I->wantTo("Install plugin shipping Giao Hang Nhanh");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->enablePlugin($this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkoutShippingGiaoHangNhanh(AcceptanceTester $I, $scenario)
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
		$I = new ShippingGiaoHangNhanh($scenario); 
		$I->checkoutWithShippingGiaoHangNhanh($this->categoryName, $this->product['name'], $this->customerInformation, $this->product['price'], $this->shipping, $this->pluginName);

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