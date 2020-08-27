<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Step\Traits;

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
trait Delete
{
	/**
	 * Method for delete item
	 *
	 * @param   string  $item  Name of the item
	 * @return  void
	 * @throws \Exception
	 */
	public function deleteItem($item = '')
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;

		/** @var AbstractStep $tester */
		$tester = $this;

		$tester->searchItem($item);
		$tester->see($item, $pageClass::$resultRow);
		$tester->waitForElementVisible($pageClass::$checkAllXpath, 30);
		$tester->wait(0.5);
		$tester->click($pageClass::$checkAllXpath);
		$tester->waitForText($pageClass::$buttonDelete, 30);
		$tester->click($pageClass::$buttonDelete);
		$tester->acceptPopup();
		$tester->waitForText($pageClass::$namePage, 30, $pageClass::$headPage);
		$tester->searchItem($item);
		$tester->dontSee($item, $pageClass::$resultRow);
	}
}
