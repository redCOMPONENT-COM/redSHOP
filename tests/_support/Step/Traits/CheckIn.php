<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester\Step\Traits;

/**
 * Trait class for test with check-in feature
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.0
 */
trait CheckIn
{
	/**
	 * Method for click button "Check-in" without choice
	 *
	 * @param   \AdminJ3Page  $pageClass  Page class
	 *
	 * @return  void
	 */
	public function deleteWithoutChoice($pageClass = null)
	{
		$tester = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->click($pageClass::$buttonCheckIn);
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
	}
}
