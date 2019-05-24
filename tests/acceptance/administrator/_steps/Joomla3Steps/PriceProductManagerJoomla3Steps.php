<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use PriceProductJoomla3Page;

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
	public function addPriceProduct($productName, $productPrice, $quantityStart, $quantityEnd, $priceDiscount, $startDate, $endDate, $text = true)
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
		if($text)
		{
			$I->waitForText(PriceProductJoomla3Page::$messagePrice,30, PriceProductJoomla3Page::$idInstallSuccess);
		}
		else
		{
			$I->waitForText(PriceProductJoomla3Page::$messageQuantity, 30, PriceProductJoomla3Page::$idInstallSuccess);
		}
	}
}