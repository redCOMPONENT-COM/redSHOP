<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;
use Configuration\ConfigurationSteps;
use Frontend\Shipping\ShippingPostDanmark;

/**
 * Class shippingPostDanmarkCest
 * @since 3.0.2
 */
class shippingPostDanmarkCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $extensionURL;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $pluginName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $pluginURL;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $package;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $shipping;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $product;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $function;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $paymentMethod;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $consumerId;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $mapKey;

	/**
	 * shippingPostDanmarkCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker        = Faker\Factory::create();
		$this->categoryName = $this->faker->bothify("Category Demo ?##?");

		$this->product = array(
			"name"          => $this->faker->bothify("Product Demo ?##?"),
			"number"        => $this->faker->numberBetween(999, 9999),
			"price"         => $this->faker->numberBetween(1, 900)
		);

		$this->customerInformation = array(
			"userName"     => $this->faker->userName,
			"email"        => $this->faker->email,
			"firstName"    => $this->faker->firstName,
			"lastName"     => $this->faker->lastName,
			"address"      => $this->faker->address,
			"postalCode"   => "5000",
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
			"quantityChange"   => 'yes',
			"quantityInCart"   => 0,
			"minimumOrder"     => 0,
			"enableQuotation"  => 'no'
		);

		$this->extensionURL = 'extension url';
		$this->pluginName   = 'PostDanmark - Shipping Location';
		$this->pluginURL    = 'paid-extensions/tests/releases/plugins/';
		$this->package      = 'plg_redshop_shipping_postdanmark.zip';

		// Shipping info
		$this->shipping = array(
			'shippingName' => 'Shipping PostDanmark',
			'shippingRate' => 10
		);

		$this->paymentMethod = 'RedSHOP - Bank Transfer Payment';
		$this->function      = 'saveclose';

		// PostDanmark Shipping
		$this->consumerId = '9caff6cc9346a9812bcc0b1ec2e1f9ad';
		$this->mapKey     = 'AIzaSyA0SvjoLNDEH561DBDXK_LzIa';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install plugin shipping Post Danmark");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable plugin shipping Post Danmark');
		$I->enablePlugin($this->pluginName);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configShippingPostDanmark($this->pluginName, $this->consumerId, $this->mapKey);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function onePageCheckoutWithShippingPostDanmark(AcceptanceTester $I, $scenario)
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
		$I = new ShippingPostDanmark($scenario);
		$I->onePageCheckoutWithShippingPostDanmark($this->categoryName, $this->product, $this->customerInformation, $this->shipping, $this->pluginName);

		$I->wantToTest('Check Order on Backend');
		$I = new ConfigurationSteps($scenario);
		$I->checkShippingTotal($this->shipping, $this->product, $this->customerInformation, $this->categoryName, $this->paymentMethod, $this->pluginName);
	}

	/**
	 * @param ProductManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function clearAll(ProductManagerJoomla3Steps $I, $scenario)
	{
		$I->wantToTest('Delete a Product');
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
