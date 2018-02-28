<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use ManufacturerPage;

/**
 * Class ManufacturerManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ManufacturerManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Test Manufacturer Creation
	 *
	 * @param   string  $manufacturerName  Name of the Manufacturer
	 * @param   string  $email             Name of the Manufacturer
	 *
	 * @return void
	 */
	public function addManufacturer($manufacturerName, $email)
	{
		$tester = $this;
		$tester->amOnPage(ManufacturerPage::$URL);
		$tester->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
		$tester->click(ManufacturerPage::$buttonNew);
		$tester->waitForElement(ManufacturerPage::$detailsTab, 30);
		$tester->click(ManufacturerPage::$detailsTab);
		$tester->fillField(ManufacturerPage::$manufacturerName, $manufacturerName);
		$tester->fillField(ManufacturerPage::$emailManufacture, $email);
		$tester->fillField(ManufacturerPage::$productPerPage, 12);
		$tester->chooseOnSelect2(ManufacturerPage::$fieldTemplate, ManufacturerPage::$templateSection);
		$tester->click(ManufacturerPage::$buttonSaveClose);
		$tester->waitForText(ManufacturerPage::$manufacturerSuccessMessage, 60, '.alert-success');
		$tester->see(ManufacturerPage::$manufacturerSuccessMessage, '.alert-success');
		$tester->executeJS('window.scrollTo(0,0)');
		$tester->click(['link' => 'ID']);
		$tester->see($manufacturerName);
	}

	/**
	 * Function to Test Editing of a Manufacturer
	 *
	 * @param   string $manufacturerName Name of the Manufacturer which is to be edited
	 * @param   string $updatedName      Updated Name for the Manufacturer
	 *
	 * @return void
	 */
	public function editManufacturer($manufacturerName = 'Manufacturer Test', $updatedName = 'Updated Name')
	{
		$I = $this;
		$I->amOnPage(ManufacturerPage::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($manufacturerName);
		$I->click(ManufacturerPage::$selectFirst);
		$I->click(ManufacturerPage::$buttonEdit);
		$I->waitForElement(ManufacturerPage::$detailsTab, 30);
		$I->click(ManufacturerPage::$detailsTab);
		$I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Edit Page');
		$I->fillField(ManufacturerPage::$manufacturerName, $updatedName);
		$I->click(ManufacturerPage::$buttonSaveClose);
		$I->waitForText(ManufacturerPage::$manufacturerSuccessMessage, 60, '.alert-success');
		$I->see(ManufacturerPage::$manufacturerSuccessMessage, '.alert-success');
		$I->see($updatedName);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to change State of a Manufacturer
	 *
	 * @param   string $name  Name of the Manufacturer
	 * @param   string $state State of the Manufacturer
	 *
	 * @return void
	 */
	public function changeManufacturerState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(ManufacturerPage::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($name);
		$I->click(ManufacturerPage::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to Search for a Manufacturer
	 *
	 * @param   string $name         Name of the Manufacturer
	 * @param   string $functionName Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchManufacturer($name, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage(ManufacturerPage::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);

		if ($functionName == 'Search')
		{
			$I->see($name);
		}
		else
		{
			$I->dontSee($name);
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to get State of the Manufacturer
	 *
	 * @param   String $name Name of the Manufacturer
	 *
	 * @return string
	 */
	public function getManufacturerState($name)
	{
		$I = $this;
		$I->amOnPage(ManufacturerPage::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($name);
		$text = $I->grabAttributeFrom(ManufacturerPage::$manufacturerStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);

		return $result;
	}

	/**
	 * Function to Delete Manufacturer
	 *
	 * @param   String $name Name of the Manufacturer which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteManufacturer($name)
	{
		$I = $this;
		$I->amOnPage(ManufacturerPage::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($name);
		$I->click(ManufacturerPage::$selectFirst);
		$I->click('Delete');
		$I->dontSee($name);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}
}
