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
    public function addCardNew($cardName = 'Sample Card', $cardPrice = '10', $cardValue = '10', $cardValidity = '10',$function){
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->click(\GiftCardManagerPage::$newButton);
        switch ($function){
            case 'save':
                $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
                $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
                $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
                $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
                $I->click(\GiftCardManagerPage::$saveButton);
                $I->waitForText(\GiftCardManagerPage::$messageSaveSuccess, 60, \GiftCardManagerPage::$selectorSuccess);
                break;
            case 'saveclose':
                $I->waitForElement(\GiftCardManagerPage::$giftCardName, 30);
                $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
                $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
                $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
                $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
                $I->click(\GiftCardManagerPage::$saveCloseButton);
                $I->waitForText(\GiftCardManagerPage::$messageSaveSuccess, 60, \GiftCardManagerPage::$selectorSuccess);
                $I->filterListBySearching($cardName);
                $I->seeElement(['link' => $cardName]);
                break;


        }
    }
    /**
     * Function to do the validation for different buttons on gift card views
     *
     * @param $buttonName
     *
     */
    public function checkButtons($buttonName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->waitForText(\GiftCardManagerPage::$namePageManagement, 30, \GiftCardManagerPage::$selectorNamePage);

        switch ($buttonName) {
            case 'cancel':
                $I->click(\GiftCardManagerPage::$newButton);
                $I->waitForElement(\GiftCardManagerPage::$giftCardName, 30);
                $I->click(\GiftCardManagerPage::$cancelButton);
                $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
                break;
            case 'edit':
                $I->click(\GiftCardManagerPage::$editButton);
                $I->acceptPopup();
                break;
            case 'delete':
                $I->click(\GiftCardManagerPage::$deleteButton);
                $I->acceptPopup();
                break;
            case 'publish':
                $I->click(\GiftCardManagerPage::$publishButton);
                $I->acceptPopup();
                break;
            case 'unpublish':
                $I->click(\GiftCardManagerPage::$unpublishButton);
                $I->acceptPopup();
                break;
        }
        $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
    }

    /**
     *
     * Function validate Missing Field in Edit View of Gift Cards
     *
     * @param string $fieldName
     *
     * @return void
     */

    public function giftCardEditViewMissingFieldValidation($fieldName)
    {
        $I = $this;
        $faker = \Faker\Factory::create();
        $cardPrice = '10';
        $cardValue = '10';
        $cardValidity = '10';
        $cardName = $faker->bothify('Gift Card Name ##??');
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->waitForText(\GiftCardManagerPage::$namePageManagement, 30, \GiftCardManagerPage::$selectorNamePage);
        $I->click(\GiftCardManagerPage::$newButton);
        $I->waitForElement(\GiftCardManagerPage::$giftCardName, 30);

        switch ($fieldName) {
            case 'cardName':
                $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
                $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
                $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
                $I->fillField(\GiftCardManagerPage::$giftCardName, "");
                $I->click(\GiftCardManagerPage::$saveButton);
                $I->see(\GiftCardManagerPage::$messageInvalidName, \GiftCardManagerPage::$errorValid);
                break;
            case 'cardValidity':
                $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
                $I->fillField(\GiftCardManagerPage::$giftCardValidity, "");
                $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
                $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
                $I->click(\GiftCardManagerPage::$saveButton);
                $I->see(\GiftCardManagerPage::$messageInvalidCart, \GiftCardManagerPage::$errorValid);
                break;
            case 'cardValue':
                $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
                $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
                $I->fillField(\GiftCardManagerPage::$giftCardValue, "");
                $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
                $I->click(\GiftCardManagerPage::$saveButton);
                $I->see(\GiftCardManagerPage::$messageInvalidGiftCart, \GiftCardManagerPage::$errorValid);
                break;
            case 'cardPrice':
                $I->fillField(\GiftCardManagerPage::$giftCardPrice, "");
                $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
                $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
                $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
                $I->click(\GiftCardManagerPage::$saveButton);
                $I->see(\GiftCardManagerPage::$messageInvalidPrice, \GiftCardManagerPage::$errorValid);
                break;
        }
    }

    public function editCard($cardName = 'Sample Card', $newCardName ,$function){
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName);
        $I->click(['link' => $cardName]);
        switch ($function){
            case 'save':
                $I->waitForElement(\GiftCardManagerPage::$giftCardName, 30);
                $I->fillField(\GiftCardManagerPage::$giftCardName, $newCardName);
                $I->click(\GiftCardManagerPage::$saveCloseButton);
                $I->waitForText(\GiftCardManagerPage::$messageSaveSuccess, 30, \GiftCardManagerPage::$selectorSuccess);
                $I->filterListBySearching($newCardName);
                $I->seeElement(['link' => $newCardName]);
                break;
            case 'saveclose':
                $I->waitForElement(\GiftCardManagerPage::$giftCardName, 30);
                $I->fillField(\GiftCardManagerPage::$giftCardName, $newCardName);
                $I->click(\GiftCardManagerPage::$saveButton);
                $I->waitForText(\GiftCardManagerPage::$messageSaveSuccess, 60, \GiftCardManagerPage::$selectorSuccess);
                $I->click(\GiftCardManagerPage::$closeButton);
                $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
                break;


        }
    }

    public function editCardCloseButton($cardName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName);
        $I->click(['link' => $cardName]);
        $I->waitForElement(\GiftCardManagerPage::$giftCardName, 30);
        $I->click(\GiftCardManagerPage::$closeButton);
        $I->filterListBySearching($cardName);
        $I->seeElement(['link' => $cardName]);
    }


    /**
     * Function to Edit a Gift Card when clicks on checkbox of card then edit button
     *
     * @param   string $cardName Name of the card which is to be edited
     * @param   string $newCardName New Name for the Card
     *
     * @return void
     */
    public function editCardWithEditButton($cardName = 'Card Name', $newCardName = 'New Name')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName);
        $I->click(\GiftCardManagerPage::$getGiftCard);
        $I->click(\GiftCardManagerPage::$editButton);
        $I->waitForElement(\GiftCardManagerPage::$giftCardName, 30);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $newCardName);
        $I->click(\GiftCardManagerPage::$saveCloseButton);
        $I->waitForText(\GiftCardManagerPage::$messageSaveSuccess, 60, \GiftCardManagerPage::$selectorSuccess);
        $I->filterListBySearching($newCardName);
        $I->seeElement(['link' => $newCardName]);
    }

    /**
     * Function to Delete a Gift Card
     *
     * @param   string $cardName Name on the Card
     *
     * @return void
     */
    public function deleteCard($cardName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName);
        $I->click(\GiftCardManagerPage::$firstResult);
        $I->click(\GiftCardManagerPage::$editButton);
        $I->dontSeeElement(['link' => $cardName]);
    }

    /**
     * Function to Search for a Gift Card
     *
     * @param   string $cardName Name of the card for which search is being called
     * @param   string $functionName Name of the function after which Search is being Called
     *
     * @return void
     */
    public function searchCard($cardName = 'Sample Card', $functionName = 'Search')
    {
        $I=$this;
        $I->filterListBySearching($cardName);
        $I->seeElement(['link' => $cardName]);
//        $this->search(new \GiftCardManagerPage, $cardName, \GiftCardManagerPage::$giftCardResultRow, $functionName);
    }

    /**
     * Function to Change State of a Gift Card
     *
     * @param   String $cardName Name of the Card for which the state is to be Changed
     *
     * @return void
     */
    public function changeCardState($cardName = 'Sample Card')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName);
        $I->seeElement(['link' => $cardName]);
        $I->click(\GiftCardManagerPage::$getCartStatus);
    }

    public function changeCardUnpublishButton($cardName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName);
        $I->checkAllResults();
        $I->click(\GiftCardManagerPage::$unpublishButton);
        $I->waitForText(\GiftCardManagerPage::$messageUnpublishSuccess, 60, \GiftCardManagerPage::$selectorSuccess);
    }

    public function changeCardPublishButton($cardName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName);
        $I->checkAllResults();
        $I->click(\GiftCardManagerPage::$publishButton);
        $I->waitForText(\GiftCardManagerPage::$messagePublishSuccess, 60, \GiftCardManagerPage::$selectorSuccess);
    }


    public function changeAllCardUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\GiftCardManagerPage::$unpublishButton);
        $I->wait(3);
    }

    public function changeAllCardPublishButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\GiftCardManagerPage::$publishButton);;
    }


    /**
     * Function to get State of a Card
     *
     * @param   string $cardName Name of the card for which State is to be determined
     *
     * @return string
     */
    public function getCardState($cardName)
    {
        $result = $this->getState(new \GiftCardManagerPage, $cardName, \GiftCardManagerPage::$giftCardResultRow, \GiftCardManagerPage::$giftCardState);
        return $result;
    }
}
