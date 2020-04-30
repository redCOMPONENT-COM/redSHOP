<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

/**
 * Class ProductNotForSaleOnFrontendCest
 * @since 1.4.0
 */
class ProductNotForSaleOnFrontendCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $categoryName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $maximumQuantity;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $price;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $showPriceYes;

	/**
	 * ProductNotForSaleOnFrontendCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker             = Faker\Factory::create();
		$this->productName       = $this->faker->bothify('Product name ?###?');
		$this->categoryName      = $this->faker->bothify('Category name ?###?');
		$this->minimumPerProduct = 1;
		$this->minimumQuantity   = 1;
		$this->maximumQuantity   = $this->faker->numberBetween(100, 1000);
		$this->productNumber     = $this->faker->numberBetween(999, 9999);
		$this->price             = $this->faker->numberBetween(100, 999);
		$this->showPriceYes      = 'Yes';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param   \Codeception\Scenario $scenario Scenario
	 * @return  void
	 * @throws  Exception
	 * @since 1.4.0
	 */
	public function productNotForSaleOnFrontend(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('create category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySaveClose($this->categoryName);

		$I->wantTo('create product with stockroom in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductNotForSale($this->productName, $this->productNumber, $this->price, $this->categoryName);

		$I->wantTo("I want to check product have show price in frontend");
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->productFrontend($this->categoryName, $this->productName, $this->showPriceYes, $this->price);

		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}
