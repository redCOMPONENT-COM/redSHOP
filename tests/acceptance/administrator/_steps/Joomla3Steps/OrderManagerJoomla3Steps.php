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
     * Function to Add a new Order
     *
     * @return void
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
        $I->resizeWindow(1920, 1080);
        $I->waitForElement(\OrderManagerPage::$address, 30);
        $I->fillField(\OrderManagerPage::$address, $address);
        $I->fillField(\OrderManagerPage::$zipcode, $zipcode);
        $I->fillField(\OrderManagerPage::$city, $city);
        $I->fillField(\OrderManagerPage::$phone, $phone);

        $I->click(\OrderManagerPage::$applyUser);
        $I->click(\OrderManagerPage::$applyUser);
        $I->scrollTo(\OrderManagerPage::$productId);
        $I->waitForElement(\OrderManagerPage::$productId, 30);

        $I->click(\OrderManagerPage::$productId);
        $I->waitForElement(\OrderManagerPage::$productsSearch, 30);
        $I->fillField(\OrderManagerPage::$productsSearch, $nameProduct);
        $I->waitForElement($userOrderPage->returnSearch($nameProduct), 30);
        $I->click($userOrderPage->returnSearch($nameProduct));

        $I->fillField(\OrderManagerPage::$quanlityFirst, $quantity);


        $I->click(\OrderManagerPage::$buttonSave);
        $I->see(\OrderManagerPage::$buttonClose, \OrderManagerPage::$close);
    }

    public function editOrder($nameUser, $status, $paymentStatus, $newQuantity)
    {
        $I = $this;
        $I->amOnPage(\OrderManagerPage::$URL);

        $this->searchOrder($nameUser);
        $I->click($nameUser);
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

    public function deleteOrder($nameUser)
    {
        $I = $this;
        $I->amOnPage(\OrderManagerPage::$URL);
        $this->searchOrder($nameUser);
        $I->waitForElement(\OrderManagerPage::$deleteFirst, 30);
        $I->click(\OrderManagerPage::$deleteFirst);
        $I->click(\OrderManagerPage::$buttonDelete);
        $I->acceptPopup();
        $I->see(\OrderManagerPage::$messageDeleteSuccess, \OrderManagerPage::$selectorSuccess);
    }

    public function searchOrder($name)
    {
        $I = $this;
        $I->wantTo('Search the User ');
        $I->amOnPage(\OrderManagerPage::$URL);
        $I->filterListBySearchOrder($name, \OrderManagerPage::$filter);
    }
}
