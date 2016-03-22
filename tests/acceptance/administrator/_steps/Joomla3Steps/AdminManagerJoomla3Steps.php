<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class AdminManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class AdminManagerJoomla3Steps extends \AcceptanceTester
{
	/**
	 * Function to CheckForNotices and Warnings
	 *
	 * @return  bool
	 */
	public function checkForNotices()
	{
		$this->checkForPhpNoticesOrWarnings();
	}

	/**
	 * Function to Check for Presence of Notices and Warnings on all the Modules of Extension
	 *
	 * @return void
	 */
	public function CheckAllLinks()
	{
		$I = $this;

		foreach (\AdminManagerPage::$allExtensionPages as $page => $url)
		{
			$I->amOnPage($url);
			$I->verifyNotices(false, $this->checkForNotices(), $page);
			$I->click('New');
			$I->verifyNotices(false, $this->checkForNotices(), $page . ' New');
			$I->click('Cancel');
		}

	}

	/**
	 * Function to Search for an Item
	 *
	 * @param   Object  $pageClass     Class Object for which Search is to be done
	 * @param   String  $searchItem    Search Variable
	 * @param   String  $resultRow     Xpath for the field to be searched in
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function search($pageClass, $searchItem, $resultRow, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);

		if ($functionName == 'Search')
		{
			$I->see($searchItem, $resultRow);
		}
		else
		{
			$I->dontSee($searchItem, $resultRow);
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to Delete an Item
	 *
	 * @param   object  $pageClass   Page Class where we need to delete the Item
	 * @param   string  $deleteItem  Item which is to be Deleted
	 * @param   string  $resultRow   Result Row Where we need to pick the item from
	 * @param   string  $check       Selection Box Path
	 *
	 * @return void
	 */
	public function delete($pageClass, $deleteItem, $resultRow, $check)
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($deleteItem, $resultRow);
		$I->click($check);
		$I->click('Delete');
		$I->dontSee($deleteItem, $resultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to get State of an Item in the Administrator
	 *
	 * @param   Object  $pageClass      Page at which Operation is to be performed
	 * @param   String  $item           Item for which the State is being fetched
	 * @param   String  $resultRow      Result Row where we need to pick the item from
	 * @param   String  $itemStatePath  Path to the State for the Item
	 *
	 * @return string  Result of state
	 */
	public function getState($pageClass, $item, $resultRow, $itemStatePath)
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForText($item, 30, $resultRow);
		$I->see($item, $resultRow);
		$text = $I->grabAttributeFrom($itemStatePath, 'onclick');

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
	 * Function to change State of an Item in the Backend
	 *
	 * @param   Object  $pageClass  Page Class on which we are performing the Operation
	 * @param   String  $item       Item which we are supposed to change
	 * @param   String  $state      State for the Item
	 * @param   String  $resultRow  Result row where we need to look for the item
	 * @param   String  $check      Checkbox path for Selecting the Item
	 *
	 * @return void
	 */
	public function changeState($pageClass, $item, $state, $resultRow, $check)
	{
		$I = $this;
		$I->amOnPage($pageClass::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->waitForText($item, 30, $resultRow);
		$I->see($item, $resultRow);
		$I->click($check);

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
}
