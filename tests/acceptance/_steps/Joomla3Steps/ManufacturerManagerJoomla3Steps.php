<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
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
	 *
	 * @return void
	 */
	public function addManufacturer($manufacturerName = 'Testing Manufacturers')
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
		$I->click('New');
		$I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab, 30);
		$I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
		$I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $manufacturerName);
		$I->click('Save & Close');
		$I->waitForText(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage, 60);
		$I->see(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage);
		$I->click('ID');
		$I->click('ID');
		$I->see($manufacturerName, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to Test Editing of a Manufacturer
	 *
	 * @param   string  $manufacturerName  Name of the Manufacturer which is to be edited
	 * @param   string  $updatedName       Updated Name for the Manufacturer
	 *
	 * @return void
	 */
	public function editManufacturer($manufacturerName = 'Manufacturer Test', $updatedName = 'Updated Name')
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($manufacturerName, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->click(\ManufacturerManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab, 30);
		$I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
		$I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Edit Page');
		$I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $updatedName);
		$I->click('Save & Close');
		$I->waitForText(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage, 60);
		$I->see(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage);
		$I->see($updatedName, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to change State of a Manufacturer
	 *
	 * @param   string  $name   Name of the Manufacturer
	 * @param   string  $state  State of the Manufacturer
	 *
	 * @return void
	 */
	public function changeState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->click(\ManufacturerManagerJoomla3Page::$selectFirst);

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
	 * Function to Search for a Manufacturer
	 *
	 * @param   string  $name          Name of the Manufacturer
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchManufacturer($name, $functionName = 'Search')
	{
		$this->search(new \ManufacturerManagerJoomla3Page, $name, \ManufacturerManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Manufacturer
	 *
	 * @param   String  $name  Name of the Manufacturer
	 *
	 * @return string
	 */
	public function getState($name)
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$text = $I->grabAttributeFrom(\ManufacturerManagerJoomla3Page::$manufacturerStatePath, 'onclick');

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
	 * Function to Delete Manufacturer
	 *
	 * @param   String  $name  Name of the Manufacturer which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteManufacturer($name)
	{
		$this->delete(new \ManufacturerManagerJoomla3Page, $name, \ManufacturerManagerJoomla3Page::$firstResultRow, \ManufacturerManagerJoomla3Page::$selectFirst);
	}
}
