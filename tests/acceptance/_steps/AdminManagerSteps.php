<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class AdminManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class AdminManagerSteps extends \AcceptanceTester
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

		for ($i = 0; $i < count(\AdminManagerPage::$allLinks); $i++)
		{
			$I->amOnPage(\AdminManagerPage::$allLinks[$i][1]);
			$I->verifyNotices(false, $this->checkForNotices(), \AdminManagerPage::$allLinks[$i][0]);
			$I->click('New');
			$I->verifyNotices(false, $this->checkForNotices(), \AdminManagerPage::$allLinks[$i][0] . ' New');
			$I->click('Cancel');
		}
	}
}
