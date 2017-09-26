<?php
/**
 * Checkout with product discount on price
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\DiscountProductJoomla3Steps;
use AcceptanceTester\ConfigurationManageJoomla3Steps;
class CheckoutDiscountOnProductCest
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
		$this->subtotal="DKK 50,00";
		$this->Discount ="";
		$this->Total="DKK 50,00";

		$this->productPrice = 50;
		$this->condition = "Higher";
		$this->type = "Percentage";
		$this->GroupName = "Product Name";
		$this->discountAmount = 50;
		$this->groupName = "Default Private";
	}

	public function deleteData($scenario)
	{
		$I= new RedshopSteps($scenario);
		$I->clearAllData();
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}


	/**
	 * Step1 : create category
	 * Step2 : create product have price is 100
	 * Step3 : Create Mass  and create discount higher 50 and have discount is 50 percentage
	 * Step4 : Goes on frontend and checkout with this product (when rung configuration we don't show shipping inside cart)
	 * Step5 : Delete data
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function checkoutOnProductPrice(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);


		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I = new DiscountProductJoomla3Steps($scenario);
		$I->addDiscountToday($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->CategoryName, $this->groupName);
		$I->see(\DiscountProductJ3Page::$messageSaveDiscountSuccess, \DiscountProductJ3Page::$selectorSuccess);

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
	}
}