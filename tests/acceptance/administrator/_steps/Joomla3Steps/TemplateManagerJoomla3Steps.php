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
		$I->waitForElement(\TemplateManagerJoomla3Page::$templateName,30);
		$I->fillField(\TemplateManagerJoomla3Page::$templateName, $templateName);
		$I->click(\TemplateManagerJoomla3Page::$templateSectionDropDown);
		$I->click($templateManagerPage->templateSection($templateSection));
		$I->click('Save & Close');
		$I->waitForText(\TemplateManagerJoomla3Page::$templateSuccessMessage,60,'.alert-success');
		$I->see(\TemplateManagerJoomla3Page::$templateSuccessMessage, '.alert-success');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see(strtolower($templateName), \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click(['link' => 'ID']);
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
		$I->click(['link' => 'ID']);
		$I->see(strtolower($templateName), \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click(\TemplateManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\TemplateManagerJoomla3Page::$templateName,30);
		$I->fillField(\TemplateManagerJoomla3Page::$templateName, $templateUpdatedName);
		$I->click('Save & Close');
		$I->waitForText(\TemplateManagerJoomla3Page::$templateSuccessMessage,60,'.alert-success');
		$I->see(\TemplateManagerJoomla3Page::$templateSuccessMessage, '.alert-success');
		$I->see($templateUpdatedName, \TemplateManagerJoomla3Page::$firstResultRow);
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to change State of a Template
	 *
	 * @param   string  $name   Name of the  Template
	 * @param   string  $state  State of the  Template
	 *
	 * @return void
	 */
	public function changeTemplateState($name, $state = 'unpublish')
	{
		$this->changeState(new \TemplateManagerJoomla3Page, $name, $state, \TemplateManagerJoomla3Page::$firstResultRow, \TemplateManagerJoomla3Page::$selectFirst);
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
		$this->search(new \TemplateManagerJoomla3Page, $name, \TemplateManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Template
	 *
	 * @param   String  $name  Name of the Template
	 *
	 * @return string
	 */
	public function getTemplateState($name)
	{
		$result = $this->getState(new \TemplateManagerJoomla3Page, $name, \TemplateManagerJoomla3Page::$firstResultRow, \TemplateManagerJoomla3Page::$templateStatePath);

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
		$this->delete(new \TemplateManagerJoomla3Page, $name, \TemplateManagerJoomla3Page::$firstResultRow, \TemplateManagerJoomla3Page::$selectFirst);
	}
}
