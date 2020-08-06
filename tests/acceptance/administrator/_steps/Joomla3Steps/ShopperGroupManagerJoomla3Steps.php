<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use ShopperGroupJ3Page;
use AdminJ3Page;

/**
 * Class ShopperGroupManagerJoomla3Steps
 * @package AcceptanceTester
 * @since 2.1.0
 */
class ShopperGroupManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $shopperName
	 * @param $shopperType
	 * @param $shopperCustomer
	 * @param $shopperGroupPortal
	 * @param $category
	 * @param $shipping
	 * @param $shippingRate
	 * @param $shippingCheckout
	 * @param $showVat
	 * @param $catalog
	 * @param $showPrice
	 * @param $enableQuotation
	 * @param $function
	 *
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function addShopperGroups($shopperName, $shopperType, $shopperCustomer, $shopperGroupPortal, $category,
									 $shipping, $shippingRate, $shippingCheckout, $showVat, $catalog, $showPrice, $enableQuotation, $function)
	{
		$tester = $this;
		$tester->amOnPage(ShopperGroupJ3Page::$URL);
		$tester->click(ShopperGroupJ3Page::$buttonNewXpath);
		$tester->fillField(ShopperGroupJ3Page::$shopperName, $shopperName);

		if ($shopperType != null)
		{
			$tester->click(ShopperGroupJ3Page::$shopperGroupType);
			$tester->waitForElement(ShopperGroupJ3Page::$shopperType,30);
			$tester->fillField(ShopperGroupJ3Page::$shopperType, $shopperType);
			$userShopperPage = new ShopperGroupJ3Page();
			$tester->waitForElement($userShopperPage->returnSearch($shopperType), 60);
			$tester->click($userShopperPage->returnSearch($shopperType));
		}

		$tester->click(ShopperGroupJ3Page::$customerType);
		$tester->waitForElement(ShopperGroupJ3Page::$customerTypeSearch, 30);
		$tester->fillField(ShopperGroupJ3Page::$customerTypeSearch, $shopperCustomer);
		$tester->pressKey(
			ShopperGroupJ3Page::$customerTypeSearch,
			\Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER
		);

		if ($shopperGroupPortal == 'yes')
		{
			$tester->click(ShopperGroupJ3Page::$shopperGroupPortalYes);
		}
		else
		{
			$tester->click(ShopperGroupJ3Page::$shopperGroupPortalNo);
		}
		$tester->click(ShopperGroupJ3Page::$categoryFiled);

		$tester->fillField(ShopperGroupJ3Page::$categoryFill, $category);
		$tester->pressKey(ShopperGroupJ3Page::$categoryFill, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		if ($shipping == 'yes')
		{
			$tester->click(ShopperGroupJ3Page::$shippingYes);
		}
		else
		{
			$tester->click(ShopperGroupJ3Page::$shippingNo);
		}
		$tester->fillField(ShopperGroupJ3Page::$shippingRate, $shippingRate);
		$tester->fillField(ShopperGroupJ3Page::$shippingCheckout, $shippingCheckout);

		if ($showVat == 'yes')
		{
			$tester->click(ShopperGroupJ3Page::$vatYes);
		}
		else
		{
			$tester->click(ShopperGroupJ3Page::$vatNo);
		}

		$tester->click(ShopperGroupJ3Page::$showPrice);
		$tester->waitForElement(ShopperGroupJ3Page::$showPriceSearch, 30);
		$tester->fillField(ShopperGroupJ3Page::$showPriceSearch, $showPrice);
		$tester->pressKey(ShopperGroupJ3Page::$showPriceSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		if ($catalog != null)
		{
			$tester->click(ShopperGroupJ3Page::$catalogId);
			$tester->waitForElement(ShopperGroupJ3Page::$catalogSearch, 30);
			$tester->fillField(ShopperGroupJ3Page::$catalogSearch, $catalog);
			$tester->pressKey(ShopperGroupJ3Page::$catalogSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}


		if ($enableQuotation == 'yes')
		{
			$tester->click(ShopperGroupJ3Page::$quotationYes);
		}
		else
		{
			$tester->click(ShopperGroupJ3Page::$quotationNo);
		}

		$tester->click(ShopperGroupJ3Page::$publishYes);

		switch ($function)
		{
			case 'save':
				$tester->click(ShopperGroupJ3Page::$buttonSave);
				$tester->see(ShopperGroupJ3Page::$saveSuccess, ShopperGroupJ3Page::$selectorSuccess);
				break;
			case 'saveclose':
				$tester->click(ShopperGroupJ3Page::$buttonSaveClose);
				$tester->see(ShopperGroupJ3Page::$saveSuccess, ShopperGroupJ3Page::$selectorSuccess);
				$tester->see(ShopperGroupJ3Page::$namePageManagement, ShopperGroupJ3Page::$selectorPageTitle);
				break;
		}
	}

	/**
	 * @since 2.1.0
	 */
	public function changeStateShopperGroups()
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(ShopperGroupJ3Page::$URL);

		$I->click(ShopperGroupJ3Page::$shopperFirstStatus);
	}

	/**
	 * @param $status
	 * @since 2.1.0
	 */
	public function changeStateShopperGroup($status)
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(ShopperGroupJ3Page::$URL);
		$I->click(ShopperGroupJ3Page::$shopperFirstStatus);

		switch ($status)
		{
			case 'unpublished':
				$currentState = $I->getShopperGroupsStates();
				$I->verifyState('unpublished', $currentState);
				break;
			case 'published':
				$currentState = $I->getShopperGroupsStates();
				$I->verifyState('published', $currentState);
				break;
		}
	}

	/**
	 * @param $idShopperGroups
	 * @since 2.1.0
	 */
	public function checkCloseButton($idShopperGroups)
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(ShopperGroupJ3Page::$URL);

		$I->click(ShopperGroupJ3Page::$nameShopperGroupsFirst);
		$URLEdit = ShopperGroupJ3Page::$URLEdit . $idShopperGroups;
		$I->amOnPage($URLEdit);
		$I->checkForPhpNoticesOrWarnings($URLEdit);
		$I->click(ShopperGroupJ3Page::$buttonClose);
		$I->see(ShopperGroupJ3Page::$namePageManagement, ShopperGroupJ3Page::$selectorPageTitle);
	}

	/**
	 * @param $nameShopperGroups
	 * @param $idShopperGroups
	 * @param $nameEdit
	 * @since 2.1.0
	 */
	public function editShopperGroups($nameShopperGroups, $idShopperGroups, $nameEdit)
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(ShopperGroupJ3Page::$URL);
		$I->click(ShopperGroupJ3Page::$nameShopperGroupsFirst);
		$URLEdit = ShopperGroupJ3Page::$URLEdit . $idShopperGroups;
		$I->amOnPage($URLEdit);
		$I->checkForPhpNoticesOrWarnings($URLEdit);
		$I->waitForElementVisible(ShopperGroupJ3Page::$shopperName, 30);
		$I->fillField(ShopperGroupJ3Page::$shopperName, $nameEdit);
		$I->click(ShopperGroupJ3Page::$buttonSave);
		$I->see(ShopperGroupJ3Page::$saveSuccess, ShopperGroupJ3Page::$selectorSuccess);
	}

	/**
	 * Function delete shopper group with option no
	 * @since 2.1.0
	 */
	public function deleteShopperGroupsNo()
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(ShopperGroupJ3Page::$URL);

		$I->click(ShopperGroupJ3Page::$shopperFirst);
		$I->click(ShopperGroupJ3Page::$buttonDelete);
		$I->cancelPopup();
	}

	/**
	 * Function add shopper group with missing name
	 * @since 2.1.0
	 */
	public function addShopperGroupsMissingName()
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->click(ShopperGroupJ3Page::$buttonNew);
		$I->click(ShopperGroupJ3Page::$buttonSaveClose);
		$I->waitForText(ShopperGroupJ3Page::$messageMissingName, 30);
		$I->see(ShopperGroupJ3Page::$messageMissingName);
	}

	/**
	 * @param $status
	 * @since 2.1.0
	 */
	public function changStatusAllShopperGroups($status)
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkAllResults();
		switch ($status)
		{
			case 'publish':
				$I->click(ShopperGroupJ3Page::$buttonPublish);
				$I->see(ShopperGroupJ3Page::$publishSuccess, ShopperGroupJ3Page::$selectorSuccess);
				break;
			case 'unpublish':
				$I->click(ShopperGroupJ3Page::$buttonUnpublish);
				$I->see(ShopperGroupJ3Page::$unpublishSuccess, ShopperGroupJ3Page::$selectorSuccess);
				break;
		}
		$I->see(ShopperGroupJ3Page::$namePageManagement, ShopperGroupJ3Page::$selectorPageTitle);
	}

	/**
	 * @param $buttonName
	 * @since 2.1.0
	 */
	public function checkButtons($buttonName)
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		switch ($buttonName)
		{
			case 'delete':
				$I->click(ShopperGroupJ3Page::$buttonDelete);
				$I->acceptPopup();
				break;
			case 'unpublish':
				$I->click(ShopperGroupJ3Page::$buttonUnpublish);
				$I->acceptPopup();
				break;
			case 'publish':
				$I->click(ShopperGroupJ3Page::$buttonPublish);
				$I->acceptPopup();
				break;
			case 'cancel':
				$I->click(ShopperGroupJ3Page::$buttonNew);
				$I->click(ShopperGroupJ3Page::$buttonCancel);
				break;
		}
		$I->see(ShopperGroupJ3Page::$namePageManagement, ShopperGroupJ3Page::$headPage);
	}

	/**
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function deleteAllShopperGroups()
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(ShopperGroupJ3Page::$URL);
		$I->checkAllResults();
		$I->click(ShopperGroupJ3Page::$buttonDelete);
		$I->acceptPopup();
		$I->waitForElement(ShopperGroupJ3Page::$selectorSuccess, 30);
	}

	/**
	 * @return string
	 * @since 2.1.0
	 */
	public function getShopperGroupsStates()
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$text = $I->grabAttributeFrom(ShopperGroupJ3Page::$shopperFirstStatus, 'onclick');
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
	 * @param $shoppergroupname
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function deleteShopperGroups($shoppergroupname)
	{
		$I = $this;
		$I->amOnPage(ShopperGroupJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings(ShopperGroupJ3Page::$URL);
		$I->waitForElementVisible(ShopperGroupJ3Page::$searchField, 30);
		$I->fillField(ShopperGroupJ3Page::$searchField, $shoppergroupname);
		$I->waitForElementVisible(ShopperGroupJ3Page::$searchButton, 30);
		$I->click(ShopperGroupJ3Page::$searchButton);
		$I->waitForText($shoppergroupname, 10);

		$shoppergroup = new ShopperGroupJ3Page();
		$I->waitForElementVisible($shoppergroup->xPathShoppergroupName($shoppergroupname), 30);
		$I->waitForElementVisible(ShopperGroupJ3Page::$shopperFirst, 30);
		$I->click(ShopperGroupJ3Page::$shopperFirst);
		$I->wait(0.5);
		$I->waitForText(ShopperGroupJ3Page::$buttonDelete, 30);
		$I->click(ShopperGroupJ3Page::$buttonDelete);
		$I->wait(0.5);
		$I->canSeeInPopup(ShopperGroupJ3Page::$messageDeleteInPopup);
		$I->acceptPopup();
		$I->waitForText(ShopperGroupJ3Page::$messageNoItemOnTable, 30);
	}
}
