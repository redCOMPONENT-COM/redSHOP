<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class ProductManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
use \ProductManagerPage as ProductManagerPage;
class ProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{

    public function copyProduct($nameProduct)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $this->searchProduct($nameProduct);
        $I->checkAllResults();
        $I->click(ProductManagerPage::$buttonCopy);
        $I->waitForText(ProductManagerPage::$messageCopySuccess, 60, ProductManagerPage::$selectorSuccess);
    }

    public function checkButton($name)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->waitForText(ProductManagerPage::$namePage, 30, ProductManagerPage::$namePageXpath);

        switch ($name)
        {
            case 'edit':
                $I->click(ProductManagerPage::$buttonEdit);
                $I->acceptPopup();
                break;
            case 'copy':
                $I->click(ProductManagerPage::$buttonCopy);
                $I->acceptPopup();
                break;
            case 'delete':
                $I->click(ProductManagerPage::$buttonDelete);
                $I->acceptPopup();
                break;
            case 'publish':
                $I->click(ProductManagerPage::$buttonPublish);
                $I->acceptPopup();
                break;
            case 'unpublish':
                $I->click(ProductManagerPage::$buttonUnpublish);
                $I->acceptPopup();
                break;
            case 'assignNewCategory':
                $I->click(ProductManagerPage::$buttonAssignNewCategory);
                $I->acceptPopup();
                break;
            case 'removeCategory':
                $I->click(ProductManagerPage::$buttonRemoveCategory);
                $I->acceptPopup();
                break;
            default:
                break;
        }
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }
    public function unPublishAllProducts()
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\ProductManagerPage::$checkAllProducts);
        $I->click(ProductManagerPage::$buttonUnpublish);
        $I->waitForText(ProductManagerPage::$messageHead, 30, ProductManagerPage::$selectorSuccess);
    }


    public function publishAllProducts()
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\ProductManagerPage::$checkAllProducts);
        $I->click(ProductManagerPage::$buttonPublish);
        $I->waitForText(ProductManagerPage::$messageHead, 30, ProductManagerPage::$selectorSuccess);
    }
    
    public function createProductSave($productName, $category, $productNumber, $price, $minimumPerProduct, $minimumQuantity, $maximumQuantity, $discountStart, $discountEnd)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(ProductManagerPage::$buttonNew);
        $I->waitForElement(ProductManagerPage::$productName, 30);
        $I->fillField(ProductManagerPage::$productName, $productName);
        $I->fillField(ProductManagerPage::$productNumber,$productNumber);
        $I->fillField(ProductManagerPage::$productPrice, $price);
        $I->click(ProductManagerPage::$categoryId);
        $I->fillField(ProductManagerPage::$categoryFile, $category);
        $usePage  = new ProductManagerPage();
        $I->waitForElement($usePage->returnChoice($category));
        $I->click($usePage->returnChoice($category));
        $I->fillField(ProductManagerPage::$discountStart, $discountStart);
        $I->fillField(ProductManagerPage::$discountEnd, $discountEnd);
        $I->fillField(ProductManagerPage::$minimumPerProduct, $minimumPerProduct);
        $I->fillField(ProductManagerPage::$minimumQuantity, $minimumQuantity);
        $I->fillField(ProductManagerPage::$maximumQuantity, $maximumQuantity);
        $I->click(ProductManagerPage::$buttonSave);
        $I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
    }


    public function createMissingCases($category, $productNumber, $productName, $price, $function)
    {
        $I = $this;
        $I->amOnPage(ProductManagerPage::$URL);
        $I->click(ProductManagerPage::$buttonNew);
        $I->waitForElement(ProductManagerPage::$productName, 30);
        $usePage = new ProductManagerPage();

        switch ($function)
        {
            case 'category':
                $I->fillField(ProductManagerPage::$productName, $productName);
                $I->fillField(ProductManagerPage::$productNumber,$productNumber);
                $I->fillField(ProductManagerPage::$productPrice, $price);
                $I->click(ProductManagerPage::$buttonSave);
                $I->acceptPopup();
                break;
            case 'name':
                $I->fillField(ProductManagerPage::$namePage,'');
                $I->waitForElement(ProductManagerPage::$categoryId, 30);
                $I->click(ProductManagerPage::$categoryId);
                $I->fillField(ProductManagerPage::$categoryFile, $category);
                $I->waitForElement($usePage->returnChoice($category));
                $I->click($usePage->returnChoice($category));
                $I->click(ProductManagerPage::$buttonSave);
                $I->acceptPopup();
                break;
            case 'number':
                $I->fillField(ProductManagerPage::$productNumber, '');
                $I->fillField(ProductManagerPage::$namePage, $productName);
                $I->click(ProductManagerPage::$buttonSave);
                $I->acceptPopup();
                break;
            default:
                break;
        }

        $I->waitForElement(ProductManagerPage::$productName, 30);
        $I->click(ProductManagerPage::$buttonClose);
        $I->searchProduct($productName);
        $I->fillField(ProductManagerPage::$searchField, $productName);
        $I->pressKey(ProductManagerPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($productName);
    }

    public function checkStartMoreThanEnd($product = array())
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(ProductManagerPage::$buttonNew);

        $I->waitForElement(ProductManagerPage::$productName, 30);
        $I->fillField(ProductManagerPage::$productName, $product['name']);
        $I->fillField(ProductManagerPage::$productNumber, $product['number']);
        $I->fillField(ProductManagerPage::$productPrice, $product['price']);
        $usePage  = new ProductManagerPage();
        $I->click(ProductManagerPage::$categoryId);
        $I->fillField(ProductManagerPage::$categoryFile, $product['category']);
        $I->waitForElement($usePage->returnChoice($product['category']));
        $I->click($usePage->returnChoice($product['category']));

        $I->wantToTest('check discount start date before discount end ');
        if (isset($product['discountStart']))
        {
            $I->fillField(ProductManagerPage::$discountStart, $product['discountEnd']);
        }

        if(isset($product['discountEnd']))
        {
            $I->fillField(ProductManagerPage::$discountEnd,  $product['discountStart']);
        }
        $I->click(ProductManagerPage::$buttonSave);
        $I->acceptPopup();

        $I->fillField(ProductManagerPage::$discountStart, 0);
        $I->fillField(ProductManagerPage::$discountEnd,  0);
        
        $I->wantTo('create Product Quantity Start More Than Quantity End');

        if(isset($product['minimumQuantity']))
        {
            $I->fillField(ProductManagerPage::$minimumQuantity, $product['maximumQuantity']);
        }

        if(isset($product['maximumQuantity']))
        {
            $I->fillField(ProductManagerPage::$maximumQuantity, $product['minimumQuantity']);
        }
        
        $I->click(ProductManagerPage::$buttonSave);
        $I->acceptPopup();
        
        $I->fillField(ProductManagerPage::$minimumQuantity, 0);
        $I->fillField(ProductManagerPage::$maximumQuantity,0);
        
        $I->wantToTest('create Discount Price More Than Price');
        if(isset($product['discountPrice']))
        {
            $I->fillField(\ProductManagerPage::$discountPrice, $product['discountPrice']);
        }
        $I->click(ProductManagerPage::$buttonSave);
        $I->acceptPopup();
        $I->seeInField(ProductManagerPage::$productName, $product['name']);


    }
    public function deleteProduct($productName)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $this->searchProduct($productName);
        $I->checkAllResults();
        $I->click(ProductManagerPage::$buttonDelete);
        
        $I->wantTo('Test with delete product but then cancel');
        $I->cancelPopup();
        
        $I->wantTo('Test with delete product then accept');
        $I->click(ProductManagerPage::$buttonDelete);
        $I->acceptPopup();
        $I->waitForText(ProductManagerPage::$messageDeleteProductSuccess, 60, ProductManagerPage::$selectorSuccess);
        $I->dontSee($productName);
    }
    

    public function createProductSaveClose($productName, $category, $productNumber, $price)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(ProductManagerPage::$buttonNew);
        $I->waitForElement(ProductManagerPage::$productName, 30);
        $I->fillField(ProductManagerPage::$productName, $productName);
        $I->fillField(ProductManagerPage::$productNumber,$productNumber);
        $I->fillField(ProductManagerPage::$productPrice, $price);
        $I->click(ProductManagerPage::$categoryId);
        $I->fillField(ProductManagerPage::$categoryFile, $category);
        $usePage  = new ProductManagerPage();
        $I->waitForElement($usePage->returnChoice($category));
        $I->click($usePage->returnChoice($category));
        $I->click(ProductManagerPage::$buttonSaveClose);
        $I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
    }

    public function createProductSaveNew($productName, $category, $productNumber, $price)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(ProductManagerPage::$buttonNew);
        $I->waitForElement(ProductManagerPage::$productName, 30);
        $I->fillField(ProductManagerPage::$productName, $productName);
        $I->fillField(ProductManagerPage::$productNumber,$productNumber);
        $I->fillField(ProductManagerPage::$productPrice, $price);
        $I->click(ProductManagerPage::$categoryId);
        $I->fillField(ProductManagerPage::$categoryFile, $category);
        $usePage  = new ProductManagerPage();
        $I->waitForElement($usePage->returnChoice($category));
        $I->click($usePage->returnChoice($category));
        $I->click(ProductManagerPage::$buttonSaveNew);
        $I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
        $I->waitForElement(ProductManagerPage::$productName, 30);
    }
    
    public function createProductCancel()
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(ProductManagerPage::$buttonNew);
        $I->waitForElement(ProductManagerPage::$productName, 30);
        $I->click(ProductManagerPage::$buttonCancel);
        $I->waitForText(ProductManagerPage::$messageCancel, 30, ProductManagerPage::$selectorSuccess);
    }


    public function checkSelectCategory($category)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(ProductManagerPage::$categorySearch);
        $I->waitForElement(ProductManagerPage::$categorySearchField);
        $I->fillField(ProductManagerPage::$categorySearchField, $category);
        $usePage = new ProductManagerPage();
        $I->waitForElement($usePage->returnChoice($category));
        $I->click($usePage->returnChoice($category));
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }


    public function checkSelectStatus($statusSearch)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->click(['xpath' => '//div[@id="s2id_product_sort"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $statusSearch);
        
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $statusSearch . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $statusSearch . "')]"]);
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }


    public function createProductSaveCopy($productName, $productCategory, $productNumber, $price)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        
        $I->click("Save & Copy");
        $I->waitForText('Product Copied', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }

    public function createProductWithAttribute($productName, $productCategory, $productNumber, $price, $nameAttribute,$valueAttribute,$priceAttribute)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(ProductManagerPage::$buttonProductAttribute);
        $I->waitForElement(['xpath' => "//h3[text()='Product Attributes']"], 60);
        $I->click("+ Add Attribute parameter");
        $I->waitForElement(['xpath' => "//a[text()='Attribute parameter']"], 60);
        $I->fillField(['xpath' => '//input[@name="attribute[1][name]"]'], $nameAttribute);
        $I->fillField(['xpath'=>'//input[@name="attribute[1][property][0][name]"]'],$valueAttribute);
        $I->fillField(['xpath'=>'//input[@name="attribute[1][property][0][price]"]'],$priceAttribute);
        $I->click("Save");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }

    public function createProductWithStockRoom($productName, $quantityStock,$PreorderStock)
    {
        $I = $this;
        $I->searchProduct($productName);
        $I->wait(3);
        $I->click(['link' => $productName]);
        $I->waitForElement(\ProductManagerPage::$productName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Edit');
        $I->click("Stockroom");
        $I->click('//ul[@class="tabconfig nav nav-pills nav-stacked"]/li[7]/a');
        $I->fillField(['xpath'=>'//input[@name="quantity[]"]'],$quantityStock);
        $I->fillField(['xpath'=>'//input[@name="preorder_stock[]"]'],$PreorderStock);
        $I->click("Save");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }

    public function deleteAttributeValue($productName){
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->searchProduct($productName);
        $I->click(['link' => $productName]);
        $I->waitForElement(\ProductManagerPage::$productName, 30);
        $I->click(ProductManagerPage::$buttonProductAttribute);
        $I->waitForElement(['xpath' => "//h3[text()='Product Attributes']"], 60);
        $I->click(ProductManagerPage::$buttonDelete);
        $I->cancelPopup();
        $I->click(ProductManagerPage::$buttonDelete);
        $I->acceptPopup();
    }

    public function deleteAttribute($productName){
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->searchProduct($productName);
        $I->click(['link' => $productName]);
        $I->waitForElement(\ProductManagerPage::$productName, 30);
        $I->click(ProductManagerPage::$buttonProductAttribute);
        $I->waitForElement(['xpath' => "//h3[text()='Product Attributes']"], 60);
        $I->click(ProductManagerPage::$buttonDeleteAttribute);
        $I->cancelPopup();
        $I->click(ProductManagerPage::$buttonDeleteAttribute);
        $I->acceptPopup();

    }


    //The function for edit product

    public function createProductWithAccessories($productName, $productCategory, $productNumber, $price, $productAccessories)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click("Accessory/Related Product");
        $I->waitForElement(['xpath' => "//h3[text()='Accessories']"], 60);
        $I->waitForElement(['xpath' => "//h3[text()='Related product']"], 60);
        $this->selectAccessories($productAccessories);
        $I->click("Save");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
    }

    private function selectRelatedProduct($relatedProduct)
    {
        $I = $this;
        $I->click(['xpath' => '//div[@id="s2id_related_product"]//a']);
        $I->fillField(['id' => "s2id_related_product"], $relatedProduct);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $relatedProduct . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $relatedProduct . "')]"]);
    }

    public function selectAccessories($accessoryName)
    {
        $I = $this;
        $I->click(['xpath' => '//div[@id="s2id_product_accessory_search"]//a']);
        $I->waitForElement(['id' => "s2id_product_accessory_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $accessoryName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $accessoryName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $accessoryName . "')]"]);
    }

    public function checkEditSave($productName, $productNameEdit)
    {

        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->searchProduct($productName);
        $I->click(['link' => $productName]);
        $I->waitForElement(\ProductManagerPage::$productName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Edit');
        $I->fillField(\ProductManagerPage::$productName, $productNameEdit);
        $I->click("Save & Close");
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }


    public function searchProduct($productName)
    {
        $I = $this;
        $I->wantTo('Search the Product');
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->filterListBySearchingProduct($productName);
    }


    // The test case for product used stockroom
	public function createProductInStock($productName,$productNumber,$price,$productCategory,$quantityInStock, $preOrder){

		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->waitForText(\ProductManagerPage::$namePage, 30, \ProductManagerPage::$headPage);
		$I->click(\ProductManagerPage::$buttonNew);
		$I->waitForElement(\ProductManagerPage::$productName, 30);
		$I->fillField(\ProductManagerPage::$productName, $productName);
		$I->fillField(\ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(\ProductManagerPage::$productPrice, $price);
		$I->click(\ProductManagerPage::$categoryId);
		$I->fillField(\ProductManagerPage::$categoryFile, $productCategory);
		$usePage = new \ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($productCategory));
		$I->click($usePage->returnChoice($productCategory));

        $I->pauseExecution();
		$I->click(\ProductManagerPage::$stockroomTab);
		$I->waitForElement(\ProductManagerPage::$quantityInStock,30);
		$I->fillField(\ProductManagerPage::$quantityInStock,$quantityInStock);
		$I->fillField(\ProductManagerPage::$preOrderStock,$preOrder);

		$I->click(\ProductManagerPage::$buttonSave);
		$I->waitForText(\ProductManagerPage::$messageSaveSuccess, 30, \ProductManagerPage::$selectorSuccess);
	}


}
