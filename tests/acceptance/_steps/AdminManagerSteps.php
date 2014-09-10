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
}
