<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class TemplateManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class TemplateManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to create a New Template
	 *
	 * @param   string  $templateName     Name of the Template
	 * @param   string  $templateSection  Section for the Template
	 *
	 * @return void
	 */
	public function addTemplate($templateName = 'Testing', $templateSection = 'Add to cart')
	{
		$I = $this;
		$I->amOnPage(\TemplateManagerJoomla3Page::$URL);
		$templateManagerPage = new \TemplateManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Template Manager Page');
		$I->click('New');
		$I->waitForElement(\TemplateManagerJoomla3Page::$templateName, 30);
		$I->fillField(\TemplateManagerJoomla3Page::$templateName, $templateName);
		$I->click(\TemplateManagerJoomla3Page::$templateSectionDropDown);
		$I->click($templateManagerPage->templateSection($templateSection));
		$I->click('Save & Close');
		$I->waitForText(\TemplateManagerJoomla3Page::$templateSuccessMessage, 60);
		$I->see(\TemplateManagerJoomla3Page::$templateSuccessMessage);
		$I->click('ID');
		$I->see(strtolower($templateName), \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to edit an already created Template
	 *
	 * @param   string  $templateName         Current Name for the Template
	 * @param   string  $templateUpdatedName  New Name for the Template
	 *
	 * @return void
	 */
	public function editTemplate($templateName = 'Current', $templateUpdatedName = 'UpdatedName')
	{
		$I = $this;
		$I->amOnPage(\TemplateManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see(strtolower($templateName), \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click(\TemplateManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\TemplateManagerJoomla3Page::$templateName, 30);
		$I->fillField(\TemplateManagerJoomla3Page::$templateName, $templateUpdatedName);
		$I->click('Save & Close');
		$I->waitForText(\TemplateManagerJoomla3Page::$templateSuccessMessage);
		$I->see(\TemplateManagerJoomla3Page::$templateSuccessMessage);
		$I->see($templateUpdatedName, \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to change State of a Template
	 *
	 * @param   string  $name   Name of the  Template
	 * @param   string  $state  State of the  Template
	 *
	 * @return void
	 */
	public function changeState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\TemplateManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see(strtolower($name), \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click(\TemplateManagerJoomla3Page::$selectFirst);

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
	 * Function to Search for a Template
	 *
	 * @param   string  $name          Name of the Template
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchTemplate($name, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage(\TemplateManagerJoomla3Page::$URL);
		$I->click('ID');

		if ($functionName == 'Search')
		{
			codecept_debug(\TemplateManagerJoomla3Page::$firstResultRow);
			$I->see(strtolower($name), \TemplateManagerJoomla3Page::$firstResultRow);
		}
		else
		{
			$I->dontSee(strtolower($name), \TemplateManagerJoomla3Page::$firstResultRow);
		}

		$I->click('ID');
	}

	/**
	 * Function to get State of the Template
	 *
	 * @param   String  $name  Name of the Template
	 *
	 * @return string
	 */
	public function getState($name)
	{
		$I = $this;
		$I->amOnPage(\TemplateManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see(strtolower($name), \TemplateManagerJoomla3Page::$firstResultRow);
		$text = $I->grabAttributeFrom(\TemplateManagerJoomla3Page::$templateStatePath, 'onclick');

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
	 * Function to Delete Template
	 *
	 * @param   String  $name  Name of the Template which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteTemplate($name)
	{
		$I = $this;
		$I->amOnPage(\TemplateManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see(strtolower($name), \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click(\TemplateManagerJoomla3Page::$selectFirst);
		$I->click('Delete');
		$I->dontSee(strtolower($name), \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}
}