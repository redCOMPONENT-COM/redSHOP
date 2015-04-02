<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class GiftCardManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class GiftCardManagerJoomla3Steps extends AdminManagerJoomla3Steps
{

	/**
	 * Function to add a new Gift Card
	 *
	 * @param   string  $cardName      Name of the Card
	 * @param   string  $cardPrice     Price for the new Card
	 * @param   string  $cardValue     Value of the new Card
	 * @param   string  $cardValidity  Validity Period for the new Card
	 *
	 * @return void
	 */
	public function addCard($cardName = 'Sample Card', $cardPrice = '10', $cardValue = '10', $cardValidity = '10')
	{
		$I = $this;
		$I->amOnPage(\GiftCardManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
		$I->waitForElement(\GiftCardManagerPage::$giftCardName);
		$I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
		$I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
		$I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
		$I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
		$I->click('Save & Close');
		$I->see('Gift Card Saved', '.alert-success');
		$I->click('ID');
		$I->see($cardName, \GiftCardManagerPage::$giftCardResultRow);
		$I->click('ID');
	}

	/**
	 * Function to Edit a Gift Card
	 *
	 * @param   string  $cardName     Name of the card which is to be edited
	 * @param   string  $newCardName  New Name for the Card
	 *
	 * @return void
	 */
	public function editCard($cardName = 'Card Name', $newCardName = 'New Name')
	{
		$I = $this;
		$I->amOnPage(\GiftCardManagerPage::$URL);
		$I->click('ID');
		$I->see($cardName, \GiftCardManagerPage::$giftCardResultRow);
		$I->click(\GiftCardManagerPage::$firstResult);
		$I->click('Edit');
		$I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Edit View');
		$I->waitForElement(\GiftCardManagerPage::$giftCardName);
		$I->fillField(\GiftCardManagerPage::$giftCardName, $newCardName);
		$I->click('Save & Close');
		$I->see('Gift Card Saved', '.alert-success');
		$I->see($newCardName, \GiftCardManagerPage::$giftCardResultRow);
		$I->click('ID');
	}

	/**
	 * Function to Delete a Gift Card
	 *
	 * @param   string  $cardName  Name on the Card
	 *
	 * @return void
	 */
	public function deleteCard($cardName = 'Sample Card')
	{
		$this->delete(new \GiftCardManagerPage, $cardName, \GiftCardManagerPage::$giftCardResultRow, \GiftCardManagerPage::$firstResult);
	}

	/**
	 * Function to Search for a Gift Card
	 *
	 * @param   string  $cardName      Name of the card for which search is being called
	 * @param   string  $functionName  Name of the function after which Search is being Called
	 *
	 * @return void
	 */
	public function searchCard($cardName = 'Sample Card', $functionName = 'Search')
	{
		$this->search(new \GiftCardManagerPage, $cardName, \GiftCardManagerPage::$giftCardResultRow, $functionName);
	}

	/**
	 * Function to Change State of a Gift Card
	 *
	 * @param   String  $cardName  Name of the Card for which the state is to be Changed
	 * @param   String  $state     State to which it is to be Changed
	 *
	 * @return void
	 */
	public function changeCardState($cardName = 'Sample Card', $state = 'unpublish')
	{
		$this->changeState(new \GiftCardManagerPage, $cardName, $state, \GiftCardManagerPage::$giftCardResultRow, \GiftCardManagerPage::$firstResult);
	}

	/**
	 * Function to get State of a Card
	 *
	 * @param   string  $cardName  Name of the card for which State is to be determined
	 *
	 * @return string
	 */
	public function getCardState($cardName = 'Sample Card')
	{
		$result = $this->getState(new \GiftCardManagerPage, $cardName, \GiftCardManagerPage::$giftCardResultRow, \GiftCardManagerPage::$giftCardState);

		return $result;
	}
}
