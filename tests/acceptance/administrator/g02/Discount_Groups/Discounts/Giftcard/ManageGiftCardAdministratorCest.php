<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\GiftCardManagerJoomla3Steps;

/**
 * Class ManageGiftCardAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4.0
 */
class ManageGiftCardAdministratorCest
{
	/**
	 * @var \Faker\Generator
	 * @since 1.4.0
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $randomCardName;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $cardNameSave;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $cardNameSaveEdit;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	protected $newRandomCardName;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $cardPrice;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $cardValue;

	/**
	 * @var int
	 * @since 1.4.0
	 */
	protected $cardValidity;

	/**
	 * ManageGiftCardAdministratorCest constructor.
	 * @since 1.4.0
	 */
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

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 1.4.0
     * @throws \Exception
	 */
	public function addCard(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different Missing Fields in the Edit View');
		$I = new GiftCardManagerJoomla3Steps($scenario);
		$I->wantTo('Create new cart with save button');
		$I->addCardNew($this->randomCardName, $this->cardPrice, $this->cardValue, $this->cardValidity,'save');
		$I->wantTo('Edit the cart above with save button');
		$I->editCard($this->randomCardName, $this->newRandomCardName,'save');

		$I->wantTo('Create card with save and close button ');
		$I->addCardNew($this->cardNameSave, $this->cardPrice, $this->cardValue, $this->cardValidity,'saveclose');
		$I->wantTo('Edit the cart above with save and close button');
		$I->editCard($this->newRandomCardName, $this->newRandomCardName,'saveclose');
	}

	/**
	 *  Function to validate Missing Field Validations, Error Messages
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
     * @throws \Exception
	 * @since 1.4.0
	 */
	public function validateMissingFieldEditView(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different Missing Fields in the Edit View');
		$I = new GiftCardManagerJoomla3Steps($scenario);
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
     * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkButtons(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different buttons on Gift Card Views');
		$I = new GiftCardManagerJoomla3Steps($scenario);
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
     * @throws \Exception
	 * @since 1.4.0
	 */
	public function updateCardCloseButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Gift Card gets updated in Administrator');
		$I = new GiftCardManagerJoomla3Steps($scenario);
		$I->editCardCloseButton($this->newRandomCardName);
		$I->searchCard($this->newRandomCardName);

		$I->wantTo('Test if Gift Card gets updated in Administrator');
		$I->editCardWithEditButton($this->cardNameSave, $this->cardNameSaveEdit);
		$I->searchCard($this->cardNameSaveEdit);

		$I->wantTo('Test if State Unpublish of a Gift Card gets Updated in Administrator');
		$I->changeCardState($this->cardNameSaveEdit);
		$I->verifyState('unpublished', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');

		$I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
		$I->changeCardState($this->cardNameSaveEdit);
		$I->verifyState('published', $I->getCardState($this->cardNameSaveEdit), 'State Must be published');

		$I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
		$I->changeCardUnpublishButton($this->cardNameSaveEdit);
		$I->verifyState('unpublished', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');

		$I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
		$I->changeCardPublishButton($this->cardNameSaveEdit);
		$I->verifyState('published', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');

		$I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
		$I->changeAllCardUnpublishButton();
		$I->verifyState('unpublished', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');

		$I->wantTo('Test  State Publish of a Gift Card gets Updated in Administrator');
		$I->changeAllCardPublishButton();
		$I->verifyState('published', $I->getCardState($this->cardNameSaveEdit), 'State Must be Unpublished');

		$I->wantTo('Deletion of Gift Card in Administrator');
		$I->deleteCard($this->cardNameSaveEdit);
	}
}
