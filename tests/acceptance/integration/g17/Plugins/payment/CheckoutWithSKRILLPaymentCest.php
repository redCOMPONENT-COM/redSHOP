<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;
use Configuration\ConfigurationSteps;
use Frontend\payment\CheckoutWithSkillPayment;

/**
 * Class CheckoutWithSKRILLPaymentCest
 * @since 2.1.3
 */
class CheckoutWithSKRILLPaymentCest
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
	public $minimumQuantity;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $maximumQuantity;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $checkoutAccountInformation;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $group;

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
	 * @var array
	 * @since 2.1.3
	 */
	public $cartSetting;

	/**
	 * CheckoutWithSKRILLPaymentCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->faker            = Faker\Factory::create();
		$this->categoryName     = $this->faker->bothify('CategoryName ?###?');
		$this->productName      = $this->faker->bothify('Product Testing ??##?');
		$this->productNumber    = $this->faker->numberBetween(999, 9999);
		$this->productPrice     = 100;
		$this->minimumQuantity  = 1;
		$this->maximumQuantity  = $this->faker->numberBetween(11, 100);

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

		$this->customerInformation = array(
			"userName"      => $this->faker->bothify('UserName ?####?'),
			"password"      => $this->faker->bothify('Password ?##?'),
			"email"         => $this->faker->email,
			"firstName"     => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"      => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"       => $this->faker->address,
			"postalCode"    => "5000",
			"city"          => $this->faker->city,
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => "0334110366",
			"shopperGroup"  => 'Default Private',
		);
		$this->group          = 'Registered';

		$this->extensionURL   = 'extension url';
		$this->pluginName     = 'Money Booker Payments';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->package        = 'plg_redshop_payment_rs_payment_moneybooker.zip';

		$this->checkoutAccountInformation = array(
			"email"           => "javier@redcomponent.com",
			"password"        => "gunjan105",
			"debitCardNumber" => "4000001234567890",
			"cvv"             => "123",
			"cardExpiryMonth" => '5',
			"cardExpiryYear"  => '25',
			"shippingAddress" => "some place on earth",
			"First"           => 'Your',
			"Last"            => "Name"
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since  2.1.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since  2.1.3
	 */
	public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("install plugin payment Skrill");
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		$I->wantTo('Enable Plugin Skrill Payments in Administrator');
		$I->enablePlugin($this->pluginName);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configMoneyBookerPlugin($this->pluginName, $this->checkoutAccountInformation['email'], $this->checkoutAccountInformation['password']);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since  2.1.3
	 */
	public function testSkrillPaymentPlugin(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo('I Want to setting cart');
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I = new CheckoutWithSkillPayment($scenario);
		$I->checkoutProductWithSkillPayment($this->checkoutAccountInformation, $this->productName, $this->categoryName, $this->customerInformation);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since  2.1.3
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

		$I->wantTo('Disable Plugin');
		$I->disablePlugin($this->pluginName);
	}
}