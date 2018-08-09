<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
/**
 * Class ProductNotForSaleOnFrontendCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4
 */
class ProductNotForSaleOnFrontendCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->productName = 'ProductName' . rand(100, 999);
		$this->categoryName = "CategoryName" . rand(1, 100);
		$this->minimumPerProduct = 1;
		$this->minimumQuantity = 1;
		$this->maximumQuantity = $this->faker->numberBetween(100, 1000);
		$this->ProductNumber = $this->faker->numberBetween(999, 9999);
		$this->price = 100;
		$this->saleYes = 'Yes';
		$this->saleNo = 'No';
		$this->showPriceYes = 'Yes';
		$this->showPriceNo = 'No';
        $this->priceFrontend = "DKK 100,00";
	}

	/**
	 * @param AcceptanceTester $I
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 *
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function productNotForSaleOnFrontend(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('create category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantTo('create product with stockroom in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductNotForSale($this->productName, $this->ProductNumber, $this->price, $this->categoryName);

		$I->wantTo("I want to check product have show price in frontend");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->productFrontend($this->categoryName, $this->productName, $this->showPriceYes, $this->priceFrontend);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}