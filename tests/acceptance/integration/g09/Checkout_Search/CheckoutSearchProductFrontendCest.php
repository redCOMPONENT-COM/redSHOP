<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\CheckoutSearchProductFrontendSteps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\PayPalPluginManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class CheckoutSearchProductFrontendCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since   2.1.0
 */
class CheckoutSearchProductFrontendCest
{
	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $productName;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $total;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $subtotal;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $userName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $password;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $email;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $group;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $firstName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $lastName;

	/**
	 * @var \Faker\Generator
	 * @since 2.1.0
	 */
	public $faker;

	/**
	 * @var array
	 * @since 2.1.0
	 */
	protected $cartSetting;

	/**
	 * @var array
	 * @since 2.1.0
	 */
	protected $module;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $paymentMethod;

	/**
	 * @var array
	 * @since 2.1.0
	 */
	protected $customerInformation;

	/**
	 * CheckoutSearchProductFrontendCest constructor.
	 * @since 2.1.0
	 */
	public function __construct()
	{
		//Product & Category
		$this->faker               = Faker\Factory::create();
		$this->productName         = $this->faker->bothify('Product Name ?##?');;
		$this->categoryName        = $this->faker->bothify('Category Name ?##?');
		$this->subtotal            = "DKK 1.000,00";
		$this->total               = "DKK 1.000,00";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;

		$this->module  = array();
		$this->module['name']               = 'Search product';
		$this->module['module']             = 'redSHOP Search';
		$this->module['Position']           = 'position-2';
		$this->module['SearchTypeField']    = 'no';
		$this->module['SearchField']        = 'yes';
		$this->module['CategoryField']      = 'no';
		$this->module['ManufacturerField']  = 'no';
		$this->module['ProductSearchTitle'] = 'no';
		$this->module['KeywordTitle']       = 'no';

		//User
		$this->paymentMethod       = 'RedSHOP - Bank Transfer Payment';
		$this->customerInformation = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Ho Chi Minh",
			"country"    => "Viet Nam",
			"state"      => "",
			"phone"      => "0334110355"
		);

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
	}

	/**
	 * @param CheckoutSearchProductFrontendSteps $I
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function createModuleRedShopSearch(CheckoutSearchProductFrontendSteps $I)
	{
		$I->doAdministratorLogin();
		$I->createModuleRedShopSearch($this->module);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function createProductAndCategory(ConfigurationSteps $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Setting cart on Administrator');
		$I->cartSetting($this->cartSetting);

		$I = new PayPalPluginManagerJoomla3Steps($scenario);
		$I->wantTo('Disable PayPal');
		$I->disablePlugin('PayPal');

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create Category in Administrator');
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
	}

	/**
	 * @param CheckoutSearchProductFrontendSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function checkoutSearchProductFrontend(CheckoutSearchProductFrontendSteps $I, $scenario)
	{
		$I->wantTo('Checkout with product search on front-end');
		$I->checkoutSearchProductFrontend($this->productName,$this->customerInformation);
		$I->doAdministratorLogin();
		$I->wantTo('Check Order');
		$I = new ConfigurationSteps($scenario);
		$I->checkPriceTotal($this->randomProductPrice, $this->customerInformation['firstName'], $this->customerInformation['firstName'],$this->customerInformation['lastName'], $this->productName, $this->categoryName, $this->paymentMethod);
	}

	/**
	 * @param CheckoutSearchProductFrontendSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function clearAllData(CheckoutSearchProductFrontendSteps $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Deletion of Order in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->customerInformation['firstName']);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}
