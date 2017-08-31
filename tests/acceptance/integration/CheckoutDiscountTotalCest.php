<?php
/**
 * Checkout with discount total
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\DiscountManagerJoomla3Steps;
class CheckoutDiscountTotalCest
{
	public function __construct()
	{

		$this->faker = Faker\Factory::create();
		$this->ProductName = 'ProductName' . rand(100, 999);
		$this->CategoryName = "CategoryName" . rand(1, 100);
		$this->minimumPerProduct = 1;
		$this->minimumQuantity = 1;
		$this->maximumQuantity = $this->faker->numberBetween(100, 1000);
		$this->discountStart = "12-12-2016";
		$this->discountEnd = "23-05-2017";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice = 100;

		$this->subtotal="DKK 100,00";
		$this->Discount ="DKK 0,00";
		$this->Total="DKK 50,00";

		$this->discountName = 'Discount' . rand(1, 100);
		$this->amount = 150;
		$this->discountAmount = 50;
		$this->startDate = '13-06-2017';
		$this->endDate = '13-08-2017';
		$this->shopperGroup = 'Default Private';
		$this->discountType = 'Total';
		$this->discountCondition='Lower';

	}


	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Step1 : create category
	 * Step2 : create product have price is 100
	 * Step3 : Create Mass  and create discount lower 150 and have discount is 50
	 * Step4 : Goes on frontend and checkout with this product
	 * Step5 : Delete data
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

		$I->wantTo('Test Discount creation with save and close button in Administrator');
		$I = new DiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscount($this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType,$this->discountCondition);

		$I->wantTo('Checkout with discount at total');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkoutWithDiscount($this->ProductName,$this->CategoryName,$this->subtotal,$this->Discount,$this->Total);

	}


	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->ProductName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->CategoryName);

		$I->wantTo('Delete discount total');
		$I=new DiscountManagerJoomla3Steps($scenario);
		$I->deleteDiscount($this->discountName,$this->amount);
	}
}