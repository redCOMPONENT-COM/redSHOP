<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Step\Traits;

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
	 * @return  void
	 */
	public function checkInWithoutChoice()
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->click($pageClass::$buttonCheckIn);
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
	}

	/**
	 * Method for test check-in all results
	 *
	 * @return  void
	 */
	public function checkInAllResult()
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->checkAllResults();
		$tester->click($pageClass::$buttonCheckIn);
		$tester->assertSystemMessageContains($pageClass::$messageCheckInSuccess);
	}
}
