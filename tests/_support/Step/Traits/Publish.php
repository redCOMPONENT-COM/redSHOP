<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester\Step\Traits;

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
	 * Method for click button "Publish" without choice
	 *
	 * @param   \AdminJ3Page  $pageClass  Page class
	 *
	 * @return  void
	 */
	public function publishWithoutChoice($pageClass = null)
	{
		$tester = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->click($pageClass::$buttonPublish);
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
	}

	/**
	 * Method for click button "Unpublish" without choice
	 *
	 * @param   \AdminJ3Page  $pageClass  Page class
	 *
	 * @return  void
	 */
	public function unpublishWithoutChoice($pageClass = null)
	{
		$tester = $this;
		$tester->amOnPage($pageClass::$url);
		$tester->click($pageClass::$buttonUnpublish);
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
	}
}
