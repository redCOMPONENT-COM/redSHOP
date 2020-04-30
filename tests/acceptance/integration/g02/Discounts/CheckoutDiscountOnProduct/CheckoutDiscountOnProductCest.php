<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\DiscountProductSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
/**
 * Class CheckoutDiscountOnProductCest
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.6.0
 */
class CheckoutDiscountOnProductCest
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
	 * CheckoutDiscountOnProductCest constructor.
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
		$this->subtotal            = "DKK 50,00";
		$this->discount            = "";
		$this->total               = "DKK 50,00";
		$this->productPrice        = 50;
		$this->condition           = "Higher";
		$this->type                = "Percentage";
		$this->discountAmount      = 50;
		$this->groupName           = "Default Private";
		$this->userName            = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password            = $this->faker->bothify('Password ?##?');
		$this->email               = $this->faker->email;
		$this->shopperGroup        = 'Default Private';
		$this->group               = 'Super Users';
		$this->firstName           = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName            = 'Last';
	}

	/**
	 * Method run before test.
	 *
	 * @param   AcceptanceTester $I
	 *
	 * @return  void
	 * @since 1.6.0
	 * @throws \Exception
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}


	/**
	 * Step1 : create category
	 * Step2 : create product have price is 100
	 * Step3 : Create Mass  and create discount higher 50 and have discount is 50 percentage
	 * Step4 : Goes on frontend and checkout with this product (when run configuration we don't show shipping inside cart)
	 * Step5 : Delete data
	 *
	 * @param  AcceptanceTester $I
	 * @param  mixed            $scenario
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.6.0
	 */
	public function checkoutOnProductPrice(AcceptanceTester $I, $scenario)
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

		$I = new DiscountProductSteps($scenario);
		$I->addDiscountToday($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->categoryName, $this->groupName);

		$I->wantTo("I want to create user");
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);

		$I->wantTo('I want to login in site page');
		$I->doFrontEndLogin($this->userName, $this->password);

		$I->wantTo('Checkout with discount at total');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDiscount($this->productName, $this->categoryName, $this->subtotal, $this->discount, $this->total);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}