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
		$this->categoryName   = 'Events and Forms';
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
			$this->categoryName,
			$this->groupName
		);
	}

	/**
	 * Function create discount on product with save and close button
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 */
	public function addDiscountProductSaveClose(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
		$client->addDiscountProductSaveClose(
			$this->productPrice,
			$this->condition,
			$this->type,
			$this->discountAmount,
			$this->startDate,
			$this->endDate,
			$this->categoryName,
			$this->groupName
		);
		$client->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 * Function check cancel button
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 */
	public function addDiscountProductCancelButton(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client = new DiscountProductSteps($scenario);
		$client->addDiscountProductCancelButton();
		$client->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function add discount product missing amount
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 */
	public function addDiscountProductMissingAmountSaveClose(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test add discount missing amount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addDiscountProductMissingAmountSaveClose($this->productPrice, $this->condition, $this->type, $this->startDate, $this->endDate, $this->categoryName, $this->groupName);
		$I->see(\DiscountProductPage::$namePageDetail, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function add discount missing shopper groups
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 */
	public function addDiscountProductMissingShopperGroupSaveClose(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test add discount missing shopper groups in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addDiscountProductMissingShopperGroupSaveClose($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->startDate, $this->endDate, $this->categoryName);
		$I->see(\DiscountProductPage::$namePageDetail, \DiscountProductPage::$pageTitle);
	}

	/**
	 * Function add discount product have start day more than end day
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 */
	public function addDiscountProductStartMoreThanEnd(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test add discount product start more than end  in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addDiscountProductStartMoreThanEnd($this->productPrice, $this->condition, $this->type, $this->discountAmount, $this->startDate, $this->endDate, $this->categoryName, $this->groupName);
		$I->see(\DiscountProductPage::$namePageDetail, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function check edit button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function checkEditButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('check Edit button  in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->checkEditButton();
		$I->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function check delete button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function checkDeleteButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('heck Delete button   in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->checkDeleteButton();
		$I->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function check publish button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */
	public function checkPublishButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('check Publish button  in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->checkPublishButton();
		$I->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function check unpublish button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 */
	public function checkUnpublishButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('check Unpublish button  in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->checkUnpublishButton();
		$I->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 * Function check unpublish all discount
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends createDiscountSave
	 *
	 */
	public function checkUnpublishAll(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Unpublish all discount Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->checkUnpublishAll();
		$I->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function check publish all discount
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends checkUnpublishAll
	 *
	 */
	public function checkPublishAll(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish all discounts in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->checkPublishAll();
		$I->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}

	/**
	 *
	 * Function delete all discounts
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends addDiscountProductSaveClose
	 *
	 */
	public function checkDeleteAll(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deleted all discounts in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountProductSteps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->checkDeleteAll();
		$I->see(\DiscountProductPage::$namePage, \DiscountProductPage::$pageTitle);
	}
}