c<?php
/**
 * Checkout product with stockroom
 *
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

class CheckoutWithStockroomCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();

		//create category
		$this->randomCategoryName = $this->faker->bothify('TestingCategory ?##');

		//create product
		$this->productName     = 'Testing ProductManagement' . rand(99, 999);
		$this->productNumber   = rand(999, 9999);
		$this->productPrice    = 24;
		$this->quantityInStock = 1;
		$this->preOrder        = 0;

		$this->subtotal = "DKK 24,00";
		$this->Total    = "DKK 24,00";
	}

	/**
	 * @param $scenario
	 *
	 * step1 : clear all database .
	 * step2 : create configuration and start stockroom
	 * step3 : create category
	 * step4 : create product add quantity in stockroom is 1 and preorder is 0
	 * step5 : goes on frontend and add to cart 2 times
	 * step6 : webpage show out of stock
	 * step7 : check value at cart for make sure price is correct
	 * step8 : delete all data
	 */

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function checkProductInsideStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Start stockroom ');
		$I->featureUsedStockRoom();
		$I->see(\ConfigurationPage::$namePage, \ConfigurationPage::$selectorPageTitle);

		$I->wantTo('create category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->randomCategoryName);

		$I->wantTo('create product with stockroom in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductInStock($this->productName, $this->productNumber, $this->productPrice, $this->randomCategoryName, $this->quantityInStock, $this->preOrder);

		$I->wantTo('create product with stockroom in Administrator');
		$I = new ProductCheckoutManagerJoomla3Steps($scenario);
		$I->checkProductInsideStockRoom($this->productName, $this->randomCategoryName, $this->subtotal, $this->Total);

	}

	/**
	 * Function delete data
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function clearUp(AcceptanceTester $I, $scenario)
	{
		$I = new ConfigurationSteps($scenario);
		$I->wantTo('Stop stockroom ');
		$I->featureOffStockRoom();
	}
}