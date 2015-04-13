<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class FrontEndManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class FrontEndManagerJoomla3Steps extends \AcceptanceTester
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
	 * Function to Check for Presence of Notices and Warnings on all the MenuType on the FrontEnd
	 *
	 * @return void
	 */
	public function CheckAllFrontEndLinks()
	{
		$I = $this;

		foreach (\FrontEndManagerJoomla3Page::$allFrontEndPages as $page => $url)
		{
			$I->amOnPage($url);
			$I->verifyNotices(false, $this->checkForNotices(), $page);
		}

	}
}
