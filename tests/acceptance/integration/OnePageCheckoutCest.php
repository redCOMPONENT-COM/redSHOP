<?php

/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class OnePageCheckoutCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ConfigurationSteps as ConfigurationSteps ;
use AcceptanceTester\OrderManagerJoomla3Steps;
class OnePageCheckoutCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->ProductName = 'ProductName' . rand(100, 999);
//        $this-> ProductName= 'redCARPENTER';
        $this->CategoryName = "CategoryName" . rand(1, 100);
        
//        $this-> CategoryName= 'Templates';
        $this->ManufactureName = "ManufactureName" . rand(1, 10);
        $this->MassDiscountAmoutTotal = 90;
        $this->MassDiscountPercent = 0.3;
        $this->minimumPerProduct = 1;
        $this->minimumQuantity = 1;
        $this->maximumQuantity = $this->faker->numberBetween(100, 1000);
        $this->discountStart = "12-12-2016";
        $this->discountEnd = "23-05-2017";
        $this->randomProductNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductPrice = 100;

        $this->subtotal="DKK 100,00";
        $this->Total="DKK 100,00";


        $this->customerInformation = array(
            "email" => "test@test" . rand() . ".com",
            "firstName" => "Tester",
            "lastName" => "User",
            "address" => "Some Place in the World",
            "postalCode" => "23456",
            "city" => "Bangalore",
            "country" => "India",
            "state" => "Karnataka",
            "phone" => "8787878787"
        );

        $this->customerBussinesInformation=array(
            "email" => "test@test" . rand() . ".com",
            "companyName"=>"CompanyName",
            "businessNumber"=>1231312,
            "firstName" => "BusinessDemo",
            "lastName" => "UserBusiness",
            "address" => "Some Place in the World",
            "postalCode" => "23456",
            "city" => "Bangalore",
            "country" => "India",
            "state" => "Karnataka",
            "phone" => "8787878787",
            "eanNumber"=>1212331331231,
        );

        //configuration enable one page checkout
        $this->addcart = 'product';
        $this->allowPreOrder = 'yes';
        $this->cartTimeOut = $this->faker->numberBetween(100, 10000);
        $this->enabldAjax = 'no';
        $this->defaultCart = null;
        $this->buttonCartLead = 'Back to current view';
        $this->onePage = 'yes';
        $this->showShippingCart = 'no';
        $this->attributeImage = 'no';
        $this->quantityChange = 'no';
        $this->quantityInCart = 0;
        $this->minimunOrder = 0;
        $this->enableQuation = 'no';
        $this->onePageNo='no';
        $this->onePageYes='yes';

        $this->buttonCartLeadEdit = 'Directly to cart';
        $this->shippingWithVat     = "DKK 0,00";

    }


    /**
     * Step1: Clear all database
     * Step2: Goes on configuration and setup this site can use quotation
     * Step3: Create category
     * Step4: Create product inside category
     * Step5: Goes on frontend and create quotation with private account
     * Step6: goes on frontend login and logout to clear all at site frontend
     * Step7: Goes on frontend and create quotation with business account
     * Step8: Goes on admin page and delete all data and convert cart setting the same default demo
     */
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

    public function onePageCheckout(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('setup up one page checkout at admin');
//        $I = new ConfigurationSteps($scenario);
//        $I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePageYes,$this->showShippingCart,$this->attributeImage,$this->quantityChange,$this->quantityInCart,$this->minimunOrder);

        $I->wantTo('Create Category in Administrator');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->addCategorySave($this->CategoryName);

        $I = new ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSaveClose($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice);


        $I = new ProductCheckoutManagerJoomla3Steps($scenario);
        $I->comment('Test one page checkout with private user');
        $I->onePageCheckout($this->ProductName,$this->CategoryName,$this->subtotal,$this->Total,$this->customerBussinesInformation,'business');

        $I->comment('Test one page checkout with business user');
        $I->onePageCheckoutLogin('admin', 'admin', $this->ProductName, $this->CategoryName, $this->shippingWithVat, $this->Total);
//        $I->onePageCheckout($this->ProductName,$this->CategoryName,$this->subtotal,$this->Total,$this->customerInformation,'private');

    }

//    public function clearUpDatabse(AcceptanceTester $I,$scenario )
//    {
////        $I->wantTo('setup up one page checkout is no at admin');
////        $I = new ConfigurationSteps($scenario);
////        $I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLeadEdit, $this->onePageNo,$this->showShippingCart,$this->attributeImage,$this->quantityChange,$this->quantityInCart,$this->minimunOrder);
//
//        $I->wantTo('Delete product');
//        $I = new ProductManagerJoomla3Steps($scenario);
//        $I->deleteProduct($this->ProductName);
//
//        $I->wantTo('Delete Category');
//        $I = new CategoryManagerJoomla3Steps($scenario);
//        $I->deleteCategory($this->CategoryName);
//
//        $I->wantTo('Test Order delete by user  in Administrator');
//        $I = new OrderManagerJoomla3Steps($scenario);
//        $I->deleteOrder($this->customerInformation['firstName']);
//        $I->deleteOrder($this->customerBussinesInformation['firstName']);
//
//    }

}