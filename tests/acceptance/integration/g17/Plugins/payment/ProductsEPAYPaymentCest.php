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
use Faker\Factory;
use Administrator\plugins\PluginPaymentManagerJoomla;
use Frontend\payment\CheckoutWithEPAYPayment;

/**
 * Class ProductsEPAYPaymentCest
 * @package  AcceptanceTester
 * @since    2.1.2
 */
class ProductsEPAYPaymentCest
{
	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $productPrice;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $addcart;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $allowPreOrder;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $cartTimeOut;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $enabldAjax;

	/**
	 * @var null
	 * @since 2.1.2
	 */
	protected $defaultCart;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $buttonCartLead;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $onePage;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $showShippingCart;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $attributeImage;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $quantityChange;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $quantityInCart;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $minimunOrder;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $enableQuation;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $onePageNo;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $onePageYes;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $merchantID;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $keyMD5;

	/**
	 * @var array
	 * @since 2.1.2
	 */
	protected $customerInformation;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $extensionURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $pluginName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $pluginURL;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $pakage;

	/**
	 * ProductsEPAYPaymentCest constructor.
	 * @since 2.1.2
	 */
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
			"firstName"     => $this->faker->bothify('FirstNameCustomer ?####?'),
			"lastName"      => $this->faker->bothify('LastNameCustomer ?####?'),
			"address"       => "Some Place In The World",
			"postalCode"    => "23456",
			"city"          => "HCM",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => "8787878787",
			"shopperGroup"  => 'Default Private',
		);
		$this->oder           = $this->customerInformation["firstName"].' '.$this->customerInformation["lastName"];
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
	 * @since 2.1.2
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("Install plugin EPay Payments");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->pakage);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin EPay Payments');
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

		$I->wantTo('I want to Create Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to Create Product');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I->wantTo('I want to Checkout With EPAY');
		$I = new CheckoutWithEPAYPayment($scenario);
		$I->CheckoutWithEPAYPayment($this->productName, $this->categoryName, $this->customerInformation);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->productPrice, $this->customerInformation["email"], $this->customerInformation["firstName"], $this->customerInformation["lastName"], $this->productName, $this->categoryName, $this->pluginName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion Of Order In Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->customerInformation["email"]);

		$I->wantTo('Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}