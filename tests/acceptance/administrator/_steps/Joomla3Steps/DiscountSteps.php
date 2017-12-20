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
	 */
	public function addDiscount($name, $amount, $discountAmount, $shopperGroup, $discountType, $discountCondition)
	{
		$client = $this;
		$client->amOnPage(\DiscountPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\DiscountPage::$buttonNew);
		$client->waitForElement(\DiscountPage::$fieldAmount, 30);
		$client->fillField(\DiscountPage::$fieldName, $name);
		$client->fillField(\DiscountPage::$fieldAmount, $amount);
		$client->fillField(\DiscountPage::$fieldDiscountAmount, $discountAmount);
		$client->chooseRadio(\DiscountPage::$fieldDiscountType, $discountType);
		$client->chooseRadio(\DiscountPage::$fieldCondition, $discountCondition);
		$client->chooseOnSelect2(\DiscountPage::$fieldShopperGroup, $shopperGroup);
		$client->click(\DiscountPage::$buttonSaveClose);
		$client->waitForText(\DiscountPage::$messageSaveSuccess, 60, \DiscountPage::$saveSuccess);
		$client->searchDiscount($name);
	}

	public function addDiscountSave($name, $amount, $discountAmount, $shopperGroup, $discountType)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkForPhpNoticesOrWarnings(\DiscountPage::$url);
		$I->click(\DiscountPage::$buttonNew);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->fillField(\DiscountPage::$fieldName, $name);
		$I->fillField(\DiscountPage::$fieldAmount, $amount);
		$I->fillField(\DiscountPage::$fieldDiscountAmount, $discountAmount);

		$I->click(\DiscountPage::$discountType);
		$I->fillField(\DiscountPage::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountPage::$searchResults, 30);

		$I->click(\DiscountPage::$searchResults);
		$I->click(\DiscountPage::$searchShopperId);

		$userDiscountPage = new \DiscountPage();
		$I->waitForElement($userDiscountPage->resultChoice($shopperGroup), 30);
		$I->click($userDiscountPage->resultChoice($shopperGroup));


		$I->click(\DiscountPage::$buttonSave);
		$I->waitForText(\DiscountPage::$messageSaveSuccess, 60, \DiscountPage::$saveSuccess);
	}

	public function addDiscountCancel()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountPage::$buttonNew);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->click(\DiscountPage::$buttonCancel);
		$I->waitForText(\DiscountPage::$namePageManagement, 30, \DiscountPage::$headPage);
	}

	public function addDiscountStartThanEnd($name, $amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountPage::$buttonNew);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->fillField(\DiscountPage::$fieldName, $name);
		$I->fillField(\DiscountPage::$fieldAmount, $amount);
		$I->fillField(\DiscountPage::$fieldDiscountAmount, $discountAmount);
		$I->fillField(\DiscountPage::$fieldStartDate, $endDate);
		$I->fillField(\DiscountPage::$fieldEndDate, $startDate);
		$I->click(\DiscountPage::$discountType);
		$I->fillField(\DiscountPage::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountPage::$searchResults, 30);

		$I->click(\DiscountPage::$searchResults);
		$I->click(\DiscountPage::$searchShopperId);

		$userDiscountPage = new \DiscountPage();
		$I->waitForElement($userDiscountPage->resultChoice($shopperGroup), 30);
		$I->click($userDiscountPage->resultChoice($shopperGroup));

		$I->click(\DiscountPage::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
	}

	public function addDiscountWithAllFieldsEmpty()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountPage::$buttonNew);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->click(\DiscountPage::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
	}

	public function addDiscountMissingName($amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountPage::$buttonNew);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->fillField(\DiscountPage::$fieldAmount, $amount);
		$I->fillField(\DiscountPage::$fieldDiscountAmount, $discountAmount);
		$I->fillField(\DiscountPage::$fieldStartDate, $startDate);
		$I->fillField(\DiscountPage::$fieldEndDate, $endDate);
		$I->click(\DiscountPage::$discountType);
		$I->fillField(\DiscountPage::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountPage::$searchResults, 30);

		$I->click(\DiscountPage::$searchResults);
		$I->click(\DiscountPage::$searchShopperId);

		$userDiscountPage = new \DiscountPage();
		$I->waitForElement($userDiscountPage->resultChoice($shopperGroup), 30);
		$I->click($userDiscountPage->resultChoice($shopperGroup));

		$I->click(\DiscountPage::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
	}

	public function addDiscountMissingAmount($name, $amount, $shopperGroup, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountPage::$buttonNew);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->fillField(\DiscountPage::$fieldName, $name);
		$I->fillField(\DiscountPage::$fieldAmount, $amount);
		$I->fillField(\DiscountPage::$fieldStartDate, $endDate);
		$I->fillField(\DiscountPage::$fieldEndDate, $startDate);
		$I->click(\DiscountPage::$discountType);
		$I->fillField(\DiscountPage::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountPage::$searchResults, 30);

		$I->click(\DiscountPage::$searchResults);
		$I->click(\DiscountPage::$searchShopperId);

		$userDiscountPage = new \DiscountPage();
		$I->waitForElement($userDiscountPage->resultChoice($shopperGroup), 30);
		$I->click($userDiscountPage->resultChoice($shopperGroup));

		$I->click(\DiscountPage::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
	}

	public function addDiscountMissingShopperGroups($name, $amount, $discountAmount, $discountType, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(\DiscountPage::$buttonNew);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->fillField(\DiscountPage::$fieldName, $name);
		$I->fillField(\DiscountPage::$fieldAmount, $amount);
		$I->fillField(\DiscountPage::$fieldDiscountAmount, $discountAmount);
		$I->fillField(\DiscountPage::$fieldStartDate, $endDate);
		$I->fillField(\DiscountPage::$fieldEndDate, $startDate);
		$I->click(\DiscountPage::$discountType);
		$I->fillField(\DiscountPage::$discountTypeSearch, $discountType);
		$I->waitForElement(\DiscountPage::$searchResults, 30);
		$I->click(\DiscountPage::$searchResults);

		$I->click(\DiscountPage::$buttonSave);
		$I->acceptPopup();
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
	}

	/**
	 * Function to edit an existing Discount
	 *
	 * @param   string $name      Discount name
	 * @param   string $amount    Amount for the Discount
	 * @param   string $newAmount New Amount for the Discount
	 *
	 * @return void
	 */
	public function editDiscount($name = '', $amount = '100', $newAmount = '1000')
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$verifyAmount    = \DiscountPage::getCurrencyCode() . $amount . ',00';
		$newVerifyAmount = \DiscountPage::getCurrencyCode() . $newAmount . ',00';
		$I->filterListBySearching($name, \DiscountPage::$filter);
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElement(['link' => $verifyAmount]);
		$I->click(['link' => $verifyAmount]);
		$I->waitForElement(\DiscountPage::$fieldAmount, 30);
		$I->fillField(\DiscountPage::$fieldAmount, $newAmount);
		$I->click(\DiscountPage::$buttonSaveClose);
		$I->waitForText(\DiscountPage::$messageSaveSuccess, 60, \DiscountPage::$saveSuccess);
		$I->click(\DiscountPage::$buttonReset);
		$I->filterListBySearching($name, \DiscountPage::$filter);
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
		$I->click(\DiscountPage::$discountState);

	}

	/**
	 * Function to Search for a Discount
	 *
	 * @param   string $amount       Amount of the Discount
	 * @param   string $functionName Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchDiscount($discountName)
	{
		$I = $this;
//        $I->wantTo('Search Discount ');
		$I->amOnPage(\DiscountPage::$url);
		$I->waitForText(\DiscountPage::$namePageManagement, 30, \DiscountPage::$headPage);
		$I->filterListBySearchDiscount($discountName);
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
		$I->amOnPage(\DiscountPage::$url);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$I->click(\DiscountPage::$discountCheckBox);
		$I->click(\DiscountPage::$buttonUnpublish);
		$I->waitForText(\DiscountPage::$messageUnpublishSuccess, 60, \DiscountPage::$saveSuccess);
	}

	public function publishDiscountStateButton($discountName)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$I->click(\DiscountPage::$discountCheckBox);
		$I->click(\DiscountPage::$buttonPublish);
		$I->waitForText(\DiscountPage::$messagePublishSuccess, 60, \DiscountPage::$saveSuccess);
	}

	public function unpublishAllDiscount()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkAllResults();
		$I->click(\DiscountPage::$buttonUnpublish);
		$I->waitForText(\DiscountPage::$messageUnpublishSuccess, 60, \DiscountPage::$saveSuccess);
	}

	public function publishAllDiscount()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkAllResults();
		$I->click(\DiscountPage::$buttonPublish);
		$I->waitForText(\DiscountPage::$messagePublishSuccess, 60, \DiscountPage::$saveSuccess);
	}

	public function deleteAllDiscount()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->checkAllResults();
		$I->click(\DiscountPage::$buttonDelete);
		$I->waitForText(\DiscountPage::$messageDeleteSuccess, 60, \DiscountPage::$saveSuccess);
	}

	public function getDiscountState($discountName)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->click('Reset');
		$I->searchDiscount($discountName);
		$I->see($discountName);
		$text = $I->grabAttributeFrom(\DiscountPage::$discountState, 'onclick');
		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}
		else
		{
			$result = 'unpublished';
		}
		return $result;
	}

	/**
	 * Function to Delete Discount
	 *
	 * @param   string $name   Discount name
	 * @param   String $amount Amount of the Discount which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteDiscount($name)
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->filterListBySearching($name, \DiscountPage::$filter);
		$I->click(\DiscountPage::$selectFirst);
		$I->click(\DiscountPage::$buttonDelete);
		$I->dontSeeElement(['link' => $name]);
	}

	public function checkEditButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->click(\DiscountPage::$buttonEdit);
		$I->acceptPopup();
		$I->see(\DiscountPage::$namePageManagement, \DiscountPage::$selectorPageTitle);
	}

	public function checkDeleteButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->click(\DiscountPage::$buttonDelete);
		$I->acceptPopup();
		$I->see(\DiscountPage::$namePageManagement, \DiscountPage::$selectorPageTitle);
	}

	public function checkPublishButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->click(\DiscountPage::$buttonPublish);
		$I->acceptPopup();
		$I->see(\DiscountPage::$namePageManagement, \DiscountPage::$selectorPageTitle);
	}

	public function checkUnpublishButton()
	{
		$I = $this;
		$I->amOnPage(\DiscountPage::$url);
		$I->click(\DiscountPage::$buttonUnpublish);
		$I->acceptPopup();
		$I->see(\DiscountPage::$namePageManagement, \DiscountPage::$selectorPageTitle);
	}


	public function resultShopperGroup($shopperGroup)
	{
		$I = $this;
		$I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
		$I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
	}
}