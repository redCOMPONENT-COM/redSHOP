<?php

/**
 */
class ManageShopperGroupAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->shopperName = 'Testing shopper ' . $this->faker->randomNumber();
        $this->shopperType = 'Default Private';
        $this->categoryName = 'Testing Category ' . $this->faker->randomNumber();
        $this->customerType = 'Company customer';
        $this->shippingRate = $this->faker->numberBetween(1, 100);
        $this->shippingCheckout = $this->faker->numberBetween(1, 100);
        $this->catalog = 'Yes';
        $this->showPrice = 'Yes';

    }

//    public function createCategory(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo('Test Category Save creation in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
//        $I->wantTo('Create a Category Save button');
//        $I->addCategorySave($this->categoryName);
//
//    }

    public function creatShopperGroup(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Category Save creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ShopperGroupManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category Save button');
        $I->addShopperGroupsSave($this->shopperName, $this->shopperType, $this->customerType,"Templates",$this->shippingRate,$this->shippingCheckout,$this->catalog,$this->showPrice);
    }
}