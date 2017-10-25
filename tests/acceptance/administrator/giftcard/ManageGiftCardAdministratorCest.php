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
        $this->randomCardName = $this->faker->bothify('Edit Name ##??');
        $this->cardNameSave = $this->faker->bothify('Card Name ##??');
        $this->cardNameSaveEdit = 'New' . $this->cardNameSave;
        $this->newRandomCardName = $this->faker->bothify('New ##??');
        $this->cardPrice = $this->faker->numberBetween(99, 999);
        $this->cardValue = $this->faker->numberBetween(9, 99);
        $this->cardValidity = $this->faker->numberBetween(1, 15);
    }
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    public function addCard(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test to validate different Missing Fields in the Edit View');
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->addCardNew($this->randomCardName, $this->cardPrice, $this->cardValue, $this->cardValidity,'save');
        $I->addCardNew($this->cardNameSave, $this->cardPrice, $this->cardValue, $this->cardValidity,'saveclose');
    }
    /**
     * Function to Test Gift Card Updation in the Administrator
     *
     * @depends addCard
     */
    public function editCard(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Edit Card with save and save&close ');
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->editCard($this->randomCardName, $this->newRandomCardName,'save');
        $I->editCard($this->newRandomCardName, $this->newRandomCardName,'saveclose');
    }
    /**
     *  Function to validate Missing Field Validations, Error Messages
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function validateMissingFieldEditView(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test to validate different Missing Fields in the Edit View');
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->giftCardEditViewMissingFieldValidation('cardName');
        $I->giftCardEditViewMissingFieldValidation('cardValue');
        $I->giftCardEditViewMissingFieldValidation('cardPrice');
        $I->giftCardEditViewMissingFieldValidation('cardValidity');
    }

    /**
     * Function to validate different buttons on Gift Card Views
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkButtons(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test to validate different buttons on Gift Card Views');
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->checkButtons('edit');
        $I->checkButtons('cancel');
        $I->checkButtons('publish');
        $I->checkButtons('unpublish');
    }

    /**
     *
     * Function check Close button inside Gift Card Edit
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * * @depends editCard
     */
    public function updateCardCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Gift Card gets updated in Administrator');
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
     *
     *
     */
    public function updateGiftCardEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Gift Card gets updated in Administrator');
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->editCardWithEditButton($this->cardNameSave, $this->cardNameSaveEdit);
        $I->searchCard($this->cardNameSaveEdit);
    }

    /**
     * Test for State Change is unpublish in Gift Card Administrator
     *
     *
     */
    public function changeGiftCardStateUnpublish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if State Unpublish of a Gift Card gets Updated in Administrator');
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
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->deleteCard($this->cardNameSaveEdit);
    }
}
