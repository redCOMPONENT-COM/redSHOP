<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;

/**
 * Class ProductsCest
 * @since 2.1.4
 */
class ProductsCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.4
	 */
	protected $fake;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomCategoryName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomProductName;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $minimumPerProduct;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $minimumQuantity;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $maximumQuantity;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $discountStart;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $discountEnd;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductNumberNew;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductAttributeNumber;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $randomProductNameAttribute;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $randomProductPrice;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $discountPriceThanPrice;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $statusProducts;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $searchCategory;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $newProductName;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $nameAttribute;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $valueAttribute;

	/**
	 * @var int
	 * @since 2.1.4
	 */
	protected $priceAttribute;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $nameProductAccessories;

	/**
	 * @var string
	 * @since 2.1.4
	 */
	protected $nameRelatedProduct;

	/**
	 * @var array
	 * @since 2.1.4
	 */
	protected $product;

	/**
	 * @var array
	 * @since 2.1.4
	 */
	protected $configAttribute;

	public function __construct()
	{
		$this->fake                         = Faker\Factory::create();
		$this->randomCategoryName           = $this->fake->bothify('TestingCategory ?##?');
		$this->randomProductName            = $this->fake->bothify('Testing ProductManagement ?##?');
		$this->minimumPerProduct            = 2;
		$this->minimumQuantity              = 3;
		$this->maximumQuantity              = 5;
		$this->discountStart                = "2016-12-12";
		$this->discountEnd                  = "2017-05-23";
		$this->randomProductNumber          = rand(999, 9999);
		$this->randomProductNumberNew       = rand(999, 9999);
		$this->randomProductAttributeNumber = rand(999, 9999);
		$this->randomProductNameAttribute   = $this->fake->bothify('Testing Attribute ?##?');
		$this->randomProductPrice           = rand(9, 19);
		$this->discountPriceThanPrice       = 100;
		$this->statusProducts               = 'Product on sale';
		$this->searchCategory               = 'Category';
		$this->newProductName               = $this->fake->bothify('New-Test Product ?##?');
		$this->nameAttribute                = 'Size';
		$this->valueAttribute               = "Z";
		$this->priceAttribute               = 12;
		$this->nameProductAccessories       = $this->fake->bothify('Product Accessories ?##?');
		$this->nameRelatedProduct           = $this->fake->bothify('Product Related ?##?');

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

		$this->configAttribute =
			[
				'attributeRequire' => 'yes',
				'multipleSelection' => 'yes',
				'hideAttributePrice' => 'yes',
				'published' => 'yes'
			];
	}

	/**
	 * Function to test ProductManagement Manager in Administrator
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

	/**
	 * @param ProductManagerSteps $I
	 * @since 2.1.4
	 */
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

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function addCategory(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category');
		$I->addCategorySave($this->randomCategoryName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function createProductQuantityStartThanEnd(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Product QuantityStart More Than End Product Number Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add Product  Missing Product Number  product inside the category');
		$I->checkStartMoreThanEnd($this->product);

	}

	/**
	 * @param ProductManagerSteps $I
	 * @throws Exception
	 * @since 2.1.4
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

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @since 2.1.4
	 */
	public function copyProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Copy Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to copy product inside the category');
		$I->copyProduct($this->randomProductName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function checkSelectCategory(AcceptanceTester $I, $scenario)
	{

		$I->wantTo('Test Select Category Product in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to check select category add product inside the category');
		$I->checkSelectCategory($this->randomCategoryName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function checkStatusSearch(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Select Status Category Product in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to check select status category add product inside the category');
		$I->checkSelectStatus($this->statusProducts);
	}

	/**
	 * @param ProductManagerSteps $I
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function deleteProduct(ProductManagerSteps $I)
	{
		$I->wantTo('Delete Product Manager in Administrator');
		$I->deleteProduct($this->randomProductName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function createProductSaveClose(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save Close Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function updateNameProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test update Name of Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to Update name product inside the category');
		$I->checkEditSave($this->randomProductName, $this->newProductName);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function createProductSaveNew(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Save New Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveNew($this->randomProductName, $this->randomCategoryName, $this->randomProductNumberNew, $this->randomProductPrice);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function createProductCancel(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Cancel  Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductCancel();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function deleteProductUpdate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete Product Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to delete product inside the category');
		$I->deleteProduct($this->randomProductName);
		$I->deleteProduct($this->newProductName);
	}

	/**
	 * @param ProductManagerSteps $I
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function createProductMissingName(ProductManagerSteps $I)
	{
		$I->wantTo('I Want to add Product Missing name product inside the category');
		$I->createMissingCases($this->randomCategoryName, $this->randomProductNumber, $this->randomProductName, $this->randomProductPrice, 'name');
		$I->createMissingCases($this->randomCategoryName, $this->randomProductNumber, $this->randomProductName, $this->randomProductPrice, 'category');
		$I->createMissingCases($this->randomCategoryName, $this->randomProductNumber, $this->randomProductName, $this->randomProductPrice, 'number');
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function createProductWithAttribute(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test create Product with attribute Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductWithAttribute($this->randomProductNameAttribute, $this->randomCategoryName, $this->randomProductAttributeNumber, $this->randomProductPrice, $this->nameAttribute, $this->valueAttribute, $this->priceAttribute);
		$I->saveAsCopyProductAttribute($this->randomProductNameAttribute, $this->configAttribute);
		$I->CheckProductAttributeAfterSaveAsCopy($this->randomProductNameAttribute . ' (2)', $this->configAttribute);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function deleteAttributeValue(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test delete attribute value DeProduct Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->deleteAttributeValue($this->randomProductNameAttribute, $this->valueAttribute);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function deleteAttribute(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Delete Attribute Product Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->deleteAttribute($this->randomProductNameAttribute);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function createProductWithAccessories(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Product Accessories Save Manager in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductWithAccessories($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->nameProductAccessories);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function unPublishAllProducts(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test ProductManagement Unpublish all products in Administrator');
		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Unpublish all products');
		$I->unPublishAllProducts();
		$I->wantTo('Publish all products');
		$I->publishAllProducts();
	}

	/**
	 * @param ProductManagerSteps $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function deleteAllProduct(ProductManagerSteps $I, $scenario)
	{
		$I->wantTo('Delete product');
		$I->deleteProduct($this->randomProductName);
		$I->deleteProduct($this->randomProductNameAttribute);
		$I->deleteProduct($this->nameRelatedProduct);
		$I->deleteProduct($this->nameProductAccessories);

		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->randomCategoryName);
	}
}
