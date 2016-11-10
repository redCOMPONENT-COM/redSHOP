<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class QuestionManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class QuestionManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a new Question for a Product
	 *
	 * @param   string  $productName      Name of the Product for which the Question is
	 * @param   string  $userPhoneNumber  Phone number of the User Posting Question
	 * @param   string  $question         Question which is to be asked
	 *
	 * @return void
	 */
	public function addQuestion($productName = 'TestingCategory554', $userPhoneNumber = '123456', $question = 'Why is this Happening')
	{
		$I = $this;
		$I->wantTo('Create a Category');
		$I->amOnPage(\QuestionManagerJoomla3Page::$URL);
		$questionManagerPage = new \QuestionManagerJoomla3Page;
		//$I->verifyNotices(false, $this->checkForNotices(), 'Question Manager Page');
		$I->click('New');
		$I->waitForElement(\QuestionManagerJoomla3Page::$userPhone,30);
		$I->fillField(\QuestionManagerJoomla3Page::$userPhone, $userPhoneNumber);
		$I->click(\QuestionManagerJoomla3Page::$productNameDropDown);
		$I->fillField(['id' => 's2id_autogen1_search'], 'Product 1');
		$I->waitForElement(['css' => 'span.select2-match'], 60);
		$I->click(['css' => 'span.select2-match']);
		$I->scrollTo(\QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor,0,-200);
		$I->click(\QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->fillField(\QuestionManagerJoomla3Page::$newquestion, $question, 60);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(\QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		
		$I->click('Save & Close');
		$I->waitForText(\QuestionManagerJoomla3Page::$questionSuccessMessage,60,['id' => 'system-message-container']);
		$I->see(\QuestionManagerJoomla3Page::$questionSuccessMessage,['id' => 'system-message-container']);
		
	}

	/**
	 * Function to edit an pre-exisiting Question against a Product
	 *
	 * @param   string  $question         Old Question
	 * @param   string  $updatedQuestion  New Question
	 *
	 * @return void
	 */
	public function editQuestion($question = 'Why is this Happening', $updatedQuestion = 'Updated question')
	{
		$I = $this;
		$I->amOnPage(\QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('//*[@id="reset"]');
		$I->wait(1);
		$I->fillField(['id' => 'filter'], $question);
		$I->pressKey(['id' => 'filter'], \WebDriverKeys::ENTER);
		$I->click(\QuestionManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\QuestionManagerJoomla3Page::$userPhone,30);
		$I->scrollTo(\QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor,0,-200);
		$I->click(\QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->fillField(\QuestionManagerJoomla3Page::$newquestion, $updatedQuestion, 60);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(\QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->click('Save & Close');
		$I->waitForText(\QuestionManagerJoomla3Page::$questionSuccessMessage,60,['id' => 'system-message-container']);
		$I->see(\QuestionManagerJoomla3Page::$questionSuccessMessage, ['id' => 'system-message-container']);
		
		
	}

	/**
	 * Function to change State of a Question
	 *
	 * @param   string  $question  Question
	 * @param   string  $state     State of the Question
	 *
	 * @return void
	 */
	public function changeQuestionState($updatedQuestion, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('//*[@id="reset"]');
		$I->fillField(['id' => 'filter'], $updatedQuestion);
		$I->pressKey(['id' => 'filter'], \WebDriverKeys::ENTER);		
		$I->click(\QuestionManagerJoomla3Page::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
			$I->waitForText(\QuestionManagerJoomla3Page::$Messagechangestate,60,['id' => 'system-message-container']);
		    $I->see(\QuestionManagerJoomla3Page::$Messagechangestate, ['id' => 'system-message-container']); 

		}
		else
		{
			$I->click("Unpublish");
			$I->waitForText(\QuestionManagerJoomla3Page::$Messagechangestate,60,['id' => 'system-message-container']);
		    $I->see(\QuestionManagerJoomla3Page::$Messagechangestate, ['id' => 'system-message-container']);
		}
	}

	/**
	 * Function to Search for a Question
	 *
	 * @param   string  $question      Question
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchQuestion($question, $functionName = 'Search')
	{
		$this->search(new \QuestionManagerJoomla3Page, $question, \QuestionManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Question
	 *
	 * @param   String  $question  Question  for which state is to be fetched
	 *
	 * @return string
	 */
	public function getQuestionState($question)
	{
		$result = $this->getState(new \QuestionManagerJoomla3Page, $question, \QuestionManagerJoomla3Page::$firstResultRow, \QuestionManagerJoomla3Page::$questionStatePath);

		return $result;
	}

	/**
	 * Function to Delete Question
	 *
	 * @param   String  $question  Question which  is to be Deleted
	 *
	 * @return void
	 */
	public function deleteQuestion($updatedQuestion)
	{
		$I = $this;
		$I->amOnPage(\QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('//*[@id="reset"]');
		$I->fillField(['id' => 'filter'], $updatedQuestion);
		$I->pressKey(['id' => 'filter'], \WebDriverKeys::ENTER);		
		$I->click(\QuestionManagerJoomla3Page::$selectFirst);
		$I->click('Delete');
		$I->waitForText(\QuestionManagerJoomla3Page::$Messagedeletestate,60,['id' => 'system-message-container']);
		$I->see(\QuestionManagerJoomla3Page::$Messagedeletestate, ['id' => 'system-message-container']);
		$I->dontSee($updatedQuestion);
	}
}
