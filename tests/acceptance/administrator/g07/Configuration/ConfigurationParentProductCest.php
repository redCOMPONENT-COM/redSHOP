<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use Configuration\ProductsConfigurationSteps;

/**
 * Class ConfigurationParentProductCest
 * @since 2.1.4
 */
class ConfigurationParentProductCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.4
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $productNameParent;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $productName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomCategoryName;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $productNumber;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $price;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $productNumber1;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $optionYes;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $optionNo;

	/**
	 * ConfigurationParentProductCest constructor.
	 * @since 2.1.4
	 */
	public function __construct()
	{
		$this->faker              = Faker\Factory::create();
		$this->productNameParent  = $this->faker->bothify('Product name ?##?');
		$this->productName        = $this->faker->bothify('Testing productName_?##?');
		$this->randomCategoryName = $this->faker->bothify('Category Demo ?##?');
		$this->productNumber      = $this->faker->numberBetween(999, 9999);
		$this->price              = $this->faker->numberBetween(99, 199);
		$this->productNumber1     = $this->faker->numberBetween(99, 199);
		$this->optionYes          = "Yes";
		$this->optionNo           = "No";
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function checkProductPurchaseParentYes(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo ('I Want To Configuration Product Accessory Yes');
		$I->configProductPurchaseParent($this->optionYes);

		$I->wantTo('Create Category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySaveClose($this->randomCategoryName);

		$I->wantTo('Create Products ');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Products');
		$I->createProductSaveClose($this->productNameParent, $this->randomCategoryName, $this->productNumber, $this->price);

		$I->wantTo('Create a Category');
		$I->createProductChild($this->productName, $this->randomCategoryName, $this->productNumber1, $this->price, $this->productNameParent);

		$I = new ProductsConfigurationSteps($scenario);
		$I->checkProductConfigPurchaseParent($this->randomCategoryName, $this->productNameParent, $this->optionYes);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function checkProductPurchaseParentNo(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo ('I Want To Configuration Product Accessory No');
		$I->configProductPurchaseParent($this->optionNo);

		$I = new ProductsConfigurationSteps($scenario);
		$I->checkProductConfigPurchaseParent($this->randomCategoryName, $this->productNameParent, $this->optionNo);

		$I->wantTo('I Want To Delete Product Child');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProductChild($this->productName);

		$I->wantTo('I Want To Delete Product');
		$I->deleteProduct($this->productNameParent);

		$I->wantTo('I Want To Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);
	}
}
