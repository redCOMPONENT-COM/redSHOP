<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Step\Traits;

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
	 * @return  void
	 */
	public function publishWithoutChoice()
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->waitForElement($pageClass::$selectorToolBar, 30);
		$tester->clickToolbarButton('publish');
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
	}

	/**
	 * Method for test "Publish" all results.
	 *
	 * @return  void
	 */
	public function publishAllResults()
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->waitForElement($pageClass::$selectorToolBar, 30);
		$tester->checkAllResults();
		$tester->clickToolbarButton('publish');
		$tester->waitForElement($pageClass::$searchField, 30);
		$tester->assertSystemMessageContains($pageClass::$messagePublishSuccess);
	}

	/**
	 * Method for click button "Unpublish" without choice
	 *
	 * @return  void
	 */
	public function unpublishWithoutChoice()
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->waitForElement($pageClass::$selectorToolBar, 30);
		$tester->clickToolbarButton('unpublish');
		$tester->acceptPopup();
		$tester->waitForElement($pageClass::$searchField, 30);
	}

	/**
	 * Method for test "Unpublish" all results.
	 *
	 * @return  void
	 */
	public function unpublishAllResults()
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->amOnPage($pageClass::$url);
		$tester->waitForElement($pageClass::$selectorToolBar, 30);
		$tester->checkAllResults();
		$tester->clickToolbarButton('unpublish');
		$tester->waitForElement($pageClass::$searchField, 30);
		$tester->assertSystemMessageContains($pageClass::$messageUnpublishSuccess);
	}

	/**
	 * Method for get state of user.
	 *
	 * @param   string  $item  Name of item, which can be use for search.
	 *
	 * @return  string         State of item.
	 */
	protected function getItemState($item)
	{
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->searchItem($item);
		$tester->see($item, $pageClass::$resultRow);

		$text = $tester->grabAttributeFrom($pageClass::$statePath, 'onclick');

		return strpos($text, 'unpublish') !== false ? 'published' : 'unpublished';
	}

	/**
	 * Method for change item state by click on "Status" button in table row.
	 *
	 * @param   string  $item  Item name
	 *
	 * @return  void
	 */
	protected function changeItemStateByStatusButton($item)
	{
		/** @var \AdminJ3Page $pageClass */
		$pageClass = $this->pageClass;
		$tester    = $this;

		$tester->searchItem($item);
		$tester->see($item, $pageClass::$resultRow);
		$tester->click($pageClass::$statePath);
	}
}
