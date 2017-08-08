<?php
/**
 * Steps for order status at admin page
 */

namespace AcceptanceTester;


class OrderStatusJoomla3Steps extends AdminManagerJoomla3Steps
{
	public function addOrderStatus($nameStatus, $codeStatus, $function, $status)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusJ3Page::$URL);
		$I->click(\OrderStatusJ3Page::$buttonNew);
		switch ($status) {
			case 'publish':
				switch ($function) {
					case 'save':
						$I->fillField(\OrderStatusJ3Page::$statusName, $nameStatus);
						$I->fillField(\OrderStatusJ3Page::$statusCode, $codeStatus);
						$I->click(\OrderStatusJ3Page::$statusPublish);
						$I->click(\OrderStatusJ3Page::$buttonSave);
						$I->waitForText(\OrderStatusJ3Page::$messageItemSaveSuccess, 60, \OrderStatusJ3Page::$selectorSuccess);
						break;
					case 'saveclose':
						$I->fillField(\OrderStatusJ3Page::$statusName, $nameStatus);
						$I->fillField(\OrderStatusJ3Page::$statusCode, $codeStatus);
						$I->click(\OrderStatusJ3Page::$statusPublish);
						$I->click(\OrderStatusJ3Page::$buttonSaveClose);
						$I->waitForText(\OrderStatusJ3Page::$messageItemSaveSuccess, 60, \OrderStatusJ3Page::$selectorSuccess);
						$I->filterListBySearching($nameStatus);
						$I->seeElement(['link' => $nameStatus]);
						break;
				}
				break;
			case 'unpublish':
				switch ($function) {
					case 'save':
						$I->fillField(\OrderStatusJ3Page::$statusName, $nameStatus);
						$I->fillField(\OrderStatusJ3Page::$statusCode, $codeStatus);
						$I->click(\OrderStatusJ3Page::$statusUnpublish);
						$I->click(\OrderStatusJ3Page::$buttonSave);
						$I->waitForText(\OrderStatusJ3Page::$messageItemSaveSuccess, 60, \OrderStatusJ3Page::$selectorSuccess);
						break;
					case 'saveclose':
						$I->fillField(\OrderStatusJ3Page::$statusName, $nameStatus);
						$I->fillField(\OrderStatusJ3Page::$statusCode, $codeStatus);
						$I->click(\OrderStatusJ3Page::$statusUnpublish);
						$I->click(\OrderStatusJ3Page::$buttonSaveClose);
						$I->waitForText(\OrderStatusJ3Page::$messageItemSaveSuccess, 60, \OrderStatusJ3Page::$selectorSuccess);
						$I->filterListBySearching($nameStatus);
						$I->seeElement(['link' => $nameStatus]);
						break;
				}
		}

	}

	public function checkButtons($buttonName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusJ3Page::$URL);
		$I->waitForText(\OrderStatusJ3Page::$namePage, 30, \OrderStatusJ3Page::$headPage);

		switch ($buttonName) {
			case 'cancel':
				$I->click(\OrderStatusJ3Page::$buttonNew);
				$I->waitForElement(\OrderStatusJ3Page::$statusName, 30);
				$I->click(\OrderStatusJ3Page::$buttonCancel);
				$I->see(\OrderStatusJ3Page::$namePage, \OrderStatusJ3Page::$selectorPageTitle);
				break;
			case 'edit':
				$I->click(\OrderStatusJ3Page::$buttonEdit);
				$I->acceptPopup();
				break;
			case 'delete':
				$I->click(\OrderStatusJ3Page::$buttonDelete);
				$I->acceptPopup();
				break;
			case 'publish':
				$I->click(\OrderStatusJ3Page::$buttonPublish);
				$I->acceptPopup();
				break;
			case 'unpublish':
				$I->click(\OrderStatusJ3Page::$buttonUnpublish);
				$I->acceptPopup();
				break;
		}
		$I->see(\OrderStatusJ3Page::$namePage, \OrderStatusJ3Page::$selectorPageTitle);
	}

	public function editOrderStatus($nameStatus, $newNameStatus, $fucntion)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusJ3Page::$URL);
		$I->filterListBySearching($nameStatus);
		$I->click(['link' => $nameStatus]);
		switch ($fucntion) {
			case 'save':
				$I->fillField(\OrderStatusJ3Page::$statusName, $newNameStatus);
				$I->click(\OrderStatusJ3Page::$statusUnpublish);
				$I->click(\OrderStatusJ3Page::$buttonSave);
				$I->waitForText(\OrderStatusJ3Page::$messageItemSaveSuccess, 60, \OrderStatusJ3Page::$selectorSuccess);
				break;
			case 'saveclose':
				$I->fillField(\OrderStatusJ3Page::$statusName, $newNameStatus);
				$I->click(\OrderStatusJ3Page::$statusUnpublish);
				$I->click(\OrderStatusJ3Page::$buttonSaveClose);
				$I->waitForText(\OrderStatusJ3Page::$messageItemSaveSuccess, 60, \OrderStatusJ3Page::$selectorSuccess);
				break;
		}
		$I->filterListBySearching($newNameStatus);
		$I->seeElement(['link' => $newNameStatus]);
	}

	public function deleteOrderStatus($nameStatus)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusJ3Page::$URL);
		$I->filterListBySearching($nameStatus);
		$I->checkAllResults();
		$I->click(\OrderStatusJ3Page::$buttonDelete);
		$I->see(\OrderStatusJ3Page::$messageItemDeleteSuccess, \OrderStatusJ3Page::$selectorSuccess);
	}

}