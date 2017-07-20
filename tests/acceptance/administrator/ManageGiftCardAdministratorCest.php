<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

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
        $this->randomCardName = 'EditName'.rand(1,1000);
        $this->cardNameSave = 'Cart Name' . rand(1, 100);
        $this->cardNameSaveEdit = 'New' . $this->cardNameSave;
        $this->newRandomCardName = 'New ';
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
     *  Function to create card with save button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCardSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Gift Card creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->addCardSave($this->cardNameSave, $this->cardPrice, $this->cardValue, $this->cardValidity);
    }

    /**
     *  Function to check cancel button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Gift Card creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->checkCancelButton();
        $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
    }

    /**
     *  Function to create card missing name
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCardMissingName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Gift Card creation missing name in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->addCardMissingName($this->cardPrice, $this->cardValue, $this->cardValidity);
    }

    /**
     *  Function to create card missing Card Price
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCardMissingCardPrice(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Gift Card creation  missing card price in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->addCardMissingCardPrice($this->randomCardName, $this->cardValue, $this->cardValidity);
    }

    /**
     *  Function to create card missing Card Value
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCardMissingGiftCardValue(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Gift Card creation missing gift card value in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->addCardMissingGiftCardValue($this->randomCardName, $this->cardPrice, $this->cardValidity);
    }

    /**
     *  Function to create card missing Card Validity
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function createCardMissingGiftCardValidity(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Gift Card creation missing card validity in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->addCardMissingGiftCardValidity($this->randomCardName, $this->cardPrice, $this->cardValue);
    }

    /**
     *
     * Functon check Edit Button without choice any gift card
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check Edit Button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->checkEditButton();
        $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
    }

    /**
     * Functon check Delete Button without choice any gift card
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check Edit Button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->checkDeleteButton();
        $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
    }

    /**
     * Functon check Publish Button without choice any gift card
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check Edit Button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->checkPublishButton();
        $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
    }

    /**
     * Functon check Unpublish Button without choice any gift card
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check Edit Button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->checkUnpublishButton();
        $I->see(\GiftCardManagerPage::$namePageManagement, \GiftCardManagerPage::$selectorNamePage);
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
     * Function to Test Gift Card Updation with save button in the Administrator
     *
     * @depends updateGiftCard
     */
    public function editCardSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Gift Card gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->editCardSave($this->newRandomCardName, $this->newRandomCardName);
    }

    /**
     *
     * Function check Close button inside Gift Card Edit
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * * @depends updateGiftCard
     */
    public function updateCardCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Gift Card gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->editCardCloseButton($this->newRandomCardName);
        $I->searchCard($this->newRandomCardName);
    }

    /**
     * Function create edit Gift Card with edit button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     *  * @depends createCardSave
     *
     */
    public function updateGiftCardEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Gift Card gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->editCardWithEditButton($this->cardNameSave, $this->cardNameSaveEdit);
        $I->searchCard($this->cardNameSaveEdit);
    }

    /**
     * Test for State Change is unpublish in Gift Card Administrator
     *
     * @depends updateGiftCard
     */
    public function changeGiftCardStateUnpublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State Unpublish of a Gift Card gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->changeCardState($this->cardNameSaveEdit);
        $I->verifyState('unpublished', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');
    }

    /**
     * Test for State Change is unpublish in Gift Card Administrator
     *
     * @depends changeGiftCardStateUnpublish
     */
    public function changeGiftCardStatePublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->changeCardState($this->cardNameSaveEdit);
        $I->verifyState('published', $I->getCardState($this->cardNameSaveEdit), 'State Must be published');
    }

    /**
     * Test for State Change unpublish with unpublish button is unpublish in Gift Card Administrator
     *
     * @depends changeGiftCardStatePublish
     */
    public function changeCardUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->changeCardUnpublishButton($this->cardNameSaveEdit);
        $I->wait(3);
        $I->verifyState('unpublished', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');
    }

    /**
     * Test for State Change publish with publish button in Gift Card Administrator
     *
     * @depends changeCardUnpublishButton
     */
    public function changeCardPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->changeCardPublishButton($this->cardNameSaveEdit);
        $I->verifyState('published', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');
    }

    /**
     * Test for All Gift Card is unpublish Administrator
     *
     * @depends changeCardPublishButton
     */
    public function changeAllCardUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->changeAllCardUnpublishButton();
        $I->wait(3);
        $I->verifyState('unpublished', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');
    }

    /**
     * Test for All Gift Card is publish Administrator
     *
     * @depends changeAllCardUnpublishButton
     */
    public function changeAllCardPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->changeAllCardPublishButton();
        $I->verifyState('published', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');
    }

    /**
     * Function to Test Gift Card Deletion
     *
     * @depends changeAllCardUnpublishButton
     */
    public function deleteGiftCard(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of Gift Card in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->deleteCard($this->cardNameSaveEdit);
    }
}
