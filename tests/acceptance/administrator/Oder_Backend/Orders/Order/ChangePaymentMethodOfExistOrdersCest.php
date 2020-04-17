<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class ChangePaymentMethodOfExistOrdersCest
 * @since 3.0.2
 */
class ChangePaymentMethodOfExistOrdersCest
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
	protected $randomCategoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $randomProductName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productStart;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productEnd;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $password;

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
	 * @var string
	 * @since 3.0.2
	 */
	protected $userNameDelete;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $searchOrder;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $paymentMethod;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $paymentPayPal;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $cartSetting;

	/**
	 * ChangePaymentMethodOfExistOrdersCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->randomCategoryName  = $this->faker->bothify('CategoryTesting ??####?');
		$this->randomProductName   = $this->faker->bothify('TestingProductManagement ??####?');
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
		$this->paymentPayPal       = 'PayPal';

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
	public function ChangePaymentMethodOnOrderDetail(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');

		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('I want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->productStart, $this->productEnd);

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Add products in cart');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->addProductToCartWithBankTransfer($this->randomProductName, $this->randomCategoryName, $this->randomProductPrice, $this->userName, $this->password );
		$I->wantTo('change payment method');
		$I->changePaymentMethodOnBackend($this->firstName, $this->paymentMethod, $this->paymentPayPal);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I->wantTo('Disable one page checkout');
		$this->cartSetting["onePage"] = 'no';
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->randomProductName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);

		$I->wantTo('Deletion of User in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userNameDelete, 'true');

		$I->wantTo('Deletion of Order Total Discount in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->searchOrder);
	}
}