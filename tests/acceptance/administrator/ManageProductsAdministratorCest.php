<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageProductsAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageProductsAdministratorCest
{


    public function __construct()
    {
        $this->randomCategoryName = 'TestingCategory' . rand(99, 999);
        $this->ramdoCategoryNameAssign = 'CategoryAssign' . rand(99, 999);
        $this->randomProductName = 'Testing Products' . rand(99, 999);
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

    /**
     * Function to test Products Manager in Administrator
     *
     * @param   AcceptanceTester $I Tester Object
     * @param   String $scenario Scenario Name
     *
     * @return void
     */
    public function testProductAdministrator(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Category in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySave($this->randomCategoryName);
    }

    /**
     * cleae  * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Edit Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkEditButton();
    }


    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCopyButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Copy Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkCopyButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Delete  Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkDeleteButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Publish Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkPublishButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Unpublish Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkUnpublishButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkAssignNewCategoryButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Assign New Category Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkAssignNewCategoryButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkRemoveCategoryButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Assign New Category Button Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->checkRemoveCategoryButton();
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     *
     */
    public function createProductSave(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
    }

    public function copyProduct(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Copy Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to copy product inside the category');
        $I->copyProduct($this->randomProductName);
    }

    public function checkSelectCategory(AcceptanceTester $I, $scenario)
    {

        $I->wantTo('Test Select Category Product in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to check select category add product inside the category');
        $I->checkSelectCategory($this->randomCategoryName);
    }

    public function checkStatusSearch(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Select Status Category Product in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to check select category add product inside the category');
        $I->checkSelectStatus($this->statusProducts);
    }

    public function deleteProductCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Delete Product Then Click Cancel Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to delete then click cancel product inside the category');
        $I->deleteProductCancel($this->randomProductName);
    }


    public function deleteProduct(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Delete Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to delete product inside the category');
        $I->deleteProduct($this->randomProductName);
    }


    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */

    public function createProductSaveClose(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save Close Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSaveClose($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
    }

    public function updateNameProduct(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test update Name of Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to Update name product inside the category');
        $I->checkEditSave($this->randomProductName, $this->newProductName);
    }

    public function createProductSaveNew(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Save New Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSaveNew($this->randomProductName, $this->randomCategoryName, $this->randomProductNumberNew, $this->randomProductPrice);
    }


    public function createProductCancel(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Cancel  Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductCancel();
    }

    public function createProductMissingName(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Missing Name Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add Product Missing name product inside the category');
        $I->createProductMissingName($this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
    }

    public function createProductMissingProductNumber(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Missing Product Number Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add Product  Missing Product Number  product inside the category');
        $I->createProductMissingProductNumber($this->randomProductName, $this->randomCategoryName, $this->randomProductPrice);
    }

    public function createPrductMissingCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Missing Category Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add Product  Missing Product Number  product inside the category');
        $I->createPrductMissingCategory($this->randomProductName, $this->randomProductNumber, $this->randomProductPrice);
    }

    public function createProductQuantityStartThanEnd(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Product QuantityStart More Than End Product Number Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add Product  Missing Product Number  product inside the category');
        $I->createProductQuantityStartThanEnd($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

    }

    public function deleteProductUpdate(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Delete Product Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to delete product inside the category');
        $I->deleteProduct($this->newProductName);
    }

    public function createDiscountPriceThanPrice(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Discount Price More Than Price Missing Product Number Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add Product Discount Price More Than Price Missing Product Number Manager inside the category');
        $I->createDiscountPriceThanPrice($this->randomProductName, $this->randomCategoryName, $this->randomProductNumberNew, $this->priceProductForThan, $this->discountPriceThanPrice);
    }

    public function createProductDayStartThanEnd(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Product Day Start More Than End Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add Product  Missing Product Number  product inside the category');
        $I->createProductDayStartThanEnd($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);
    }

    public function createProductWithAttribute(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test create Product with attribute Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductWithAttribute($this->randomProductNameAttribute, $this->randomCategoryName, $this->randomProductAttributeNumber, $this->randomProductPrice, $this->nameAttribute, $this->valueAttribute, $this->priceAttribute);
    }


    public function cancelDeleteAttributeValue(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test cancel Delete Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->cancelDeleteAttributeValue($this->randomProductNameAttribute);
    }

    public function deleteAttributeValue(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test delete attribute value DeProduct Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->deleteAttributeValue($this->randomProductNameAttribute);
    }


    public function cancelDeleteAttribute(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Cancel  Delete attribute Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->cancelDeleteAttribute($this->randomProductNameAttribute);
    }


    public function deleteAttribute(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Delete Attribute Product Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->deleteAttribute($this->randomProductNameAttribute);
    }


    public function createProductWithAccessories(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Product Accessories Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductWithAccessories($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->nameProductAccessories);
    }


    public function unPublishAllProducts(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Products Unpublish all products in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('Unpublish all products');
        $I->unPublishAllProducts();
        $I->see("Product Management", '.page-title');
    }

    public function publishAllProducts(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Products Publish all products in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('Publish all products');
        $I->publishAllProducts();
        $I->see("Product Management", '.page-title');
    }

    public function featureUsedStockRoom(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test used Stockroom is Yes in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
        $I->wantTo('Start stook room ');
        $I->featureUsedStockRoom();
        $I->see("Configuration", '.page-title');

    }

    public function createProductWithStockRoom(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test create Product with stockroom Save Manager in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product with stockroom ');
        $I->createProductWithStockRoom($this->randomProductName, $this->quantityStock, $this->PreorderStock);
    }

    public function featureStockRoomNo(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test used Stockroom is No in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
        $I->wantTo('Off stook room ');
        $I->featureOffStockRoom();
        $I->see("Configuration", '.page-title');
    }


}
