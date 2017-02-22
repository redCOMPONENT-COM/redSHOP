<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class CustomFieldManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CustomFieldManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a new Custom Field
	 *
	 * @param   string  $name     Name of the new Field
	 * @param   string  $title    Title of the new Field
	 * @param   string  $type     Type of the new Field
	 * @param   string  $section  Section of the new Field
	 * @param   String  $option   Optional Field Value
	 *
	 * @return void
	 */
	public function addField($name = 'SampleField', $title = 'Field Title', $type = 'Text area', $section = 'Category', $option = 'Testing Options')
	{
		$I = $this;
		$fieldsForOptions = 
		array("Check box",
			"Country selection box");
		if ($type == "Check box")
		{
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$customFieldsManagerPage = new \CustomFieldManagerJoomla3Page;
		//$I->verifyNotices(false, $this->checkForNotices(), 'Fields Manager Page');
		$I->click('New');
		$I->click(\CustomFieldManagerJoomla3Page::$fieldTypeDropDown);
		$I->fillField('//*[@id="s2id_autogen1_search"]', $type); 
		$I->waitForElement($customFieldsManagerPage->fieldType($type),30);
		$I->click($customFieldsManagerPage->fieldType($type));
		$I->click(\CustomFieldManagerJoomla3Page::$fieldSectionDropDown);
		$I->fillField('//*[@id="s2id_autogen2_search"]', $section);
		$I->waitForElement($customFieldsManagerPage->fieldSection($section),30);
		$I->click($customFieldsManagerPage->fieldSection($section));
		$I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName,30);
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldName, $name);		
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $title);
		$I->fillField(\CustomFieldManagerJoomla3Page::$optionValueField,$option);
		$I->click('Save & Close');
		$I->waitForText(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage,10,\CustomFieldManagerJoomla3Page::$fieldMessagesLocation);

		if ($type == "Check box")
		{
			$I->executeJS('window.scrollTo(0,0)');
			$I->click(['link' => 'ID']);
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	    }

	    if ($type == "Country selection box")
		{
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$customFieldsManagerPage = new \CustomFieldManagerJoomla3Page;
		//$I->verifyNotices(false, $this->checkForNotices(), 'Fields Manager Page');
		$I->click('New');
		$I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName,30);
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldName, $name);
		$I->click(\CustomFieldManagerJoomla3Page::$fieldTypeDropDown);
		$I->fillField('//*[@id="s2id_autogen1_search"]', $type); 
		$I->waitForElement($customFieldsManagerPage->fieldType($type),30);
		$I->click($customFieldsManagerPage->fieldType($type));
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $title);
		$I->click(\CustomFieldManagerJoomla3Page::$fieldSectionDropDown);
		$I->fillField('//*[@id="s2id_autogen2_search"]', $section);
		$I->waitForElement($customFieldsManagerPage->fieldSection($section),30);
		$I->click($customFieldsManagerPage->fieldSection($section));
		//$I->fillField(\CustomFieldManagerJoomla3Page::$optionValueField,$option);
		$I->click('Save & Close');
		$I->waitForText(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage,10,\CustomFieldManagerJoomla3Page::$fieldMessagesLocation);
        $I->click('//*[@id="editcell"]/div[1]/div[1]/div/input[3]');
		if ($type == "Country selection box")
		{
			$I->executeJS('window.scrollTo(0,0)');
			$I->click(['link' => 'ID']);
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		var_dump($title);
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	    }
	}

	/**
	 * Function to Edit a Field
	 *
	 * @param   string  $title         Current Title of the Field
	 * @param   string  $updatedTitle  New Title for the Field
	 *
	 * @return void
	 */
	public function editField($title = 'Field Title', $updatedTitle = 'New Title')
	{
		$I = $this;
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$I->click(['link' => 'ID']);
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->click(\CustomFieldManagerJoomla3Page::$selectFirst);
		$I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName,30);
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $updatedTitle);
		$I->click('Save & Close');
		
	}

	/**
	 * Function to change State of a Custom Field
	 *
	 * @param   string  $title  Title of the Custom Field
	 * @param   string  $state  State of the Mail Template
	 *
	 * @return void
	 */
	public function changeFieldState($title, $state = 'unpublish')
	{
		$this->changeState(new \CustomFieldManagerJoomla3Page, $title, $state, \CustomFieldManagerJoomla3Page::$firstResultRow, \CustomFieldManagerJoomla3Page::$selectFirst);	
		
	}

	/**
	 * Function to Search for a Custom Field
	 *
	 * @param   string  $title         Title of the Field
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchField($title, $functionName = 'Search')
	{
		$this->search(new \CustomFieldManagerJoomla3Page, $title, \CustomFieldManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Custom Field
	 *
	 * @param   String  $title  Title of the Custom Field
	 *
	 * @return string
	 */
	public function getFieldState($title)
	{
		$result = $this->getState(new \CustomFieldManagerJoomla3Page, $title, \CustomFieldManagerJoomla3Page::$firstResultRow, \CustomFieldManagerJoomla3Page::$fieldStatePath);

		return $result;
	}

	/**
	 * Function to Delete Custom Field
	 *
	 * @param   String  $title  Title of the Field which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteCustomField($title)
	{
		$this->delete(new \CustomFieldManagerJoomla3Page, $title, \CustomFieldManagerJoomla3Page::$firstResultRow, \CustomFieldManagerJoomla3Page::$selectFirst);
	}
}
