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
use AcceptanceTester\UserManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;
use Frontend\payment\PayPalPluginManagerJoomla3Steps;

/**
 * Class Products2CheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class PaypalCheckoutCest
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
	protected $payPalInformation;
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
		$this->productPrice     = 10;
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
			"email"         => "qa_test_buyer@gmail.com",
			"firstName"     => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"      => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"       => "Some Place in the World",
			"postalCode"    => "23456",
			"city"          => "HCM",
			"country"       => "Denmark",
			"state"         => "Karnataka",
			"phone"         => "8787878787",
			"shopperGroup"  => 'Default Private',
		);
		$this->group          = 'Registered';

		$this->pluginName     = 'PayPal';

		$this->payPalInformation = array(
			"username" => "qa_test_buyer@gmail.com",
			"password" => "123456789",
			"email"    => "qa.busines@gmail.com",
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
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function configPayPalPlugin(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo('Enable Plugin payment Paypal');
		$I->enablePlugin($this->pluginName);
		$I = new PluginPaymentManagerJoomla($scenario);
		$I->configPayPalPlugin($this->pluginName, $this->payPalInformation['email']);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function testProductsCheckoutFrontEnd(ConfigurationSteps $I, $scenario)
	{
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

		$I = new PayPalPluginManagerJoomla3Steps($scenario);
		$I->checkoutProductWithPayPalPayment( $this->customerInformation, $this->payPalInformation, $this->productName, $this->categoryName);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotalHaveStatusOder($this->productPrice, $this->customerInformation["firstName"], $this->customerInformation["firstName"], $this->customerInformation["lastName"], $this->productName, $this->categoryName, $this->pluginName, "Paid");
	}

	/**
	 * @param OrderManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since    2.1.2
	 */
	public function clearAllData(OrderManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo('Deletion of Order in Administrator');
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
	}
}