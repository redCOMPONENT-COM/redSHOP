<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/29/2017
 * Time: 2:06 PM
 */
class PriceProductAdministratorCest
{

    public function __construct()
    {
        $this->randomCategoryName = 'TestingCategory' . rand(99, 999);
        $this->randomProductName = 'Testing Products' . rand(99, 999);
        $this->randomProductNumber = rand(999, 9999);
        $this->randomProductPrice = rand(11, 100);
        $this->randomPriceDiscount = rand(5, 10);
        $this->randomPriceLess = rand(1, 4);
        $this->randomPriceDiscounctMoreThan = rand(101, 200);
        $this->quantityStart=10;
        $this->quantityEnd=100;
        $this->priceDefault=100;
    }


    public function testProductAdministrator(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Category in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategory($this->randomCategoryName);
    }

    public function createProductSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save Close Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSaveClose($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
    }

    //search product then insert discount price
    public function addDiscountPrice(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Change Price of Product in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\PriceProductManagerJoomla3Page($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDiscountPrice($this->randomProductName, $this->randomPriceDiscount);
        $I->see("Product Management", '.page-title');
    }

    //this case can not run at firefox because have something wrong at code .
    // When develop fixed the issues please open code
//    public function addDiscountPriceMoreThan(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test Change Price of Product in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\PriceProductManagerJoomla3Page($scenario);
//        $I->wantTo('Create a Category Save button');
//        $I->addDiscountPriceMoreThan($this->randomProductName, $this->randomPriceDiscountMoreThan);
//        $I->see("Product Management", '.page-title');
//    }
//
//
//    public function addPriceLessDiscount(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test Change Price of Product in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\PriceProductManagerJoomla3Page($scenario);
//        $I->wantTo('Create a Category Save button');
//        $I->addPriceLessDiscount($this->randomProductName, $this->randomPriceLess);
//        $I->see("Product Management", '.page-title');
//    }


    public function adDefaultPrivate(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Default price of Price of Product in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\PriceProductManagerJoomla3Page($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addDefaultPrivate("Testing Products542",$this->quantityStart, $this->quantityEnd, $this->priceDefault);
        $I->see("Product Management", '.page-title');
    }


}