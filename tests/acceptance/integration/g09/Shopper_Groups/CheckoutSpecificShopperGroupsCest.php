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
	 * CheckoutSpecificShopperGroupsCest constructor.
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->ProductName         = 'ProductName' . rand(100, 999);
		$this->CategoryName        = "CategoryName" . rand(1, 100);
		$this->minimumPerProduct   = 1;
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(100, 1000);
		$this->discountStart       = "2016-12-12";
		$this->discountEnd         = "2017-05-27";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;
		$this->subtotal            = "DKK 100,00";
		$this->shippingWithVat     = "DKK 10,00";
		$this->Total               = "DKK 110,00";

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
	 */
	public function checkoutWithSpecificShopperGroups(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->wantTo('Test enable Stockroom in Administrator');
		$I = new Configuration\ConfigurationSteps($scenario);
		$I->wantTo('Test off Stockroom in Administrator');
		$I->featureOffStockRoom();

		$I->wantTo('Enable PayPal');
		$I->enablePlugin('PayPal');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);


		$I = new ShopperGroupManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addShopperGroups($this->shopperName, $this->shopperType, $this->customerType, $this->shopperGroupPortal, $this->CategoryName, $this->shipping, $this->shippingRate, $this->shippingCheckout, $this->catalog, $this->showVat, $this->showPrice, $this->enableQuotation, 'save');


		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Test Checkout Product with specific Shopper Group');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutSpecificShopperGroup($this->userName, $this->password, $this->ProductName, $this->CategoryName, $this->shippingWithVat, $this->Total);

		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function clearData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete discount total');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName);
	}
}
