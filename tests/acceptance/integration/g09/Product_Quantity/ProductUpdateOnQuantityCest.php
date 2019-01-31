<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ProductUpdateOnQuantitySteps;

/**
 * Class ProductUpdateOnQuantityCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1
 */
class ProductUpdateOnQuantityCest
{
	/**
	 * @var   Generator
	 */
	protected $faker;

	/**
	 * @var string
	 */
	protected $menuItem;

	/**
	 * @var string
	 */
	protected  $menuCategory;

	/**
	 * @var string
	 */
	protected  $nameProduct;

	/**
	 * @var int
	 */
	protected  $quantity;



	public function __construct()
	{
		$this->faker           = Faker\Factory::create();

		$this->menuItem        = 'Cart';
		$this->menuCategory    = 'redSHOP';

		$this->nameProduct     = $this->faker->bothify('Product Name ?##?');;
		$this->quantity        = 10;
		$this->categoryName    = $this->faker->bothify('Category Name ?##?');
		$this->subtotal        = "DKK 1.000,00";
		$this->total           = "DKK 1.000,00";
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;
	}


	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @throws Exception
	 */
	public function _before(ProductUpdateOnQuantitySteps $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @throws Exception
	 */
	public function createMenuItem(ProductUpdateOnQuantitySteps $I)
	{
		$I->wantTo("Menu item cart in front end");
		$I->createNewMenuItem($this->menuItem, $this->menuCategory, $this->menuItem);
	}

	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function addToCartWithProductUpdateQuantity(ProductUpdateOnQuantitySteps $I,$scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->nameProduct, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I->wantToTest("Review product");
		$I = new OrderManagerJoomla3Steps($scenario);
		$I->checkReview($this->nameProduct);

		$I = new ProductUpdateOnQuantitySteps($scenario);
		$I->checkProductUpdateQuantity($this->nameProduct,$this->quantity,$this->menuItem,$this->total);
	}
}