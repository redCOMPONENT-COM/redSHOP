<?php

/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;

/**
 * Class OnePageCheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4.0
 */
class OnePageCheckoutCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $productName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $total;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $subtotal;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $userName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $password;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $email;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $group;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $lastName;

	/**
	 * @var integer
	 * @since 1.4.0
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 * @since 1.4.0
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 * @since 1.4.0
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $discountEnd;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	protected $customerInformationSecond;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	protected $customerBussinesInformation;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	protected $customerBussinesInformationSecond;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $shippingWithVat;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	protected $cartSetting;

	/**
	 * OnePageCheckoutCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->productName            =  $this->faker->bothify('productName ?####?');
		$this->categoryName           =  $this->faker->bothify('categoryName ?####?');
		$this->minimumPerProduct      = 1;
		$this->minimumQuantity        = 1;
		$this->maximumQuantity        = $this->faker->numberBetween(100, 1000);
		$this->discountStart          = "12-12-2016";
		$this->discountEnd            = "23-05-2017";
		$this->randomProductNumber    = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice     = 100;

		$this->subtotal = "DKK 100,00";
		$this->total    = "DKK 100,00";

		$this->userName        = $this->faker->bothify('OnePageCest ?####?');
		$this->password        = $this->faker->bothify('Password ?##?');
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Registered';
		$this->firstName       = $this->faker->bothify('OnePageCest FN ?#####?');
		$this->lastName        = 'Last';

		$this->customerInformation = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"country"    => "India",
			"state"      => "Karnataka",
			"phone"      => "8787878787"
		);

		$this->customerInformationSecond = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"country"    => "India",
			"state"      => "Karnataka",
			"phone"      => "8787878787"
		);

		$this->customerBussinesInformation = array(
			"email"          => "test@test" . rand() . ".com",
			"companyName"    => "CompanyName",
			"businessNumber" => 1231312,
			"firstName"      => $this->faker->bothify('firstName ?####?'),
			"lastName"       => $this->faker->bothify('lastName ?####?'),
			"address"        => "Some Place in the World",
			"postalCode"     => "23456",
			"city"           => "Bangalore",
			"country"        => "India",
			"state"          => "Karnataka",
			"phone"          => "8787878787",
			"eanNumber"      => 1212331331231,
		);

		$this->customerBussinesInformationSecond = array(
			"email"          => "test@test" . rand() . ".com",
			"companyName"    => $this->faker->bothify('Name Company ?###?'),
			"businessNumber" => 1231312,
			"firstName"      => $this->faker->bothify('firstNameSecond ?####?'),
			"lastName"       => $this->faker->bothify('lastNameSecond  ?####?'),
			"address"        => "Some Place in the World",
			"postalCode"     => "23456",
			"city"           => "Bangalore",
			"country"        => "India",
			"state"          => "Karnataka",
			"phone"          => "8787878787",
			"eanNumber"      => 1212331331231,
		);

		$this->shippingWithVat    = "DKK 0,00";

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
	}

	/**
	 * Step1: Clear all database
	 * Step2: Goes on configuration and setup this site can use quotation
	 * Step3: Create category
	 * Step4: Create product inside category
	 * Step5: Goes on frontend and create quotation with private account
	 * Step6: goes on frontend login and logout to clear all at site frontend
	 * Step7: Goes on frontend and create quotation with business account
	 * Step8: Goes on admin page and delete all data and convert cart setting the same default demo
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function onePageCheckout(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Disable paypal plugin');
		$I->disablePlugin('PayPal');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('Test one page checkout with business user with name is customerBussinesInformation[firstName]');
		$I->onePageCheckout('admin', 'admin', $this->productName, $this->categoryName, $this->subtotal, $this->total, $this->customerBussinesInformation, 'business', 'no');

		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantTo('checkout with user with login ');
		$I->checkoutOnePageWithLogin($this->userName, $this->password, $this->productName, $this->categoryName, $this->shippingWithVat, $this->total);
		$I->doFrontendLogout();


		$I->wantToTest('Test one page checkout with private with user login is customerInformation[firstName]');
		$I->onePageCheckout($this->customerInformation['firstName'], $this->customerInformation['firstName'], $this->productName, $this->categoryName, $this->subtotal, $this->total, $this->customerInformation, 'private', 'yes');
		$I->doFrontendLogout();

		$I = new UserManagerJoomla3Steps($scenario);
		$I->wantTo('Delete acccunt userName');
		$I->deleteUser($this->firstName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function businessCreatePrivateNo(AcceptanceTester $I, $scenario)
	{
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->comment('want to check bussines ');
		$I->wantToTest('Test one page checkout with business with user login is customerBussinesInformationSecond[firstName]');

		$I->onePageCheckout($this->customerBussinesInformationSecond['firstName'], $this->customerBussinesInformationSecond['firstName'], $this->productName, $this->categoryName, $this->subtotal, $this->total, $this->customerBussinesInformationSecond, 'business', 'yes');
		$I->doFrontendLogout();

		$I->doAdministratorLogin();
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
		$I->wantTo('checkout with user with login ');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutOnePageWithLogin($this->userName, $this->password, $this->productName, $this->categoryName, $this->shippingWithVat, $this->total);
		$I->doFrontendLogout();

		$I->comment('Test one page checkout with private user');
		$I->wantToTest('Test one page checkout with private and do not login is customerInformationSecond[firstName]');
		$I->onePageCheckout('admin', 'admin', $this->productName, $this->categoryName, $this->subtotal, $this->total, $this->customerInformationSecond, 'private', 'no');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function clearUpDatabase(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Test Order delete by user  in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder($this->customerInformation['firstName']);
		$I->deleteOrder($this->customerBussinesInformation['firstName']);
		$I->deleteOrder($this->customerBussinesInformationSecond['firstName']);
		$I->deleteOrder($this->customerInformationSecond['firstName']);

		$I->wantToTest('Delete all users');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation['firstName']);
		$I->deleteUser($this->customerBussinesInformation['firstName']);
		$I->deleteUser($this->customerInformationSecond['firstName']);
		$I->deleteUser($this->customerBussinesInformationSecond['firstName']);
		$I->deleteUser($this->firstName);
	}
}