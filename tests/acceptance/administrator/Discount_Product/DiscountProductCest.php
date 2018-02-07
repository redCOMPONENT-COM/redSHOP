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
		$this->startDate      = "19-06-2017";
		$this->endDate        = "23-06-2017";
		$this->discountAmount = $this->faker->numberBetween(1, 10);
		$this->groupName      = "Default Private";

		// Higher
		$this->condition = 3;

		// Percentage
		$this->type = 1;
	}

	public function createCategory(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client->wantTo('Test Category creation in Administrator');
		$client = new CategorySteps($scenario);
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
	public function createDiscountSave(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
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
	public function addDiscountProductCancelButton(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
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
	public function addDiscountProductMissingAmountSaveClose(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
		$client->addDiscountProductMissingAmountSaveClose(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name'],
			$this->groupName
		);
	}

	/**
	 *
	 * Function add discount missing shopper groups
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends addDiscountProductMissingAmountSaveClose
	 */
	public function addDiscountProductMissingShopperGroupSaveClose(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
		$client->addDiscountProductMissingShopperGroupSaveClose(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->dataCategory['name']
		);
	}

	/**
	 * Function add discount product have start day more than end day
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends addDiscountProductMissingShopperGroupSaveClose
	 */
	public function addDiscountProductStartMoreThanEnd(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
		$client->addDiscountProductStartMoreThanEnd(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->endDate,
			$this->startDate,
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
	 * @depends addDiscountProductStartMoreThanEnd
	 */
	public function checkDeleteButton(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
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
	public function checkDeleteAll(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
		$client->addDiscountToday($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->dataCategory['name'], $this->groupName);
		$client->checkDeleteAll();
	}
}
