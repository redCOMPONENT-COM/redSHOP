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
	 * @param $productName
	 * @param $questionInformation
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function addQuestion($productName, $questionInformation)
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$questionManagerPage = new QuestionManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Question Manager Page');
		$I->click('New');
		$I->waitForElement(QuestionManagerJoomla3Page::$userPhone,30);
		$I->fillField(QuestionManagerJoomla3Page::$userPhone, $questionInformation['phone']);
		$I->waitForElement(QuestionManagerJoomla3Page::$userAddress,30);
		$I->fillField(QuestionManagerJoomla3Page::$userAddress, $questionInformation['address']);
		$I->click(QuestionManagerJoomla3Page::$productNameDropDown);
		$I->fillField(QuestionManagerJoomla3Page::$productNameSearchField, $productName);
		$I->waitForElement($questionManagerPage->productName($productName),60);
		$I->click($questionManagerPage->productName($productName));
		$I->scrollTo(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor,0,-200);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->click(QuestionManagerJoomla3Page::$buttonToggle);
		$I->click(QuestionManagerJoomla3Page::$question);

		$I->fillField(QuestionManagerJoomla3Page::$question, $questionInformation ['question']);
		$I->click(QuestionManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(QuestionManagerJoomla3Page::$questionSuccessMessage,60,QuestionManagerJoomla3Page::$idInstallSuccess);
		$I->see(QuestionManagerJoomla3Page::$questionSuccessMessage,QuestionManagerJoomla3Page::$idInstallSuccess);
	}

	/**
	 * @param $questionInformation
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function editQuestion($questionInformation)
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField(QuestionManagerJoomla3Page::$searchField, $questionInformation['question']);
		$I->pressKey(QuestionManagerJoomla3Page::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->wait(1);
		$I->checkOption(QuestionManagerJoomla3Page::$selectFirst);
		$I->click(QuestionManagerJoomla3Page::$buttonEdit);
		$I->waitForElement(QuestionManagerJoomla3Page::$userPhone,30);
		$I->scrollTo(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor,0,-200);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->click(QuestionManagerJoomla3Page::$buttonToggle);
		$I->click(QuestionManagerJoomla3Page::$question);

		$I->fillField(QuestionManagerJoomla3Page::$question, $questionInformation['edit']);
		$I->click(QuestionManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(QuestionManagerJoomla3Page::$questionSuccessMessage,60, QuestionManagerJoomla3Page::$idInstallSuccess);
		$I->see(QuestionManagerJoomla3Page::$questionSuccessMessage, QuestionManagerJoomla3Page::$idInstallSuccess);
	}

	/**
	 * @param $questionInformation
	 * @param string $state
	 * @since 3.0.2
	 */
	public function changeQuestionState($questionInformation, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField(QuestionManagerJoomla3Page::$searchField, $questionInformation['edit']);
		$I->pressKey(QuestionManagerJoomla3Page::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
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
	 * @param $questionInformation
	 * @since 3.0.2
	 */
	public function deleteQuestion($questionInformation)
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField(QuestionManagerJoomla3Page::$searchField, $questionInformation['edit']);
		$I->pressKey(QuestionManagerJoomla3Page::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->checkOption(QuestionManagerJoomla3Page::$selectFirst);
		$I->click(QuestionManagerJoomla3Page::$buttonDelete);
		$I->acceptPopup();

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

	/**
	 * @param $productName
	 * @param $questionInformation
	 * @since 3.0.2
	 */
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
