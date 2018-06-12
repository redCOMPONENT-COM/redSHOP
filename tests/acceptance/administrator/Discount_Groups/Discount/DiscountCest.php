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

        $client->wantToTest('Deletion of Discount in Administrator');
        $client->deleteDiscount($this->discountName);
	}

	/**
	 * Function add Discount with save button
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @depends createDiscount
	 *
	 * @return  void
	 */
	public function addDiscountSaveChangeStatus(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with save button in Administrator');
		$client = new DiscountSteps($scenario);
		$client->addDiscountSave($this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType);

        $client->wantTo('Test if State of a Discount gets Updated in Administrator');
        $client->changeDiscountState($this->discountName);
        $client->verifyState('unpublished', $client->getDiscountState($this->discountName));
	}


	/**
	 * Function test discount with start date higher than end date.
	 *
	 * @param   AcceptanceTester $client   Acceptance Tester case.
	 * @param   string           $scenario Scenario for test.
	 *
	 * @depends addDiscountSaveChangeStatus
	 *
	 * @return  void
	 */
	public function addDiscountBadCases(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Discount creation with start date higher than end date.');
		$client = new DiscountSteps($scenario);
		$client->addDiscountStartThanEnd(
			$this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
		);

        $client->wantTo('Test Discount creation with missing name.');
        $client->addDiscountMissingName(
            $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
        );

        $client->wantTo('Test Discount creation with missing amount.');
        $client->addDiscountMissingAmount(
            $this->discountName, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate
        );

        $client->wantTo('Test Discount creation with missing shopper groups.');
        $client->addDiscountMissingShopperGroups(
            $this->discountName, $this->amount, $this->discountAmount, $this->discountType, $this->startDate, $this->endDate
        );

        $client->wantTo('Test Discount creation with missing all fields.');
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
