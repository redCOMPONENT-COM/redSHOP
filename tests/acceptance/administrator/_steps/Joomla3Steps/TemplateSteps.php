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
		$client = $this;
		$client->amOnPage(\TemplatePage::$url);
		$client->click(\TemplatePage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TemplatePage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TemplatePage::$fieldName, $templateName);
		$client->chooseOnSelect2(\TemplatePage::$fieldSection, $templateSection);
		$client->click(\TemplatePage::$buttonSaveClose);
		$client->waitForText(\TemplatePage::$messageItemSaveSuccess, 60, \TemplatePage::$selectorSuccess);
		$client->see(\TemplatePage::$messageItemSaveSuccess, \TemplatePage::$selectorSuccess);
		$client->searchTemplate($templateName);
		$client->see($templateName, \TemplatePage::$resultRow);
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
		$client = $this;
		$client->amOnPage(\TemplatePage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchTemplate($templateName);
		$client->click($templateName);
		$client->waitForElement(\TemplatePage::$fieldName, 30);
		$client->fillField(\TemplatePage::$fieldName, $templateUpdatedName);
		$client->click(\TemplatePage::$buttonSaveClose);
		$client->waitForText(\TemplatePage::$messageItemSaveSuccess, 60, \TemplatePage::$selectorSuccess);
		$client->see(\TemplatePage::$messageItemSaveSuccess, \TemplatePage::$selectorSuccess);
	}

	/**
	 * Function to change State of a Template
	 *
	 * @param   string $name  Name of the  Template
	 *
	 * @return void
	 */
	public function changeTemplateState($name)
	{
		$client = $this;
		$client->amOnPage(\TemplatePage::$url);
		$client->searchTemplate($name);
		$client->wait(3);
		$client->see($name, \TemplatePage::$resultRow);
		$client->click(\TemplatePage::$statePath);
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
		$client = $this;
		$client->amOnPage(\TemplatePage::$url);
		$client->searchSupplier($name);
		$client->wait(3);
		$client->see($name, \TemplatePage::$resultRow);
		$text = $client->grabAttributeFrom(\TemplatePage::$statePath, 'onclick');
		echo "Get status text " . $text;

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}
		else
		{
			$result = 'unpublished';
		}

		echo "Status need show" . $result;

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
		$client = $this;
		$client->amOnPage(\TemplatePage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchTemplate($templateName);
		$client->checkAllResults();
		$client->click(\TemplatePage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForText(\TemplatePage::$messageItemDeleteSuccess, 60, \TemplatePage::$selectorSuccess);
		$client->see(\TemplatePage::$messageItemDeleteSuccess, \TemplatePage::$selectorSuccess);
		$client->fillField(\TemplatePage::$searchField, $templateName);
		$client->pressKey(\TemplatePage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($templateName, \TemplatePage::$resultRow);
	}
}
