<?php
/**
 * Checkout with mass discount
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\MassDiscountManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

class MassDiscountCheckoutCest
{
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->ProductName            = 'ProductName' . rand(100, 999);
		$this->MassDiscountName       = 'MassDiscount' . rand(10, 100);
		$this->MassDiscountNameSave   = 'MassDiscountSave' . rand(10, 1000);
		$this->MassDiscountNameEdit   = 'Edit' . $this->MassDiscountName;
		$this->CategoryName           = "CategoryName" . rand(1, 100);
		$this->ManufactureName        = "ManufactureName" . rand(1, 10);
		$this->MassDiscountAmoutTotal = 90;
		$this->MassDiscountPercent    = 0.3;
		$this->minimumPerProduct      = 1;
		$this->minimumQuantity        = 1;
		$this->maximumQuantity        = $this->faker->numberBetween(100, 1000);
		$this->discountStart          = '';
		$this->discountEnd            = '';
		$this->randomProductNumber    = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice     = 100;

		$this->subtotal = "DKK 10,00";
		$this->Discount = "";
		$this->Total    = "DKK 10,00";

		//Create User
		$this->userName = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password = $this->faker->bothify('Password ?##?');
		$this->email = $this->faker->email;
		$this->shopperGroup = 'Default Private';
		$this->group = 'Super Users';
		$this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
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
	 * (Checkout with product discount and don't show shipping cart at cart checkout )
	 * Step1 : create category
	 * Step2 : create product
	 * Step3 : Create Mass Discount
	 * Step4 : Goes on frontend and checkout with this product
	 * Step5 : Delete all data
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function checkoutWithMassDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->CategoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscount($this->MassDiscountName, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);

		$I = new \AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->wantTo("I want to create user");
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);

		$I->wantTo('I want to login in site page');
		$I->doFrontEndLogin($this->userName, $this->password);

		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDiscount($this->ProductName, $this->CategoryName, $this->subtotal, $this->Discount, $this->Total);
	}


	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->deleteMassDiscountOK($this->MassDiscountName);

		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->ProductName);

		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->CategoryName);
	}
}
