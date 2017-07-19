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
     * @param   string $cardName Name of the Card
     * @param   string $cardPrice Price for the new Card
     * @param   string $cardValue Value of the new Card
     * @param   string $cardValidity Validity Period for the new Card
     *
     * @return void
     */
    public function addCard($cardName = 'Sample Card', $cardPrice = '10', $cardValue = '10', $cardValidity = '10')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\GiftCardManagerPage::$URLNew);
//        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
        $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
        $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
        $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
        $I->click(\GiftCardManagerPage::$saveCloseButton);
        $I->waitForText('Item successfully saved', 60, ['id' => 'system-message-container']);
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->seeElement(['link' => $cardName]);
    }

    /**
     * Function to add new Gift and clicks save button
     *
     * @param string $cardName
     * @param string $cardPrice
     * @param string $cardValue
     * @param string $cardValidity
     *
     * @return void
     */
    public function addCardSave($cardName = 'Sample Card', $cardPrice = '10', $cardValue = '10', $cardValidity = '10')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
        $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
        $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
        $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
        $I->click(\GiftCardManagerPage::$saveButton);
        $I->waitForText('Item successfully saved', 60, ['id' => 'system-message-container']);
    }

    /**
     * Function check Cancel button
     *
     */
    public function checkCancelButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
        $I->click(\GiftCardManagerPage::$cancelButton);
    }

    /**
     *
     * Function add Gift Card with but missing name
     *
     * @param string $cardPrice
     * @param string $cardValue
     * @param string $cardValidity
     */
    public function addCardMissingName($cardPrice = '10', $cardValue = '10', $cardValidity = '10')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
        $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
        $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
        $I->click(\GiftCardManagerPage::$saveButton);
        $I->see('Invalid field: Gift Card Name', ['xpath' => "//div[@id='system-message-container']/div/div"]);
    }

    /**
     *
     * Function add Gift Card missing card price
     *
     * @param string $cardName
     * @param string $cardValue
     * @param string $cardValidity
     */
    public function addCardMissingCardPrice($cardName = 'Sample Card', $cardValue = '10', $cardValidity = '10')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
        $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
        $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
        $I->click(\GiftCardManagerPage::$saveButton);
        $I->see('Invalid field:  Gift Card Price ', ['xpath' => "//div[@id='system-message-container']/div/div"]);
    }

    /**
     * Function to add new Gift and clicks save button
     *
     * @param string $cardName
     * @param string $cardPrice
     * @param string $cardValidity
     *
     * @return void
     */
    public function addCardMissingGiftCardValue($cardName = 'Sample Card', $cardPrice = '10', $cardValidity = '10')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
        $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
        $I->fillField(\GiftCardManagerPage::$giftCardValidity, $cardValidity);
        $I->click(\GiftCardManagerPage::$saveButton);
        $I->see('Invalid field:  Gift Card Value ', ['xpath' => "//div[@id='system-message-container']/div/div"]);
    }

    /**
     * Function to add new Gift and clicks save button
     *
     * @param string $cardName
     * @param string $cardPrice
     * @param string $cardValue
     *
     * @return void
     */
    public function addCardMissingGiftCardValidity($cardName, $cardPrice, $cardValue)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$newButton);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $cardName);
        $I->fillField(\GiftCardManagerPage::$giftCardPrice, $cardPrice);
        $I->fillField(\GiftCardManagerPage::$giftCardValue, $cardValue);
        $I->click(\GiftCardManagerPage::$saveButton);
        $I->see('Invalid field:  Gift Card Validity', ['xpath' => "//div[@id='system-message-container']/div/div"]);
    }

    /**
     * Function to Edit a Gift Card when clicks on name of gift card
     *
     * @param   string $cardName Name of the card which is to be edited
     * @param   string $newCardName New Name for the Card
     *
     * @return void
     */
    public function editCard($cardName = 'Card Name', $newCardName = 'New Name')
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->click(['link' => $cardName]);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Edit View');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $newCardName);
        $I->click(\GiftCardManagerPage::$saveCloseButton);
        $I->see('Item successfully saved', ['id' => 'system-message-container']);
        $I->filterListBySearching($newCardName, ['id' => 'filter_search']);
        $I->seeElement(['link' => $newCardName]);
    }

    public function editCardSave($cardName, $newCardName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->click(['link' => $cardName]);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Edit View');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $newCardName);
        $I->click(\GiftCardManagerPage::$saveButton);
        $I->see('Item successfully saved', ['id' => 'system-message-container']);

    }

    public function editCardCloseButton($cardName){
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->click(['link' => $cardName]);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Edit View');
        $I->click(\GiftCardManagerPage::$closeButton);
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
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
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->click(['xpath' => "//div[@class='table-responsive']/table/tbody/tr/td[1]"]);
        $I->click(\GiftCardManagerPage::$editButton);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Edit View');
        $I->waitForElement(\GiftCardManagerPage::$giftCardName);
        $I->fillField(\GiftCardManagerPage::$giftCardName, $newCardName);
        $I->click(\GiftCardManagerPage::$saveCloseButton);
        $I->see('Item successfully saved', ['id' => 'system-message-container']);
        $I->filterListBySearching($newCardName, ['id' => 'filter_search']);
        $I->seeElement(['link' => $newCardName]);
    }

    /**
     * Function check edit button show alter
     */
    public function checkEditButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$editButton);
        $I->acceptPopup();
    }

    /**
     * Function check Delete button
     */
    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$deleteButton);
        $I->acceptPopup();
    }

    /**
     * Function check publish button without choice any gift card
     */
    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$publishButton);
        $I->acceptPopup();
    }

    /**
     * Function check unpublish button without choice any gift card
     */
    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
        $I->click(\GiftCardManagerPage::$unpublishButton);
        $I->acceptPopup();
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
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
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
        $this->search(new \GiftCardManagerPage, $cardName, \GiftCardManagerPage::$giftCardResultRow, $functionName);
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
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->wait(3);
        $I->seeElement(['link' => $cardName]);
        $I->click(['xpath' => "//div[@class='table-responsive']/table/tbody/tr/td[2]"]);
    }

    public function changeCardUnpublishButton($cardName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->wait(3);
        $I->click(\GiftCardManagerPage::$checkAllCart);
        $I->click(\GiftCardManagerPage::$unpublishButton);
        $I->wait(3);
        $I->see('1 item successfully unpublished', '.alert-success');
    }

    public function changeCardPublishButton($cardName)
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->filterListBySearching($cardName, ['id' => 'filter_search']);
        $I->wait(3);
        $I->click(\GiftCardManagerPage::$checkAllCart);
        $I->click(\GiftCardManagerPage::$publishButton);
        $I->see('1 item successfully published', '.alert-success');
    }


    public function changeAllCardUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->click(\GiftCardManagerPage::$checkAllCart);
        $I->click(\GiftCardManagerPage::$unpublishButton);
        $I->wait(3);
        $I->see(\GiftCardManagerPage::$messageSuccess, \GiftCardManagerPage::$selectorSuccess);
    }

    public function changeAllCardPublishButton()
    {
        $I = $this;
        $I->amOnPage(\GiftCardManagerPage::$URL);
        $I->click(\GiftCardManagerPage::$checkAllCart);
        $I->click(\GiftCardManagerPage::$publishButton);;
        $I->wait(3);
        $I->see(\GiftCardManagerPage::$messageSuccess, \GiftCardManagerPage::$selectorSuccess);
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
