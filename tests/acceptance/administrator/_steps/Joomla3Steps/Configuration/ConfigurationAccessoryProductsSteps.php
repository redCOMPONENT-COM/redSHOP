<?php


namespace Configuration;


use AcceptanceTester;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

class ConfigurationAccessoryProductsCest
{
    public function createProductWithAccessories(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Accessories Save Manager in Administrator');
        $I = new ConfigurationSteps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->configurationProductAccessoryYes();
        
        $I->wantTo('Create Category ');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySaveClose($this->randomCategoryName);

        $I->wantTo('Create Category ');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->createProductSaveClose($this->productName, $this->randomCategoryName, $this->productNumber ,$this->price);

        $I->wantTo('Create Category ');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->createProductWithAccessories($this->productNameAccessories, $this->randomCategoryName, $this->productNumber1 ,$this->price,$this->productName );



        $I->wantTo('Test Product Accessories Save Manager in Administrator');
        $I = new ConfigurationSteps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->configurationProductAccessoryNo();
    }

}