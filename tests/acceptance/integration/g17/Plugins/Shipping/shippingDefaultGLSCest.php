<?php
/**
 * @package     redSHOP
 * @subpackage  ProductsCheckoutStripePaymentCest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\CheckoutWithShippingRateSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

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
	 * @var mixed
	 * @since 2.1.3
	 */
	protected $total;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $customerInformation;

	/**
	 * shippingDefaultGLSCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();

		$this->extensionURL = 'extension url';
		$this->pluginName = 'default GLS';
		$this->pluginURL = 'paid-extensions/tests/releases/plugins/';
		$this->package = 'plg_redshop_shipping_default_shipping_gls.zip';

		// Shipping info
		$this->shipping = array(
			'shippingName' => 'Shipping Default GLS',
			'shippingRate' => 10
		);

		$this->categoryName = $this->faker->bothify("Category Demo ?##?");
		$this->product = array(
			"name"          => $this->faker->bothify("Product Demo ?##?"),
			"number"        => $this->faker->numberBetween(999,9999),
			"price"         => $this->faker->numberBetween(1,990)
		);

		$this->total = $this->product['price'] + $this->shipping['shippingRate'];

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
			"phone"         => $this->faker->phoneNumber,
			"shopperGroup"  => 'Default Private',
			'group'         => 'Registered'
		);
	}

	/**
	 * @param AcceptanceTes
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
		$I->wantTo("install plugin shipping default GLS");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable plugin shipping default GLS');
		$I->enablePlugin($this->pluginName);

		$I = new ShippingSteps($scenario);
		$I->createShippingRateStandard($this->pluginName, $this->shipping, 'saveclose');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkoutWithShippingDefaultGLS(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Create User');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->customerInformation['userName'], $this->customerInformation['userName'], $this->customerInformation['email'], $this->customerInformation['group'],
			$this->customerInformation['shopperGroup'], $this->customerInformation['firstName'], $this->customerInformation['lastName'], 'saveclose');
		$I->editAddShipping($this->customerInformation['firstName'], $this->customerInformation['lastName'], $this->customerInformation['address'],
			$this->customerInformation['city'], $this->customerInformation['phone'], $this->customerInformation['postalCode']);

		$I->wantToTest('Create Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantToTest("Create Product");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->product['name'], $this->categoryName, $this->product['number'], $this->product['price']);

		$I->wantToTest('Checkout with '.$this->shipping['shippingName'].' just create');
		$I = new CheckoutWithShippingRateSteps($scenario);
		$I->checkoutWithShippingRate($this->customerInformation['userName'], $this->customerInformation['userName'], $this->categoryName,
			$this->product['name'], $this->total, $this->shipping);

		$I->wantToTest('Check Order on Backend');
		$I = new ConfigurationSteps($scenario);
		$I->checkPriceTotal($this->product['price'], $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"],
			$this->product['name'], $this->categoryName, $this->pluginName);
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