<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class DiscountManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class DiscountManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Discount
	 *
	 * @param   string $name Discount name
	 * @param   string $amount Discount Amount
	 * @param   string $discountAmount Amount on the Discount
	 * @param   string $shopperGroup Group for the Shopper
	 * @param   string $discountType Type of Discount
	 *
	 * @return void
	 */
	public function addDiscount($name, $amount, $discountAmount, $shopperGroup, $discountType,$discountCondition)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$verifyAmount = 'DKK ' . $amount . ',00';
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$name, $name);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
		$I->click(\DiscountManagerJ3Page::$discountType);
		$I->fillField(\DiscountManagerJ3Page::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountManagerJ3Page::$searchResults, 30);

		$I->click(\DiscountManagerJ3Page::$searchResults);
		$I->click(\DiscountManagerJ3Page::$searchShopperId);
		$userDiscountPage = new \DiscountManagerJ3Page();
		$I->fillField(\DiscountManagerJ3Page::$searchShopperField, $shopperGroup);
		$I->waitForElement($userDiscountPage->returnChoice($shopperGroup), 60);
		$I->click($userDiscountPage->returnChoice($shopperGroup));

		$I->click(\DiscountManagerJ3Page::$conditionId);
		$I->waitForElement(\DiscountManagerJ3Page::$conditionSearch,30);
		$I->fillField(\DiscountManagerJ3Page::$conditionSearch,$discountCondition);
		$I->waitForElement($userDiscountPage->returnChoice($discountCondition), 60);
		$I->click($userDiscountPage->returnChoice($discountCondition));

		$I->click(\DiscountManagerJ3Page::$buttonSaveClose);
		$I->waitForText(\DiscountManagerJ3Page::$messageSaveSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
		$I->filterListBySearching($name, \DiscountManagerJ3Page::$filter);
		$I->seeElement(['link' => $verifyAmount]);
	}

	public function addDiscountSave($name, $amount, $discountAmount, $shopperGroup, $discountType)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(\DiscountManagerJ3Page::$URL);
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$name, $name);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);

		$I->click(\DiscountManagerJ3Page::$discountType);
		$I->fillField(\DiscountManagerJ3Page::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountManagerJ3Page::$searchResults, 30);

		$I->click(\DiscountManagerJ3Page::$searchResults);
		$I->click(\DiscountManagerJ3Page::$searchShopperId);
		$userDiscountPage = new \DiscountManagerJ3Page();
		$I->fillField(\DiscountManagerJ3Page::$searchShopperField, $shopperGroup);
		$I->waitForElement($userDiscountPage->returnChoice($shopperGroup), 60);
		$I->click($userDiscountPage->returnChoice($shopperGroup));

		$I->click(\DiscountManagerJ3Page::$buttonSave);
		$I->waitForText(\DiscountManagerJ3Page::$messageSaveSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
	}

	public function addDiscountCancel()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->click(\DiscountManagerJ3Page::$buttonCancel);
		$I->waitForText(\DiscountManagerJ3Page::$namePageManagement, 30, \DiscountManagerJ3Page::$headPage);
	}

	public function addDiscountStartThanEnd($name, $amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$name, $name);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
		$I->fillField(\DiscountManagerJ3Page::$startDate, $endDate);
		$I->fillField(\DiscountManagerJ3Page::$endDate, $startDate);
		$I->click(\DiscountManagerJ3Page::$discountType);
		$I->fillField(\DiscountManagerJ3Page::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountManagerJ3Page::$searchResults, 30);

		$I->click(\DiscountManagerJ3Page::$searchResults);
		$I->click(\DiscountManagerJ3Page::$searchShopperId);
		$userDiscountPage = new \DiscountManagerJ3Page();
		$I->fillField(\DiscountManagerJ3Page::$searchShopperField, $shopperGroup);
		$I->waitForElement($userDiscountPage->returnChoice($shopperGroup), 30);
		$I->click($userDiscountPage->returnChoice($shopperGroup));

		$I->click(\DiscountManagerJ3Page::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
	}

	public function addDiscountWithAllFieldsEmpty()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->click(\DiscountManagerJ3Page::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
	}

	public function addDiscountMissingName($amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
		$I->fillField(\DiscountManagerJ3Page::$startDate, $startDate);
		$I->fillField(\DiscountManagerJ3Page::$endDate, $endDate);
		$I->click(\DiscountManagerJ3Page::$discountType);
		$I->fillField(\DiscountManagerJ3Page::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountManagerJ3Page::$searchResults, 30);

		$I->click(\DiscountManagerJ3Page::$searchResults);
		$I->click(\DiscountManagerJ3Page::$searchShopperId);
		$I->fillField(\DiscountManagerJ3Page::$searchShopperField, $shopperGroup);
		$userDiscountPage = new \DiscountManagerJ3Page();
		$I->waitForElement($userDiscountPage->returnChoice($shopperGroup), 30);
		$I->click($userDiscountPage->returnChoice($shopperGroup));

		$I->click(\DiscountManagerJ3Page::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
	}

	public function addDiscountMissingAmount($name, $amount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$name, $name);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$startDate, $endDate);
		$I->fillField(\DiscountManagerJ3Page::$endDate, $startDate);
		$I->click(\DiscountManagerJ3Page::$discountType);
		$I->fillField(\DiscountManagerJ3Page::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountManagerJ3Page::$searchResults, 30);

		$I->click(\DiscountManagerJ3Page::$searchResults);
		$I->click(\DiscountManagerJ3Page::$searchShopperId);
		$I->fillField(\DiscountManagerJ3Page::$searchShopperField, $shopperGroup);
		$userDiscountPage = new \DiscountManagerJ3Page();
		$I->waitForElement($userDiscountPage->returnChoice($shopperGroup), 30);
		$I->click($userDiscountPage->returnChoice($shopperGroup));

		$I->click(\DiscountManagerJ3Page::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
	}

	public function addDiscountMissingShopperGroups($name, $amount, $discountAmount, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountManagerJ3Page::$buttonNew);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$name, $name);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
		$I->fillField(\DiscountManagerJ3Page::$startDate, $endDate);
		$I->fillField(\DiscountManagerJ3Page::$endDate, $startDate);
		$I->click(\DiscountManagerJ3Page::$discountType);
		$I->fillField(\DiscountManagerJ3Page::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountManagerJ3Page::$searchResults, 30);
		$I->click(\DiscountManagerJ3Page::$searchResults);

		$I->click(\DiscountManagerJ3Page::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
	}

	/**
	 * Function to edit an existing Discount
	 *
	 * @param   string $name Discount name
	 * @param   string $amount Amount for the Discount
	 * @param   string $newAmount New Amount for the Discount
	 *
	 * @return void
	 */
	public function editDiscount($name = '', $amount = '100', $newAmount = '1000')
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$verifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $amount . ',00';
		$newVerifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $newAmount . ',00';
		$I->filterListBySearching($name, \DiscountManagerJ3Page::$filter);
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElement(['link' => $verifyAmount]);
		$I->click(['link' => $verifyAmount]);
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$amount, $newAmount);
		$I->click(\DiscountManagerJ3Page::$buttonSaveClose);
		$I->waitForText(\DiscountManagerJ3Page::$messageSaveSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
		$I->click(\DiscountManagerJ3Page::$buttonReset);
		$I->filterListBySearching($name, \DiscountManagerJ3Page::$filter);
		$I->seeElement(['link' => $newVerifyAmount]);
	}

	/**
	 * Function to change State of a Discount
	 *
	 * @param   string $discountName Discount name
	 *
	 * @return void
	 */
	public function changeDiscountState($discountName)
	{
		$I = $this;
//        $I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$I->click(\DiscountManagerJ3Page::$discountState);

	}

	/**
	 * Function to change State of a Discount
	 *
	 * @param   string $discountName Discount name
	 *
	 * @return void
	 */
	public function unpublishDiscountStateButton($discountName)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$I->click(\DiscountManagerJ3Page::$discountCheckBox);
		$I->click(\DiscountManagerJ3Page::$buttonUnpublish);
		$I->waitForText(\DiscountManagerJ3Page::$messageUnpublishSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
	}

	public function publishDiscountStateButton($discountName)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$I->click(\DiscountManagerJ3Page::$discountCheckBox);
		$I->click(\DiscountManagerJ3Page::$buttonPublish);
		$I->waitForText(\DiscountManagerJ3Page::$messagePublishSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
	}

	public function unpublishAllDiscount()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkAllResults();
		$I->click(\DiscountManagerJ3Page::$buttonUnpublish);
		$I->waitForText(\DiscountManagerJ3Page::$messageUnpublishSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
	}

	public function publishAllDiscount()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkAllResults();
		$I->click(\DiscountManagerJ3Page::$buttonPublish);
		$I->waitForText(\DiscountManagerJ3Page::$messagePublishSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
	}

	public function deleteAllDiscount()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->checkAllResults();
		$I->click(\DiscountManagerJ3Page::$buttonDelete);
		$I->waitForText(\DiscountManagerJ3Page::$messageDeleteSuccess, 60, \DiscountManagerJ3Page::$saveSuccess);
	}

	public function getDiscountState($discountName)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$text = $I->grabAttributeFrom(\DiscountManagerJ3Page::$discountState, 'onclick');
		if (strpos($text, 'unpublish') > 0) {
			$result = 'published';
		} else {
			$result = 'unpublished';
		}
		return $result;
	}

	/**
	 * Function to Search for a Discount
	 *
	 * @param   string $amount Amount of the Discount
	 * @param   string $functionName Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchDiscount($discountName)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->waitForText(\DiscountManagerJ3Page::$namePageManagement, 30, \DiscountManagerJ3Page::$headPage);
		$I->filterListBySearchDiscount($discountName);
	}

	/**
	 * Function to Delete Discount
	 *
	 * @param   string $name Discount name
	 * @param   String $amount Amount of the Discount which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteDiscount($name)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->filterListBySearching($name, \DiscountManagerJ3Page::$filter);
		$I->click(\DiscountManagerJ3Page::$selectFirst);
		$I->click(\DiscountManagerJ3Page::$buttonDelete);
		$I->dontSeeElement(['link' => $name]);
	}

	public function checkEditButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click(\DiscountManagerJ3Page::$buttonEdit);
		$I->acceptPopup();
		$I->see(\DiscountManagerJ3Page::$namePageManagement, \DiscountManagerJ3Page::$selectorPageTitle);
	}

	public function checkDeleteButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click(\DiscountManagerJ3Page::$buttonDelete);
		$I->acceptPopup();
		$I->see(\DiscountManagerJ3Page::$namePageManagement, \DiscountManagerJ3Page::$selectorPageTitle);
	}

	public function checkPublishButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click(\DiscountManagerJ3Page::$buttonPublish);
		$I->acceptPopup();
		$I->see(\DiscountManagerJ3Page::$namePageManagement, \DiscountManagerJ3Page::$selectorPageTitle);
	}

	public function checkUnpublishButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click(\DiscountManagerJ3Page::$buttonUnpublish);
		$I->acceptPopup();
		$I->see(\DiscountManagerJ3Page::$namePageManagement, \DiscountManagerJ3Page::$selectorPageTitle);
	}


	public function resultShopperGroup($shopperGroup)
	{
		$I = $this;
		$I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
		$I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
	}
}