<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use OrderStatusManagerPage;

/**
 * Class OrderStatusManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.4
 */
class OrderStatusManagerSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Create Order Status
	 *
	 * @param String $orderStatusName name of Order Status
	 *
	 * @param String $orderStatusCode code of Order Status
	 *
	 * @return void
	 */
	public function createOrderStatus($orderStatusName,$orderStatusCode)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->click(\OrderStatusManagerPage::$buttonNew);
		$I->fillField(\OrderStatusManagerPage::$orderstatusName, $orderStatusName);
		$I->fillField(\OrderStatusManagerPage::$orderstatusCode, $orderStatusCode);
		$I->click(\OrderStatusManagerPage::$buttonSave);
		$I->waitForText(\OrderStatusManagerPage::$messageItemSaveSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	/**
	 * Function to Create Order Status Missing Name
	 *
	 * @param String $orderStatusCode code of Order Status
	 *
	 * @return void
	 */
	public function createOrderStatusMissingName($orderStatusCode)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->click(\OrderStatusManagerPage::$buttonNew);
		$I->fillField(\OrderStatusManagerPage::$orderstatusCode, $orderStatusCode);
		$I->click(\OrderStatusManagerPage::$buttonSave);
		$I->waitForText(\OrderStatusManagerPage::$messageNameFieldRequired, 30, \OrderStatusManagerPage::$selectorMissing);
	}

	/**
	 * Function to Create Order Status Missing Code
	 *
	 * @param String $orderStatusName name of Order Status
	 *
	 * @return void
	 */
	public function createOrderStatusMissingCode($orderStatusName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->click(\OrderStatusManagerPage::$buttonNew);
		$I->fillField(\OrderStatusManagerPage::$orderstatusName, $orderStatusName);
		$I->click(\OrderStatusManagerPage::$buttonSave);
		$I->waitForText(\OrderStatusManagerPage::$messageCodeFieldRequired, 30, \OrderStatusManagerPage::$selectorMissing);
	}

	/**
	 * Function to Change Name Order Status
	 *
	 * @param String $changename name change of Order Status
	 *
	 * @return void
	 */

	public function editOrderStatus($orderStatusName, $changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($orderStatusName);
		$I->click(\OrderStatusManagerPage::$editButton);
		$I->waitForElement(\OrderStatusManagerPage::$orderstatusName, 30);
		$I->fillField(\OrderStatusManagerPage::$orderstatusName, $changeName);
		$I->click(\OrderStatusManagerPage::$buttonSaveClose);
		$I->waitForText(\OrderStatusManagerPage::$messageItemSaveSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	/**
	 * Function to Search Order Status
	 *
	 * @param String $orderStatusName name of Order Status
	 *
	 * @return void
	 */
	public function searchOrderStatus($orderStatusName)
	{
		$I = $this;
		$I->wantTo('Search the Order Status');
		$I->click(\OrderStatusManagerPage::$buttonReset);
		$I->fillField(\OrderStatusManagerPage::$filterSearch, $orderStatusName);
		$I->presskey(\OrderStatusManagerPage::$filterSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonCheckIn);
	}

	/**
	 * Function to Change Status Unpublish for Order Status
	 *
	 * @param String $changeName name of Order Status
	 *
	 * @return void
	 */
	public function changeStatusUnpublish($changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($changeName);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonUnpublish);
		$I->waitForText(\OrderStatusManagerPage::$messageUnpublishSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	/**
	 * Function to Change Status Publish for Order Status
	 *
	 * @param String $changeName name of Order Status
	 *
	 * @return void
	 */
	public function changeStatusPublish($changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($changeName);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonPublish);
		$I->waitForText(\OrderStatusManagerPage::$messagePublishSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	/**
	 * Function to Delete Order Status
	 *
	 * @param String $changeName name of Order Status
	 *
	 * @return void
	 */
	public function deleteOrderStatus($changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($changeName);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonDelete);
		$I->wantTo('Test with delete Order Status but then cancel');
		$I->cancelPopup();

		$I->wantTo('Test with delete Order Status then accept');
		$I->click(\OrderStatusManagerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForText(\OrderStatusManagerPage::$messageDeleteSuccess, 60, \OrderStatusManagerPage::$selectorSuccess);
		$I->dontSee($changeName);
	}

	/**
	 * @param $statusName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function deleteOrderStatusUsing($statusName)
	{
		$I = $this;
		$I->amOnPage(OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($statusName);
		$I->checkAllResults();
		$I->click(OrderStatusManagerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForText(OrderStatusManagerPage::$messageDeleteFail, 60, OrderStatusManagerPage::$selectorMissing);
	}
}