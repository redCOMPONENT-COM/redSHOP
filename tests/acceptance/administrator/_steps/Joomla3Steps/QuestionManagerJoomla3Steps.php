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
		$I->waitForText(QuestionManagerJoomla3Page::$buttonNew, 30);
		$I->click(QuestionManagerJoomla3Page::$buttonNew);
		$I->waitForElement(QuestionManagerJoomla3Page::$userPhone, 30);
		$I->fillField(QuestionManagerJoomla3Page::$userPhone, $questionInformation['phone']);
		$I->waitForElement(QuestionManagerJoomla3Page::$userAddress, 30);
		$I->fillField(QuestionManagerJoomla3Page::$userAddress, $questionInformation['address']);
		$I->waitForElementVisible(QuestionManagerJoomla3Page::$productNameDropDown, 30);
		$I->click(QuestionManagerJoomla3Page::$productNameDropDown);
		$I->fillField(QuestionManagerJoomla3Page::$productNameSearchField, $productName);
		$I->waitForElement($questionManagerPage->productName($productName), 30);
		$I->click($questionManagerPage->productName($productName));
		$I->scrollTo(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor, 0, -200);
		$I->waitForElementVisible(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor, 30);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->waitForText(QuestionManagerJoomla3Page::$buttonToggle, 30);
		$I->click(QuestionManagerJoomla3Page::$buttonToggle);
		$I->waitForElementVisible(QuestionManagerJoomla3Page::$question, 10);
		$I->click(QuestionManagerJoomla3Page::$question);

		$I->fillField(QuestionManagerJoomla3Page::$question, $questionInformation ['question']);
		$I->waitForText(QuestionManagerJoomla3Page::$buttonSaveClose, 30);
		$I->click(QuestionManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(QuestionManagerJoomla3Page::$questionSuccessMessage, 30, QuestionManagerJoomla3Page::$idInstallSuccess);
		$I->see(QuestionManagerJoomla3Page::$questionSuccessMessage, QuestionManagerJoomla3Page::$idInstallSuccess);
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
		$I->waitForText(QuestionManagerJoomla3Page::$buttonEdit, 30);
		$I->click(QuestionManagerJoomla3Page::$buttonEdit);
		$I->waitForElement(QuestionManagerJoomla3Page::$userPhone, 30);
		$I->scrollTo(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor,0,-200);
		$I->waitForElementVisible(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor, 30);
		$I->click(QuestionManagerJoomla3Page::$toggleQuestionDescriptionEditor);
		$I->waitForText(QuestionManagerJoomla3Page::$buttonToggle, 10);
		$I->click(QuestionManagerJoomla3Page::$buttonToggle);
		$I->waitForElementVisible(QuestionManagerJoomla3Page::$question, 30);
		$I->click(QuestionManagerJoomla3Page::$question);
		$I->fillField(QuestionManagerJoomla3Page::$question, $questionInformation['edit']);
		$I->waitForText(QuestionManagerJoomla3Page::$buttonSaveClose, 30);
		$I->click(QuestionManagerJoomla3Page::$buttonSaveClose);
		$I->waitForText(QuestionManagerJoomla3Page::$questionSuccessMessage, 30, QuestionManagerJoomla3Page::$idInstallSuccess);
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
	 * @throws \Exception
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
		$I->waitForText(QuestionManagerJoomla3Page::$buttonDelete, 30);
		$I->click(QuestionManagerJoomla3Page::$buttonDelete);
		$I->acceptPopup();
	}

	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $questionInformation
	 * @param $user
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function addQuestionOnProductDetailOnFrontend($productName, $categoryName, $questionInformation, $user = array())
	{
		$I = $this;

		if((isset($user)))
		{
			$I->doFrontEndLogin($user['userName'], $user['password']);
		}

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->checkForPhpNoticesOrWarnings();
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonWriteQuestion);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonWriteQuestion);

		if((isset($user)))
		{
			try
			{
				$I->executeJS(FrontEndProductManagerJoomla3Page::jQueryIframe());
				$I->wait(1);
				$I->switchToIFrame(FrontEndProductManagerJoomla3Page::$nameIframe);
				$I->waitForElementVisible(QuestionManagerJoomla3Page::$fieldYourQuestion, 30);
				$I->fillField(QuestionManagerJoomla3Page::$fieldYourQuestion, $questionInformation['question2']);
				$I->waitForElementVisible(QuestionManagerJoomla3Page::$sendButton, 10);
				$I->click(QuestionManagerJoomla3Page::$sendButton);
			}catch (\Exception $exception)
			{
			}
		}else
		{
			try
			{
				$I->executeJS(FrontEndProductManagerJoomla3Page::jQueryIframe());
				$I->wait(1);
				$I->switchToIFrame(FrontEndProductManagerJoomla3Page::$nameIframe);
				$I->waitForElementVisible(QuestionManagerJoomla3Page::$fieldNameQuestion, 30);
				$I->fillField(QuestionManagerJoomla3Page::$fieldNameQuestion, $questionInformation['userName']);
				$I->fillField(QuestionManagerJoomla3Page::$fieldEmailQuestion, $questionInformation['email']);
				$I->fillField(QuestionManagerJoomla3Page::$fieldYourQuestion, $questionInformation['question1']);
				$I->waitForElementVisible(QuestionManagerJoomla3Page::$sendButton, 10);
				$I->click(QuestionManagerJoomla3Page::$sendButton);
			}catch (\Exception $exception)
			{
			}
		}
	}

	/**
	 * @param $productName
	 * @param $questionInformation
	 * @throws \Exception
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
		$I->waitForText(QuestionManagerJoomla3Page::$buttonEdit, 60);
		$I->click(QuestionManagerJoomla3Page::$buttonEdit);
		$I->see($productName);
		$I->waitForText(QuestionManagerJoomla3Page::$buttonClose, 60);
		$I->click(QuestionManagerJoomla3Page::$buttonClose);
	}

	/**
	 * Delete All Question
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function deleteAll()
	{
		$I = $this;
		$I->amOnPage(QuestionManagerJoomla3Page::$URL);
		$I->checkAllResults();
		$I->waitForText(QuestionManagerJoomla3Page::$buttonDelete, 30);
		$I->click(QuestionManagerJoomla3Page::$buttonDelete);
		$I->acceptPopup();
	}
}
