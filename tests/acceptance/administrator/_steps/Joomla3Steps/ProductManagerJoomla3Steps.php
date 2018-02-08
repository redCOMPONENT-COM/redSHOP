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
use ProductManagerPage as ProductManagerPage;

class ProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	public function copyProduct($nameProduct)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->filterListBySearchingProduct($nameProduct);
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
		$I->checkAllResults();
		$I->click(ProductManagerPage::$buttonUnpublish);
		$I->waitForText(ProductManagerPage::$messageHead, 30, ProductManagerPage::$selectorSuccess);
	}


	public function publishAllProducts()
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->checkAllResults();
		$I->click(ProductManagerPage::$buttonPublish);
		$I->waitForText(ProductManagerPage::$messageHead, 30, ProductManagerPage::$selectorSuccess);
	}

	public function createProductSave($productName, $category, $productNumber, $prices, $minimumPerProduct, $minimumQuantity, $maximumQuantity, $discountStart, $discountEnd)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->waitForElement(ProductManagerPage::$productPrice, 30);
		$I->addValueForField(ProductManagerPage::$productPrice, $prices);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
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
				$I->fillField(ProductManagerPage::$productNumber, $productNumber);
				$I->fillField(ProductManagerPage::$productPrice, $price);
				$I->click(ProductManagerPage::$buttonSave);
				$I->acceptPopup();
				break;
			case 'name':
				$I->fillField(ProductManagerPage::$productName, '');
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
				$I->fillField(ProductManagerPage::$productName, $productName);
				$I->click(ProductManagerPage::$buttonSave);
				$I->acceptPopup();
				break;
			default:
				break;
		}
		$I->click(ProductManagerPage::$buttonCancel);
		$I->waitForElement(ProductManagerPage::$productFilter, 30);
		$I->fillField(ProductManagerPage::$productFilter, $productName);
		$I->pressKey(ProductManagerPage::$productFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
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
		$usePage = new ProductManagerPage();
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $product['category']);
		$I->waitForElement($usePage->returnChoice($product['category']));
		$I->click($usePage->returnChoice($product['category']));

		$I->wantToTest('check discount start date before discount end ');
		if (isset($product['discountStart']))
		{
			$I->fillField(ProductManagerPage::$discountStart, $product['discountEnd']);
		}

		if (isset($product['discountEnd']))
		{
			$I->fillField(ProductManagerPage::$discountEnd, $product['discountStart']);
		}
		$I->click(ProductManagerPage::$buttonSave);
		$I->acceptPopup();

		$I->fillField(ProductManagerPage::$discountStart, 0);
		$I->fillField(ProductManagerPage::$discountEnd, 0);

		$I->wantTo('create Product Quantity Start More Than Quantity End');

		if (isset($product['minimumQuantity']))
		{
			$I->fillField(ProductManagerPage::$minimumQuantity, $product['maximumQuantity']);
		}

		if (isset($product['maximumQuantity']))
		{
			$I->fillField(ProductManagerPage::$maximumQuantity, $product['minimumQuantity']);
		}

		$I->click(ProductManagerPage::$buttonSave);
		$I->acceptPopup();

		$I->fillField(ProductManagerPage::$minimumQuantity, 0);
		$I->fillField(ProductManagerPage::$maximumQuantity, 0);

		$I->wantToTest('create Discount Price More Than Price');
		if (isset($product['discountPrice']))
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

	public function searchProduct($productName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->filterListBySearchingProduct($productName);
	}

	public function createProductSaveClose($productName, $category, $productNumber, $price)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category));
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	public function createProductWithVATGroups($productName, $category, $productNumber, $price, $vatGroups)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category));
		$I->click($usePage->returnChoice($category));
		$I->waitForElement(ProductManagerPage::$vatDropdownList, 30);
		$I->click(ProductManagerPage::$vatDropdownList);
		$I->waitForElement(ProductManagerPage::$vatSearchField, 30);
		$I->fillField(ProductManagerPage::$vatSearchField, $vatGroups);
		$I->waitForElement($usePage->returnChoice($vatGroups));
		$I->click($usePage->returnChoice($vatGroups));
		
		
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->pauseExecution();
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	public function createProductSaveNew($productName, $category, $productNumber, $price)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
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
		$I->click(ProductManagerPage::$searchStatusId);
		$I->waitForElement(ProductManagerPage::$searchStatusField);
		$I->fillField(ProductManagerPage::$searchStatusField, $statusSearch);

		$usePage = new \ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($statusSearch), 60);
		$I->click($usePage->returnChoice($statusSearch));
		$I->waitForElement(\ProductManagerPage::$productFilter, 30);
	}

	public function createProductSaveCopy($productName, $category, $productNumber, $price)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category));
		$I->click($usePage->returnChoice($category));

		$I->click(ProductManagerPage::$buttonSaveCopy);
		$I->waitForText(ProductManagerPage::$messageCopySuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	public function createProductWithAttribute($productName, $category, $productNumber, $price, $nameAttribute, $valueAttribute, $priceAttribute)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category));
		$I->click($usePage->returnChoice($category));


		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->click(ProductManagerPage::$addAttribute);
		$I->waitForElement(ProductManagerPage::$attributeNameFirst, 30);
		$I->fillField(ProductManagerPage::$attributeNameFirst, $nameAttribute);
		$I->fillField(ProductManagerPage::$attributeNamePropertyFirst, $valueAttribute);
		$I->fillField(ProductManagerPage::$attributePricePropertyFirst, $priceAttribute);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	public function deleteAttributeValue($productName)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->searchProduct($productName);
		$I->click(['link' => $productName]);
		$I->waitForElement(\ProductManagerPage::$productName, 30);
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->click(ProductManagerPage::$buttonDelete);
		$I->cancelPopup();
		$I->click(ProductManagerPage::$buttonDelete);
		$I->acceptPopup();
	}

	//The function for edit product

	public function deleteAttribute($productName)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->searchProduct($productName);
		$I->click(['link' => $productName]);
		$I->waitForElement(\ProductManagerPage::$productName, 30);
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->click(ProductManagerPage::$buttonDeleteAttribute);
		$I->cancelPopup();
		$I->click(ProductManagerPage::$buttonDeleteAttribute);
		$I->acceptPopup();

	}

	public function createProductWithAccessories($productName, $category, $productNumber, $price, $productAccessories)
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category));
		$I->click($usePage->returnChoice($category));


		$I->click(ProductManagerPage::$accessoryTab);
		$I->waitForElement(ProductManagerPage::$accessoriesValue, 60);
		$I->waitForElement(ProductManagerPage::$relatedProduct, 60);
		$this->selectAccessories($productAccessories);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	public function selectAccessories($accessoryName)
	{
		$I = $this;
		$I->waitForElement(ProductManagerPage::$accessorySearchID);
		$I->click(ProductManagerPage::$accessorySearchID);
		$I->fillField(\ProductManagerPage::$accessSearchField, $accessoryName);
		$userPage = new ProductManagerPage();
		$I->waitForElement($userPage->returnChoice($accessoryName), 60);
		$I->click($userPage->returnChoice($accessoryName));
	}

	public function checkEditSave($productName, $productNameEdit)
	{

		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->searchProduct($productName);
		$I->click(['link' => $productName]);
		$I->waitForElement(\ProductManagerPage::$productName, 30);
		$I->fillField(\ProductManagerPage::$productName, $productNameEdit);
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForElement(\ProductManagerPage::$productFilter, 30);
	}

	public function createProductInStock($productName, $productNumber, $prices, $productCategory, $quantityInStock, $preOrder)
	{

		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->waitForText(\ProductManagerPage::$namePage, 30, \ProductManagerPage::$headPage);
		$I->click(\ProductManagerPage::$buttonNew);
		$I->waitForElement(\ProductManagerPage::$productName, 30);
		$I->fillField(\ProductManagerPage::$productName, $productName);
		$I->fillField(\ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $prices);
		$I->click(\ProductManagerPage::$categoryId);
		$I->fillField(\ProductManagerPage::$categoryFile, $productCategory);
		$usePage = new \ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($productCategory));
		$I->click($usePage->returnChoice($productCategory));


		$I->click(\ProductManagerPage::$stockroomTab);
		$I->waitForElement(\ProductManagerPage::$quantityInStock, 30);
		$I->fillField(\ProductManagerPage::$quantityInStock, $quantityInStock);
		$I->fillField(\ProductManagerPage::$preOrderStock, $preOrder);
		$I->click(\ProductManagerPage::$buttonSave);
		$I->waitForText(\ProductManagerPage::$messageSaveSuccess, 30, \ProductManagerPage::$selectorSuccess);
	}

	// The test case for product used stockroom

	private function selectRelatedProduct($relatedProduct)
	{
		$I = $this;
		$I->click(ProductManagerPage::$relatedProductId);
		$I->fillField(ProductManagerPage::$relatedProductId, $relatedProduct);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($relatedProduct), 60);
		$I->click($usePage->returnChoice($relatedProduct));
	}
}
