<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use QuestionManagerJoomla3Page;
use FrontEndProductManagerJoomla3Page;

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
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addQuestion($productName, $question)
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$questionManagerPage = new QuestionManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Question Manager Page');
		$I->click('New');
		$I->waitForElement(QuestionManagerJoomla3Page::$userPhone,30);
		$I->fillField(QuestionManagerJoomla3Page::$userPhone, $question['phone']);
		$I->click(QuestionManagerJoomla3Page::$productNameDropDown);
		$I->fillField(QuestionManagerJoomla3Page::$productNameSearchField, $productName);
		$I->waitForElement($questionManagerPage->productName($productName),60);
		$I->click($questionManagerPage->productName($productName));
		$I->scrollTo(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor,0,-200);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->fillField(QuestionManagerJoomla3Page::$question, $question['question']);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->click('Save & Close');
		$I->waitForText(QuestionManagerJoomla3Page::$questionSuccessMessage,60,QuestionManagerJoomla3Page::$idInstallSuccess);
		$I->see(QuestionManagerJoomla3Page::$questionSuccessMessage,QuestionManagerJoomla3Page::$idInstallSuccess);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
		$I->wait(1);
		$I->fillField(['id' => 'filter'], $question);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $productName]);
		$I->seeElement(['link' => $productName]);
	}

	/**
	 * Function to edit an pre-exisiting Question against a Product
	 *
	 * @param   string  $question         Old Question
	 * @param   string  $updatedQuestion  New Question
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editQuestion($question = 'Why is this Happening', $updatedQuestion = 'Updated question')
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($question, QuestionManagerJoomla3Page::$firstResultRow);
		$I->click(QuestionManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(QuestionManagerJoomla3Page::$userPhone,30);
		$I->scrollTo(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor,0,-200);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->fillField(QuestionManagerJoomla3Page::$question, $updatedQuestion);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->click('Save & Close');
		$I->waitForText(QuestionManagerJoomla3Page::$questionSuccessMessage,60, QuestionManagerJoomla3Page::$idInstallSuccess);
		$I->see(QuestionManagerJoomla3Page::$questionSuccessMessage, QuestionManagerJoomla3Page::$idInstallSuccess);
		$I->see($updatedQuestion, QuestionManagerJoomla3Page::$firstResultRow);
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to change State of a Question
	 *
	 * @param   string  $question  Question
	 * @param   string  $state     State of the Question
	 *
	 * @return void
	 * @since 1.4.0
	 */
	public function changeQuestionState($question, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
		$I->wait(1);
		$I->fillField(['id' => 'filter'], $question);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->wait(1);
		$I->checkOption(QuestionManagerJoomla3Page::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}
	}

	/**
	 * Function to Search for a Question
	 *
	 * @param   string  $question      Question
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 * @since 1.4.0
	 */
	public function searchQuestion($question, $functionName = 'Search')
	{
		$this->search(new QuestionManagerJoomla3Page, $question, QuestionManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Question
	 *
	 * @param   String  $question  Question  for which state is to be fetched
	 *
	 * @return string
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function getQuestionState($question)
	{
		$result = $this->getState(new QuestionManagerJoomla3Page, $question, QuestionManagerJoomla3Page::$firstResultRow, QuestionManagerJoomla3Page::$questionStatePath);

		return $result;
	}

	/**
	 * Function to Delete Question
	 *
	 * @param   String  $question  Question which  is to be Deleted
	 *
	 * @return void
	 * @since 1.4.0
	 */
	public function deleteQuestion($question)
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
		$I->wait(1);
		$I->fillField(['id' => 'filter'], $question);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->wait(1);
		$I->checkOption(QuestionManagerJoomla3Page::$selectFirst);
		$I->click('Delete');
		$I->wait(1);
		$I->dontSee($question, ['id' => 'adminform']);
	}


	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $questionInformation
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function addQuestionOnProductDetailOnFrontendMissingLogin($productName, $categoryName, $questionInformation)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv,30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList,30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonWriteQuestion);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonWriteQuestion);

		$I->executeJS(FrontEndProductManagerJoomla3Page::jQueryIframe());
		$I->wait(0.5);
		$I->switchToIFrame(FrontEndProductManagerJoomla3Page::$nameIframe);

		$I->fillField(QuestionManagerJoomla3Page::$fieldNameQuestion, $questionInformation['userName']);
		$I->fillField(QuestionManagerJoomla3Page::$fieldEmailQuestion, $questionInformation['email']);
		$I->fillField(QuestionManagerJoomla3Page::$fieldYourQuestion, $questionInformation['question1']);
		$I->waitForElementVisible(QuestionManagerJoomla3Page::$sendButton, 10);
		$I->click(QuestionManagerJoomla3Page::$sendButton);

	}

	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $questionInformation
	 * @param $user
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function addQuestionOnProductDetailOnFrontendLogin($productName, $categoryName, $questionInformation, $user)
	{
		$I = $this;
		$I->doFrontEndLogin($user['userName'], $user['password']);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv,30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList,30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonWriteQuestion);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonWriteQuestion);

		$I->executeJS(FrontEndProductManagerJoomla3Page::jQueryIframe());
		$I->wait(0.5);
		$I->switchToIFrame(FrontEndProductManagerJoomla3Page::$nameIframe);

		$I->fillField(QuestionManagerJoomla3Page::$fieldYourQuestion, $questionInformation['question2']);
		$I->waitForElementVisible(QuestionManagerJoomla3Page::$sendButton, 10);
		$I->click(QuestionManagerJoomla3Page::$sendButton);
	}

	public function checkQuestionInAdministrator($productName, $questionInformation)
	{
		$I = $this;
		$I->wantTo('Search the Question');
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
		$I->wait(1);
		$I->fillField(QuestionManagerJoomla3Page::$searchField, $questionInformation['question1']);
		$I->pressKey(QuestionManagerJoomla3Page::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->wait(1);
		$I->checkOption(QuestionManagerJoomla3Page::$selectFirst);
		$I->click(QuestionManagerJoomla3Page::$buttonEdit);
		$I->see($productName);
		$I->click(QuestionManagerJoomla3Page::$buttonClose);
	}

	public function deleteAll()
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->checkAllResults();
		$I->click(QuestionManagerJoomla3Page::$buttonDelete);
		$I->acceptPopup();
	}
}
