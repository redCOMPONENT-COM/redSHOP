<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\MailCenterSteps;
use Codeception\Scenario;

/**
 * Class MailCenterCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class MailCenterCest
{
	/**
	 * @var  string
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $subject;

	/**
	 * @var string
	 */
	public $bcc;

	/**
	 * @var string
	 */
	public $mailSection;

	/**
	 * @var string
	 */
	public $newName;

	/**
	 * MailCenterCest constructor.
	 */
	public function __construct()
	{
		$this->faker       = Faker\Factory::create();
		$this->name        = $this->faker->bothify('MailCenterCest ?##?');
		$this->subject     = $this->faker->bothify('MailCenterCest Subject ?##?');
		$this->bcc         = 'BCC Test' . $this->faker->numberBetween(100, 1000);
		$this->mailSection = 'Ask question about product';
		$this->newName     = 'Updated ' . $this->name;
	}

	/**
	 * Function to Test Mail Center Creation in Backend
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function createMailCenter(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test Mail Center creation in Administrator');
		$client->doAdministratorLogin();
		$client = new MailCenterSteps($scenario);
		$client->addMail($this->name, $this->subject, $this->bcc, $this->mailSection);
		$client->searchMail($this->name);
		$client->see($this->name);
	}

	/**
	 * Function to Test Mail Center Update in the Administrator
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends createMailCenter
	 */
	public function updateMailCenter(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test if Mail Center gets updated in Administrator');
		$client->doAdministratorLogin();
		$client = new MailCenterSteps($scenario);
		$client->editMail($this->name, $this->newName);
	}

	/**
	 * Test for State Change in Mail Center Administrator
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends updateMailCenter
	 */
	public function changeMailCenterState(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Test if State of a Mail Center gets Updated in Administrator');
		$client->doAdministratorLogin();
		$client = new MailCenterSteps($scenario);
		$client->changeMailState($this->newName);
		$client->verifyState('unpublished', $client->getMailState($this->newName));
	}

	/**
	 * Function to Test Mail Center Deletion
	 *
	 * @param   AcceptanceTester  $client    Acceptance Tester case.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends changeMailCenterState
	 */
	public function deleteMailCenter(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('Deletion of Mail Center in Administrator');
		$client->doAdministratorLogin();
		$client = new MailCenterSteps($scenario);
		$client->deleteMailTemplate($this->newName);
	}
}
