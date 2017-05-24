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
class ProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a Product
	 *
	 * @return void
	 */
	public function addProduct()
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Manager New');
		$I->click('Cancel');
	}


	public function checkEditButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Edit");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkCopyButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Copy");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function copyProduct($nameProduct){
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $this->searchProduct($nameProduct);
        $I->checkAllResults();
        $I->click("Copy");
        $I->waitForText("Product Copied", 60, '.alert-success');
        $I->see("Product Copied", '.alert-success');
    }

    public function checkDeleteButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }



    public function checkPublishButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Publish");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkUnpublishButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Unpublish");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkAssignNewCategoryButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Assign new Category");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }

    public function checkRemoveCategoryButton(){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("Remove Category");
        $I->acceptPopup();
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }




    public function createProductSave($productName, $productCategory, $productNumber, $price, $minimumPerProduct, $minimumQuantity, $maximumQuantity, $discountStart,$discountEnd)
    {
        $I=$this;
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
        $I->fillField(\ProductManagerPage::$discountStart,$discountStart);
        $I->fillField(\ProductManagerPage::$discountEnd,$discountEnd);
        $I->fillField(\ProductManagerPage::$minimumPerProduct,$minimumPerProduct);
        $I->fillField(\ProductManagerPage::$minimumQuantity,$minimumQuantity);
        $I->fillField(\ProductManagerPage::$maximumQuantity,$maximumQuantity);
        $I->click("Save");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }

    public function createProductMissingName( $productCategory, $productNumber, $price){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_number"], $productNumber);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click("Save");
        $I->acceptPopup();
        $I->waitForElement(['id' => "product_name"], 30);
//
    }

    public function createProductMissingProductNumber($productName,$productCategory, $price){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(['id' => "product_name"], $productName);
        $I->fillField(['id' => "product_price"], $price);
        $I->click(['xpath' => "//div[@id='s2id_product_category']//ul/li"]);
        $I->fillField(['xpath' => "//div[@id='s2id_product_category']//ul/li//input"], $productCategory);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click(['xpath' => "//span[contains(text(), '" . $productCategory . "')]"]);
        $I->click("Save");
        $I->acceptPopup();
        $I->waitForElement(['id' => "product_name"], 30);
    }

    public function createPrductMissingCategory($productName, $productNumber, $price){
        $I=$this;
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
        $I->click("Save");
        $I->acceptPopup();
        $I->waitForElement(['id' => "product_name"], 30);
    }


    public function deleteProductCancel($productName){
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $this->searchProduct($productName);
        $I->checkAllResults();
        $I->click("Delete");
        $I->cancelPopup();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);

    }
    public function deleteProduct($productName)
    {
        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $this->searchProduct($productName);
        $I->checkAllResults();
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForText("Product deleted successfully", 60, '.alert-success');
        $I->see("Product deleted successfully", '.alert-success');
        $I->dontSee($productName);
    }


    public function createProductSaveClose($productName, $productCategory, $productNumber, $price)
    {
        $I=$this;
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
        $I->click("Save & Close");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->see('Product details saved', ['class' => 'alert-success']);
    }

    public function createProductSaveNew($productName, $productCategory, $productNumber, $price)
    {
        $I=$this;
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
        $I->click("Save & New");
        $I->waitForText('Product details saved', 30, ['class' => 'alert-success']);
        $I->waitForElement(['id' => "product_name"], 30);
    }



    public function createProductCancel()
    {
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click("New");
        $I->waitForElement(['id' => "product_name"], 30);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Cancel");
        $I->waitForText('Product detail editing cancelled', 30, ['class' => 'alert-success']);
        $I->see('Product detail editing cancelled', ['class' => 'alert-success']);
    }



    public function checkSelectCategory( $categoryName){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click(['xpath' => '//div[@id="s2id_category_id"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $categoryName);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $categoryName . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $categoryName . "')]"]);
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }


    public function checkSelectStatus( $statusSearch){
        $I=$this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->click(['xpath' => '//div[@id="s2id_product_sort"]//a']);
        $I->waitForElement(['id' => "s2id_autogen2_search"]);
        $I->fillField(['id' => "s2id_autogen3_search"], $statusSearch);
        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $statusSearch . "')]"], 60);
        $I->click(['xpath' => "//span[contains(text(), '" . $statusSearch . "')]"]);
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }


    public function createProductSaveCopy($productName, $productCategory, $productNumber, $price)
    {
        $I=$this;
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

    //The function for edit product

    public function checkEditSave($productName, $productNameEdit){

        $I = $this;
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->searchProduct($productName);
        $I->wait(3);
        $I->click(['link' => $productName]);
        $I->waitForElement(\ProductManagerPage::$productName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Edit');
        $I->fillField(\ProductManagerPage::$productName, $productNameEdit);
        $I->click("Save & Close");
        $I->waitForElement(\ProductManagerPage::$productFilter, 30);
    }


    public function searchProduct($productName){
        $I = $this;
        $I->wantTo('Search the Product');
        $I->amOnPage(\ProductManagerPage::$URL);
        $I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
        $I->filterListBySearchingProduct($productName);
    }




}
