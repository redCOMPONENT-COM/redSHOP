<?php
/**
 * @package     redSHOP
 * @subpackage  ProductsCheckoutStripePaymentCest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderSteps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;
use Configuration\ConfigurationSteps;
use Frontend\payment\CheckoutWithStripePayment;

/**
 * Class ProductsCheckoutStripePaymentCest
 * @since 2.1.3
 */
class ProductsCheckoutStripePaymentCest
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
	 * @var string
	 * @since 2.1.3
	 */
	protected $secretKey;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $publishableKey;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $productPrice;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.1.3
	 */
	protected $maximumQuantity;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $informationVisa;

	/**
	 * ProductsCheckoutStripePaymentCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();

		$this->extensionURL     = 'extension url';
		$this->pluginName       = 'redSHOP Payment - Stripe';
		$this->pluginURL        = 'paid-extensions/tests/releases/plugins/';
		$this->package          = 'plg_redshop_payment_stripe.zip';
		$this->secretKey        = 'sk_test_3macQ0wmSqMrOzfyneBCdAaa';
		$this->publishableKey   = 'pk_test_dbkhgfbAZjhDJGpZ863DgwXe';

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

		$this->categoryName     = $this->faker->bothify('Category ?###?');
		$this->productName      = $this->faker->bothify('Product ??####?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = 100;
		$this->minimumQuantity  = 1;
		$this->maximumQuantity  = $this->faker->numberBetween(11, 100);

		$this->customerInformation = array(
			"email"             => $this->faker->email,
			"firstName"         => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"          => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"           => "Some Place in the World",
			"postalCode"        => "5000",
			"city"              => "Blangstedgaardsvej 1",
			"country"           => "Denmark",
			"state"             => "Odense SØ",
			"phone"             => "8787878887",
			"shopperGroup"      => 'Default Private',
			);

		$this->informationVisa = array(
			"cardNumber"       => '5105105105105100',
			"date"             => '0220',
			"cvc"              => '123',
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
		$I->wantTo("install plugin payment Bank Transfer Discount");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin Bank Transfer Discount Payments in Administrator');
		$I->enablePlugin($this->pluginName);

		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configStripePlugin($this->pluginName, $this->secretKey, $this->publishableKey);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkoutWithPaymentStripePlugin(ConfigurationSteps $I, $scenario)
	{
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I->wantTo('checkout with Plugin Bank Transfer Discount Payments in Administrator');
		$I = new CheckoutWithStripePayment($scenario);
		$I->wantTo('One Steps checkout with payment');
		$I->checkoutWithStripePayment($this->categoryName, $this->productName, $this->customerInformation, $this->informationVisa);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotalHaveStatusOder($this->productPrice, $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"], $this->productName, $this->categoryName, $this->pluginName, "Paid");
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Deletion of Order Total Discount in Administrator');
		$I = new OrderSteps($scenario);
		$I->deleteOrder($this->customerInformation["firstName"]);

		$I->wantTo('Disable Plugin');
		$I->disablePlugin($this->pluginName);
	}
}
