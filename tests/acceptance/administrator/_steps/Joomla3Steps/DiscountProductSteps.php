<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use DiscountProductPage;
use AdminJ3Page;

/**
 * Class DiscountProductSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1.0
 */
class DiscountProductSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Discount
	 * @param $productPrice
	 * @param $condition
	 * @param $type
	 * @param $discountAmount
	 * @param $startDate
	 * @param $endDate
	 * @param $category
	 * @param $groupName
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function addDiscountProductSave($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountProductPage::$buttonNew);
		$client->waitForElementVisible(DiscountProductPage::$fieldAmount, 30);
		$client->fillField(DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(DiscountProductPage::$fieldCondition, $condition);
		$client->waitForElementVisible(DiscountProductPage::$fieldDiscountType, 30);
		$client->checkOption(DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(DiscountProductPage::$fieldEndDate, $endDate);
		$client->fillField(DiscountProductPage::$inputCategoryID, $category);
		$client->pressKey(DiscountProductPage::$inputCategoryID, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->chooseOnSelect2(DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(DiscountProductPage::$buttonSave);
		$client->assertSystemMessageContains(DiscountProductPage::$messageItemSaveSuccess);
	}

	/**
	 * Function to Add a New Discount today
	 * @param $productPrice
	 * @param $condition
	 * @param $type
	 * @param $discountAmount
	 * @param $category
	 * @param $groupName
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function addDiscountToday($productPrice, $condition, $type, $discountAmount, $category, $groupName)
	{
		$client = $this;
		$toDay = date('Y-m-d');

		$client->amOnPage(DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountProductPage::$buttonNew);
		$client->waitForElement(DiscountProductPage::$fieldAmount, 30);
		$client->fillField(DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(DiscountProductPage::$fieldStartDate, $toDay);
		$client->fillField(DiscountProductPage::$fieldEndDate, $toDay);
		$client->fillField(DiscountProductPage::$inputCategoryID, $category);
		$client->pressKey(DiscountProductPage::$inputCategoryID, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->chooseOnSelect2(DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(DiscountProductPage::$buttonSaveClose);
		$client->assertSystemMessageContains(DiscountProductPage::$messageItemSaveSuccess);
	}

	/**
	 * Function to Add a New Discount with save and close button
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscountProductCancelButton()
	{
		$client = $this;
		$client->amOnPage(DiscountProductPage::$url);
		$client->click(DiscountProductPage::$buttonNew);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountProductPage::$buttonCancel);
	}

	/**
	 * @param $productPrice
	 * @param $condition
	 * @param $type
	 * @param $startDate
	 * @param $endDate
	 * @param $category
	 * @param $groupName
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function addDiscountProductMissingAmountSaveClose($productPrice, $condition, $type, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountProductPage::$buttonNew);
		$client->waitForElement(DiscountProductPage::$fieldAmount, 30);
		$client->fillField(DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(DiscountProductPage::$fieldEndDate, $endDate);
		$client->fillField(DiscountProductPage::$inputCategoryID, $category);
		$client->pressKey(DiscountProductPage::$inputCategoryID, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->chooseOnSelect2(DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(DiscountProductPage::$buttonSaveClose);
		$client->assertSystemMessageContains(DiscountProductPage::$messageErrorAmountZero);
	}

	/**
	 * @param $productPrice
	 * @param $condition
	 * @param $type
	 * @param $discountAmount
	 * @param $startDate
	 * @param $endDate
	 * @param $category
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function addDiscountProductMissingShopperGroupSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category)
	{
		$client = $this;

		$client->amOnPage(DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountProductPage::$buttonNew);
		$client->waitForElement(DiscountProductPage::$fieldAmount, 30);
		$client->fillField(DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(DiscountProductPage::$fieldEndDate, $endDate);
		$client->fillField(DiscountProductPage::$inputCategoryID, $category);
		$client->pressKey(DiscountProductPage::$inputCategoryID, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->click(DiscountProductPage::$buttonSaveClose);
		$client->assertSystemMessageContains(DiscountProductPage::$messageErrorFieldRequired);
	}

	/**
	 * Function to Add a New Discount with save and close button start date higher than end date.
	 * @param $productPrice
	 * @param $condition
	 * @param $type
	 * @param $discountAmount
	 * @param $startDate
	 * @param $endDate
	 * @param $category
	 * @param $groupName
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function addDiscountProductStartMoreThanEnd($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountProductPage::$buttonNew);
		$client->waitForElement(DiscountProductPage::$fieldAmount, 30);
		$client->fillField(DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(DiscountProductPage::$fieldDiscountAmount, $discountAmount);

		$client->addValueForField(DiscountProductPage::$fieldStartDate, $endDate, 10);
		$client->addValueForField(DiscountProductPage::$fieldEndDate, $startDate, 10);
		$client->fillField(DiscountProductPage::$inputCategoryID, $category);
		$client->pressKey(DiscountProductPage::$inputCategoryID, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->chooseOnSelect2(DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(DiscountProductPage::$buttonSave);
		$client->assertSystemMessageContains(DiscountProductPage::$messageErrorStartDateHigherEndDate);
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 * @since 2.1.0
	 */
	public function checkDeleteButton()
	{
		$client = $this;
		$client->amOnPage(DiscountProductPage::$url);
		$client->click(DiscountProductPage::$buttonDelete);
		$client->acceptPopup();
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 * @since 2.1.0
	 */
	public function checkPublishButton()
	{
		$client = $this;
		$client->amOnPage(DiscountProductPage::$url);
		$client->click(DiscountProductPage::$buttonPublish);
		$client->acceptPopup();
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 * @since 2.1.0
	 */
	public function checkUnpublishButton()
	{
		$client = $this;
		$client->amOnPage(DiscountProductPage::$url);
		$client->click(DiscountProductPage::$buttonUnpublish);
		$client->acceptPopup();
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 * @since 2.1.0
	 */
	public function checkUnpublishAll()
	{
		$client = $this;
		$client->amOnPage(DiscountProductPage::$url);
		$client->checkAllResults();
		$client->click(DiscountProductPage::$buttonUnpublish);
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 * @since 2.1.0
	 */
	public function checkPublishAll()
	{
		$client = $this;
		$client->amOnPage(DiscountProductPage::$url);
		$client->checkAllResults();
		$client->click(DiscountProductPage::$buttonPublish);
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 * @since 2.1.0
	 */
	public function checkDeleteAll()
	{
		$client = $this;
		$client->amOnPage(DiscountProductPage::$url);
		$client->checkAllResults();
		$client->click(DiscountProductPage::$buttonDelete);
		$client->acceptpopup();
	}

	/**
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function deleteAllDiscountProducts()
	{
		$I = $this;
		$I->amOnPage(DiscountProductPage::$url);
		$I->checkAllResults();
		$I->click(AdminJ3Page::$buttonDelete);
		$I->acceptPopup();

		try
		{
			$I->waitForText(DiscountProductPage::$deleteSuccess, 5, AdminJ3Page::$selectorSuccess);
			$I->see(DiscountProductPage::$messageDeleteSuccess, DiscountProductPage::$selectorSuccess);
		} catch (\Exception $e)
		{
			$I->waitForText(DiscountProductPage::$messageNoItemOnTable, 10, DiscountProductPage::$selectorAlert);
			$I->see(DiscountProductPage::$messageNoItemOnTable, DiscountProductPage::$selectorAlert);
		}
	}
}
