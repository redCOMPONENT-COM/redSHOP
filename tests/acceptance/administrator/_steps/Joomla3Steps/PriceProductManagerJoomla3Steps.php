<?php
/**
 * Class PriceProductManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */

namespace AcceptanceTester;


class PriceProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{


//    public function addDiscountPrice ($nameProduct,$price,$discountPrice)
    public function addDiscountPrice($productName, $priceDiscount)
    {
        $I = $this;
        $I->amOnPage(\PriceProductJoomla3Page::$URL);
        $I->searchProduct($productName);
        $I->wait(5);
        $I->fillField(\PriceProductJoomla3Page::$discount, $priceDiscount);
        $I->click(\PriceProductJoomla3Page::$saveButton);
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

    public function searchProduct($productName)
    {
        $I = $this;
        $I->wantTo('Search the Product');
        $I->amOnPage(\PriceProductJoomla3Page::$URL);
        $I->see(\PriceProductJoomla3Page::$namePage, \PriceProductJoomla3Page::$selectorPage);
        $I->filterListBySearchingProductPrice($productName);
    }

    public function filterListBySearchingProductPrice($text, $searchField = ['name' => 'keyword'])
    {
        $I = $this;
        $I->executeJS('window.scrollTo(0,0)');
        $I->fillField($searchField, $text);
        $I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(['link' => $text]);
    }
}
