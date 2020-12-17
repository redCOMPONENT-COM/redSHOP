<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use GiftCardManagerPage;

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
	 * @param string $cardName
	 * @param string $cardPrice
	 * @param string $cardValue
	 * @param string $cardValidity
	 * @param $function
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addCardNew($cardName = 'Sample Card', $cardPrice = '10', $cardValue = '10', $cardValidity = '10',$function)
	{
		 $I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->click(GiftCardManagerPage::$buttonNew);
		switch ($function){
			case 'save':
				$I->fillField(GiftCardManagerPage::$giftCardName, $cardName);
				$I->fillField(GiftCardManagerPage::$giftCardPrice, $cardPrice);
				$I->fillField(GiftCardManagerPage::$giftCardValidity, $cardValidity);
				$I->fillField(GiftCardManagerPage::$giftCardValue, $cardValue);
				$I->click(GiftCardManagerPage::$buttonSave);
				$I->waitForText(GiftCardManagerPage::$messageItemSaveSuccess, 60, GiftCardManagerPage::$selectorSuccess);
				$I->waitForElement(GiftCardManagerPage::$giftCardCancelButton,30);
				$I->click(GiftCardManagerPage::$giftCardCancelButton);
				break;

			case 'saveclose':
				$I->waitForElement(GiftCardManagerPage::$giftCardName, 30);
				$I->fillField(GiftCardManagerPage::$giftCardName, $cardName);
				$I->fillField(GiftCardManagerPage::$giftCardPrice, $cardPrice);
				$I->fillField(GiftCardManagerPage::$giftCardValidity, $cardValidity);
				$I->fillField(GiftCardManagerPage::$giftCardValue, $cardValue);
				$I->click(GiftCardManagerPage::$buttonSaveClose);
				$I->waitForText(GiftCardManagerPage::$messageItemSaveSuccess, 60, GiftCardManagerPage::$selectorSuccess);
				$I->filterListBySearching($cardName);
				$I->seeElement(GiftCardManagerPage::xpathLink($cardName));
				break;
		}
	}

	/**
	 * Function to do the validation for different buttons on gift card views
	 * @param $buttonName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkButtons($buttonName)
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->waitForText(GiftCardManagerPage::$namePageManagement, 30, GiftCardManagerPage::$selectorPageTitle);

		switch ($buttonName) {
			case 'cancel':
				$I->click(GiftCardManagerPage::$buttonNew);
				$I->waitForElement(GiftCardManagerPage::$giftCardName, 30);
				$I->click(GiftCardManagerPage::$buttonCancel);
				$I->see(GiftCardManagerPage::$namePageManagement, GiftCardManagerPage::$selectorPageTitle);
				break;
			case 'edit':
				$I->click(GiftCardManagerPage::$buttonEdit);
				$I->acceptPopup();
				break;
			case 'delete':
				$I->click(GiftCardManagerPage::$buttonDelete);
				$I->acceptPopup();
				break;
			case 'publish':
				$I->click(GiftCardManagerPage::$buttonPublish);
				$I->acceptPopup();
				break;
			case 'unpublish':
				$I->click(GiftCardManagerPage::$buttonUnpublish);
				$I->acceptPopup();
				break;
		}
		$I->see(GiftCardManagerPage::$namePageManagement, GiftCardManagerPage::$selectorPageTitle);
	}

	/**
	 *
	 * Function validate Missing Field in Edit View of Gift Cards
	 *
	 * @param string $fieldName
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function giftCardEditViewMissingFieldValidation($fieldName)
	{
		$I = $this;
		$faker = \Faker\Factory::create();
		$cardPrice = '10';
		$cardValue = '10';
		$cardValidity = '10';
		$cardName = $faker->bothify('Gift Card Name ##??');
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->waitForText(GiftCardManagerPage::$namePageManagement, 30, GiftCardManagerPage::$selectorPageTitle);
		$I->click(GiftCardManagerPage::$buttonNew);
		$I->waitForElement(GiftCardManagerPage::$giftCardName, 30);

		switch ($fieldName) {
			case 'cardName':
				$I->fillField(GiftCardManagerPage::$giftCardPrice, $cardPrice);
				$I->fillField(GiftCardManagerPage::$giftCardValidity, $cardValidity);
				$I->fillField(GiftCardManagerPage::$giftCardValue, $cardValue);
				$I->fillField(GiftCardManagerPage::$giftCardName, "");
				$I->click(GiftCardManagerPage::$buttonSave);
				$I->see(GiftCardManagerPage::$messageInvalidName, GiftCardManagerPage::$errorValid);
				break;
			case 'cardValidity':
				$I->fillField(GiftCardManagerPage::$giftCardPrice, $cardPrice);
				$I->fillField(GiftCardManagerPage::$giftCardValidity, "");
				$I->fillField(GiftCardManagerPage::$giftCardValue, $cardValue);
				$I->fillField(GiftCardManagerPage::$giftCardName, $cardName);
				$I->click(GiftCardManagerPage::$buttonSave);
				$I->see(GiftCardManagerPage::$messageInvalidCart, GiftCardManagerPage::$errorValid);
				break;
			case 'cardValue':
				$I->fillField(GiftCardManagerPage::$giftCardPrice, $cardPrice);
				$I->fillField(GiftCardManagerPage::$giftCardValidity, $cardValidity);
				$I->fillField(GiftCardManagerPage::$giftCardValue, "");
				$I->fillField(GiftCardManagerPage::$giftCardName, $cardName);
				$I->click(GiftCardManagerPage::$buttonSave);
				$I->see(GiftCardManagerPage::$messageInvalidGiftCart, GiftCardManagerPage::$errorValid);
				break;
			case 'cardPrice':
				$I->fillField(GiftCardManagerPage::$giftCardPrice, "");
				$I->fillField(GiftCardManagerPage::$giftCardValidity, $cardValidity);
				$I->fillField(GiftCardManagerPage::$giftCardValue, $cardValue);
				$I->fillField(GiftCardManagerPage::$giftCardName, $cardName);
				$I->click(GiftCardManagerPage::$buttonSave);
				$I->see(GiftCardManagerPage::$messageInvalidPrice, GiftCardManagerPage::$errorValid);
				break;
		}
	}

	/**
	 * @param string $cardName
	 * @param $newCardName
	 * @param $function
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editCard($cardName = 'Sample Card', $newCardName ,$function){
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->filterListBySearching($cardName);

		$I->click(GiftCardManagerPage::xpathLink($cardName));
		switch ($function){
			case 'save':
				$I->waitForElement(GiftCardManagerPage::$giftCardName, 30);
				$I->fillField(GiftCardManagerPage::$giftCardName, $newCardName);
				$I->click(GiftCardManagerPage::$buttonSave);
				$I->waitForText(GiftCardManagerPage::$messageItemSaveSuccess, 30, GiftCardManagerPage::$selectorSuccess);
				$I->seeInField(GiftCardManagerPage::$giftCardName, $newCardName);
				$I->click(GiftCardManagerPage::$buttonClose);
				break;
			case 'saveclose':
				$I->waitForElement(GiftCardManagerPage::$giftCardName, 30);
				$I->fillField(GiftCardManagerPage::$giftCardName, $newCardName);
				$I->click(GiftCardManagerPage::$buttonSaveClose);
				$I->waitForText(GiftCardManagerPage::$messageItemSaveSuccess, 60, GiftCardManagerPage::$selectorSuccess);
				break;
		}
	}

	/**
	 * @param $cardName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editCardCloseButton($cardName)
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->filterListBySearching($cardName);
		$I->click(GiftCardManagerPage::xpathLink($cardName));
		$I->waitForElement(GiftCardManagerPage::$giftCardName, 30);
		$I->click(GiftCardManagerPage::$buttonClose);
		$I->filterListBySearching($cardName);
		$I->seeElement(GiftCardManagerPage::xpathLink($cardName));
	}


	/**
	 * Function to Edit a Gift Card when clicks on checkbox of card then edit button
	 *
	 * @param   string $cardName Name of the card which is to be edited
	 * @param   string $newCardName New Name for the Card
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editCardWithEditButton($cardName = 'Card Name', $newCardName = 'New Name')
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->filterListBySearching($cardName);
		$I->click(GiftCardManagerPage::$getGiftCard);
		$I->click(GiftCardManagerPage::$buttonEdit);
		$I->waitForElement(GiftCardManagerPage::$giftCardName, 30);
		$I->fillField(GiftCardManagerPage::$giftCardName, $newCardName);
		$I->click(GiftCardManagerPage::$buttonSave);
		$I->waitForText(GiftCardManagerPage::$messageItemSaveSuccess, 60, GiftCardManagerPage::$selectorSuccess);
		$I->seeInField(GiftCardManagerPage::$giftCardName, $newCardName);
		$I->click(GiftCardManagerPage::$buttonClose);
	}

	/**
	 * Function to Delete a Gift Card
	 *
	 * @param   string $cardName Name on the Card
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteCard($cardName)
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->filterListBySearching($cardName);
		$I->click(GiftCardManagerPage::$firstResult);
		$I->click(GiftCardManagerPage::$buttonEdit);
		$I->dontSeeElement(GiftCardManagerPage::xpathLink($cardName));
	}

	/**
	 * Function to Search for a Gift Card
	 *
	 * @param   string $cardName Name of the card for which search is being called
	 * @param   string $functionName Name of the function after which Search is being Called
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function searchCard($cardName = 'Sample Card', $functionName = 'Search')
	{
		$I=$this;
		$I->filterListBySearching($cardName);
		$I->seeElement(GiftCardManagerPage::xpathLink($cardName));
	}

	/**
	 * Function to Change State of a Gift Card
	 *
	 * @param   String $cardName Name of the Card for which the state is to be Changed
	 * @since 1.4.0
	 * @return void
	 * @throws \Exception
	 */
	public function changeCardState($cardName = 'Sample Card')
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->filterListBySearching($cardName);
		$I->seeElement(GiftCardManagerPage::xpathLink($cardName));
		$I->click(GiftCardManagerPage::$getCartStatus);
	}

	/**
	 * @param $cardName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function changeCardUnpublishButton($cardName)
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->filterListBySearching($cardName);
		$I->checkAllResults();
		$I->click(GiftCardManagerPage::$buttonUnpublish);
	}

	/**
	 * @param $cardName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function changeCardPublishButton($cardName)
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->filterListBySearching($cardName);
		$I->checkAllResults();
		$I->click(GiftCardManagerPage::$buttonPublish);
	}

	/**
	 * Function change status all cart to unpublish
	 * @since 1.4.0
	 */
	public function changeAllCardUnpublishButton()
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->checkAllResults();
		$I->click(GiftCardManagerPage::$buttonUnpublish);
	}

	/**
	 * Function change status all cart to publish
	 * @since 1.4.0
	 */
	public function changeAllCardPublishButton()
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->checkAllResults();
		$I->click(GiftCardManagerPage::$buttonPublish);;
	}


	/**
	 * Function to get State of a Card
	 *
	 * @param   string $cardName Name of the card for which State is to be determined
	 *
	 * @return string
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function getCardState($cardName)
	{
		$I = $this;
		$I->amOnPage(GiftCardManagerPage::$URL);
		$I->searchCard($cardName);
		$I->waitForElement(GiftCardManagerPage::$giftCardResultRow);
		$I->see($cardName, GiftCardManagerPage::$giftCardResultRow);
		$text = $I->grabAttributeFrom(GiftCardManagerPage::$getCartStatus, 'onclick');
		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';

		}else
		{
		  $result = 'unpublished';
		}
		return $result;
	}
}
