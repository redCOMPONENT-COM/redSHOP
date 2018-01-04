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
 * Trait class for test with publish feature
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.0
 */
trait Delete
{
	/**
	 * Method for test button "Delete"
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testButtonDelete(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Check button Delete without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->deleteWithoutChoice();
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}

	/**
	 * Method for test delete item
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testButtonDelete
	 */
	public function testItemDelete(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test delete item.');

		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);
		$step->deleteItem($this->dataNew[$this->nameField]);
	}
}
