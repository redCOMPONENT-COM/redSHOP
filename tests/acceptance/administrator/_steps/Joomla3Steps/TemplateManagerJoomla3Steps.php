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
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=template');
		$I->waitForText('Template Management', 30, ['css' => 'h1']);
		$I->verifyNotices(false, $this->checkForNotices(), 'Template Manager Page');
		$I->click('New');
		$I->waitForElement(\TemplateManagerJoomla3Page::$templateName,30);
		$I->fillField(\TemplateManagerJoomla3Page::$templateName, $templateName);
		$I->click(['xpath' => "//div[@id='s2id_template_section']"]);
		$I->fillField(['id' => "s2id_autogen1_search"], $templateSection);
		$I->waitForElement(['xpath' => "//span[contains(text(), '" . $templateSection . "')]"], 30);
		$I->click(['xpath' => "//span[contains(text(), '" . $templateSection . "')]"]);
		$I->click('Save & Close');
		$I->waitForText(\TemplateManagerJoomla3Page::$templateSuccessMessage,60,['id' => 'system-message-container']);
		$I->see(\TemplateManagerJoomla3Page::$templateSuccessMessage, ['id' => 'system-message-container']);
		$I->filterListBySearching($templateName, ['id' => "filter"]);
		$I->seeElement(['link' => strtolower($templateName)]);
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
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=template');
		$I->waitForText('Template Management', 30, ['css' => 'h1']);
		$I->filterListBySearching($templateName, ['id' => "filter"]);
		$I->click(\TemplateManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\TemplateManagerJoomla3Page::$templateName,30);
		$I->fillField(\TemplateManagerJoomla3Page::$templateName, $templateUpdatedName);
		$I->click('Save & Close');
		$I->waitForText(\TemplateManagerJoomla3Page::$templateSuccessMessage,60,['id' => 'system-message-container']);
		$I->see(\TemplateManagerJoomla3Page::$templateSuccessMessage, ['id' => 'system-message-container']);
		$I->click('Reset');
		$I->filterListBySearching($templateUpdatedName, ['id' => "filter"]);
		$I->seeElement(['link' => strtolower($templateUpdatedName)]);
		$I->dontSeeElement(['link' => strtolower($templateName)]);
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
		$I = $this;
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=template');
		$I->waitForText('Template Management', 30, ['css' => 'h1']);
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
	 * @param   String  $templateName  Name of the Template which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteTemplate($templateName)
	{
		$I = $this;
		$I->amOnPage(\TemplateManagerJoomla3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchTemplate($templateName);
		$I->click(\TemplateManagerJoomla3Page::$selectFirst);
		$I->click("Delete");
		$I->acceptPopup();
		$I->waitForText("1 item successfully deleted", 60, '.alert-success');
		$I->see("1 item successfully deleted", '.alert-success');
		$I->fillField(\TemplateManagerJoomla3Page::$filter, $templateName);
		$I->pressKey(\TemplateManagerJoomla3Page::$filter, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($templateName, \TemplateManagerJoomla3Page::$firstResultRow);
	}
}
