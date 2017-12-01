<?php

/**
 * Created by PhpStorm.
 * User: nhung
 * Date: 29/11/2017
 * Time: 18:04
 */
use AcceptanceTester\ShippingSteps as ShippingSteps ;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps as ShopperGroupManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
class CheckoutShippingCest
{
    public function __construct()
    {

        $this->faker = Faker\Factory::create();

        //info product for first shipping rate
        $this->ProductName = $this->faker->bothify('ProductName ?##?');
        $this->CategoryName = $this->faker->bothify('Category name ?###?');
        $this->CategoryNamePlus = $this->CategoryName;
        $this->minimumPerProduct = 1;
        $this->minimumQuantity = 1;
        $this->maximumQuantity = $this->faker->numberBetween(100, 1000);
        $this->discountStart = "12-12-2016";
        $this->discountEnd = "23-05-2017";
        $this->randomProductNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductPrice = 100;

        //info product for second shipping rate
        $this->productNameSecond = $this->faker->bothify('ProductName ?##?');
        $this->randomProductPriceSecond = 100;
        $this->randomProductNumberSecond = $this->faker->bothify('number ?##?');
        $this->categoryNameSecond = $this->faker->bothify('CategoryName ?##?');

        $this->subtotal="DKK 100,00";
        $this->Total= 0;

        //shipping info
        $this->shippingName = 'TestingShippingRate' . rand(99, 999);
        $this->shippingNameSecond = $this->faker->bothify('NameShiipingSecond ?##?');
        $this->shippingNameSaveClose = "TestingSave" . rand(1, 100);
        $this->shippingRate = rand(1, 100);
        $this->shippingRateEdit = rand(100, 1000);
        $this->weightStart = "";
        $this->weightEnd = "";
        $this->volumeStart = "";
        $this->volumeEnd = "";
        $this->shippingRateLenghtStart = "";
        $this->shippingRateLegnhtEnd = "";
        $this->shippingRateWidthStart = "";
        $this->shippingRateWidthEnd = "";
        $this->shippingRateHeightStart = "";
        $this->shippingRateHeightEnd = "";
        $this->orderTotalStart = "";
        $this->orderTotalEnd = "";
        $this->zipCodeStart = "";
        $this->zipCodeEnd = "";
        $this->country = "";
        $this->shippingRateProduct = "";
        $this->shippingPriority = "";
        $this->shippingRateFor = "";
        $this->shippingVATGroups = "";
        $this->pickup = "pick";


        //create shopper groups
        $this->shopperName = $this->faker->bothify(' Testing shopper ##??');
        $this->shopperType = null;
        $this->customerType = 'Company customer';
        $this->shippingRate = 10;
        $this->shippingCheckout = $this->faker->numberBetween(1, 100);
        $this->catalog = 'Yes';
        $this->showPrice = 'Yes';
        $this->shipping = 'yes';
        $this->enableQuotation='no';
        $this->showVat='no';
        $this->shopperGroupPortal='no';

        //create user
        $this->userName = $this->faker->bothify('UserName ?##?');
        $this->password = 'test';
        $this->email = $this->faker->email;
        $this->group = 'Administrator';
        $this->firstName = $this->faker->bothify('FirstName FN ?##?');
        $this->lastName = 'Last';


        //create other user for checkout do not apply shipping rate
        $this->userNameSecond = $this->faker->bothify('UserName ?##?');
        $this->passwordSecond = 'test';
        $this->emailSecond = $this->faker->email;
        $this->shopperGroupSecond = 'Default Private';
        $this->groupSecond = 'Administrator';
        $this->firstNameSecond = $this->faker->bothify('FirstName FN ?##?');
        $this->lastNameSecond = $this->faker->bothify('Lasename ?##?');

    }
//
//    public function deleteData($scenario)
//    {
//        $I= new RedshopSteps($scenario);
//        $I->clearAllData();
//    }

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }


    public function preCheckout(AcceptanceTester $I, $scenario)
    {
//        $I = new CategoryManagerJoomla3Steps($scenario);
//        $I->wantTo('Create first category');
//        $I->addCategorySave($this->CategoryName);


//        $I->wantTo('Create the second Category');
//        $I->addCategorySave($this->categoryNameSecond);
//
//        $this->CategoryName = '- '.$this->CategoryName;
//        $I = new ProductManagerJoomla3Steps($scenario);
//        $I->wantTo('I Want to add product inside the category');
//        $I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
//
//        $I->wantTo('Create second product and add for second category');
//        $I->createProductSave($this->productNameSecond, $this->categoryNameSecond, $this->randomProductNumberSecond, $this->randomProductPriceSecond, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
//
//        $I = new ShopperGroupManagerJoomla3Steps($scenario);
//        $I->addShopperGroups($this->shopperName, $this->shopperType, $this->customerType,$this->shopperGroupPortal, $this->CategoryName,$this->shipping,$this->shippingRate, $this->shippingCheckout, $this->catalog,$this->showVat, $this->showPrice, $this->enableQuotation,'save');

//        $I->wantTo('Test User creation with save button in Administrator');
//        $I = new UserManagerJoomla3Steps($scenario);
//        $I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperName, $this->firstName, $this->lastName, 'save');

        $I->wantTo('Add other shopper user and do not apply any shipping for this user');
        $I = new UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userNameSecond, $this->passwordSecond, $this->emailSecond, $this->groupSecond, $this->shopperGroupSecond, $this->firstNameSecond, $this->lastNameSecond, 'save');





        $I->wantTo('Test Discount creation with save and close button in Administrator');
        $I = new ShippingSteps($scenario);
        $I->wantTo('Create a shipping rate for first category');
        $I->createShippingRateStandard($this->shippingName, $this->shippingRate, $this->weightStart, $this->weightEnd, $this->volumeStart, $this->volumeEnd, $this->shippingRateLenghtStart, $this->shippingRateLegnhtEnd, $this->shippingRateWidthStart, $this->shippingRateWidthEnd, $this->shippingRateHeightStart, $this->shippingRateHeightEnd
            , $this->orderTotalStart, $this->orderTotalEnd, $this->zipCodeStart, $this->zipCodeEnd, $this->country, $this->shippingRateProduct, $this->CategoryName,
            $this->shopperName, $this->shippingPriority, $this->shippingRateFor, $this->shippingVATGroups, 'save');

        $I->wantTo('Create a Shipping rate for second category');
        $I->createShippingRateStandard($this->shippingNameSecond, $this->shippingRate, $this->weightStart, $this->weightEnd, $this->volumeStart, $this->volumeEnd, $this->shippingRateLenghtStart, $this->shippingRateLegnhtEnd, $this->shippingRateWidthStart, $this->shippingRateWidthEnd, $this->shippingRateHeightStart, $this->shippingRateHeightEnd
            , $this->orderTotalStart, $this->orderTotalEnd, $this->zipCodeStart, $this->zipCodeEnd, $this->country, $this->shippingRateProduct, $this->categoryNameSecond,
            $this->shopperName, $this->shippingPriority, $this->shippingRateFor, $this->shippingVATGroups, 'save');


        $this->Total  = $this->randomProductPrice + $this->shippingRate;
        $this->TotalShow = 'DKK '.$this->Total;
        $I->wantTo('Test This user will apply shipping rate');
        $I = new ProductCheckoutManagerJoomla3Steps($scenario);
        $I->checkoutSpecificShopperGroup($this->userName,$this->password,$this->ProductName ,$this->CategoryNamePlus,$this->shippingRate,$this->TotalShow);


    }
    public function checkoutWithUseNotApplyShipping(AcceptanceTester $I, $scenario)
    {
        $this->TotalNotIncludeShipping = 'DKK '.$this->randomProductPrice;
        $I = new ProductCheckoutManagerJoomla3Steps($scenario);
        $I->wantTo('Test with user do not apply shipping rate');
        $I->checkoutSpecificShopperGroup($this->userNameSecond,$this->passwordSecond,$this->ProductName ,$this->CategoryNamePlus,'0',$this->TotalNotIncludeShipping);

    }
}