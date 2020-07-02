<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps as ConfigurationSteps;

/**
 * Class CheckoutValidationTermsConditionCest
 * @since 3.0.2
 */
class CheckoutValidationTermsConditionCest
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
	public $categoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $productName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	public $productPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $total;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $subtotal;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $userName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $password;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $email;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $group;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $lastName;

	/**
	 * @var integer
	 * @since 3.0.2
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 * @since 3.0.2
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 * @since 3.0.2
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $discountEnd;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $customerInformation;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $cartSetting;

	/**
	 * CheckoutValidationTermsConditionCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->productName         =  $this->faker->bothify('productName ?####?');
		$this->categoryName        =  $this->faker->bothify('categoryName ?####?');
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(100, 1000);
		$this->discountStart       = "12-12-2016";
		$this->discountEnd         = "23-05-2017";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;
		$this->subtotal            = "DKK 100,00";
		$this->total               = "DKK 100,00";

		$this->customerInformation = array(
			"email"      => $this->faker->email,
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"country"    => "India",
			"state"      => "Karnataka",
			"phone"      => "8787878787"
		);

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
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function CheckoutValidationTermsConditionCest(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Disable paypal plugin');
		$I->disablePlugin('PayPal');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Config Accept Terms Conditions');
		$I->configAcceptTermsConditions('user');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantToTest('Test one page checkout with private with user login is customerInformation[firstName]');
		$I->onePageCheckout($this->customerInformation['firstName'], $this->customerInformation['firstName'], $this->productName, $this->categoryName, $this->subtotal, $this->total, $this->customerInformation, 'private', 'yes');

		$I = new CheckoutOnFrontEnd($scenario);
		$I->checkOutWithoutAcceptTermsConditions($this->productName, $this->categoryName, $this->randomProductPrice);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
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

		$I->wantToTest('Delete all users');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->customerInformation['firstName']);

		$I->wantToTest('Delete a Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantToTest('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}
