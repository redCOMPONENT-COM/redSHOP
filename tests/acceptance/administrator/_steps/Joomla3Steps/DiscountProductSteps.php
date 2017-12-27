<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

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
	 *
	 * @param   integer $productPrice   Discount name
	 * @param   integer $condition      Discount Amount
	 * @param   integer $type           Amount on the Discount
	 * @param   integer $discountAmount Group for the Shopper
	 * @param   string  $startDate      Type of Discount
	 * @param   string  $endDate        Discount conditions
	 * @param   string  $category       Discount conditions
	 * @param   string  $groupName      Discount conditions
	 *
	 * @return void
	 */
	public function addDiscountProductSave($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$buttonNew);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
		$client->fillField(\DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(\DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(\DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(\DiscountProductPage::$fieldEndDate, $endDate);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldCategory, $category);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(\DiscountProductPage::$buttonSave);
		$client->assertSystemMessageContains(\DiscountProductPage::$messageItemSaveSuccess);
	}

	/**
	 * Function to Add a New Discount today
	 *
	 * @param   integer $productPrice   Product price
	 * @param   integer $condition      Discount condition
	 * @param   integer $type           Type on the Discount
	 * @param   integer $discountAmount Discount amount
	 * @param   string  $category       Category name
	 * @param   string  $groupName      Shopper group name
	 *
	 * @return void
	 */
	public function addDiscountToday($productPrice, $condition, $type, $discountAmount, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$buttonNew);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
		$client->fillField(\DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(\DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(\DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldCategory, $category);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(\DiscountProductPage::$buttonSaveClose);
		$client->assertSystemMessageContains(\DiscountProductPage::$messageItemSaveSuccess);
	}

	/**
	 * Function to Add a New Discount with save and close button
	 *
	 * @return void
	 */
	public function addDiscountProductCancelButton()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->click(\DiscountProductPage::$buttonNew);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$buttonCancel);
	}

	/**
	 * Function to Add a New Discount with save and close button missing amount
	 *
	 * @param   integer $productPrice Discount name
	 * @param   integer $condition    Discount Amount
	 * @param   integer $type         Type on the Discount
	 * @param   string  $startDate    Start date
	 * @param   string  $endDate      End date
	 * @param   string  $category     Category name
	 * @param   string  $groupName    Shopper group name
	 *
	 * @return void
	 */
	public function addDiscountProductMissingAmountSaveClose($productPrice, $condition, $type, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$buttonNew);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
		$client->fillField(\DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(\DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(\DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(\DiscountProductPage::$fieldEndDate, $endDate);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldCategory, $category);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(\DiscountProductPage::$buttonSaveClose);
		$client->assertSystemMessageContains(\DiscountProductPage::$messageErrorAmountZero);
	}

	/**
	 * Function to Add a New Discount with save and close button missing amount
	 *
	 * @param   integer $productPrice   Discount name
	 * @param   integer $condition      Discount Amount
	 * @param   integer $type           Type on the Discount
	 * @param   integer $discountAmount Amount of discount
	 * @param   string  $startDate      Start date
	 * @param   string  $endDate        End date
	 * @param   string  $category       Category name
	 *
	 * @return void
	 */
	public function addDiscountProductMissingShopperGroupSaveClose($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category)
	{
		$client = $this;

		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$buttonNew);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
		$client->fillField(\DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(\DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(\DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(\DiscountProductPage::$fieldEndDate, $endDate);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldCategory, $category);
		$client->click(\DiscountProductPage::$buttonSaveClose);
		$client->assertSystemMessageContains(\DiscountProductPage::$messageErrorFieldRequired);
	}

	/**
	 * Function to Add a New Discount with save and close button start date higher than end date.
	 *
	 * @param   integer $productPrice   Discount name
	 * @param   integer $condition      Discount Amount
	 * @param   integer $type           Type on the Discount
	 * @param   integer $discountAmount Discount amount
	 * @param   string  $startDate      Start date
	 * @param   string  $endDate        End date
	 * @param   string  $category       Category name
	 * @param   string  $groupName      Shopper group name
	 *
	 * @return void
	 */
	public function addDiscountProductStartMoreThanEnd($productPrice, $condition, $type, $discountAmount, $startDate, $endDate, $category, $groupName)
	{
		$client = $this;

		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountProductPage::$buttonNew);
		$client->waitForElement(\DiscountProductPage::$fieldAmount, 30);
		$client->fillField(\DiscountProductPage::$fieldAmount, $productPrice);
		$client->selectOption(\DiscountProductPage::$fieldCondition, $condition);
		$client->selectOption(\DiscountProductPage::$fieldDiscountType, $type);
		$client->fillField(\DiscountProductPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(\DiscountProductPage::$fieldStartDate, $startDate);
		$client->fillField(\DiscountProductPage::$fieldEndDate, $endDate);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldCategory, $category);
		$client->chooseOnSelect2(\DiscountProductPage::$fieldShopperGroup, $groupName);
		$client->click(\DiscountProductPage::$buttonSaveClose);
		$client->assertSystemMessageContains(\DiscountProductPage::$messageErrorStartDateHigherEndDate);
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 */
	public function checkDeleteButton()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->click(\DiscountProductPage::$buttonDelete);
		$client->acceptPopup();
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 */
	public function checkPublishButton()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->click(\DiscountProductPage::$buttonPublish);
		$client->acceptPopup();
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 */
	public function checkUnpublishButton()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->click(\DiscountProductPage::$buttonUnpublish);
		$client->acceptPopup();
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 */
	public function checkUnpublishAll()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkAllResults();
		$client->click(\DiscountProductPage::$buttonUnpublish);
		$client->assertSystemMessageContains(\DiscountProductPage::$messageUnpublishSuccess);
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 */
	public function checkPublishAll()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkAllResults();
		$client->click(\DiscountProductPage::$buttonPublish);
		$client->assertSystemMessageContains(\DiscountProductPage::$messagePublishSuccess);
	}

	/**
	 * Method for check delete button
	 *
	 * @return  void
	 */
	public function checkDeleteAll()
	{
		$client = $this;
		$client->amOnPage(\DiscountProductPage::$url);
		$client->checkAllResults();
		$client->click(\DiscountProductPage::$buttonDelete);
		$client->acceptpopup();
		$client->assertSystemMessageContains(\DiscountProductPage::$messageDeleteSuccess);
	}
}
