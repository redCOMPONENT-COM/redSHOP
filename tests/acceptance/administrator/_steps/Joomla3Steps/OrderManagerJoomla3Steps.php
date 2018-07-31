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
		$I->pressKey(\OrderManagerPage::$userSearch, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(\OrderManagerPage::$fistName, 30);
		$I->see($nameUser);
		$I->wait(3);
		$I->waitForElement(\OrderManagerPage::$applyUser, 30);
		$I->executeJS("jQuery('.button-apply').click()");
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
    /**
     * @param $nameUser
     * @throws \Exception
     */
	public function deleteOrder($nameUser)
	{
		$I = $this;
		$I->amOnPage(\OrderManagerPage::$URL);
		$this->searchOrder($nameUser);
		$I->waitForElement(\OrderManagerPage::$deleteFirst, 30);
		$I->click(\OrderManagerPage::$deleteFirst);
		$I->click(\OrderManagerPage::$buttonDelete);
		$I->acceptPopup();
	}
    /**
     * @param $productName
     */
    public function searchProduct($productName)
    {
        $I = $this;
        $I->wantTo('Search the Product');
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->filterListBySearchingProduct($productName);
    }
    /**
     * @param $name
     * @throws \Exception
     */
    public function checkReview($name)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->searchProduct($name);
        $I->click(['link' => $name]);
        $I->waitForElement(\ProductManagerPage::$productName, 30);
        $I->click(\ProductManagerPage::$buttonReview);
        $I->switchToNextTab();
        $I->waitForElement(\ProductManagerPage::$namePageXpath, 30);
        $I->waitForText($name, 30, \ProductManagerPage::$namePageXpath);
    }
    /**
     * @param $nameProduct
     * @param $discountName
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function addProductToCart($nameProduct, $username, $password)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkReview($nameProduct);
        $I->see($nameProduct);
        $I->click(\ProductManagerPage::$buttonAddToCart);
        $I->waitForText("Product has been added to your cart.", 10, '.alert-message');
        $I->see("Product has been added to your cart.", '.alert-message');
        $I->click(\ProductManagerPage::$buttonGoToCheckOut);
        $I->click(\ProductManagerPage::$buttonCheckOut);
        $I->fillField(\ProductManagerPage::$username, $username);
        $I->fillField(\ProductManagerPage::$password, $password);
        $I->click(\ProductManagerPage::$buttonLogin);
        $I->waitForElement(\AdminJ3Page::$acceptTerms, '30');
        $I->click(\AdminJ3Page::$acceptTerms);
        $I->click(\AdminJ3Page::$checkoutFinalStep);
        $I->see('Order Information');
    }
}
