<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/29/2017
 * Time: 2:03 PM
 */

namespace AcceptanceTester;


class PriceProductManagerJoomla3Page extends AdminManagerJoomla3Steps
{


//    public function addDiscountPrice ($nameProduct,$price,$discountPrice)
    public function addDiscountPrice($productName, $priceDiscount)
    {
        $I = $this;
        $I->amOnPage(\PriceProductJ3Page::$URL);
        $I->searchProduct($productName);
        $I->wait(5);
        $I->fillField(\PriceProductJ3Page::$discount, $priceDiscount);
        $I->click(['xpath' => "//a[contains(@href,'savediscountprice')]"]);
    }


    public function addDiscountPriceMoreThan($productName, $priceDiscountThan)
    {
        $I = $this;
        $I->amOnPage(\PriceProductJ3Page::$URL);
        $I->searchProduct($productName);
        $I->wait(3);
        $I->fillField(\PriceProductJ3Page::$discount, $priceDiscountThan);
        $I->click(['xpath' => "//a[contains(@href,'savediscountprice')]"]);

    }

    public function addPriceLessDiscount($productName, $randomPriceLess)
    {
        $I = $this;
        $I->amOnPage(\PriceProductJ3Page::$URL);a
        $I->searchProduct($productName);
        $I->wait(3);
        $I->fillField(\PriceProductJ3Page::$priceProduct, $randomPriceLess);
        $I->click(['xpath' => "//a[contains(@href,'saveprice')]"]);

    }

    public function adDefaultPrivate($productName, $quantityStart, $quantityEnd,$priceDefault)
    {
        $I = $this;
        $I->amOnPage(\PriceProductJ3Page::$URL);
        $I->searchProduct($productName);
        $I->wait(3);
        $I->click(['xpath' => "/html/body/div[1]/div/div/section[2]/form/div[2]/div/table/tbody/tr/td[5]/a/i"]);
        $I->wait(5);
        $I->fillField(\PriceProductJ3Page::$quantityStart, $quantityStart);
        $I->fillField(\PriceProductJ3Page::$quantityEnd, $quantityEnd);
        $I->fillField(\PriceProductJ3Page::$priceDefault, $priceDefault);
        $I->click(['xpath' => "//button[@onclick='Joomla.submitbutton(\"saveprice\")']"]);
    }

    public function searchProduct($productName)
    {
        $I = $this;
        $I->wantTo('Search the Product');
        $I->amOnPage(\PriceProductJ3Page::$URL);
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->filterListPriceProductSearch($productName);
    }


}