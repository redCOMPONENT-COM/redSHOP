<?php

use AcceptanceTester\DiscountProductSteps;

/**
 * * Class ManageDiscountProductAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.0
 */
class DiscountProductCest
{
	/**
	 * @var \Faker\Generator
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $categoryName;

	/**
	 * @var integer
	 */
	public $noPage;

	/**
	 * @var integer
	 */
	public $productPrice;

	/**
	 * @var integer
	 */
	public $condition;

	/**
	 * @var integer
	 */
	public $type;

	/**
	 * @var string
	 */
	public $startDate;

	/**
	 * @var string
	 */
	public $endDate;

	/**
	 * @var integer
	 */
	public $discountAmount;

	/**
	 * @var string
	 */
	public $groupName;

	/**
	 * ManageDiscountProductAdministratorCest constructor
	 */
	public function __construct()
	{
		$this->faker          = Faker\Factory::create();
		$this->dataCategory = array();
		$this->dataCategory['name'] = $this->faker->bothify('Category Name ?##?');
		$this->dataCategory['type'] = 'Total';
		$this->noPage         = $this->faker->randomNumber();
		$this->productPrice   = $this->faker->numberBetween(100, 999);
		$this->startDate      = "2017-06-19";
		$this->endDate        = "2017-06-23";
		$this->discountAmount = $this->faker->numberBetween(1, 10);
		$this->groupName      = "Default Private";

		// Higher
		$this->condition = 3;

		// Percentage
		$this->type = 1;
	}

	/**
	 * @param AcceptanceTester $I
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function createCategory(CategorySteps $client)
	{
		$client->wantTo('Test Category creation in Administrator');
		$client->wantTo('Create a Category');
		$client->addNewItem($this->dataCategory);
	}
	/**
	 * Function to Test Discount Product Creation in Backend
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 */
	public function createDiscountSave(DiscountProductSteps $client)
	{
		$client->addDiscountProductSave(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name'],
			$this->groupName
		);
	}

	/**
	 * Function check cancel button
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends createDiscountSave
	 */
	public function addDiscountProductCancelButton(DiscountProductSteps $client)
	{
		$client->addDiscountProductCancelButton();
		$client->see(\DiscountProductPage::$namePage, \DiscountProductPage::$selectorPageTitle);
	}

	/**
	 *
	 * Function add discount product missing amount
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends addDiscountProductCancelButton
	 */
	public function addDiscountProductMissingAmountSaveClose(DiscountProductSteps $client)
	{
		$client->wantTo('Add product discount missing amount then clicks on save and close button');
		$client->addDiscountProductMissingAmountSaveClose(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name'],
			$this->groupName
		);
		$client->wantTo('Add product discount missing shopper group then clicks on save and close button');
		$client->addDiscountProductMissingShopperGroupSaveClose(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name']
		);

		$client->wantTo('Add discount product strt more than end ');
		$client->addDiscountProductStartMoreThanEnd(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name'],
			$this->groupName
		);
	}

	/**
	 * Function check delete button
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends addDiscountProductMissingAmountSaveClose
	 */
	public function checkDeleteButton(DiscountProductSteps $client)
	{
		$client->checkDeleteButton();
		$client->see(\DiscountProductPage::$namePage, \DiscountProductPage::$selectorPageTitle);
	}

	/**
	 * Function delete all discounts
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends checkDeleteButton
	 *
	 */
	public function checkDeleteAll(DiscountProductSteps $client)
	{
		$client->addDiscountToday($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->dataCategory['name'], $this->groupName);
		$client->checkDeleteAll();
	}
}
