<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\MassDiscountManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class MassDiscountCheckoutCest
 * @since 2.1.4
 */
class MassDiscountCheckoutCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.4
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $massDiscountName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $massDiscountNameSave;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $massDiscountNameEdit;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $manufactureName;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $massDiscountAmountTotal;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $subtotal;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $discount;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $total;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $password;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $email;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $lastName;

	/**
	 * MassDiscountCheckoutCest constructor.
	 * @since 2.1.4
	 */
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->productName            = $this->faker->bothify('ProductName ?##?');
		$this->massDiscountName       = $this->faker->bothify('MassDiscount ?##?');
		$this->massDiscountNameSave   = $this->faker->bothify('MassDiscountSave ?##?');
		$this->massDiscountNameEdit   = 'Edit' . $this->massDiscountNameSave;
		$this->categoryName           = $this->faker->bothify('CategoryName ?##?');
		$this->manufactureName        = $this->faker->bothify('ManufactureName ?##?');
		$this->massDiscountAmountTotal = 90;
		$this->discountStart          = '';
		$this->discountEnd            = '';
		$this->randomProductNumber    = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice     = 100;

		$this->subtotal = "DKK 10,00";
		$this->discount = "";
		$this->total    = "DKK 10,00";

		//Create User
		$this->userName = $this->faker->bothify('User name ?##?');
		$this->password = $this->faker->bothify('Password ?##?');
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Super Users';
		$this->firstName = $this->faker->bothify('first name ?##?');
		$this->lastName = 'Last';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 *(Checkout with product discount and don't show shipping cart at cart checkout )
	 * Step1 : create category
	 * Step2 : create product
	 * Step3 : Create Mass Discount
	 * Step4 : Goes on frontend and checkout with this product
	 * Step5 : Delete all data
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function checkoutWithMassDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscount($this->massDiscountName, $this->massDiscountAmountTotal, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->wantTo("I want to create user");
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);

		$I->wantTo('I want to login in site page');
		$I->doFrontEndLogin($this->userName, $this->password);

		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDiscount($this->productName, $this->categoryName, $this->subtotal, $this->discount, $this->total);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->deleteMassDiscountOK($this->massDiscountName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->productName);

		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->categoryName);
	}
}
