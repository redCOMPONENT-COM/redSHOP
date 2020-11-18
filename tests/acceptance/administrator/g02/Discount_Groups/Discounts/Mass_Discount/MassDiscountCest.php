<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\MassDiscountManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

/**
 * Class check page Mass Discount. This page missing create mass discount have start date before end date
 *
 * @since 1.6.0
 */
class MassDiscountCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.6.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $productName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $massDiscountName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $massDiscountNameSave;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $massDiscountNameEdit;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $manufacturerName;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $massDiscountAmountInput;

	/**
	 * @var float
	 * @since 1.6.0
	 */
	public $massDiscountPercentage;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $minimumPerProduct;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $minimumQuantity;

	/**
	 * @var integer
	 * @since 1.6.0
	 */
	public $maximumQuantity;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $discountStart;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $discountEnd;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $randomProductNumber;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $randomProductNumberNew;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $randomProductAttributeNumber;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $randomProductNameAttribute;

	/**
	 * @var string
	 * @since 1.6.0
	 */
	public $randomProductPrice;

	/**
	 * MassDiscountCest constructor.
	 * @since 1.6.0
	 */
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
		$this->productName                  = 'ProductName' . rand(100, 999);
		$this->massDiscountName             = 'MassDiscount' . rand(10, 100);
		$this->massDiscountNameSave         = 'MassDiscountSave' . rand(10, 1000);
		$this->massDiscountNameEdit         = 'Edit' . $this->massDiscountName;
		$this->categoryName                 = "CategoryName" . rand(1, 100);
		$this->manufacturerName             = "ManufactureName" . rand(1, 10);
		$this->massDiscountAmountInput      = $this->faker->numberBetween(10, 100);
		$this->massDiscountPercentage       = 0.3;
		$this->minimumPerProduct            = $this->faker->numberBetween(1, 3);
		$this->minimumQuantity              = $this->faker->numberBetween(10, 100);
		$this->maximumQuantity              = $this->faker->numberBetween(100, 1000);
		$this->discountStart                = "2016-12-12";
		$this->discountEnd                  = "2017-05-23";
		$this->randomProductNumber          = $this->faker->numberBetween(999, 9999);
		$this->randomProductNumberNew       = $this->faker->numberBetween(999, 9999);
		$this->randomProductAttributeNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductNameAttribute   = 'Testing Attribute' . rand(99, 999);
		$this->randomProductPrice           = $this->faker->numberBetween(99, 199);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws \Exception
	 * @since 1.6.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Function to create new Category
	 *
	 * @param   AcceptanceTester $tester   client
	 * @param   mixed            $scenario Scenario
	 * @throws \Exception
	 * @return  void
	 * @since 1.6.0
	 */
	public function createCategory(AcceptanceTester $tester, $scenario)
	{
		$tester->wantTo('Create Category in Administrator');
		$tester = new CategoryManagerJoomla3Steps($scenario);
		$tester->addCategorySave($this->categoryName);

		$tester = new ProductManagerJoomla3Steps($scenario);
		$tester->wantTo('I Want to add product inside the category');
		$tester->createProductSave(
			$this->productName,
			$this->categoryName,
			$this->randomProductNumber,
			$this->randomProductPrice,
			$this->minimumPerProduct,
			$this->minimumQuantity,
			$this->maximumQuantity,
			$this->discountStart,
			$this->discountEnd
		);

		$tester = new MassDiscountManagerJoomla3Steps($scenario);
		$tester->wantTo('Test check add Mass discount ');
		$tester->addMassDiscount(
			$this->massDiscountName, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName
		);

		$tester->wantTo(' add Mass discount with Save and Close in Administrator');
		$tester = new MassDiscountManagerJoomla3Steps($scenario);
		$tester->addMassDiscountSaveClose(
			$this->massDiscountNameSave, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd,
			$this->categoryName, $this->productName
		);

		$tester->wantTo('Test check add Mass missing discount ');
		$tester->addMassDiscountMissingAllFields();

		$tester->wantTo('check cancel button ');
		$tester->checkCancelButton();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.6.0
	 */
	public function addMassDiscountBadCases(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount missing name in Administrator');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountMissingName($this->massDiscountAmountInput, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName);

		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountMissingAmount($this->massDiscountName, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName);

		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountMissingProducts($this->massDiscountName, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.6.0
	 */
	public function editMassDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' Edit Name Mass discount with save in Administrator');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->editMassDiscount($this->massDiscountName, $this->massDiscountNameEdit);

		$I->wantTo('Test check add Mass discount ');
		$I->editButtonMassDiscountSave($this->massDiscountNameEdit, $this->massDiscountName);

		$I->wantTo('Test check add Mass discount ');
		$I->checkCloseButton($this->massDiscountName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.6.0
	 */
	public function deleteMassDiscountCancelYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' delete Mass discount in Administrator');
		$I = new MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->deleteMassDiscountCancel($this->massDiscountName);

		$I->wantTo('Test check delete Mass discount ');
		$I->deleteMassDiscountOK($this->massDiscountName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.6.0
	 */
	public function addMassDiscountForDeleteAll(AcceptanceTester $I, $scenario)
	{
		$I->wantTo(' add Mass discount with Save and Close in Administrator');
		$I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
		$I->wantTo('Test check add Mass discount ');
		$I->addMassDiscountSaveClose($this->massDiscountName, $this->massDiscountAmountInput, $this->discountStart, $this->discountEnd, $this->categoryName, $this->productName);
		$I->wantTo('Test check add Mass discount ');
		$I->deleteAllMassDiscountOK($this->massDiscountName);
	}
}