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
	 * Method for test button Unpublish all results and check specific 1 item value
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 */
	public function testButtonUnpublish(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Button > Unpublish without choice.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->unpublishWithoutChoice();
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);

		$tester->wantTo('Administrator > Test Unpublish all results > check value of one item.');
		$step->unpublishAllResults($this->dataNew[$this->nameField]);
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
		$tester->wantTo('Administrator > Test Unpublish all results > check value of one item.');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->unpublishAllResults($this->dataNew[$this->nameField]);
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}

	/**
	 *Method for test publish 1 item
	 * 
	 * Method for test unpublish 1 item after publish success
	 *
	 * @param \AcceptanceTester $tester
	 * @param Scenario $scenario
	 *
	 * @depends testUnpublishAll
	 */
	public function changeItemStateByStatusButton(\AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Administrator > Test publish 1 item');

		$stepClass = $this->stepClass;

		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $step */
		$step = new $stepClass($scenario);

		$step->changeItemStateByStatusButton($this->dataNew[$this->nameField], 'publish');

		$tester->wantTo('Administrator > Test unpublish 1 item');
		$step->changeItemStateByStatusButton($this->dataNew[$this->nameField], 'unpublish');

		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}
	/**
	 * Method for test button publish without choice
	 * 
	 * Method for test button publish all results and check specific 1 item
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends changeItemStateByStatusButton
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

		$tester->wantTo('Administrator > Test publish all results.');
		$step->publishAllResults($this->dataNew[$this->nameField]);
		$step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
	}
}
