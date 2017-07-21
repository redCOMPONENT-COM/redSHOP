<?php

/**
 * Class check page Mass Discount
 *
 * This page missing create mass discount have start date before end date
 *
 */
class ManagerMassDiscountAdministratorCest
{
    public function __construct()
    {

        $this->faker = Faker\Factory::create();
        $this->ProductName = 'ProductName' . rand(100, 999);
        $this->MassDiscountName = 'MassDiscount' . rand(10, 100);
        $this->MassDiscountNameSave = 'MassDiscountSave' . rand(10, 1000);
        $this->MassDiscountNameEdit = 'Edit' . $this->MassDiscountName;
        $this->CategoryName = "CategoryName" . rand(1, 100);
        $this->ManufactureName = "ManufactureName" . rand(1, 10);
        $this->MassDiscountAmoutTotal = $this->faker->numberBetween(10, 100);
        $this->MassDiscountPercent = 0.3;
        $this->minimumPerProduct = $this->faker->numberBetween(1, 3);
        $this->minimumQuantity = $this->faker->numberBetween(10, 100);
        $this->maximumQuantity = $this->faker->numberBetween(100, 1000);
        $this->discountStart = "12-12-2016";
        $this->discountEnd = "23-05-2017";
        $this->randomProductNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductNumberNew = $this->faker->numberBetween(999, 9999);
        $this->randomProductAttributeNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductNameAttribute = 'Testing Attribute' . rand(99, 999);
        $this->randomProductPrice = $this->faker->numberBetween(99, 199);
        $this->discountPriceThanPrice = $this->faker->numberBetween(10, 100);;
        $this->statusProducts = 'Product on sale';
        $this->searchCategory = 'Category';
        $this->newProductName = 'New-Test Product' . rand(99, 999);
        $this->nameAttribute = 'Size';
        $this->valueAttribute = "Z";
        $this->priceAttribute = $this->faker->numberBetween(1, 30);
        $this->nameProductAccessories = "redFORM";
        $this->nameRelatedProduct = "redITEM";
        $this->quantityStock = $this->faker->numberBetween(10, 100);;
        $this->PreorderStock = $this->faker->numberBetween(10, 100);;
        $this->priceProductForThan = $this->faker->numberBetween(10, 100);;

    }

    /*
     * Function to create new Category
     */
    public function createCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Category in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySave($this->CategoryName);
    }

    /*
     * Funtion to create Product inside the category
     *
     * @depends createCategory
     */

    public function createProductSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSave($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
    }

    /*
   * Funtion to mass Discount for Product inside the category
   *
   * @depends createProductSave
   */
    public function addMassDiscount(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscount($this->MassDiscountName, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);
    }

    public function addMassDiscountSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount with Save and Close in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscountSaveClose($this->MassDiscountNameSave, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);

    }

//    public function addMassDiscountStartThanEnd(AcceptanceTester $I, $scenario)
//    {
//        $I->wantTo(' add Mass discount Start Than End  in Administrator');
//        $I->doAdministratorLogin();
//        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
//        $I->wantTo('Test check add Mass discount ');
//        $I->addMassDiscountSaveClose($this->MassDiscountName, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);
//    }

    public function addMassDiscountMissingAllFields(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount missing all fields in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscountMissingAllFields();
    }


    public function checkCancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount check cancel button at new form in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->checkCancelButton();
    }

    public function addMassDiscountMissingName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount missing name in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscountMissingName($this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);

    }

    public function addMassDiscountMissingAmount(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount missing amount in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscountMissingAmount($this->MassDiscountName, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);

    }

    public function addMassDiscountMissingProducts(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount missing product in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscountMissingProducts($this->MassDiscountName, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd);
    }

    public function editMassDiscount(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' Edit Name Mass discount with save in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->editMassDiscount($this->MassDiscountName, $this->MassDiscountNameEdit);
    }

    public function editButtonMassDiscountSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' edit  Mass discount with save and close in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->editButtonMassDiscountSave($this->MassDiscountNameEdit, $this->MassDiscountName);
    }

    public function checkCloseButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' check button close button Mass discount in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->checkCloseButton($this->MassDiscountName);
    }

    public function deleteMassDiscountCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' delete Mass discount in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->deleteMassDiscountCancel($this->MassDiscountName);
    }

    public function deleteMassDiscountOK(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' delete mass discount Mass discount in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check delete Mass discount ');
        $I->deleteMassDiscountOK($this->MassDiscountName);
    }

    public function addMassDiscountForDeleteAll(AcceptanceTester $I, $scenario)
    {
        $I->wantTo(' add Mass discount with Save and Close in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->addMassDiscountSaveClose($this->MassDiscountName, $this->MassDiscountAmoutTotal, $this->discountStart, $this->discountEnd, $this->CategoryName, $this->ProductName);

    }

    public function deleteAllMassDiscountOK(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('delete all mass discounts in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\MassDiscountManagerJoomla3Steps($scenario);
        $I->wantTo('Test check add Mass discount ');
        $I->deleteAllMassDiscountOK($this->MassDiscountName);

    }

}