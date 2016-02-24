<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
/**
 * Class ManageQuestionAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageQuestionAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->product = 'redSHOEMANIAC';
		$this->question = $this->faker->bothify('ManageQuestionAdministratorCest ?##?');
		$this->userContactNumber = $this->faker->numberBetween(10000, 1000000);
		$this->updatedQuestion = 'Updated ' . $this->question;
	}

	/**
	 * Function to Test Questions Creation in Backend
	 *
	 */
	public function createQuestion(AcceptanceTester $I, $scenario)
	{
		$scenario->skip('@fixme: temporarily skiped due to REDSHOP-2811');
		$I->wantTo('Test Question creation in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\QuestionManagerJoomla3Steps($scenario);
		$I->addQuestion($this->product, $this->userContactNumber, $this->question);
		$I->searchQuestion($this->question);
	}

	/**
	 * Function to Test Question Updation in the Administrator
	 *
	 * @depends createQuestion
	 */
	public function updateQuestion(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Question gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\QuestionManagerJoomla3Steps($scenario);
		$I->editQuestion($this->question, $this->updatedQuestion);
		$I->searchQuestion($this->updatedQuestion);
	}

	/**
	 * Test for State Change in Question Administrator
	 *
	 * @depends updateQuestion
	 */
	public function changeQuestionState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Question gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\QuestionManagerJoomla3Steps($scenario);
		$I->changeQuestionState($this->updatedQuestion);
		$I->verifyState('unpublished', $I->getQuestionState($this->updatedQuestion));
	}

	/**
	 * Function to Test Question Deletion
	 *
	 * @depends changeQuestionState
	 */
	public function deleteQuestion(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Question in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\QuestionManagerJoomla3Steps($scenario);
		$I->deleteQuestion($this->updatedQuestion);
		$I->searchQuestion($this->updatedQuestion, 'Delete');
	}
}
