<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cest\Traits;

use Codeception\Scenario;

/**
 * Trait class for test with check-in feature
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.0
 */
class AbstractCest
{
	/**
	 * Method for test button Check-In without choice
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testCheckInWithoutChoice(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator > Test ' . $this->className . ' check-in without choice.');

		$stepClass = $this->stepClass;
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->checkinWithoutChoice();
		$step->see($pageClass::$pageManageName, $pageClass::$selectorNamePage);
	}
}
