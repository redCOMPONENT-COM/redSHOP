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
 * Class PromotionAdministratorCest
 * @since 3.0.3
 */
class PromotionAdministratorCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.3
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $categoryName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public $productName;

	/**
	 * @var int
	 * @since 3.0.3
	 */
	public $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.3
	 */
	public $randomProductPrice;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $productAwardsName;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $randomAwardsProductNumber;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $randomAwardsProductPrice;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $startDate;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $endDate;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotionMissingName;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotionStartThanEnd;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotionQuantityAwardLowZero;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotionQuantityConditionLowZero;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotionOrderVolumeLowZero;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $promotionNameEdit;

	/**
	 * @var array
	 * @since 3.0.3
	 */
	protected $promotionNew;

	/**
	 * PromotionAdministratorCest constructor.
	 * @since 3.0.3
	 */
	public function __construct()
	{
		$this->faker                     = Faker\Factory::create();
		$this->categoryName              = $this->faker->bothify('category name ?####?');
		$this->productName               = $this->faker->bothify('product name ?####?');
		$this->randomProductNumber       = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice        = $this->faker->numberBetween(100, 900);
		$this->productAwardsName         = $this->faker->bothify('product name ?####?');
		$this->randomAwardsProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomAwardsProductPrice  = $this->faker->numberBetween(100, 900);
		$this->promotionNameEdit         = $this->faker->bothify('promotion name edit ?####?');
		$this->startDate                 = date('Y-m-d');
		$this->endDate                   = date('Y-m-d', strtotime('+1 day', strtotime($this->startDate)));

		$this->promotionMissingName = array(
			"name"            => '',
			"desc"            => $this->faker->bothify('promotion desc ?####?'),
			"promotionType"   => 'Amount Product',
			"manufacturer"    => 'redcomponent.com',
			"category"        => $this->categoryName ,
			"product"         => $this->productName ,
			"conditionAmount" => $this->faker->numberBetween(1, 5),
			"fromDate"        => $this->startDate,
			"toDate"          => $this->endDate,
			"productAward"    => $this->productAwardsName,
			"awardAmount"     => $this->faker->numberBetween(1, 5),
			"freeShipping"    => 'No',
			"function"        => 'nameMissing'
		);


		$this->promotionNew = array(
			"name"            => $this->faker->bothify('promotion name ?####?'),
			"desc"            => $this->faker->bothify('promotion desc ?####?'),
			"promotionType"   => 'Amount Product',
			"manufacturer"    => 'redcomponent.com',
			"category"        => $this->categoryName ,
			"product"         => $this->productName ,
			"conditionAmount" => $this->faker->numberBetween(1, 5),
			"fromDate"        => $this->startDate,
			"toDate"          => $this->endDate,
			"productAward"    => $this->productAwardsName,
			"awardAmount"     => $this->faker->numberBetween(1, 5),
			"freeShipping"    => 'No',
		);

		$this->promotionStartThanEnd = array(
			"name"            => $this->faker->bothify('promotion name ?####?'),
			"desc"            => $this->faker->bothify('promotion desc ?####?'),
			"promotionType"   => 'Amount Product',
			"manufacturer"    => 'redcomponent.com',
			"category"        => $this->categoryName ,
			"product"         => $this->productName ,
			"conditionAmount" => $this->faker->numberBetween(1, 5),
			"fromDate"        => $this->startDate,
			"toDate"          => date('Y-m-d', strtotime('-1 day', strtotime($this->startDate))),
			"productAward"    => $this->productAwardsName,
			"awardAmount"     => $this->faker->numberBetween(1, 5),
			"freeShipping"    => 'No',
			"function"        => 'startThanEnd'
		);

		$this->promotionQuantityAwardLowZero = array(
			"name"            => $this->faker->bothify('promotion name ?####?'),
			"desc"            => $this->faker->bothify('promotion desc ?####?'),
			"promotionType"   => 'Amount Product',
			"manufacturer"    => 'redcomponent.com',
			"category"        => $this->categoryName ,
			"product"         => $this->productName ,
			"conditionAmount" => $this->faker->numberBetween(1, 5),
			"fromDate"        => $this->startDate,
			"toDate"          => $this->endDate,
			"productAward"    => $this->productAwardsName,
			"awardAmount"     => $this->faker->numberBetween(-5, -1),
			"freeShipping"    => 'No',
			"function"        => 'quantityAwardLowZero'
		);

		$this->promotionQuantityConditionLowZero = array(
			"name"            => $this->faker->bothify('promotion name ?####?'),
			"desc"            => $this->faker->bothify('promotion desc ?####?'),
			"promotionType"   => 'Amount Product',
			"manufacturer"    => 'redcomponent.com',
			"category"        => $this->categoryName ,
			"product"         => $this->productName ,
			"conditionAmount" => $this->faker->numberBetween(-5, -1),
			"fromDate"        => $this->startDate,
			"toDate"          => $this->endDate,
			"productAward"    => $this->productAwardsName,
			"awardAmount"     => $this->faker->numberBetween(1, 5),
			"freeShipping"    => 'No',
			"function"        => 'quantityConditionLowZero'
		);

		$this->promotionOrderVolumeLowZero = array(
			"name"          => $this->faker->bothify('promotion name ?####?'),
			"desc"          => $this->faker->bothify('promotion desc ?####?'),
			"promotionType" => 'Volume Order',
			"category"      => $this->categoryName,
			"product"       => $this->productName,
			"orderVolume"   => $this->faker->numberBetween(-100, -10),
			"fromDate"      => $this->startDate,
			"toDate"        => $this->endDate,
			"productAward"  => $this->productAwardsName,
			"awardAmount"   => $this->faker->numberBetween(1, 5),
			"freeShipping"  => 'Yes',
			"function"      => 'orderVolumeLowZero'
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function createData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create category in administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productAwardsName, $this->categoryName, $this->randomAwardsProductNumber, $this->randomAwardsProductPrice);
	}

	/**
	 * @param PromotionsManagementSteps $I
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function checkBadCasePromotion(PromotionsManagementSteps $I)
	{
		$I->wantTo('I want to check bad case');
		$I->badCasePromotion($this->promotionMissingName);
		$I->badCasePromotion($this->promotionOrderVolumeLowZero);
		$I->badCasePromotion($this->promotionQuantityAwardLowZero);
		$I->badCasePromotion($this->promotionStartThanEnd);
		$I->badCasePromotion($this->promotionQuantityConditionLowZero);
	}

	/**
	 * @param PromotionsManagementSteps $I
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function checkCreateUpdateDelete(PromotionsManagementSteps $I)
	{
		$I->wantTo('I want to create promotion');
		$I->createPromotion($this->promotionNew, PromotionsManagementPage::$buttonSaveClose);

		$I->wantTo('I want to edit promotion');
		$I->editPromotion($this->promotionNew['name'], $this->promotionNameEdit);

		$I->wantTo('I want to update state for promotion');
		$I->changeStatePromotionItem($this->promotionNameEdit, ProductManagerPage::$buttonUnpublish);
		$I->changeStatePromotionItem($this->promotionNameEdit, ProductManagerPage::$buttonPublish);

		$I->wantTo('I want to copy promotion');
		$I->copyPromotion($this->promotionNameEdit);

		$I->wantTo('Delete promotion');
		$I->deletePromotion($this->promotionNameEdit);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function clearUpDatabase(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete product');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);
		$I->deleteProduct($this->productAwardsName);

		$I->wantTo('Delete category');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);
	}
}