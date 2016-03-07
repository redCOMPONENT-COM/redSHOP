<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ManageGiftCardAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageGiftCardAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->randomCardName = $this->faker->bothify('ManageGiftCardAdministratorCest Card ?##?');
		$this->newRandomCardName = 'New ' . $this->randomCardName;
		$this->cardPrice = $this->faker->numberBetween(99, 999);
		$this->cardValue = $this->faker->numberBetween(9, 99);
		$this->cardValidity = $this->faker->numberBetween(1, 15);
	}

	/**
	 * Function to Test Gift Cards Creation in Backend
	 *
	 */
	public function createGiftCard(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Gift Card creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
		$I->addCard($this->randomCardName, $this->cardPrice, $this->cardValue, $this->cardValidity);
		$I->searchCard($this->randomCardName);
	}

	/**
	 * Function to Test Gift Card Updation in the Administrator
	 *
	 * @depends createGiftCard
	 */
	public function updateGiftCard(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Gift Card gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
		$I->editCard($this->randomCardName, $this->newRandomCardName);
		$I->searchCard($this->newRandomCardName);
	}

	/**
	 * Test for State Change in Gift Card Administrator
	 *
	 * @depends updateGiftCard
	 */
	public function changeGiftCardState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Gift Card gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
		$I->changeCardState($this->newRandomCardName);
		$I->verifyState('unpublished', $I->getCardState($this->newRandomCardName), 'State Must be Unpublished');
	}

	/**
	 * Function to Test Gift Card Deletion
	 *
	 * @depends changeGiftCardState
	 */
	public function deleteGiftCard(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Gift Card in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
		$I->deleteCard($this->newRandomCardName);
	}
}
