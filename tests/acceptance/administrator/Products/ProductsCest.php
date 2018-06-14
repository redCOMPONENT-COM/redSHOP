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

use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;

class ProductsCest
{
	public function __construct()
	{
		$this->fake                         = Faker\Factory::create();
		$this->randomCategoryName           = 'TestingCategory' . rand(99, 999);
		$this->ramdoCategoryNameAssign      = 'CategoryAssign' . rand(99, 999);
		$this->randomProductName            = 'Testing Products' . rand(99, 999);
		$this->minimumPerProduct            = 2;
		$this->minimumQuantity              = 3;
		$this->maximumQuantity              = 5;
		$this->discountStart                = "2016-12-12";
		$this->discountEnd                  = "2017-05-23";
		$this->randomProductNumber          = rand(999, 9999);
		$this->randomProductNumberNew       = rand(999, 9999);
		$this->randomProductAttributeNumber = rand(999, 9999);
		$this->randomProductNameAttribute   = 'Testing Attribute' . rand(99, 999);
		$this->randomProductPrice           = rand(9, 19);
		$this->discountPriceThanPrice       = 100;
		$this->statusProducts               = 'Product on sale';
		$this->searchCategory               = 'Category';
		$this->newProductName               = 'New-Test Product' . rand(99, 999);
		$this->nameAttribute                = 'Size';
		$this->valueAttribute               = "Z";
		$this->priceAttribute               = 12;
		$this->nameProductAccessories       = "redFORM";
		$this->nameRelatedProduct           = "redITEM";
		$this->quantityStock                = 4;
		$this->PreorderStock                = 2;
		$this->priceProductForThan          = 10;

		$this->product                    = array();
		$this->product['name']            = $this->newProductName;
		$this->product['number']          = $this->randomProductNumber;
		$this->product['category']        = $this->randomCategoryName;
		$this->product['price']           = $this->randomProductPrice;
		$this->product['discountStart']   = $this->discountStart;
		$this->product['discountEnd']     = $this->discountEnd;
		$this->product['discountPrice']   = $this->fake->numberBetween(100, 1000);
		$this->product['maximumQuantity'] = $this->maximumQuantity;
		$this->product['minimumQuantity'] = $this->minimumQuantity;
	}

	/**
	 * Function to test Products Manager in Administrator
	 *
	 * @param   AcceptanceTester $I        Tester Object
	 * @param   String           $scenario Scenario Name
	 *
	 * @return void
	 */
	public function _before(ProductManagerSteps $I)
	{
		$I->doAdministratorLogin();
	}

	public function checkButton(ProductManagerSteps $I)
	{
		$I->checkButton('edit');
		$I->checkButton('copy');
		$I->checkButton('delete');
		$I->checkButton('publish');
		$I->checkButton('unpublish');
		$I->checkButton('assignNewCategory');
		$I->checkButton('removeCategory');
	}

	public function testProductAdministrator(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->randomCategoryName);
	}

	public function createProductQuantityStartThanEnd(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Product QuantityStart More Than End Product Number Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add Product  Missing Product Number  product inside the category');
		$I->checkStartMoreThanEnd($this->product);

	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 */
	public function createProductSave(ProductManagerSteps $I)
	{
		$I->wantTo('Test Product Save Manager in Administrator');
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

		$I->wantTo('create Product Accessories ');
		$I->createProductSaveClose($this->nameProductAccessories, $this->randomCategoryName, $this->fake->numberBetween(1,10000), $this->randomProductPrice);

		$I->wantTo('create Related Product ');
		$I->createProductSaveClose($this->nameRelatedProduct, $this->randomCategoryName, $this->fake->numberBetween(1,10000), $this->randomProductPrice);
	}

	public function copyProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Copy Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to copy product inside the category');
		$I->copyProduct($this->randomProductName);
	}

	public function checkSelectCategory(AcceptanceTester $I, $scenario)
	{

		$I->wantTo('Test Select Category Product in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to check select category add product inside the category');
		$I->checkSelectCategory($this->randomCategoryName);
	}

	public function checkStatusSearch(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Select Status Category Product in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to check select category add product inside the category');
		$I->checkSelectStatus($this->statusProducts);
	}

	public function deleteProduct(ProductManagerSteps $I)
	{
		$I->wantTo('Delete Product Manager in Administrator');
		$I->deleteProduct($this->randomProductName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 */

	public function createProductSaveClose(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save Close Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
	}

	public function updateNameProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test update Name of Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to Update name product inside the category');
		$I->checkEditSave($this->randomProductName, $this->newProductName);
	}

	public function createProductSaveNew(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save New Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveNew($this->randomProductName, $this->randomCategoryName, $this->randomProductNumberNew, $this->randomProductPrice);
	}

	public function createProductCancel(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Cancel  Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductCancel();
	}

	public function deleteProductUpdate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete Product Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to delete product inside the category');
		$I->deleteProduct($this->randomProductName);
		$I->deleteProduct($this->newProductName);
	}

	public function createProductMissingName(ProductManagerSteps $I)
	{
		$I->wantTo('I Want to add Product Missing name product inside the category');
		$I->createMissingCases($this->randomCategoryName, $this->randomProductNumber, $this->randomProductName, $this->randomProductPrice, 'name');
		$I->createMissingCases($this->randomCategoryName, $this->randomProductNumber, $this->randomProductName, $this->randomProductPrice, 'category');
		$I->createMissingCases($this->randomCategoryName, $this->randomProductNumber, $this->randomProductName, $this->randomProductPrice, 'number');
	}

	public function createProductWithAttribute(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test create Product with attribute Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductWithAttribute($this->randomProductNameAttribute, $this->randomCategoryName, $this->randomProductAttributeNumber, $this->randomProductPrice, $this->nameAttribute, $this->valueAttribute, $this->priceAttribute);
	}

	public function deleteAttributeValue(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test delete attribute value DeProduct Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->deleteAttributeValue($this->randomProductNameAttribute);
	}

	public function deleteAttribute(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Delete Attribute Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->deleteAttribute($this->randomProductNameAttribute);
	}

	public function createProductWithAccessories(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Accessories Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductWithAccessories($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->nameProductAccessories);
	}

	public function unPublishAllProducts(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Products Unpublish all products in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Unpublish all products');
		$I->unPublishAllProducts();
		$I->wantTo('Publish all products');
		$I->publishAllProducts();
	}
}
