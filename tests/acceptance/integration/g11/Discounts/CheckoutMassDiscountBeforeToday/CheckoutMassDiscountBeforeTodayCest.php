<?php

use AcceptanceTester\MassDiscountManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
class CheckoutMassDiscountsBeforeTodayCest
{
	/**
	 * CheckoutWithMassDiscoutBeforeTodayCest constructor.
	 */
	public function __construct()
	{
		$this->fake                         = Faker\Factory::create();
		$this->randomCategoryName           = 'TestingCategory' . rand(99, 999);
		$this->randomCategoryNameAssign     = 'CategoryAssign' . rand(99, 999);
		$this->randomProductName            = 'TestingProducts' . rand(99, 999);
		$this->randomMassDiscountName           = 'Mass discount' . rand(99, 999);
		$this->minimumPerProduct            = 1;
		$this->minimumQuantity              = 1;
		$this->maximumQuantity              = 5;
		$this->productStart                 = "2018-05-05";
		$this->productEnd                   = "2018-07-08";
		$this->discountStart                = "2018-04-04";
		$this->discountEnd                  = "2018-04-20";
		$this->randomProductNumber          = rand(999, 9999);
		$this->randomProductNumberNew       = rand(999, 9999);
		$this->randomProductPrice           = rand(10, 50);
		$this->discountPriceThanPrice       = 100;
		$this->statusProducts               = 'Product on sale';
		$this->searchCategory               = 'Category';
		$this->newProductName               = 'New-Test Product' . rand(99, 999);
		$this->priceProductForThan          = 10;
		$this->totalAmount                  = $this->fake->numberBetween(100, 999);
		$this->discountAmount               = $this->fake->numberBetween(10, 100);

		$this->userName                   = $this->fake->bothify('ManageUserAdministratorCest ?##?');
		$this->password                   = $this->fake->bothify('Password ?##?');
		$this->email                      = $this->fake->email;
		$this->emailsave                  = $this->fake->email;
		$this->shopperGroup               = 'Default Private';
		$this->group                      = 'Registered';
		$this->firstName                  = $this->fake->bothify('ManageUserAdministratorCest FN ?##?');
		$this->lastName                   = 'Last';
		$this->firstNameSave              = "FirstName";
		$this->lastNameSave               = "LastName";
		$this->emailWrong                 = "email";
		$this->userNameDelete             = $this->firstName;
		$this->searchOrder                = $this->firstName.' '.$this->lastName ;
		$this->paymentMethod             = 'RedSHOP - Bank Transfer Payment';

		//configuration enable one page checkout
		$this->cartSetting = array(
			"addCart"           => 'product',
			"allowPreOrder"     => 'yes',
			"cartTimeOut"       => $this->fake->numberBetween(100, 10000),
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
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param \Codeception\Scenario $scenario
	 * @throws Exception
	 */
	public function addFunction(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
		$I->wantTo('Test User creation with save button in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('I want to add product inside the category');
		$I = new ProductManagerSteps($scenario);
		$I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->productStart, $this->productEnd);

		$I->wantTo('Create Total Discount in Administrator');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->addMassDiscountBeforeToday($this->randomMassDiscountName,  $this->totalAmount,$this->randomCategoryName );
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function addProductToCart(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->wantTo('Add products in cart');
		$I->addProductToCart($this->randomProductName, $this->randomProductPrice, $this->userName, $this->password );
	}

	/**
	 * @param ConfigurationSteps $I
	 * @throws Exception
	 */
	public function checkOrder(ConfigurationSteps $I)
	{
		$I->wantTo('Check Order');
		$I->checkPriceTotal($this->randomProductPrice, $this->searchOrder, $this->firstName, $this->lastName,  $this->randomProductName, $this->randomCategoryName, $this->paymentMethod);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{

		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->cartSetting);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductManagerSteps($scenario);
		$I->deleteProduct($this->randomProductName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);

		$I->wantTo('Deletion of User in Administrator');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->userNameDelete, 'true');

		$I->wantTo('Deletion of Total Discount in Administrator');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->deleteMassDiscountOK($this->randomMassDiscountName);

		$I->wantTo('Deletion of Order Total Discount in Administrator');
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->deleteOrder( $this->searchOrder);
	}
}
