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
	 * Method for test button Unpublish without choice
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testButtonUnpublish(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Check button unpublish without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->unpublishWithoutChoice();
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}

	/**
	 * Method for test unpublish all results
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testButtonUnpublish
	 */
	public function testUnpublishAll(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test Unpublish all results.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->unpublishAllResults();
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}

	/**
	 * Method for test button publish without choice
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testUnpublishAll
	 */
	public function testButtonPublish(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Button > Publish without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->publishWithoutChoice();
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}

	/**
	 * Method for test publish all results
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testButtonPublish
	 */
	public function testPublishAll(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator > Test publish all results.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->publishAllResults();
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}
}
