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
 * @since 2.1.2
 */
class ConfigurationAccessoryProductsCest
{
	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productNameAccessories;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $randomCategoryName;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $price;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $productNumber1;

	/**
	 * ConfigurationAccessoryProductsCest constructor.
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
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
	 * @since 2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function createProductWithAccessoriesYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Accessories Yes');
		$I = new ProductsConfigurationSteps($scenario);
		$I->wantTo ('I Want To Configuration Product Accessory Yes');
		$I->configurationProductAccessory('Yes');

		$I->wantTo('Create Category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySaveClose($this->randomCategoryName);

		$I->wantTo('Create Products ');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Products');
		$I->createProductSaveClose($this->productName, $this->randomCategoryName, $this->productNumber, $this->price);

		$I->wantTo('Create a Category');
		$I->createProductWithAccessories($this->productNameAccessories, $this->randomCategoryName, $this->productNumber1, $this->price, $this->productName);

		$I->wantTo('Check Add To Cart');
		$I = new CheckoutOnFrontEnd($scenario);
		$I->ConfigurationProductAccessoryOnCart($this->randomCategoryName, $this->productName, $this->productNameAccessories, 'Yes');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function createProductWithAccessoriesNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Accessories');
		$I = new ProductsConfigurationSteps($scenario);
		$I->wantTo ('I Want To Configuration Product Accessory No');
		$I->configurationProductAccessory('No');

		$I->wantTo('Check Add');
		$I = new CheckoutOnFrontEnd($scenario);
		$I->ConfigurationProductAccessoryOnCart($this->randomCategoryName, $this->productName, $this->productNameAccessories, 'No');

		$I->wantTo('I Want To Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productNameAccessories);

		$I->wantTo('I Want To Delete Product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('I Want To Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);
	}
}
