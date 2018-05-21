<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\DiscountSteps;

/**
 * Class ManageDiscountAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class DiscountCest
{
	/**
	 * @var \Faker\Generator
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $discountName;

	/**
	 * @var integer
	 */
	public $amount;

	/**
	 * @var integer
	 */
	public $discountAmount;

	/**
	 * @var integer
	 */
	public $newAmount;

	/**
	 * @var string
	 */
	public $startDate;

	/**
	 * @var string
	 */
	public $endDate;

	/**
	 * @var string
	 */
	public $shopperGroup;

	/**
	 * @var integer
	 */
	public $discountType;

	/**
	 * @var integer
	 */
	public $discountCondition;

	/**
	 * DiscountCest constructor.
	 */
	public function __construct()
	{
		$this->faker             = Faker\Factory::create();
		$this->discountName      = 'Discount' . rand(1, 100);
		$this->amount            = $this->faker->numberBetween(100, 999);
		$this->discountAmount    = $this->faker->numberBetween(10, 100);
		$this->newAmount         = $this->faker->numberBetween(100, 999);
		$this->startDate         = '13-06-2017';
		$this->endDate           = '13-08-2017';
		$this->shopperGroup      = 'Default Private';
		$this->discountType      = 0;
		$this->discountCondition = 1;
	}
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
	/**
	 * Function to Test Discount Creation in Backend
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 */
	public function createDiscount(AcceptanceTester $client, $scenario)
	{
		$client = new DiscountSteps($scenario);
		$client->addDiscount(
			$this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->discountCondition
		);
		$client->searchDiscount($this->discountName);
	}

	/**
	 * Function to Test Discount Deletion
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends createDiscount
	 */
	public function deleteDiscount(AcceptanceTester $client, $scenario)
	{
		$client->wantToTest('Deletion of Discount in Administrator');
		$client = new DiscountSteps($scenario);
		$client->deleteDiscount($this->discountName);
	}

	/**
	 * Function add Discount with save button
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @depends deleteDiscount
	 *
	 * @return  void
	 */
	public function addDiscountSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with save button in Administrator');
		$client = new DiscountSteps($scenario);
		$client->addDiscountSave($this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType);
	}

	/**
	 * Function change state of Discount is Ubpublish
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @depends addDiscountSave
	 *
	 * @return void
	 */
	public function changeChangeDiscountState(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test if State of a Discount gets Updated in Administrator');
		$client = new DiscountSteps($scenario);
		$client->changeDiscountState($this->discountName);
		$client->verifyState('unpublished', $client->getDiscountState($this->discountName));
	}

	/**
	 * Function test discount with start date higher than end date.
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @depends changeChangeDiscountState
	 *
	 * @return  void
	 */
	public function addDiscountStartThanEnd(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with start date higher than end date.');
		$client = new DiscountSteps($scenario);
		$client->addDiscountStartThanEnd(
			$this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
		);
	}

	/**
	 * Function test discount with missing name.
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @depends addDiscountStartThanEnd
	 *
	 * @return  void
	 */
	public function addDiscountMissingName(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with missing name.');
		$client = new DiscountSteps($scenario);
		$client->addDiscountMissingName(
			$this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
		);
	}

	/**
	 * Function test discount with missing amount.
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @depends addDiscountMissingName
	 *
	 * @return  void
	 */
	public function addDiscountMissingAmount(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with missing amount.');
		$client = new DiscountSteps($scenario);
		$client->addDiscountMissingAmount(
			$this->discountName, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
		);
	}

	/**
	 * Function test discount with missing shopper groups.
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   string            $scenario  Scenario for test.
	 *
	 * @depends addDiscountMissingAmount
	 *
	 * @return  void
	 */
	public function addDiscountMissingShopperGroups(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with missing shopper groups.');
		$client = new DiscountSteps($scenario);
		$client->addDiscountMissingShopperGroups(
			$this->discountName, $this->amount, $this->discountAmount, $this->discountType, $this->startDate, $this->endDate
		);
	}

	/**
	 * Function test discount with missing all fields.
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   string            $scenario  Scenario for test.
	 *
	 * @depends addDiscountMissingAmount
	 *
	 * @return  void
	 */
	public function addDiscountWithAllFieldsEmpty(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with missing all fields.');
		$client = new DiscountSteps($scenario);
		$client->addDiscountWithAllFieldsEmpty();
	}

	/**
	 * Function to Test Discount Updation in the Administrator
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   string            $scenario  Scenario for test.
	 *
	 * @depends createDiscount
	 *
	 * @return void
	 */
	public function updateDiscount(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test if Discount gets updated in Administrator');
		$client = new DiscountSteps($scenario);
		$client->wantTo('Update Existing Discount');
		$client->editDiscount($this->discountName, $this->amount, $this->newAmount);
	}
}
