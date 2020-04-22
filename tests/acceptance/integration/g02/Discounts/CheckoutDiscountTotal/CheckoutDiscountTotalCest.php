<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\discountSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class CheckoutdiscounttotalCest
 *
 * @since 1.6.0
 */
class CheckoutdiscounttotalCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.6.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $productName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $categoryName;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $discountEnd;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $randomProductNumber;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $subtotal;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $discount;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $total;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $productPrice;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $condition;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $type;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $discountAmount;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $groupName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $password;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $email;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $group;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $discountName;

	/**
	 * @var int
	 * @since 1.6.0
	 */
	protected $amount;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $endDate;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $discountType;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	protected $discountCondition;

	/**
	 * CheckoutdiscounttotalCest constructor.
	 * @since 1.6.0
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->productName         = $this->faker->bothify('Product testing ??##?');
		$this->categoryName        = $this->faker->bothify('Category testing ??##?');
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(100, 1000);
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-23";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;

		$this->subtotal = "DKK 100,00";
		$this->discount = "DKK 50,00";
		$this->total    = "DKK 50,00";

		$this->discountName      = 'discount' . rand(1, 100);
		$this->amount            = 150;
		$this->discountAmount    = 50;
		$this->startDate         = '2017-06-13';
		$this->endDate           = '2017-08-13';
		$this->shopperGroup      = 'Default Private';
		$this->discountType      = 'Total';
		$this->discountCondition = 'Lower';

		//Create User
		$this->userName     = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password     = $this->faker->bothify('Password ?##?');
		$this->email        = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group        = 'Super Users';
		$this->firstName    = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName     = 'Last';
	}

	/**
	 * Run before test.
	 *
	 * @param AcceptanceTester $I
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.6.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Step1 : delete all database
	 * Step1 : create category
	 * Step2 : create product have price is 100
	 * Step3 : Create Mass  and create discount lower 150 and have discount is 50
	 * Step4 : Goes on frontend and checkout with this product
	 * Step5 : Delete data
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws \Exception
	 * @since 1.6.0
	 */
	public function checkoutWithdiscounttotal(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave(
			$this->productName,
			$this->categoryName,
			$this->randomProductNumber,
			$this->randomProductPrice,
			$this->minimumPerProduct,
			$this->minimumQuantity,
			$this->maximumQuantity,
			$this->discountStart,
			$this->discountEnd
		);

		$I->wantTo('Test discount creation with save and close button in Administrator');
		$I = new discountSteps($scenario);
		$I->wantTo('Create a discount');
		$I->adddiscount(
			$this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->discountCondition
		);

		$I->wantTo("I want to create user");
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);

		$I->wantTo('I want to login in site page');
		$I->doFrontEndLogin($this->userName, $this->password);

		$I->wantTo('Checkout with discount at total');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithdiscount($this->productName, $this->categoryName, $this->subtotal, $this->discount, $this->total);

		$I = new discountSteps($scenario);
		$I->wantTo('Delete discount');
		$I->deletediscount($this->discountName);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}