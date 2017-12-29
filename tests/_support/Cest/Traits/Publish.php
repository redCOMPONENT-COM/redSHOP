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
trait Publish
{
	/**
	 * Method for test button publish without choice
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testButtonPublish(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator -> Button -> Publish without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->publishWithoutChoice($pageClass);
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}

	/**
	 * Method for test button Unpublish without choice
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testButtonPublish
	 */
	public function testButtonUnpublish(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator -> Button -> Unpublish without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->unpublishWithoutChoice($pageClass);
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}
}
