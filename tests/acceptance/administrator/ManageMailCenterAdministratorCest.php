<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageMailCenterAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageMailCenterAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('ManageMailCenterAdministratorCest ?##?');
		$this->subject = $this->faker->bothify('ManageMailCenterAdministratorCest Subject ?##?');
		$this->bcc = 'BCC Test' . $this->faker->numberBetween(100, 1000);
		$this->mailSection = 'Ask question about product';
		$this->newName = 'Updated ' . $this->name;
	}

	/**
	 * Function to Test Mail Center Creation in Backend
	 *
	 */
	public function createMailCenter(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Mail Center creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MailCenterManagerJoomla3Steps($scenario);
		$I->addMail($this->name, $this->subject, $this->bcc, $this->mailSection);
		$I->searchMail($this->name);

	}

	/**
	 * Function to Test Mail Center Update in the Administrator
	 *
	 * @depends createMailCenter
	 */
	public function updateMailCenter(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Mail Center gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MailCenterManagerJoomla3Steps($scenario);
		$I->editMail($this->name, $this->newName);
	}

	/**
	 * Test for State Change in Mail Center Administrator
	 *
	 * @depends updateMailCenter
	 */
	public function changeMailCenterState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Mail Center gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MailCenterManagerJoomla3Steps($scenario);
		$I->changeMailState($this->newName);
		$I->verifyState('unpublished', $I->getMailState($this->newName));
	}

	/**
	 * Function to Test Mail Center Deletion
	 *
	 * @depends changeMailCenterState
	 */
	public function deleteMailCenter(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Mail Center in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\MailCenterManagerJoomla3Steps($scenario);
		$I->deleteMailTemplate($this->newName);
		$I->searchMail($this->newName, 'Delete');
	}
}
