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
		$I = $this;
		$result = $I->executeInSelenium(
			function(\WebDriver $webdriver)
			{
				$haystack = strip_tags($webdriver->getPageSource());

				return (bool) (stripos($haystack, "Notice:") || stripos($haystack, "Warning:"));

			}
		);

		return $result;
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
		$I->click('ID');

		if ($functionName == 'Search')
		{
			$I->see($searchItem, $resultRow);
		}
		else
		{
			$I->dontSee($searchItem, $resultRow);
		}

		$I->click('ID');
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
		$I->click('ID');
		$I->see($deleteItem, $resultRow);
		$I->click($check);
		$I->click('Delete');
		$I->dontSee($deleteItem, $resultRow);
		$I->click('ID');
	}
}
