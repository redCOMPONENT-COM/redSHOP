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
	 *
	 * @return void
	 */
	public function addField($name = 'SampleField', $title = 'Field Title', $type = 'Text area', $section = 'Category')
	{
		$I = $this;
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$customFieldsManagerPage = new \CustomFieldManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Fields Manager Page');
		$I->click('New');
		$I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName, 30);
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldName, $name);
		$I->click(\CustomFieldManagerJoomla3Page::$fieldTypeDropDown);
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldTypeSearchField, $type);
		$I->waitForElement($customFieldsManagerPage->fieldType($type), 60);
		$I->click($customFieldsManagerPage->fieldType($type));
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $title);
		$I->click(\CustomFieldManagerJoomla3Page::$fieldSectionDropDown);
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldSectionSearchField, $section);
		$I->waitForElement($customFieldsManagerPage->fieldSection($section), 60);
		$I->click($customFieldsManagerPage->fieldSection($section));
		$I->click('Save & Close');
		$I->waitForText(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage, 60);
		$I->see(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage);
		$I->click('ID');
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
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
		$I->click('ID');
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->click(\CustomFieldManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName, 30);
		$I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $updatedTitle);
		$I->click('Save & Close');
		$I->waitForText(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage);
		$I->see(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage);
		$I->see($updatedTitle, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to change State of a Custom Field
	 *
	 * @param   string  $title  Title of the Custom Field
	 * @param   string  $state  State of the Mail Template
	 *
	 * @return void
	 */
	public function changeState($title, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->click(\CustomFieldManagerJoomla3Page::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}

		$I->click('ID');

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
		$I = $this;
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$I->click('ID');

		if ($functionName == 'Search')
		{
			$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		}
		else
		{
			$I->dontSee($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		}

		$I->click('ID');
	}

	/**
	 * Function to get State of the Custom Field
	 *
	 * @param   String  $title  Title of the Custom Field
	 *
	 * @return string
	 */
	public function getState($title)
	{
		$I = $this;
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$text = $I->grabAttributeFrom(\CustomFieldManagerJoomla3Page::$fieldStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		$I->click('ID');

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
		$I = $this;
		$I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->click(\CustomFieldManagerJoomla3Page::$selectFirst);
		$I->click('Delete');
		$I->dontSee($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}
}
