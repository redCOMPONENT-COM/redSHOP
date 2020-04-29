<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\DiscountSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps;

/**
 * Class CheckoutWithTotalDiscoutBeforeTodayCest
 * @since 2.1.0
 */
class CheckoutWithTotalDiscoutBeforeTodayCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $productName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $categoryName;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $discountEnd;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $randomProductNumber;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $subtotal;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $discount;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $total;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $productPrice;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $condition;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $type;

	/**
	 * @var integer
	 * @since 2.1.0
	 */
	public $discountAmount;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public $groupName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $password;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $email;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $shopperGroup;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $group;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $firstName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $randomDiscountName;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $productStart;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $productEnd;

	/**
	 * @var int
	 * @since 2.1.0
	 */
	protected $totalAmount;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $userNameDelete;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $searchOrder;

	/**
	 * @var string
	 * @since 2.1.0
	 */
	protected $paymentMethod;

	/**
	 * @var array
	 * @since 2.1.0
	 */
	protected $cartSetting;

	/**
	 * CheckoutWithMassDiscoutBeforeTodayCest constructor.
	 * @since 2.1.0
	 */
	public function __construct()
	{
		$this->faker                         = Faker\Factory::create();
		$this->categoryName           = 'TestingCategory' . rand(99, 999);
		$this->productName            = 'TestingProducts' . rand(99, 999);
		$this->randomDiscountName           = 'discount total order ' . rand(99, 999);
		$this->minimumPerProduct            = 2;
		$this->minimumQuantity              = 2;
		$this->maximumQuantity              = 5;
		$this->productStart                 = "2018-05-05";
		$this->productEnd                   = "2018-07-08";
		$this->discountStart                = "2018-04-04";
		$this->discountEnd                  = "2018-04-20";
		$this->randomProductNumber          = rand(999, 9999);
		$this->randomProductPrice           = rand(9, 19);
		$this->totalAmount                  = $this->faker->numberBetween(100, 999);
		$this->discountAmount               = $this->faker->numberBetween(10, 100);

		$this->userName                   = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password                   = $this->faker->bothify('Password ?##?');
		$this->email                      = $this->faker->email;
		$this->shopperGroup               = 'Default Private';
		$this->group                      = 'Registered';
		$this->firstName                  = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName                   = 'Last';
	
		$this->userNameDelete             = $this->firstName;
		$this->searchOrder                = $this->firstName.' '.$this->lastName ;
		$this->paymentMethod             = 'RedSHOP - Bank Transfer Payment';

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
	 * Method run before test.
	 *
	 * @param   AcceptanceTester $I
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param \Codeception\Scenario $scenario
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function addFunction(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->wantTo('Disable PayPal');
		$I->disablePlugin('PayPal');

		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I want to add product inside the category');
		$I = new ProductManagerSteps($scenario);
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->productStart, $this->productEnd);

		$I->wantTo('Create Total Discount in Administrator');
		$I = new DiscountSteps($scenario);
		$I->addTotalDiscountSaveClose($this->randomDiscountName,  $this->totalAmount, 'Higher', 'Total', $this->discountAmount, $this->discountStart, $this->discountEnd, $this->shopperGroup);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function addProductToCart(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->wantTo('Add products in cart');
		$I->addProductToCart($this->productName, $this->randomProductPrice, $this->userName, $this->password );
	}

	/**
	 * @param ConfigurationSteps $I
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function checkOrder(ConfigurationSteps $I)
	{
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->randomProductPrice, $this->searchOrder, $this->firstName, $this->lastName,  $this->productName, $this->categoryName, $this->paymentMethod);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.0
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductManagerSteps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Deletion of User in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userNameDelete, 'true');

		$I->wantTo('Deletion of Total Discount in Administrator');
		$I = new DiscountSteps($scenario);
		$I->deleteDiscount($this->randomDiscountName);

		$I->wantTo('Deletion of Order Total Discount in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->searchOrder);
	}
}
