<?php
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ConfigurationManageJoomla3Steps;
use AcceptanceTester\WishListFrontendSteps;

class WishListCest
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
		$this->subtotal = "DKK 100,00";
		$this->Discount = "DKK 50,00";
		$this->Total = "DKK 50,00";
		$this->idProduct = "";
		//setting wishlist Enable , requid login , enable login list list
		$this->enableWishListYes = 'yes';
		$this->enableWishListLoginYes = 'yes';
		$this->enableWishlistListYes = 'yes';

		//wishlist frontend
		$this->wishListName = 'WishList'.$this->faker->numberBetween(1,100);
		$this->id = 1;
		$this->idNew = 2;
		$this->nameWishListNew = 'WishListNew'.$this->faker->numberBetween(1,100);
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
	 * Step1 : delete all database
	 * Step1 : create category
	 * Step2 : create product have price is 100
	 * Step3 : Create Mass  and create discount lower 150 and have discount is 50
	 * Step4 : Goes on frontend and checkout with this product
	 * Step5 : Delete data
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function wishListWithoutLogin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$this->idProduct = $I->getIdProduct($this->ProductName);

		$I->wantTo('setting wishlist');
		$I = new ConfigurationManageJoomla3Steps($scenario);
		$I->wishlist($this->enableWishListYes,$this->enableWishListLoginYes,$this->enableWishlistListYes);
	}

	public function wishListLogin(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setting wishlist');
		$I = new WishListFrontendSteps($scenario);
		$I->comment('Client want to add new wishlist ');
		$I->wishListLoginNew($this->ProductName,$this->CategoryName,$this->idNew,$this->idProduct,$this->nameWishListNew);
		$I->comment('Client want to add default wishlist ');
		$I->wishListLogin($this->ProductName, $this->CategoryName, $this->id,$this->idProduct);
	}

	public function clearUpData(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->ProductName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->CategoryName);
	}
}