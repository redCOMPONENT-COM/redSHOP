<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use PriceProductJoomla3Page;
use FrontEndProductManagerJoomla3Page;
use GiftCardCheckoutPage;

/**
 * Class PriceProductManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since 2.1.2
 */
class PriceProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $productName
	 * @param $priceDiscount
	 * @throws \Exception
	 * @since 2.1.2
	 */
//    public function addDiscountPrice ($nameProduct,$price,$discountPrice)
	public function addDiscountPrice($productName, $priceDiscount)
	{
		$I = $this;
		$I->amOnPage(PriceProductJoomla3Page::$URL);
		$I->searchProduct($productName);
		$I->wait(5);
		$I->fillField(PriceProductJoomla3Page::$discount, $priceDiscount);
		$I->click(PriceProductJoomla3Page::$saveButton);
	}

//    public function addDiscountPriceMoreThan($productName, $priceDiscountThan)
//    {
//        $I = $this;
//        $I->amOnPage(\PriceProductJoomla3Page::$URL);
//        $I->searchProduct($productName);
//        $I->wait(3);
//        $I->fillField(\PriceProductJoomla3Page::$discount, $priceDiscountThan);
//        $I->click(['xpath' => "//a[contains(@href,'savediscountprice')]"]);
//
//    }
//
//    public function addPriceLessDiscount($productName, $randomPriceLess)
//    {
//        $I = $this;
//        $I->amOnPage(\PriceProductJoomla3Page::$URL);
//        $I->searchProduct($productName);
//        $I->wait(3);
//        $I->fillField(\PriceProductJoomla3Page::$priceProduct, $randomPriceLess);
//        $I->click(['xpath' => "//a[contains(@href,'saveprice')]"]);
//
//    }

	/**
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function searchProduct($productName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(PriceProductJoomla3Page::$URL);
		$I->waitForText(PriceProductJoomla3Page::$namePage, 30, PriceProductJoomla3Page::$headPage);
		$I->see(PriceProductJoomla3Page::$namePage);
		$I->filterListBySearchingProductPrice($productName);
	}

	/**
	 * @param $productName
	 * @param array $searchField
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function filterListBySearchingProductPrice($productName, $searchField = ['name' => 'keyword'])
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $productName);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $productName]);
	}

	/**
	 * @param $productName
	 * @param $productPrice
	 * @param $quantityStart
	 * @param $quantityEnd
	 * @param $priceDiscount
	 * @param $startDate
	 * @param $endDate
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function addPriceProduct($productName, $productPrice, $quantityStart, $quantityEnd, $priceDiscount, $startDate, $endDate)
	{
		$I = $this;
		$I->wantToTest("Add price product");
		$I->amOnPage(PriceProductJoomla3Page::$URL);
		$I->searchProduct($productName);
		$I->waitForText($productName, 30);
		$I->see($productName);
		$I->click($productName);
		$I->waitForElementVisible(PriceProductJoomla3Page::$buttonAddPrice, 30);
		$I->click(PriceProductJoomla3Page::$buttonAddPrice);
		$I->waitForText(PriceProductJoomla3Page::$titlePrice, 30, PriceProductJoomla3Page::$h1);
		$I->see(PriceProductJoomla3Page::$titlePrice);
		$I->click(PriceProductJoomla3Page::$buttonNew);
		$I->waitForElementVisible(PriceProductJoomla3Page::$selectOption, 30);
		$I->click(PriceProductJoomla3Page::$selectOption);
		$I->waitForElementVisible(PriceProductJoomla3Page::$search, 30);
		$I->fillField(PriceProductJoomla3Page::$search, PriceProductJoomla3Page::$defaultPrivate);
		$I->waitForElementVisible(PriceProductJoomla3Page::$chooseDefaultPrivate, 30);
		$I->click(PriceProductJoomla3Page::$chooseDefaultPrivate);
		$I->waitForElementVisible(PriceProductJoomla3Page::$productPrice, 30);
		$I->fillField(PriceProductJoomla3Page::$productPrice, $productPrice);
		$I->waitForElementVisible(PriceProductJoomla3Page::$quantityStart, 30);
		$I->fillField(PriceProductJoomla3Page::$quantityStart, $quantityStart);
		$I->waitForElementVisible(PriceProductJoomla3Page::$quantityEnd, 30);
		$I->fillField(PriceProductJoomla3Page::$quantityEnd, $quantityEnd);
		$I->waitForElementVisible(PriceProductJoomla3Page::$discountPrice, 30);
		$I->fillField(PriceProductJoomla3Page::$discountPrice, $priceDiscount);
		$I->waitForElementVisible(PriceProductJoomla3Page::$startDate, 30);
		$I->fillField(PriceProductJoomla3Page::$startDate, $startDate);
		$I->waitForElementVisible(PriceProductJoomla3Page::$endDate, 30);
		$I->addValueForField(PriceProductJoomla3Page::$endDate, $endDate, 10);
		$I->click(PriceProductJoomla3Page::$buttonSaveClose);
		$I->waitForText(PriceProductJoomla3Page::$messagePrice,30, PriceProductJoomla3Page::$idInstallSuccess);
	}


	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkoutOneStepWithDiscountPrice($userName, $password, $productName, $categoryName, $total)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->click($categoryName);
		$I->waitForElement(PriceProductJoomla3Page::$box, 30);
		$I->click($productName);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$addToCart, 10);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage, 60, GiftCardCheckoutPage::$selectorSuccess);
		$I->see(GiftCardCheckoutPage::$alertSuccessMessage);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForText(GiftCardCheckoutPage::$alertSuccessMessage, 60, GiftCardCheckoutPage::$selectorSuccess);
		$I->see(GiftCardCheckoutPage::$alertSuccessMessage);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->seeElement(['link' => $productName]);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressAddress);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, 'address');
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressPostalCode);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, 1201010);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressCity);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, "address");
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addressPhone);
		$I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, '123100120101');
		$I->click(FrontEndProductManagerJoomla3Page::$buttonSave);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$headBilling, 30, null);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->waitForText($total, 30, FrontEndProductManagerJoomla3Page::$priceEnd);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
	}
}