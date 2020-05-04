<?php
/**
 * Checkout with specific user
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

class CheckoutSpecificShopperGroupsCest
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
	protected $shopperName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperNameSaveClose;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperType;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $customerType;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $shippingRate;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $shippingCheckout;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $catalog;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $showPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $nameShopperEdit;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $idShopperChange;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shipping;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $enableQuotation;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $showVat;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperGroupPortal;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $userName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $password;

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
	protected $updateFirstName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $lastName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductPrice;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $shippingWithVat;

	protected $total;

	/**
	 * CheckoutSpecificShopperGroupsCest constructor.
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->productName         = 'productName' . rand(100, 999);
		$this->categoryName        = "categoryName" . rand(1, 100);
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(100, 1000);
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-27";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;
		$this->shippingWithVat     = "DKK 10,00";
		$this->total               = "DKK 110,00";

		// Create shopper groups
		$this->shopperName        = $this->faker->bothify(' Testing shopper ##??');
		$this->shopperType        = null;
		$this->customerType       = 'Company customer';
		$this->shippingRate       = 10;
		$this->shippingCheckout   = $this->faker->numberBetween(1, 100);
		$this->catalog            = 'Yes';
		$this->showPrice          = 'Yes';
		$this->shipping           = 'yes';
		$this->enableQuotation    = 'yes';
		$this->showVat            = 'no';
		$this->shopperGroupPortal = 'no';

		// Create user
		$this->userName     = $this->faker->bothify('UserName ?##?');
		$this->password     = 'test';
		$this->email        = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group        = 'Administrator';
		$this->firstName    = $this->faker->bothify('FirstName FN ?##?');
		$this->lastName     = 'Last';

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
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Step1 : delete all database
	 * Step1 : create category
	 * Step2 : create product have price is 100
	 * Step3 : Create shopper group and add shipping price is 10
	 * Step4 : Create user belong this groups
	 * Step4 : Goes on frontend and checkout with this user (make user user login )
	 * Step5 : Delete data
	 *
	 * @param   AcceptanceTester      $I
	 * @param   \Codeception\Scenario $scenario
	 * @since 1.4.0
	 * @throws \Exception
	 */
	public function checkoutWithSpecificShopperGroups(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->wantTo('Test enable Stockroom in Administrator');
		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Test off Stockroom in Administrator');
		$I->featureOffStockRoom();

		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);


		$I = new ShopperGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addShopperGroups($this->shopperName, $this->shopperType, $this->customerType, $this->shopperGroupPortal, $this->categoryName, $this->shipping, $this->shippingRate, $this->shippingCheckout, $this->catalog, $this->showVat, $this->showPrice, $this->enableQuotation, 'save');


		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Test Checkout Product with specific Shopper Group');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutSpecificShopperGroup($this->userName, $this->password, $this->productName, $this->categoryName, $this->shippingWithVat, $this->total);

		$I = new ConfigurationSteps($scenario);
		$this->cartSetting["onePage"] = 'no';
		$I->cartSetting($this->cartSetting);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function clearData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete discount total');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
	}
}