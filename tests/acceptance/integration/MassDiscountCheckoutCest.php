<?php
/**
 * Checkout with mass discount
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\MassDiscountManagerJoomla3Steps;

class MassDiscountCheckoutCest
{
	public function __construct()
	{

		$this->faker = Faker\Factory::create();
		$this->ProductName = 'ProductName' . rand(100, 999);
		$this->MassDiscountName = 'MassDiscount' . rand(10, 100);
		$this->MassDiscountNameSave = 'MassDiscountSave' . rand(10, 1000);
		$this->MassDiscountNameEdit = 'Edit' . $this->MassDiscountName;
		$this->CategoryName = "CategoryName" . rand(1, 100);
		$this->ManufactureName = "ManufactureName" . rand(1, 10);
		$this->MassDiscountAmoutTotal = 90;
		$this->MassDiscountPercent = 0.3;
		$this->minimumPerProduct = 1;
		$this->minimumQuantity = 1;
		$this->maximumQuantity = $this->faker->numberBetween(100, 1000);
		$this->discountStart = "12-12-2016";
		$this->discountEnd = "23-05-2017";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice = 100;

		$this->subtotal="DKK 10,00";
		$this->Discount ="";
		$this->Total="DKK 10,00";
	}


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
		$I->addCategorySave($this->CategoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscount($this->MassDiscountName, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);

		$I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDiscount($this->ProductName,$this->CategoryName,$this->subtotal,$this->Discount,$this->Total);
	}


	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->ProductName);

		$I->wantTo('Delete Category');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->CategoryName);

		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->deleteMassDiscountOK($this->MassDiscountName);
	}
}