<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
use PriceProductJoomla3Page;
use UserManagerJoomla3Page;
use AdminJ3Page;

class ProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	public function copyProduct($nameProduct)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->filterListBySearchingProduct($nameProduct);
		$I->checkAllResults();
		$I->click(ProductManagerPage::$buttonCopy);
		$I->waitForText(ProductManagerPage::$messageCopySuccess, 60, ProductManagerPage::$selectorSuccess);
	}

	public function checkButton($name)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
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
		$I->waitForElement(ProductManagerPage::$productFilter, 30);
	}

	public function unPublishAllProducts()
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->checkAllResults();
		$I->click(ProductManagerPage::$buttonUnpublish);
	}

	/**
	 * @throws \Exception
	 */
	public function publishAllProducts()
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->checkAllResults();
		$I->click(ProductManagerPage::$buttonPublish);
		$I->waitForText(ProductManagerPage::$messageHead, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $prices
	 * @param $minimumPerProduct
	 * @param $minimumQuantity
	 * @param $maximumQuantity
	 * @param $discountStart
	 * @param $discountEnd
	 *
	 * @throws \Exception
	 */
	public function createProductSave($productName, $category, $productNumber, $prices, $minimumPerProduct, $minimumQuantity, $maximumQuantity, $discountStart, $discountEnd)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->waitForElement(ProductManagerPage::$productPrice, 30);
		$I->addValueForField(ProductManagerPage::$productPrice, $prices, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->fillField(ProductManagerPage::$discountStart, $discountStart);
		$I->fillField(ProductManagerPage::$discountEnd, $discountEnd);
		$I->fillField(ProductManagerPage::$minimumPerProduct, $minimumPerProduct);
		$I->fillField(ProductManagerPage::$minimumQuantity, $minimumQuantity);
		$I->fillField(ProductManagerPage::$maximumQuantity, $maximumQuantity);
		//$I->waitForElementVisible(ProductManagerPage::$buttonSave, 30);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $prices
	 * @param $discountPrice
	 * @param $minimumPerProduct
	 * @param $minimumQuantity
	 * @param $maximumQuantity
	 * @param $discountStart
	 * @param $discountEnd
	 *
	 * @throws \Exception
	 */
	public function createProductSaveHaveDiscount($productName, $category, $productNumber, $prices, $discountPrice, $minimumPerProduct, $minimumQuantity, $maximumQuantity, $discountStart, $discountEnd)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->waitForElement(ProductManagerPage::$productPrice, 30);
		$I->addValueForField(ProductManagerPage::$productPrice, $prices, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->waitForElement(ProductManagerPage::$discountPrice, 30);
		$I->fillField(ProductManagerPage::$discountPrice, $discountPrice);
		$I->fillField(ProductManagerPage::$discountStart, $discountStart);
		$I->fillField(ProductManagerPage::$discountEnd, $discountEnd);
		$I->fillField(ProductManagerPage::$minimumPerProduct, $minimumPerProduct);
		$I->fillField(ProductManagerPage::$minimumQuantity, $minimumQuantity);
		$I->fillField(ProductManagerPage::$maximumQuantity, $maximumQuantity);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}


	/**
	 * @param $category
	 * @param $productNumber
	 * @param $productName
	 * @param $price
	 * @param $function
	 *
	 * @throws \Exception
	 */
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
				$I->waitForElement($usePage->returnChoice($category), 30);
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

	/**
	 * @param array $product
	 *
	 * @throws \Exception
	 */
	public function checkStartMoreThanEnd($product = array())
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);

		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $product['name']);
		$I->fillField(ProductManagerPage::$productPrice, $product['price']);
		$usePage = new ProductManagerPage();
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $product['category']);
		$I->waitForElement($usePage->returnChoice($product['category']), 30);
		$I->click($usePage->returnChoice($product['category']));

		$I->wantToTest('check discount start date before discount end ');
		if (isset($product['discountStart']))
		{
			$I->addValueForField(ProductManagerPage::$discountStart, $product['discountEnd'], 10);
		}
		$I->fillField(ProductManagerPage::$productNumber, $product['number']);
		if (isset($product['discountEnd']))
		{
			$I->addValueForField(ProductManagerPage::$discountEnd, $product['discountStart'], 10);
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
			$I->fillField(ProductManagerPage::$discountPrice, $product['discountPrice']);
		}
		$I->click(ProductManagerPage::$buttonSave);
		$I->acceptPopup();
		$I->seeInField(ProductManagerPage::$productName, $product['name']);
	}

	/**
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function deleteProduct($productName)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText(ProductManagerPage::$namePage, 30, ProductManagerPage::$h1);
		$this->searchProduct($productName);
		$I->checkAllResults();
		$I->click(ProductManagerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForText(ProductManagerPage::$messageDeleteProductSuccess, 60, ProductManagerPage::$selectorSuccess);
		$I->dontSee($productName);
	}

	/**
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function deleteProductChild($productName)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText(ProductManagerPage::$namePage, 30, ProductManagerPage::$h1);
		$I->click(ProductManagerPage::$buttonReset);
		$I->waitForText(ProductManagerPage::$namePage, 30, ProductManagerPage::$h1);
		$I->waitForElementVisible(ProductManagerPage::$productSecond, 30);
		$I->click(ProductManagerPage::$productSecond);
		$I->click(ProductManagerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForText(ProductManagerPage::$messageDeleteProductSuccess, 60, ProductManagerPage::$selectorSuccess);
		$I->dontSee($productName);
	}

	/**
	 * @param $productName
	 */
	public function searchProduct($productName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(ProductManagerPage::$URL);
		$I->filterListBySearchingProduct($productName);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 *
	 * @throws \Exception
	 */
	public function createProductSaveClose($productName, $category, $productNumber, $price)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $vatGroups
	 *
	 * @throws \Exception
	 */
	public function createProductWithVATGroups($productName, $category, $productNumber, $price, $vatGroups)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->waitForElement(ProductManagerPage::$vatDropdownList, 30);
		$I->click(ProductManagerPage::$vatDropdownList);
		$I->waitForElement(ProductManagerPage::$vatSearchField, 30);
		$I->fillField(ProductManagerPage::$vatSearchField, $vatGroups);
		$I->waitForElement($usePage->returnChoice($vatGroups), 30);
		$I->click($usePage->returnChoice($vatGroups));
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 *
	 * @throws \Exception
	 */
	public function createProductSaveNew($productName, $category, $productNumber, $price)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonSaveNew);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
		$I->waitForElement(ProductManagerPage::$productName, 30);
	}

	/**
	 * @throws \Exception
	 */
	public function createProductCancel()
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->click(ProductManagerPage::$buttonCancel);
		$I->waitForText(ProductManagerPage::$messageCancel, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $category
	 *
	 * @throws \Exception
	 */
	public function checkSelectCategory($category)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$categorySearch);
		$I->waitForElement(ProductManagerPage::$categorySearchField, 30);
		$I->fillField(ProductManagerPage::$categorySearchField, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->waitForElement(ProductManagerPage::$productFilter, 30);
	}

	/**
	 * @param $statusSearch
	 *
	 * @throws \Exception
	 */
	public function checkSelectStatus($statusSearch)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$searchStatusId);
		$I->waitForElement(ProductManagerPage::$searchStatusField, 30);
		$I->fillField(ProductManagerPage::$searchStatusField, $statusSearch);

		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($statusSearch), 60);
		$I->click($usePage->returnChoice($statusSearch));
		$I->waitForElement(ProductManagerPage::$productFilter, 30);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 *
	 * @throws \Exception
	 */
	public function createProductSaveCopy($productName, $category, $productNumber, $price)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));

		$I->click(ProductManagerPage::$buttonSaveCopy);
		$I->waitForText(ProductManagerPage::$messageCopySuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $nameAttribute
	 * @param $valueAttribute
	 * @param $priceAttribute
	 *
	 * @throws \Exception
	 */
	public function createProductWithAttribute($productName, $category, $productNumber, $price, $nameAttribute, $valueAttribute, $priceAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));

		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->click(ProductManagerPage::$addAttribute);

		$I->fillField($usePage->addAttributeName(0), $nameAttribute);
		$I->attributeValueProperty(0, $valueAttribute,$priceAttribute);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $configAttribute
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function saveAsCopyProductAttribute($productName,$configAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
		$this->searchProduct($productName);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(['link' => $productName]);

		$I->waitForElementVisible(ProductManagerPage::$buttonProductAttribute, 30);
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);

		switch ($configAttribute['attributeRequire'])
		{
			case "yes":

				try
				{
					$I->seeCheckboxIsChecked(ProductManagerPage::$labelAttributeRequired);
				}
				catch (\Exception $e)
				{
					$I->waitForElementVisible(ProductManagerPage::$labelAttributeRequired, 30);
					$I->click(ProductManagerPage::$labelAttributeRequired);
				}

				break;

			case "no":
				try
				{
					$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelAttributeRequired);
				}
				catch (\Exception $e)
				{
					$I->waitForElementVisible(ProductManagerPage::$labelAttributeRequired, 30);
					$I->click(ProductManagerPage::$labelAttributeRequired);
				}
				break;
		}

		switch ($configAttribute['multipleSelection'])
		{
			case "yes":

				try
				{
					$I->seeCheckboxIsChecked(ProductManagerPage::$labelMultipleSelection);
				}
				catch (\Exception $e)
				{
					$I->waitForElementVisible(ProductManagerPage::$labelMultipleSelection, 30);
					$I->click(ProductManagerPage::$labelMultipleSelection);
				}

				break;

			case "no":
				try
				{
					$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelMultipleSelection);
				}
				catch (\Exception $e)
				{
					$I->waitForElementVisible(ProductManagerPage::$labelMultipleSelection, 30);
					$I->click(ProductManagerPage::$labelMultipleSelection);
				}
				break;
		}

		switch ($configAttribute['hideAttributePrice'])
		{
			case "yes":

				try
				{
					$I->seeCheckboxIsChecked(ProductManagerPage::$labelHideAttributePrice);
				}
				catch (\Exception $e)
				{
					$I->waitForElementVisible(ProductManagerPage::$labelHideAttributePrice, 30);
					$I->click(ProductManagerPage::$labelHideAttributePrice);
				}

				break;

			case "no":
				try
				{
					$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelHideAttributePrice);
				}
				catch (\Exception $e)
				{
					$I->waitForElementVisible(ProductManagerPage::$labelHideAttributePrice, 30);
					$I->click(ProductManagerPage::$labelHideAttributePrice);
				}
				break;
		}

		switch ($configAttribute['published'])
		{
			case "yes":

				try
				{
					$I->seeCheckboxIsChecked(ProductManagerPage::$labelPublished);
				}
				catch (\Exception $e)
				{
					$I->waitForElementVisible(ProductManagerPage::$labelPublished, 30);
					$I->click(ProductManagerPage::$labelPublished);
				}

				break;

			case "no":
				try
				{
					$I->seeCheckboxIsChecked(ProductManagerPage::$labelPublished);
					$I->waitForElementVisible(ProductManagerPage::$labelPublished, 30);
					$I->click(ProductManagerPage::$labelPublished);
				}
				catch (\Exception $e)
				{
					$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelPublished);
				}
				break;
		}

		$I->waitForElementVisible(ProductManagerPage::$buttonSaveAsCopy, 30);
		$I->click(ProductManagerPage::$buttonSaveAsCopy);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $configAttribute
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function CheckProductAttributeAfterSaveAsCopy($productName, $configAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText('Product Management', 30, ['xpath' => "//h1"]);
		$this->searchProduct($productName);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(['link' => $productName]);

		$I->waitForElementVisible(ProductManagerPage::$buttonProductAttribute, 30);
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);

		switch ($configAttribute['attributeRequire'])
		{
			case "yes":
				 $I->seeCheckboxIsChecked(ProductManagerPage::$labelAttributeRequired);
				break;

			case "no":
				$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelAttributeRequired);
				break;
		}

		switch ($configAttribute['multipleSelection'])
		{
			case "yes":
				$I->seeCheckboxIsChecked(ProductManagerPage::$labelMultipleSelection);
				break;

			case "no":
				$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelMultipleSelection);
				break;
		}

		switch ($configAttribute['hideAttributePrice'])
		{
			case "yes":
					$I->seeCheckboxIsChecked(ProductManagerPage::$labelHideAttributePrice);

				break;

			case "no":
				$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelHideAttributePrice);
				break;
		}

		switch ($configAttribute['published'])
		{
			case "yes":
				$I->seeCheckboxIsChecked(ProductManagerPage::$labelPublished);
				break;

			case "no":
				$I->cantSeeCheckboxIsChecked(ProductManagerPage::$labelPublished);
				break;
		}

		$I->click(ProductManagerPage::$xpathSaveClose);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param       $productName
	 * @param       $category
	 * @param       $productNumber
	 * @param       $price
	 * @param array $attributes
	 *
	 * @throws \Exception
	 */
	public function productMultiAttribute($productName, $category, $productNumber, $price, $attributes = array())
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->waitForElement(ProductManagerPage::$productPrice, 30);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);

		$length = count($attributes);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$position = $x;
			$I->click(ProductManagerPage::$addAttribute);
			$attribute  = $attributes[$x];
			$I->fillField($usePage->addAttributeName($position), $attribute['name']);
			$I->attributeValueProperty($position, $attribute['attributeName'], $attribute['attributePrice']);
		}

		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param       $productName
	 * @param       $category
	 * @param       $productNumber
	 * @param       $price
	 * @param       $nameParameter
	 * @param array $attributes
	 *
	 * @throws \Exception
	 */
	public function productMultiAttributeValue($productName, $category, $productNumber, $price, $nameParameter, $attributes = array())
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->waitForElement(ProductManagerPage::$productPrice, 30);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);

		$position = 0;
		$I->click(ProductManagerPage::$addAttribute);
		$I->fillField($usePage->addAttributeName($position), $nameParameter);
		$length = count($attributes);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$attribute  = $attributes[$x];
			$I->waitForElement($usePage->attributeNameAttribute($position, $x),30);
			$I->fillField($usePage->attributeNameAttribute($position, $x), $attribute["attributeName"]);
			$I->waitForElement($usePage->attributePricePropertyAttribute($position, $x), 30);
			$I->fillField($usePage->attributePricePropertyAttribute($position, $x), $attribute["attributePrice"]);
			$I->click("+ Add Attribute value");
		}

		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $nameAttribute
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function deleteAttributeValue($productName, $nameAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->searchProduct($productName);
		$I->click(['link' => $productName]);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->waitForElementVisible(['link' => 'Attribute value: '.$nameAttribute], 30);
		$I->click(['link' => 'Attribute value: '.$nameAttribute]);
		$I->click(ProductManagerPage::$buttonDelete);
		$I->cancelPopup();
		$I->click(ProductManagerPage::$buttonDelete);
		$I->acceptPopup();
	}

	//The function for edit product

	/**
	 * @param $productName
	 *
	 * @throws \Exception
	 */
	public function deleteAttribute($productName)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->searchProduct($productName);
		$I->click(['link' => $productName]);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->click(ProductManagerPage::$buttonDeleteAttribute);
		$I->cancelPopup();
		$I->click(ProductManagerPage::$buttonDeleteAttribute);
		$I->acceptPopup();

	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $productAccessories
	 *
	 * @throws \Exception
	 */
	public function createProductWithAccessories($productName, $category, $productNumber, $price, $productAccessories)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));


		$I->click(ProductManagerPage::$accessoryTab);
		$I->waitForElement(ProductManagerPage::$accessoriesValue, 60);
		$I->waitForElement(ProductManagerPage::$relatedProduct, 60);
		$this->selectAccessories($productAccessories);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $accessoryName
	 *
	 * @throws \Exception
	 */
	public function selectAccessories($accessoryName)
	{
		$I = $this;
		$I->waitForElement(ProductManagerPage::$accessorySearchID, 30);
		$I->click(ProductManagerPage::$accessorySearchID);
		$I->fillField(ProductManagerPage::$accessSearchField, $accessoryName);
		$userPage = new ProductManagerPage();
		$I->waitForElement($userPage->returnChoice($accessoryName), 60);
		$I->click($userPage->returnChoice($accessoryName));
	}

	/**
	 * @param $productName
	 * @param $productNameEdit
	 *
	 * @throws \Exception
	 */
	public function checkEditSave($productName, $productNameEdit)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->searchProduct($productName);
		$I->click(['link' => $productName]);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productNameEdit);
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForElement(ProductManagerPage::$productFilter, 30);
	}

	/**
	 * @param $productName
	 * @param $productNumber
	 * @param $prices
	 * @param $productCategory
	 * @param $quantityInStock
	 * @param $preOrder
	 *
	 * @throws \Exception
	 */
	public function createProductInStock($productName, $productNumber, $prices, $productCategory, $quantityInStock, $preOrder)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->waitForText(ProductManagerPage::$namePage, 30, ProductManagerPage::$headPage);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $prices, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $productCategory);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($productCategory), 30);
		$I->click($usePage->returnChoice($productCategory));


		$I->click(ProductManagerPage::$stockroomTab);
		$I->waitForElement(ProductManagerPage::$quantityInStock, 30);
		$I->fillField(ProductManagerPage::$quantityInStock, $quantityInStock);
		$I->fillField(ProductManagerPage::$preOrderStock, $preOrder);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}


	// The test case for product used stockroom

	/**
	 * @param $relatedProduct
	 *
	 * @throws \Exception
	 */
	private function selectRelatedProduct($relatedProduct)
	{
		$I = $this;
		$I->click(ProductManagerPage::$relatedProductId);
		$I->fillField(ProductManagerPage::$relatedProductId, $relatedProduct);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($relatedProduct), 60);
		$I->click($usePage->returnChoice($relatedProduct));
	}

	/**
	 * @param $position
	 * @param $name
	 * @param $price
	 *
	 * @throws \Exception
	 */
	public function attributeValueProperty($position, $name, $price)
	{
		$I = $this;
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->attributeNameProperty($position),30);
		$I->fillField($usePage->attributeNameProperty($position), $name);
		$I->waitForElement($usePage->attributePriceProperty($position), 30);
		$I->fillField($usePage->attributePriceProperty($position), $price);
		$I->waitForElement($usePage->attributePreSelect($position),30);
		$I->click($usePage->attributePreSelect($position));
	}

	//The test case for Product not for Sale

	/**
	 * @param $productName
	 * @param $productNumber
	 * @param $prices
	 * @param $productCategory
	 *
	 * @throws \Exception
	 */
	public function createProductNotForSale($productName, $productNumber, $prices, $productCategory)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $prices,6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $productCategory);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($productCategory), 30);
		$I->click($usePage->returnChoice($productCategory));
		$I->scrollTo(ProductManagerPage::$saleYes);
		$I->waitForElement(ProductManagerPage::$saleYes, 30);
		$I->click(ProductManagerPage::$saleYes);
		if ($prices == 'No')
		{
			$I->wait(0.2);
			$I->waitForElement(ProductManagerPage::$showPriceNo, 60);
			$I->click(ProductManagerPage::$showPriceNo);
		}
		else
		{
			$I->waitForElement(ProductManagerPage::$productDiscontionueYes, 30);

			$I->waitForElement(ProductManagerPage::$showPriceYes, 60);
			$I->scrollTo(ProductManagerPage::$productDiscontionueYes);
			$I->wait(0.2);
			$I->click(ProductManagerPage::$showPriceYes);
		}

		$I->scrollTo(ProductManagerPage::$productName);
		$I->click(ProductManagerPage::$buttonSave);
	}

	/**
	 * @param $productCategory
	 * @param $productID
	 * @param $showPriceYes
	 * @param $price
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function productFrontend($productCategory, $productID, $showPriceYes, $price)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$url);
		$I->waitForElement(ProductManagerPage::$categoryID, 30);
		$I->click($productCategory);
		$I->waitForElement(ProductManagerPage::$productID, 30);
		$I->dontSee(ProductManagerPage::$addToCart);
		if($showPriceYes == 'No')
		{
			$I->waitForElement(ProductManagerPage::$productID, 30);
			$I->dontSee($price);
			$I->click($productID);
			$I->dontSee(ProductManagerPage::$addToCart);
			$I->dontSee($price);
		}
		else
		{
			$I->waitForElement(ProductManagerPage::$productID, 30);
			$I->see($price);
			$I->click($productID);
			$I->dontSee(ProductManagerPage::$addToCart);
			$I->see($price);
		}
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $nameAttribute
	 * @param $valueAttribute
	 * @param $priceAttribute
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function createProductWithAttributeStockRoom($productName, $category, $productNumber, $price, $nameAttribute, $valueAttribute, $priceAttribute)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElementVisible(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);
		$I->click(ProductManagerPage::$addAttribute);
		$I->fillField($usePage->addAttributeName(0), $nameAttribute);
		$I->waitForElementVisible($usePage->attributeNameProperty(0),30);
		$I->fillField($usePage->attributeNameProperty(0), $valueAttribute);
		$I->waitForElementVisible($usePage->attributePriceProperty(0), 30);
		$I->fillField($usePage->attributePriceProperty(0), $priceAttribute);
		$I->waitForElementVisible($usePage->attributePreSelect(0),30);
		$I->click($usePage->attributePreSelect(0));
		$I->click(ProductManagerPage::$stockroomTab);
		$I->fillField(ProductManagerPage::$quantityInStock,$valueAttribute);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30);
	}

	/**
	 * @param $productRelated
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $productName
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function createProductWithRelated($productRelated, $category, $productNumber, $price, $productName)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productRelated);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$accessoryTab);
		$I->waitForElement(ProductManagerPage::$accessoriesValue, 60);
		$I->waitForElement(ProductManagerPage::$relatedProduct, 60);
		$I->fillField(ProductManagerPage::$productRelated, $productName);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($productName), 60);
		$I->click($usePage->returnChoice($productName));
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $titleSEO
	 * @param $headingSEO
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function createProductHaveImageAndSEO($productName, $category, $productNumber, $price, $titleSEO, $headingSEO,$image)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->waitForElement(ProductManagerPage::$fileUpload, 30);
//		$I->attachFile(ProductManagerPage::$fileUpload, $image);
		$I->click(ProductManagerPage:: $tabSEO);
		$I->waitForElementVisible(ProductManagerPage::$titleSEO, 30);
		$I->fillField(ProductManagerPage::$titleSEO, $titleSEO);
		$I->waitForElementVisible(ProductManagerPage::$headingSEO, 30);
		$I->fillField(ProductManagerPage::$headingSEO, $headingSEO);
		$I->waitForText(ProductManagerPage::$buttonSave, 30);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $addprice
	 * @param $quantityStart
	 * @param $quantityEnd
	 * @param $discountprice
	 * @param $startDate
	 * @param $endDate
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function createProductWithAddPrice($productName, $category, $productNumber, $price, $shoppergroup, $addprice, $quantityStart, $quantityEnd, $discountprice, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
		$I->click(ProductManagerPage::$addPriceButton);
		$I->waitForElementVisible(ProductManagerPage::$addPriceButton, 30);
		$I->click(ProductManagerPage::$addPriceButton);
		$I->waitForElementVisible(PriceProductJoomla3Page::$priceProduct, 30);
		$userManagerPage = new UserManagerJoomla3Page;
		$I->click(UserManagerJoomla3Page::$shopperGroupDropDown);
		$I->waitForElement($userManagerPage->shopperGroup($shoppergroup), 30);
		$I->click($userManagerPage->shopperGroup($shoppergroup));
		$I->addValueForField(PriceProductJoomla3Page::$priceProduct, $addprice, 6);
		$I->fillField(PriceProductJoomla3Page::$quantityStart, $quantityStart);
		$I->fillField(PriceProductJoomla3Page::$quantityEnd, $quantityEnd);
		$I->fillField(PriceProductJoomla3Page::$discountPrice, $discountprice);
		$I->fillField(PriceProductJoomla3Page::$startDate, $startDate);
		$I->fillField(PriceProductJoomla3Page::$endDate, $endDate);
		$I->click(ProductManagerPage::$buttonSave);
		$I->waitForText(PriceProductJoomla3Page::$savePriceSuccess, 5, AdminJ3Page::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $setProductDiscontinue
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function setProductDiscontinue($productName, $setProductDiscontinue)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->searchProduct($productName);
		$I->click(['link' => $productName]);
		$I->waitForElementVisible(ProductManagerPage::$productName, 30);
		$I->waitForElementVisible(ProductManagerPage::$additionalInformation, 30);
		$product = new ProductManagerPage();

		if (isset($setProductDiscontinue))
		{
			if ($setProductDiscontinue == 'yes')
			{
				$I->waitForElementVisible($product->productDiscontinued(0), 30);
				$I->click($product->productDiscontinued(0));
			}
			else
			{
				$I->waitForElementVisible($product->productDiscontinued(1), 30);
				$I->click($product->productDiscontinued(1));
			}
		}

		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $attributeParameter
	 * @param $attributes
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function createProductAttribute($productName, $attributeParameter, $attributes, $category, $productNumber, $price)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->waitForElement(ProductManagerPage::$productPrice, 30);
		$I->addValueForField(ProductManagerPage::$productPrice, $price, 6);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElement($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));
		$I->click(ProductManagerPage::$buttonProductAttribute);
		$I->waitForElement(ProductManagerPage::$attributeTab, 60);

		$position = 0;
		$I->click(ProductManagerPage::$addAttribute);
		$I->fillField($usePage->addAttributeName($position), $attributeParameter);
		$length = count($attributes);
		$I->wantToTest($length);
		for($x = 0; $x < $length; $x++)
		{
			if($x > 0)
			{
				$I->waitForElementVisible(["link" => ProductManagerPage::$addAttributeValue], 30);
				$I->executeJS('window.scrollTo(0,0)');
				$I->waitForElementVisible(["link" => ProductManagerPage::$addAttributeValue], 30);
				$I->click(["link" => ProductManagerPage::$addAttributeValue]);
			}

			$attribute = $attributes[$x];
			$I->waitForElementVisible($usePage->attributeNameAttribute($position, $x), 30);
			$I->fillField($usePage->attributeNameAttribute($position, $x), $attribute["attributeName"]);
			$I->waitForElementVisible($usePage->attributePricePropertyAttribute($position, $x), 30);
			$I->fillField($usePage->attributePricePropertyAttribute($position, $x), $attribute["attributePrice"]);

			$lengthSubProperty = count($attribute["listSubProperty"]);

			$I->waitForElementVisible($usePage->nameSubProperty($position, $x), 30);
			$I->fillField($usePage->nameSubProperty($position, $x), $attribute['nameSubProperty']);

			$subProperty = $attribute["listSubProperty"];

			for($y = 0; $y < $lengthSubProperty; $y++)
			{
				$sub = $subProperty[$y];
				$I->waitForElementVisible($usePage->buttonAddSubProperty($x + 1), 30);
				$I->click($usePage->buttonAddSubProperty($x + 1));
				$I->waitForElementVisible($usePage->subNameProperty($position, $x, $y), 30);
				$I->fillField($usePage->subNameProperty($position, $x, $y), $sub['subPropertyName']);
				$I->waitForElementVisible($usePage->subPriceProperty($position, $x, $y), 30);
				$I->fillField($usePage->subPriceProperty($position, $x, $y), $sub['subPropertyPrice']);
			}
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElementVisible(ProductManagerPage::$xpathSaveClose, 30);
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @param $category
	 * @param $productNumber
	 * @param $price
	 * @param $productParent
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function createProductChild($productName, $category, $productNumber, $price, $productParent)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->click(ProductManagerPage::$buttonNew);
		$I->waitForElementVisible(ProductManagerPage::$productName, 30);
		$I->fillField(ProductManagerPage::$productName, $productName);
		$I->fillField(ProductManagerPage::$productNumber, $productNumber);
		$I->fillField(ProductManagerPage::$productPrice, $price);
		$I->click(ProductManagerPage::$categoryId);
		$I->fillField(ProductManagerPage::$categoryFile, $category);
		$usePage = new ProductManagerPage();
		$I->waitForElementVisible($usePage->returnChoice($category), 30);
		$I->click($usePage->returnChoice($category));

		$I->scrollTo(ProductManagerPage::$additionalInformation);
		$I->waitForElementVisible(ProductManagerPage::$productParentID, 30);
		$I->click(ProductManagerPage::$productParentID);
		$I->waitForElementVisible(ProductManagerPage::$categorySearchField, 30);
		$I->fillField(ProductManagerPage::$categorySearchField, $productParent);
		$I->waitForElementVisible($usePage->returnProductParent($productParent), 30);
		$I->click($usePage->returnProductParent($productParent));
		$I->click(ProductManagerPage::$buttonSaveClose);
		$I->waitForText(ProductManagerPage::$messageSaveSuccess, 30, ProductManagerPage::$selectorSuccess);
	}
}
