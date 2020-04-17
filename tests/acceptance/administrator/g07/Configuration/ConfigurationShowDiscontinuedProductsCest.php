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
 * Class ConfigurationShowDiscontinuedProductsCest.
 * @since 3.0.2
 */
class ConfigurationShowDiscontinuedProductsCest
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
	 * ConfigurationShowDiscontinuedProductsCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker              = Faker\Factory::create();
		$this->productName        = $this->faker->bothify('Testing productName_?##?');
		$this->randomCategoryName = $this->faker->bothify('Category Demo ?##?');
		$this->productNumber      = $this->faker->numberBetween(999, 9999);
		$this->price              = $this->faker->numberBetween(99, 199);
		$this->optionYes          = "Yes";
		$this->optionNo           = "No";
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
	public function checkProductDiscontinueYes(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo ('I Want To Configuration Product Accessory Yes');
		$I->configShowDiscontinuedProducts($this->optionYes);

		$I->wantTo('Create Category ');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySaveClose($this->randomCategoryName);

		$I->wantTo('Create Products ');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Products');
		$I->createProductSaveClose($this->productName, $this->randomCategoryName, $this->productNumber, $this->price);

		$I->wantTo('Set product Discontinue');
		$I->setProductDiscontinue($this->productName, $this->optionYes);

		$I = new ProductsConfigurationSteps($scenario);
		$I->checkProductConfigDiscontinue($this->randomCategoryName, $this->productName, $this->optionYes);
	}

	/**
	 * @param ConfigurationSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkProductDiscontinueNo(ConfigurationSteps $I, $scenario)
	{
		$I->wantTo ('I Want To Configuration Product Accessory No');
		$I->configShowDiscontinuedProducts($this->optionNo);

		$I = new ProductsConfigurationSteps($scenario);
		$I->checkProductConfigDiscontinue($this->randomCategoryName, $this->productName, $this->optionNo);

		$I = new ProductManagerJoomla3Steps($scenario);

		$I->wantTo('I Want To Delete Product');
		$I->deleteProduct($this->productName);

		$I->wantTo('I Want To Delete Category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);
	}
}
