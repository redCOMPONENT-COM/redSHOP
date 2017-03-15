<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class TextLibraryManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class TextLibraryManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Create a new Text Library Tag
	 *
	 * @param   string  $textTagName         Name of the Text Tag
	 * @param   string  $textTagDescription  Description of the Text
	 * @param   string  $textTagSection      Section under which it is supposed to be created
	 *
	 * @return void
	 */
	public function createText($textTagName = 'Testing Text Name', $textTagDescription = 'Testing Description', $textTagSection = 'Product')
	{
		$I = $this;
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=textlibrary');
		$textLibraryManagerPage = new \TextLibraryManagerJoomla3Page;
		$verifyName = '{' . $textTagName . '}';
		$I->verifyNotices(false, $this->checkForNotices(), 'Text Library  Manager Page');
		$I->click('New');
		$I->waitForElement(\TextLibraryManagerJoomla3Page::$textTagName,30);
		$I->fillField(\TextLibraryManagerJoomla3Page::$textTagName, $textTagName);
		$I->fillField(\TextLibraryManagerJoomla3Page::$textTagDescription, $textTagDescription);
		$I->click(\TextLibraryManagerJoomla3Page::$sectionDropDown);
		$I->fillField(['xpath' => "//input[@id='s2id_autogen1_search']"], $textTagSection);
		$I->waitForElement($textLibraryManagerPage->section($textTagSection), 30);
		$I->click($textLibraryManagerPage->section($textTagSection));
		$I->click('Save & Close');
		$I->waitForText(\TextLibraryManagerJoomla3Page::$textCreationSuccessMessage,60,'.alert-success');
		$I->see(\TextLibraryManagerJoomla3Page::$textCreationSuccessMessage, '.alert-success');
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField(['id' => 'filter'], $textTagName);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $verifyName]);
		$I->seeElement(['link' => $verifyName]);
	}

	/**
	 * Function to edit an already create Text Library Tag
	 *
	 * @param   string  $textTagName     Old name for the Tag
	 * @param   string  $newTextTagName  New Name for the Updated text Library Tag
	 *
	 * @return void
	 */
	public function editText($textTagName = 'Testing Text', $newTextTagName = 'Updating Testing')
	{
		$I = $this;
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=textlibrary');
		$I->executeJS('window.scrollTo(0,0)');
		$verifyName = '{' . $textTagName . '}';
		$newVerifyName = '{' . $newTextTagName . '}';
		$I->waitForText('Text Library Management', 60, ['css' => 'h1']);
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField(['id' => 'filter'], $textTagName);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $verifyName]);
		$I->seeElement(['link' => $verifyName]);
		$I->click(\TextLibraryManagerJoomla3Page::$firstResult);
		$I->click('Edit');
		$I->waitForElement(\TextLibraryManagerJoomla3Page::$textTagName,30);
		$I->fillField(\TextLibraryManagerJoomla3Page::$textTagName, $newTextTagName);
		$I->click('Save & Close');
		$I->waitForText('Text Library Detail Saved', 60, ['id' => 'system-message-container']);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
		$I->fillField(['id' => 'filter'], $newTextTagName);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $newVerifyName]);
		$I->seeElement(['link' => $newVerifyName]);
		$I->dontSeeElement(['link' => $verifyName]);
	}

	/**
	 * Function to Search for a Text Library
	 *
	 * @param   string  $textTagName   Name of the text which we need to search for
	 * @param   string  $functionName  Name of the function After which search is being called
	 *
	 * @return void
	 */
	public function searchText($textTagName, $functionName = 'Search')
	{
		$verifyName = '{' . $textTagName . '}';
		$this->search(new \TextLibraryManagerJoomla3Page, $verifyName, \TextLibraryManagerJoomla3Page::$textResultRow, $functionName);
	}

	/**
	 * Function to Change State of the Text
	 *
	 * @param   string  $textTagName  Name of the Text for which state is to be changed
	 * @param   string  $state        State for the Text Tag
	 *
	 * @return void
	 */
	public function changeTextLibraryState($textTagName = 'Sample', $state = 'unpublish')
	{
		$I = $this;
		$verifyName = '{' . $textTagName . '}';
 		$I->amOnPage('/administrator/index.php?option=com_redshop&view=textlibrary');
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
		$I->fillField(['id' => 'filter'], $textTagName);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $verifyName]);

		if ($state == 'unpublish')
		{
			$I->click(['css' => "a[data-original-title='Unpublish Item']"], 0);
			$I->waitForText('Text Library Detail UnPublished Successfully', 60, ['id' => 'system-message-container']);
		}
		else
		{
			$I->click(['css' => "a[data-original-title='Publish Item']"], 0);
			$I->waitForText('Text Library Detail Published Successfully', 60, ['id' => 'system-message-container']);
		}
	}

	/**
	 * Function to Delete Text
	 *
	 * @param   string  $textTagName  Name of the Text Field
	 *
	 * @return void
	 */
	public function deleteText($textTagName)
	{
		$I = $this;
		$verifyName = '{' . $textTagName . '}';
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=textlibrary');
		$I->waitForText('Text Library Management', 60, ['css' => 'h1']);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
		$I->fillField(['id' => 'filter'], $textTagName);
		$I->pressKey(['id' => 'filter'], \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForText('Text Library Management', 60, ['css' => 'h1']);
		$I->click(['css' => "input[name='checkall-toggle']"]);
		$I->click('Delete');
		$I->waitForText('Text Library Detail Deleted Successfully', 60, ['id' => 'system-message-container']);
		$I->dontSeeElement(['link' => $verifyName]);
	}
}
