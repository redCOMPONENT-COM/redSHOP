<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class TemplateSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class TemplateSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to create a New Template
	 *
	 * @param   string $templateName    Name of the Template
	 * @param   string $templateSection Section for the Template
	 *
	 * @return void
	 */
	public function addTemplate($templateName = 'Testing', $templateSection = 'Add to cart')
	{
		$I = $this;
		$I->amOnPage(\TemplatePage::$url);
		$I->click(\TemplatePage::$buttonNew);
		$I->verifyNotices(false, $this->checkForNotices(), \TemplatePage::$nameEditPage);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\TemplatePage::$fieldName, $templateName);
		$I->chooseOnSelect2(\TemplatePage::$fieldSection, $templateSection);
		$I->click(\TemplatePage::$buttonSaveClose);
		$I->waitForText(\TemplatePage::$messageItemSaveSuccess, 60, \TemplatePage::$selectorSuccess);
		$I->see(\TemplatePage::$messageItemSaveSuccess, \TemplatePage::$selectorSuccess);
		$I->searchTemplate($templateName);
		$I->see($templateName, \TemplatePage::$resultRow);
	}

	/**
	 * Function to edit an already created Template
	 *
	 * @param   string $templateName        Current Name for the Template
	 * @param   string $templateUpdatedName New Name for the Template
	 *
	 * @return void
	 */
	public function editTemplate($templateName = 'Current', $templateUpdatedName = 'UpdatedName')
	{
		$I = $this;
		$I->amOnPage(\TemplatePage::$url);
		$I->waitForText('Template Management', 30, ['css' => 'h1']);
		$I->filterListBySearching($templateName, ['id' => "filter"]);
		$I->click(\TemplatePage::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\TemplatePage::$fieldName, 30);
		$I->fillField(\TemplatePage::$fieldName, $templateUpdatedName);
		$I->click('Save & Close');
		$I->waitForText(\TemplatePage::$templateSuccessMessage, 60, ['id' => 'system-message-container']);
		$I->see(\TemplatePage::$templateSuccessMessage, ['id' => 'system-message-container']);
		$I->click('Reset');
		$I->filterListBySearching($templateUpdatedName, ['id' => "filter"]);
		$I->seeElement(['link' => strtolower($templateUpdatedName)]);
		$I->dontSeeElement(['link' => strtolower($templateName)]);
	}

	/**
	 * Function to change State of a Template
	 *
	 * @param   string $name  Name of the  Template
	 * @param   string $state State of the  Template
	 *
	 * @return void
	 */
	public function changeTemplateState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\TemplatePage::$url);
		$I->waitForText('Template Management', 30, ['css' => 'h1']);
		$this->changeState(new \TemplatePage, $name, $state, \TemplatePage::$firstResultRow, \TemplatePage::$selectFirst);
	}

	/**
	 * Function to Search for a Template
	 *
	 * @param   string $templateName Name of the Template
	 *
	 * @return void
	 */
	public function searchTemplate($templateName)
	{
		$client = $this;
		$client->amOnPage(\TemplatePage::$url);
		$client->waitForText(\TemplatePage::$namePage, 30, \TemplatePage::$headPage);
		$client->filterListBySearching($templateName);
	}

	/**
	 * Function to get State of the Template
	 *
	 * @param   String $name Name of the Template
	 *
	 * @return string
	 */
	public function getTemplateState($name)
	{
		$result = $this->getState(new \TemplatePage, $name, \TemplatePage::$firstResultRow, \TemplatePage::$templateStatePath);

		return $result;
	}

	/**
	 * Function to Delete Template
	 *
	 * @param   String $templateName Name of the Template which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteTemplate($templateName)
	{
		$I = $this;
		$I->amOnPage(\TemplatePage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchTemplate($templateName);
		$I->click(\TemplatePage::$selectFirst);
		$I->click("Delete");
		$I->acceptPopup();
		$I->waitForText("1 item successfully deleted", 60, '.alert-success');
		$I->see("1 item successfully deleted", '.alert-success');
		$I->fillField(\TemplatePage::$filter, $templateName);
		$I->pressKey(\TemplatePage::$filter, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($templateName, \TemplatePage::$firstResultRow);
	}
}
