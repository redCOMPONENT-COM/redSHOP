<?php
/**
 * Compare Products at frontend
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;

class CompareProductsCest
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
		$this->productNumber = $this->faker->numberBetween(999, 9999);
		$this->productPrice = 100;

		$this->subtotal="DKK 100,00";
		$this->Discount ="DKK 50,00";
		$this->Total="DKK 50,00";

		$this->productNameCompares = 'ProductName' . rand(100, 999);
		$this->productNumberCompares = $this->faker->numberBetween(999, 9999);
		$this->productPriceCompares = 80;

		$this->productPriceDKK="DKK 100,00";
		$this->productPriceComparesDKK="DKK 80,00";

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

	public function compareProducts(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->ProductName, $this->CategoryName, $this->productNumber, $this->productPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);


		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->productNameCompares, $this->CategoryName, $this->productNumberCompares, $this->productPriceCompares, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);


		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->comparesProducts($this->ProductName,$this->productNameCompares,$this->CategoryName,$this->productPriceDKK,$this->productPriceComparesDKK);

	}

	public function deleteDataEnd(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->ProductName);
		$I->deleteProduct($this->productNameCompares);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->CategoryName);
	}

}