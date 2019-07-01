<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ProductsConfigurationSteps;
use Faker\Factory;

/**
 * Class ConfigurationAccessoryProductsCest
 * since 2.1.2
 */
class ConfigurationAccessoryProductsCest
{
	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $productNameAccessories;

	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $productName;

	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $randomCategoryName;

	/**
	 * @var int
	 * since 2.1.2
	 */
	protected $productNumber;

	/**
	 * @var int
	 * since 2.1.2
	 */
	protected $price;

	/**
	 * @var int
	 * since 2.1.2
	 */
	protected $productNumber1;

	/**
	 * ConfigurationAccessoryProductsCest constructor.
	 * since 2.1.2
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->productNameAccessories       = $this->faker->bothify('Testing Accessories_?##?');
		$this->productName                  = $this->faker->bothify('Testing productName_?##?');
		$this->randomCategoryName           = $this->faker->bothify('Category Demo_?##?');
		$this->productNumber                = $this->faker->numberBetween(999, 9999);
		$this->price                        = $this->faker->numberBetween(99, 199);
		$this->productNumber1               = $this->faker->numberBetween(99, 199);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * since 2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * since 2.1.2
	 */
	public function createProductWithAccessoriesYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Accessories Save Manager ');
		$I = new ProductsConfigurationSteps($scenario);
		$I->wantTo ('I Want to add product inside the category');
		$I->configurationProductAccessoryYes();

		$I->wantTo('Create Category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySaveClose($this->randomCategoryName);

		$I->wantTo('Create Category ');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->createProductSaveClose($this->productName, $this->randomCategoryName, $this->productNumber ,$this->price);

		$I->wantTo('Create a Category');
		$I->createProductWithAccessories($this->productNameAccessories, $this->randomCategoryName, $this->productNumber1 ,$this->price,$this->productName );

		$I->wantTo('check add');
		$I = new CheckoutOnFrontEnd($scenario);
		$I->checkAddProductAccessoryYesToCart($this->randomCategoryName, $this->productName,$this->productNameAccessories );

		$I->wantTo('check cart');
		$I->checkCartWithAccessoryProductsYes( $this->productName,$this->productNameAccessories );
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * since 2.1.2
	 */
	public function createProductWithAccessoriesNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Accessories Save Manager in Administrator');
		$I = new ProductsConfigurationSteps($scenario);
		$I->wantTo ('I Want to add product inside the category');
		$I->configurationProductAccessoryNo();

		$I->wantTo('check add');
		$I = new CheckoutOnFrontEnd($scenario);
		$I->checkAddProductAccessoryNoToCart($this->randomCategoryName, $this->productName,$this->productNameAccessories );

		$I->wantTo('I Want to delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productNameAccessories);

		$I->wantTo('I Want to delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('I Want to delete category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);
	}
}
