<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class OrderManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class OrderManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $nameUser
	 * @param $address
	 * @param $zipcode
	 * @param $city
	 * @param $phone
	 * @param $nameProduct
	 * @param $quantity
	 */
	public function addOrder($nameUser, $address, $zipcode, $city, $phone, $nameProduct, $quantity)
	{
		$I = $this;
		$I->amOnPage(\OrderManagerPage::$URL);
		$I->click(\OrderManagerPage::$buttonNew);
		$I->click(\OrderManagerPage::$userId);
		$I->waitForElement(\OrderManagerPage::$userSearch, 30);
		$userOrderPage = new \OrderManagerPage();

		$I->fillField(\OrderManagerPage::$userSearch, $nameUser);
		$I->waitForElement($userOrderPage->returnSearch($nameUser));
		$I->waitForElement($userOrderPage->returnSearch($nameUser), 30);

		$I->click($userOrderPage->returnSearch($nameUser));
		$I->waitForElement(\OrderManagerPage::$fistName, 30);
		$username = $I->grabValueFrom(\OrderManagerPage::$fistName);
		if($username != $nameUser)
		{
			$I->reloadPage();
			$I->click(\OrderManagerPage::$userId);
			$I->waitForElement(\OrderManagerPage::$userSearch, 30);
			$I->fillField(\OrderManagerPage::$userSearch, $nameUser);
			$I->waitForElement($userOrderPage->returnSearch($nameUser));
			$I->waitForElement($userOrderPage->returnSearch($nameUser), 30);

			$I->click($userOrderPage->returnSearch($nameUser));
			$I->waitForElement(\OrderManagerPage::$fistName, 30);
			$I->seeInField(\OrderManagerPage::$fistName, $nameUser);
		}

		$I->waitForElement(\OrderManagerPage::$applyUser, 30);
		$I->click(\OrderManagerPage::$applyUser);
		$I->waitForElement(\OrderManagerPage::$productId, 30);
		$I->scrollTo(\OrderManagerPage::$productId);
		$I->waitForElement(\OrderManagerPage::$productId, 30);

		$I->click(\OrderManagerPage::$productId);
		$I->waitForElement(\OrderManagerPage::$productsSearch, 30);
		$I->fillField(\OrderManagerPage::$productsSearch, $nameProduct);
		$I->waitForElement($userOrderPage->returnSearch($nameProduct), 30);
		$I->click($userOrderPage->returnSearch($nameProduct));

		$I->fillField(\OrderManagerPage::$quanlityFirst, $quantity);

		$I->click(\OrderManagerPage::$buttonSave);
		$I->waitForElement(\OrderManagerPage::$close, 30);
		$I->see(\OrderManagerPage::$buttonClose, \OrderManagerPage::$close);
	}

	public function editOrder($nameUser, $status, $paymentStatus, $newQuantity)
	{
		$I = $this;
		$I->amOnPage(\OrderManagerPage::$URL);

		$this->searchOrder($nameUser);
		$I->waitForElement(\OrderManagerPage::$nameXpath, 30);
		$I->click(\OrderManagerPage::$nameXpath);
		$I->waitForElement(\OrderManagerPage::$statusOrder, 30);
		$userOrderPage = new \OrderManagerPage();
		$I->click(\OrderManagerPage::$statusOrder);
		$I->fillField(\OrderManagerPage::$statusSearch, $status);
		$I->waitForElement($userOrderPage->returnSearch($status), 30);
		$I->click($userOrderPage->returnSearch($status));

		$I->click(\OrderManagerPage::$statusPaymentStatus);
		$I->fillField(\OrderManagerPage::$statusPaymentSearch, $paymentStatus);
		$I->waitForElement($userOrderPage->returnSearch($paymentStatus), 30);
		$I->click($userOrderPage->returnSearch($paymentStatus));
		$I->fillField(\OrderManagerPage::$quantityp1, $newQuantity);
		$I->click(\OrderManagerPage::$nameButtonStatus);
	}

	public function searchOrder($name)
	{
		$I = $this;
		$I->wantTo('Search the User ');
		$I->amOnPage(\OrderManagerPage::$URL);
		$I->filterListBySearchOrder($name, \OrderManagerPage::$filter);
	}

	public function deleteOrder($nameUser)
	{
		$I = $this;
		$I->amOnPage(\OrderManagerPage::$URL);
		$this->searchOrder($nameUser);
		$I->waitForElement(\OrderManagerPage::$deleteFirst, 30);
		$I->click(\OrderManagerPage::$deleteFirst);
		$I->click(\OrderManagerPage::$buttonDelete);
		$I->acceptPopup();
//		$I->see(\OrderManagerPage::$messageDeleteSuccess, \OrderManagerPage::$selectorSuccess);
	}
}
