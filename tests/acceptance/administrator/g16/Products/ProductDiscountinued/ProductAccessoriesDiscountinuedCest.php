<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use Configuration\ProductsConfigurationSteps;

/**
 * Class ProductAccessoriesDiscountinuedCest
 * @since 3.0.2
 */
class ProductAccessoriesDiscountinuedCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $randomCategoryName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $price;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $optionYes;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $optionNo;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productID;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productAccessoriesName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productAccessoriesNumber;

	/**
	 * ProductAccessoriesDiscountinuedCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker                    = Faker\Factory::create();
		$this->productName              = $this->faker->bothify('Testing productName ?##?');
		$this->productNumber            = $this->faker->numberBetween(999, 9999);
		$this->randomCategoryName       = $this->faker->bothify('Category Demo ?##?');
		$this->productAccessoriesName   = $this->faker->bothify('Testing productName ?##?');
		$this->productAccessoriesNumber = $this->faker->numberBetween(999, 9999);
		$this->price                    = $this->faker->numberBetween(99, 199);
		$this->optionYes                = "Yes";
		$this->optionNo                 = "No";
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkProductDiscontinueAccessoriesYes(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo ('I Want To Configuration Show Discontinued Products Yes');
		$I->configShowDiscontinuedProducts($this->optionYes);

		$I->wantTo('Create Category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySaveClose($this->randomCategoryName);

		$I->wantTo('Create Products ');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Products');
		$I->createProductSaveClose($this->productAccessoriesName, $this->randomCategoryName, $this->productAccessoriesNumber, $this->price);

		$I->wantTo('Set product Discontinue');
		$I->setProductDiscontinue($this->productAccessoriesName, $this->optionYes);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Products');
		$I->createProductWithAccessories($this->productName, $this->randomCategoryName, $this->productNumber, $this->price, $this->productAccessoriesName);
		$this->productID = $I->getProductId($this->productName);
		$I = new ProductsConfigurationSteps($scenario);
		$I->checkProductDiscontinueAccessories($this->randomCategoryName, $this->productName, $this->productAccessoriesName, $this->productID, $this->optionYes);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkProductDiscontinueAccessoriesNo(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo ('I Want To Configuration Show Discontinued Products No');
		$I->configShowDiscontinuedProducts($this->optionNo);

		$I = new ProductsConfigurationSteps($scenario);
		$I->checkProductDiscontinueAccessories($this->randomCategoryName, $this->productName, $this->productAccessoriesName, $this->productID, $this->optionNo);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want To Delete Product');
		$I->deleteProduct($this->productAccessoriesName);
		$I->deleteProduct($this->productName);

		$I->wantTo('I Want To Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);
	}
}
