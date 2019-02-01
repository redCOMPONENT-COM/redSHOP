<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 *
	 * Method for test delete button
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

		$tester->wantTo('Test delete item.');
		$step->deleteItem($this->dataNew[$this->nameField]);
	}
}
