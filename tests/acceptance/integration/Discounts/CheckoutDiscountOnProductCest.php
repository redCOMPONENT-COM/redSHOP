<?php
/**
 * Checkout with product discount on price
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\DiscountProductSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

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
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $productName;

	/**
	 * @var string
	 */
	public $categoryName;

	/**
	 * @var integer
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 */
	public $discountStart;

	/**
	 * @var string
	 */
	public $discountEnd;

	/**
	 * @var integer
	 */
	public $randomProductNumber;

	/**
	 * @var integer
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 */
	public $subtotal;

	/**
	 * @var string
	 */
	public $discount;

	/**
	 * @var string
	 */
	public $total;

	/**
	 * @var integer
	 */
	public $productPrice;

	/**
	 * @var integer
	 */
	public $condition;

	/**
	 * @var integer
	 */
	public $type;

	/**
	 * @var integer
	 */
	public $discountAmount;

	/**
	 * @var string
	 */
	public $groupName;

	/**
	 * CheckoutDiscountOnProductCest constructor.
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->productName         = 'ProductName' . rand(100, 999);
		$this->categoryName        = "CategoryName" . rand(1, 100);
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
	}

//	/**
//	 * Method for clean data.
//	 *
//	 * @param   mixed $scenario Scenario
//	 *
//	 * @return  void
//	 */
//	public function deleteData($scenario)
//	{
//		$I = new RedshopSteps($scenario);
//		$I->clearAllData();
//	}

	/**
	 * Method run before test.
	 *
	 * @param   AcceptanceTester $I
	 *
	 * @return  void
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

		$I->wantTo('Checkout with discount at total');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDiscount($this->productName, $this->categoryName, $this->subtotal, $this->discount, $this->total);
	}

	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete all data');
		$I= new RedshopSteps($scenario);
		$I->clearAllData();
	}
}