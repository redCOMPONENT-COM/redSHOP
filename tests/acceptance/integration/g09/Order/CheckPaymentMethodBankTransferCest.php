<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategorySteps;
use Configuration\ConfigurationSteps as ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserSteps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderSteps;

/**
 * Class CheckPaymentMethodBankTransferCest
 * @since 1.4.0
 */
class CheckPaymentMethodBankTransferCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	public $faker;

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
	public $email;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $shopperGroup;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $group;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $firstName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $lastName;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	protected $cartSetting;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $paymentMethod;

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
	protected $productStart;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $productEnd;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $userNameDelete;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $searchOrder;

	/**
	 * CheckPaymentMethodBankTransferCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->categoryName        = $this->faker->bothify('CategoryTesting ??####?');
		$this->productName         = $this->faker->bothify('TestingProductManagement ??####?');
		$this->minimumPerProduct   = 2;
		$this->minimumQuantity     = 2;
		$this->maximumQuantity     = 5;
		$this->productStart        = "2018-05-05";
		$this->productEnd          = "2018-07-08";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = rand(9, 19);
		$this->userName            = $this->faker->bothify('UserAdministratorCest ?##?');
		$this->password            = $this->faker->bothify('Password ?##?');
		$this->email               = $this->faker->email;
		$this->shopperGroup        = 'Default Private';
		$this->group               = 'Registered';
		$this->firstName           = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName            = 'Last';
		$this->userNameDelete      = $this->firstName;
		$this->searchOrder         = $this->firstName.' '.$this->lastName ;
		$this->paymentMethod       = 'RedSHOP - Bank Transfer Payment';

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
	 * @param \Codeception\Scenario $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function checkPaymentMethodOnOrderDetail(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');

		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserSteps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('Create Category in Administrator');
		$I = new CategorySteps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I want to add product inside the category');
		$I = new ProductSteps($scenario);
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->productStart, $this->productEnd);

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Add products in cart');
		$I = new OrderSteps($scenario);
		$I->addProductToCartWithBankTransfer($this->productName, $this->categoryName, $this->randomProductPrice, $this->userName, $this->password );

		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->randomProductPrice, $this->searchOrder, $this->firstName, $this->lastName, $this->productName, $this->categoryName, $this->paymentMethod);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductSteps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategorySteps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Deletion of User in Administrator');
		$I = new UserSteps($scenario);
		$I->deleteUser($this->userNameDelete, 'true');

		$I->wantTo('Deletion of Order Total Discount in Administrator');
		$I = new OrderSteps($scenario);
		$I->deleteOrder( $this->searchOrder);
	}
}
