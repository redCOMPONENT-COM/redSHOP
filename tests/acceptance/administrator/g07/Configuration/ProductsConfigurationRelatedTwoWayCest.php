<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Faker\Factory;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use Configuration\ProductsConfigurationSteps;
use AcceptanceTester\ProductManagerJoomla3Steps;

/**
 * Class ProductsConfigurationCest
 * @since 2.1.2
 */
class ProductsConfigurationRelatedTwoWayCest
{
	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $randomCategoryName;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $randomProductPrice;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $productNumber;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	protected $productRelated;

	/**
	 * @var int
	 * @since 2.1.2
	 */
	protected $productNumberRelated;

	/**
	 * ProductsConfigurationCest constructor.
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
		$this->randomCategoryName           = $this->faker->bothify('Category Demo_?##?');
		$this->randomProductPrice           = $this->faker->numberBetween(99, 199);
		$this->productName                  = $this->faker->bothify('Testing product name?##?');
		$this->productNumber                = $this->faker->numberBetween(999, 9999);
		$this->productRelated               = $this->faker->bothify('Testing product related name?##?');
		$this->productNumberRelated         = $this->faker->numberBetween(999, 9999);
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
	public function checkConfigurationProductRelatedTwoWayYes(AcceptanceTester $I,$scenario)
	{
		$I->wantTo('Test enable Related');
		$I = new ConfigurationSteps($scenario);
		$I->checkConfigurationProductRelated('Yes');

		$I->wantTo('Create a Category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySaveClose($this->randomCategoryName);

		$I->wantTo('Create Products ');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Products');
		$I->createProductSaveClose($this->productName, $this->randomCategoryName, $this->productNumber ,$this->randomProductPrice);

		$I->wantTo('Create Product with Related');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductWithRelated($this->productRelated, $this->randomCategoryName, $this->productNumberRelated, $this->randomProductPrice, $this->productName );

		$I->wantTo('I Want to check Product With Related');
		$I = new ProductsConfigurationSteps($scenario);
		$I->wantTo('I Want to check Product With Related');
		$I->checkConfigurationProductRelated($this->randomCategoryName, $this->productName, $this->productRelated,'Yes');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function checkConfigurationProductRelatedTwoWayNo(AcceptanceTester $I,$scenario)
	{
		$I->wantTo('Test enable related in Configuration Tow Way ');
		$I = new ConfigurationSteps($scenario);
		$I->checkConfigurationProductRelated('No');

		$I->wantTo('I Want to check Product With Related');
		$I = new ProductsConfigurationSteps($scenario);
		$I->wantTo('I Want to check Product With Related');
		$I->checkConfigurationProductRelated($this->randomCategoryName,  $this->productName, $this->productRelated, 'No');

		$I->wantTo('I Want to delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productRelated);

		$I->wantTo('I Want to delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('I Want to delete category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);
	}
}
