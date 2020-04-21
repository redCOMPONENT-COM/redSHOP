<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use DiscountPage;
use MailPage;

/**
 * Class DiscountSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1.0
 */
class DiscountSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Discount
	 *
	 * @param   string $name              Discount name
	 * @param   string $amount            Discount Amount
	 * @param   string $discountAmount    Amount on the Discount
	 * @param   string $shopperGroup      Group for the Shopper
	 * @param   string $discountType      Type of Discount
	 * @param   string $discountCondition Discount conditions
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscount($name, $amount, $discountAmount, $shopperGroup, $discountType, $discountCondition)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldName, $name);
		$client->fillField(DiscountPage::$fieldAmount, $amount);
		$client->fillField(DiscountPage::$fieldDiscountAmount, $discountAmount);
		$client->selectOption(DiscountPage::$fieldDiscountType, $discountType);
		$client->selectOption(DiscountPage::$fieldCondition, $discountCondition);
		$client->chooseOnSelect2(DiscountPage::$fieldShopperGroup, $shopperGroup);
		$client->click(DiscountPage::$buttonSaveClose);
		$client->waitForText(DiscountPage::$messageItemSaveSuccess, 60, DiscountPage::$selectorSuccess);
		$client->searchDiscount($name);
	}

	/**
	 * Function to Search for a Discount Code
	 *
	 * @param   string $discountCode Code of the Discount for which we are searching
	 *
	 * @return  void
	 * @since 2.1.0
	 */
	public function searchDiscount($discountCode = '')
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->waitForText(DiscountPage::$namePage, 30, DiscountPage::$headPage);
		$client->filterListBySearching($discountCode);
	}

	/**
	 * Function to Save Discount
	 *
	 * @param   string $name           Discount name
	 * @param   string $amount         Discount Amount
	 * @param   string $discountAmount Amount on the Discount
	 * @param   string $shopperGroup   Group for the Shopper
	 * @param   string $discountType   Type of Discount
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscountSave($name, $amount, $discountAmount, $shopperGroup, $discountType)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldName, $name);
		$client->fillField(DiscountPage::$fieldAmount, $amount);
		$client->fillField(DiscountPage::$fieldDiscountAmount, $discountAmount);
		$client->selectOption(DiscountPage::$fieldDiscountType, $discountType);
		$client->chooseOnSelect2(DiscountPage::$fieldShopperGroup, $shopperGroup);
		$client->click(DiscountPage::$buttonSave);
		$client->waitForText(DiscountPage::$messageItemSaveSuccess, 60, DiscountPage::$selectorSuccess);
	}

	/**
	 * Function to Save Discount with missing fields
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscountWithAllFieldsEmpty()
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->click(DiscountPage::$saveDiscount);
		$client->waitForElement(DiscountPage::$selectorMissing, 30);
		$client->waitForText(DiscountPage::$messageError, 60, DiscountPage::$selectorMissing);
		$client->click(DiscountPage::$buttonCancel);
	}

	/**
	 * Function to Add a New Discount with fail start date higher than end date
	 *
	 * @param   string $amount         Discount Amount
	 * @param   string $discountAmount Amount on the Discount
	 * @param   string $shopperGroup   Group for the Shopper
	 * @param   string $discountType   Type of Discount
	 * @param   string $startDate      Start date.
	 * @param   string $endDate        End date.
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscountMissingName($amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldAmount, $amount);
		$client->fillField(DiscountPage::$fieldDiscountAmount, $discountAmount);
		$client->selectOption(DiscountPage::$fieldDiscountType, $discountType);
		$client->fillField(DiscountPage::$fieldStartDate, $endDate);
		$client->fillField(DiscountPage::$fieldEndDate, $startDate);
		$client->chooseOnSelect2(DiscountPage::$fieldShopperGroup, $shopperGroup);
		$client->click(DiscountPage::$buttonSave);
		$client->waitForElement(DiscountPage::$selectorMissing, 30);
		$client->waitForText(DiscountPage::$messageError, 60, DiscountPage::$selectorMissing);
		$client->click(DiscountPage::$buttonCancel);
	}

	/**
	 * Function to Add a New Discount with fail start date higher than end date
	 *
	 * @param   string $name         Discount Amount
	 * @param   string $amount       Amount on the Discount
	 * @param   string $shopperGroup Group for the Shopper
	 * @param   string $discountType Type of Discount
	 * @param   string $startDate    Start date.
	 * @param   string $endDate      End date.
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscountMissingAmount($name, $amount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldName, $name);
		$client->fillField(DiscountPage::$fieldAmount, $amount);
		$client->selectOption(DiscountPage::$fieldDiscountType, $discountType);
		$client->fillField(DiscountPage::$fieldStartDate, $endDate);
		$client->fillField(DiscountPage::$fieldEndDate, $startDate);
		$client->chooseOnSelect2(DiscountPage::$fieldShopperGroup, $shopperGroup);
		$client->click(DiscountPage::$buttonSave);
		$client->waitForElement(DiscountPage::$selectorMissing, 30);
		$client->waitForText(DiscountPage::$messageError, 60, DiscountPage::$selectorMissing);
		$client->click(DiscountPage::$buttonCancel);
	}

	/**
	 * Function to Add a New Discount with fail start date higher than end date
	 *
	 * @param   string $name           Discount Amount
	 * @param   string $amount         Amount on the Discount
	 * @param   string $discountAmount Discount amount
	 * @param   string $discountType   Type of Discount
	 * @param   string $startDate      Start date.
	 * @param   string $endDate        End date.
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscountMissingShopperGroups($name, $amount, $discountAmount, $discountType, $startDate, $endDate)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldName, $name);
		$client->fillField(DiscountPage::$fieldAmount, $amount);
		$client->fillField(DiscountPage::$fieldDiscountAmount, $discountAmount);
		$client->selectOption(DiscountPage::$fieldDiscountType, $discountType);
		$client->fillField(DiscountPage::$fieldStartDate, $endDate);
		$client->fillField(DiscountPage::$fieldEndDate, $startDate);
		$client->click(DiscountPage::$buttonSave);
		$client->waitForElement(DiscountPage::$selectorMissing, 30);
		$client->waitForText(DiscountPage::$messageError, 60, DiscountPage::$selectorMissing);
		$client->click(DiscountPage::$buttonCancel);
	}

	/**
	 * Function to Add a New Discount with fail start date higher than end date
	 *
	 * @param   string $name           Discount name
	 * @param   string $amount         Discount Amount
	 * @param   string $discountAmount Amount on the Discount
	 * @param   string $shopperGroup   Group for the Shopper
	 * @param   string $discountType   Type of Discount
	 * @param   string $startDate      Start date.
	 * @param   string $endDate        End date.
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function addDiscountStartThanEnd($name, $amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldName, $name);
		$client->fillField(DiscountPage::$fieldAmount, $amount);
		$client->fillField(DiscountPage::$fieldDiscountAmount, $discountAmount);
		$client->selectOption(DiscountPage::$fieldDiscountType, $discountType);
		$client->waitForElement(DiscountPage::$fieldStartDate, 30);
		$client->click(DiscountPage::$fieldStartDate);
		$client->addValueForField(DiscountPage::$fieldStartDate, $endDate, 10);
		$client->addValueForField(DiscountPage::$fieldEndDate, $startDate, 10);
		$client->chooseOnSelect2(DiscountPage::$fieldShopperGroup, $shopperGroup);
		$client->click(DiscountPage::$buttonSave);
		$client->waitForElement(DiscountPage::$selectorMissing, 30);
		$client->waitForText(DiscountPage::$messageError, 60, DiscountPage::$selectorMissing);
		$client->click(DiscountPage::$buttonCancel);
	}

	/**
	 * Function to edit an existing Discount
	 *
	 * @param   string $name      Discount name
	 * @param   string $amount    Amount for the Discount
	 * @param   string $newAmount New Amount for the Discount
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function editDiscount($name = '', $amount = '100', $newAmount = '1000')
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$verifyAmount    = DiscountPage::getCurrencyCode() . $amount . ',00';
		$newVerifyAmount = DiscountPage::getCurrencyCode() . $newAmount . ',00';
		$client->searchDiscount($name);
		$client->see($verifyAmount, DiscountPage::$resultRow);
		$client->click($name);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldAmount, $newAmount);

		$client->click(DiscountPage::$buttonSave);
		$client->waitForText(DiscountPage::$messageItemSaveSuccess, 60, DiscountPage::$selectorSuccess);
		$client->searchDiscount($name);
		$client->see($newVerifyAmount, DiscountPage::$resultRow);
	}

	/**
	 * Function to change State of a Discount
	 *
	 * @param   string $discountName Discount name
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function changeDiscountState($discountName)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchDiscount($discountName);
		$client->waitForText($discountName, 30, DiscountPage::$resultRow);
		$client->see($discountName);
		$client->checkAllResults();
		$client->click(DiscountPage::$discountStatePath);
	}

	/**
	 * Function to change State of a Discount
	 *
	 * @param   string $discountName Discount name
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function unpublishDiscountStateButton($discountName)
	{
		$I = $this;
		$I->amOnPage(DiscountPage::$url);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$I->click(DiscountPage::$discountCheckBox);
		$I->click(DiscountPage::$buttonUnpublish);
		$I->waitForText(DiscountPage::$messageUnpublishSuccess, 60, DiscountPage::$selectorSuccess);
	}

	/**
	 * @param $discountName
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function publishDiscountStateButton($discountName)
	{
		$I = $this;
		$I->amOnPage(DiscountPage::$url);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$I->click(DiscountPage::$discountCheckBox);
		$I->click(DiscountPage::$buttonPublish);
		$I->waitForText(DiscountPage::$messagePublishSuccess, 60, DiscountPage::$selectorSuccess);
	}

	/**
	 * Function unpublish all discount
	 * @since 2.1.0
	 */
	public function unpublishAllDiscount()
	{
		$I = $this;
		$I->amOnPage(DiscountPage::$url);
		$I->checkAllResults();
		$I->click(DiscountPage::$buttonUnpublish);
	}

	/**
	 * Function publish all discount
	 * @since 2.1.0
	 */
	public function publishAllDiscount()
	{
		$I = $this;
		$I->amOnPage(DiscountPage::$url);
		$I->checkAllResults();
		$I->click(DiscountPage::$buttonPublish);
	}

	/**
	 * Function to get State of the Discount Name
	 *
	 * @param   string $discountName Name of the Discount Name
	 *
	 * @return  string
	 * @since 2.1.0
	 */
	public function getDiscountState($discountName)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->searchDiscount($discountName);
		$client->waitForText($discountName, 30, DiscountPage::$resultRow);
		$client->see($discountName, DiscountPage::$resultRow);
		$text = $client->grabAttributeFrom(DiscountPage::$discountStatePath, 'onclick');
		echo "Get status text " . $text;

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}
		else
		{
			$result = 'unpublished';
		}

		echo "Status need show" . $result;

		return $result;
	}

	/**
	 * Function to Delete Discount
	 *
	 * @param   string $name Discount name
	 *
	 * @return void
	 * @since 2.1.0
	 */
	public function deleteDiscount($name)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchDiscount($name);
		$client->checkAllResults();
		$client->click(DiscountPage::$buttonDelete);
		$client->acceptPopup();

		try
		{
			$client->waitForText(DiscountPage::$messageDeleteSuccess, 5, DiscountPage::$selectorSuccess);
			$client->see(DiscountPage::$messageDeleteSuccess, DiscountPage::$selectorSuccess);
		}catch (\Exception $e)
		{
			$client->waitForText(DiscountPage::$messageNoItemOnTable, 10, DiscountPage::$selectorAlert);
			$client->see(DiscountPage::$messageNoItemOnTable, DiscountPage::$selectorAlert);
		}

		$client->fillField(DiscountPage::$searchField, $name);
		$client->pressKey(DiscountPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($name, DiscountPage::$resultRow);
	}

	/**
	 * @param $shopperGroup
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function resultShopperGroup($shopperGroup)
	{
		$I = $this;
		$I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
		$I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
	}

	/**
	 * @param $name
	 * @param $totalAmount
	 * @param $condition
	 * @param $discountType
	 * @param $discountAmount
	 * @param $startDate
	 * @param $endDate
	 * @param $shopperGroup
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function addTotalDiscountSaveClose($name, $totalAmount, $condition, $discountType, $discountAmount, $startDate, $endDate, $shopperGroup)
	{
		$client = $this;
		$client->amOnPage(DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(DiscountPage::$buttonNew);
		$client->waitForElement(DiscountPage::$fieldAmount, 30);
		$client->fillField(DiscountPage::$fieldName, $name);
		$client->fillField(DiscountPage::$fieldAmount, $totalAmount);
		$client->selectOption(DiscountPage::$fieldCondition, $condition);
		$client->selectOption(DiscountPage::$fieldDiscountType, $discountType);
		$client->fillField(DiscountPage::$fieldDiscountAmount, $discountAmount);
		$client->fillField(DiscountPage::$fieldStartDate, $startDate);
		$client->fillField(DiscountPage::$fieldEndDate, $endDate);
		$client->chooseOnSelect2(DiscountPage::$fieldShopperGroup, $shopperGroup);
		$client->click(DiscountPage::$buttonSaveClose);
		$client->waitForText(DiscountPage::$messageItemSaveSuccess, 30, DiscountPage::$selectorSuccess);
	}
}
