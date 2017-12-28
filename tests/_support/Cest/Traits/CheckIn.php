<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cest\Traits;

use Codeception\Scenario;
use Step\AbstractStep;

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
	 * Method for test button Check-In without choice
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testButton
	 */
	public function testButtonCheckIn(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator > Button > Check-in without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->checkInWithoutChoice($pageClass);
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}
}
