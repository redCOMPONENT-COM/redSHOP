<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/8/2017
 * Time: 2:01 PM
 */
class ManagerMassDiscountAdministratorCest
{
    public function __construct()
    {

        $this->ProductName = 'ProductName' . rand(100, 999);
        $this->MassDiscountName = 'MassDiscount' . rand(10, 100);
        $this->CategoryName = "CategoryName" . rand(1, 100);
        $this->ManufactureName = "ManufactureName" . rand(1, 10);
        $this->MassDiscountNameEdit = ProductName . 'edit';
        $this->MassDiscountAmoutTotal = rand(10, 100);
        $this->MassDiscountPercent = 0.3;


        $this->minimumPerProduct = 2;
        $this->minimumQuantity = 3;
        $this->maximumQuantity = 5;
        $this->discountStart = "12-12-2016";
        $this->discountEnd = "23-05-2017";
        $this->randomProductNumber = rand(999, 9999);
        $this->randomProductNumberNew = rand(999, 9999);
        $this->randomProductAttributeNumber = rand(999, 9999);
        $this->randomProductNameAttribute = 'Testing Attribute' . rand(99, 999);
        $this->randomProductPrice = rand(99, 199);
        $this->discountPriceThanPrice =100;
        $this->statusProducts = 'Product on sale';
        $this->searchCategory = 'Category';
        $this->newProductName = 'New-Test Product' . rand(99, 999);
        $this->nameAttribute = 'Size';
        $this->valueAttribute = "Z";
        $this->priceAttribute = 12;
        $this->nameProductAccessories = "redFORM";
        $this->nameRelatedProduct = "redITEM";
        $this->quantityStock = 4;
        $this->PreorderStock = 2;
        $this->priceProductForThan=10;

    }

    public function testProductAdministrator(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Category in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategory($this->CategoryName);
    }

    public function createProductSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
    }

    public function addMassDiscount(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscount($this->MassDiscountName, $this->MassDiscountAmoutTotal,$this->ProductName);
        $I->see("Mass Discounts Management", '.page-title');
    }

}