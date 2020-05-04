<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\PriceProductManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

/**
 * Class PriceProductCest
 * @since 1.4.0
 */
class PriceProductCest
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
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomProductPrice;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomPriceDiscount;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomPriceLess;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $randomPriceDiscountMoreThan;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $quantityStart;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $quantityEnd;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $priceDefault;

	/**
	 * PriceProductCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker                       = Faker\Factory::create();
		$this->productName                 = $this->faker->bothify('Product name ?###?');
		$this->categoryName                = $this->faker->bothify('Category name ?###?');
		$this->randomProductNumber         = rand(999, 9999);
		$this->randomProductPrice          = rand(11, 100);
		$this->randomPriceDiscount         = rand(5, 10);
		$this->randomPriceLess             = rand(1, 4);
		$this->randomPriceDiscountMoreThan = rand(101, 200);
		$this->quantityStart               = 10;
		$this->quantityEnd                 = 100;
		$this->priceDefault                = 100;
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
	 * @param CategoryManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function testProductAdministrator(CategoryManagerJoomla3Steps $I)
	{
		$I->wantTo('Create Category in Administrator');
		$I->addCategorySave($this->categoryName);
	}

	/**
	 * @param ProductManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function createProductSaveClose(ProductManagerJoomla3Steps $I)
	{
		$I->wantTo('Test Product Save Close Manager in Administrator');
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
	}

	/**
	 * Add discount price for product
	 * @param PriceProductManagerJoomla3Steps $I
	 * @throws Exception
	 */
	public function addDiscountPrice(PriceProductManagerJoomla3Steps $I)
	{
		$I->wantTo('Test Change Price of Product in Administrator');
		$I->addDiscountPrice($this->productName, $this->randomPriceDiscount);
		$I->see(PriceProductJoomla3Page::$namePage, PriceProductJoomla3Page::$selectorPageTitle);
	}

	/**
	 * @param PriceProductManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function addDiscountPriceMoreThan(PriceProductManagerJoomla3Steps $I)
	{
		$I->wantTo('Test Change Price of Product in Administrator');
		$I->addDiscountPriceMoreThan($this->productName, $this->randomPriceDiscountMoreThan);
		$I->see(PriceProductJoomla3Page::$namePage, ProductManagerPage::$selectorPageTitle);
	}

	/**
	 * @param PriceProductManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function addPriceLessDiscount(PriceProductManagerJoomla3Steps $I)
	{
		$I->wantTo('Test Change Price of Product in Administrator');
		$I->addPriceLessDiscount($this->productName, $this->randomPriceLess);
		$I->see(PriceProductJoomla3Page::$namePage, ProductManagerPage::$selectorPageTitle);
	}
}