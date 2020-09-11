<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\CheckoutChangeQuantityProductSteps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class CheckoutChangeQuantityCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.0
 */
class CheckoutChangeQuantityProductCest
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
	 * @since 2.1.3
	 */
	protected $cartSetting;

	public function __construct()
	{
		//Product & Category
		$this->faker              = Faker\Factory::create();
		$this->productName         = $this->faker->bothify('Product Name ?##?');;
		$this->categoryName        = $this->faker->bothify('Category Name ?##?');
		$this->subtotal            = "DKK 1.000,00";
		$this->total               = "DKK 1.000,00";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;

		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'no',
			"cartTimeOut"       => 'no',
			"enabledAjax"       => 'no',
			"defaultCart"       => null,
			"buttonCartLead"    => 'Back to current view',
			"onePage"           => 'yes',
			"showShippingCart"  => 'no',
			"attributeImage"    => 'no',
			"quantityChange"    => 'yes',
			"quantityInCart"    => 3,
			"minimumOrder"      => 0,
			"enableQuotation"   => 'no'
		);

		//User
		$this->userName     = $this->faker->bothify('QuantityChangeCest ?##?');
		$this->password     = $this->faker->bothify('123456');
		$this->email        = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group        = 'Super User';
		$this->firstName    = $this->faker->bothify('QuantityChangeCest FN ?##?');
		$this->lastName     = "LastName";
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Step1 : Enable Configuration change (One step checkout, quantity, shipping default same address)
	 * Step2 : Create category
	 * Step3 : Create product have price is 100
	 * Step4 : Create User
	 * Step4 : Goes on frontend
	 * Step5 : Click "Add to cart", change, checkout for product
	 * Step6 : Delete data
	 * Step7 : Disable Configuration change
	 *
	 * @param  AcceptanceTester $I
	 * @param  mixed $scenario
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function changeQuantityInCart(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Enable Quantity Change in Cart');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);

		$I->wantTo('I want to login in site page');
		$I->doFrontEndLogin($this->userName, $this->password);

		$I->wantTo('I want go to Product tab, Choose Product and Add to cart');
		$I = new CheckoutChangeQuantityProductSteps($scenario);
		$I->checkoutChangeQuantity($this->categoryName, $this->productName, $this->total);

		$I->wantTo('I want to login Site page with user just create');
		$I->doFrontendLogout();

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Delete account in redSHOP and Joomla');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName, false);

		$I->wantTo("Disable One page checkout");
		$I = new ConfigurationSteps($scenario);
		$this->cartSetting["onePage"] = 'no';
		$I->cartSetting($this->cartSetting);
	}
}