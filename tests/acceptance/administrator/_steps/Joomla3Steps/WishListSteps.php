<?php
/**
 * @package     redSHOP
 * @subpackage  Step WishList
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;
use WishListPage;

/**
 * Class WishListSteps
 * @package AcceptanceTester
 * @since 2.1.3
 */
class WishListSteps extends CheckoutMissingData
{
	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $username
	 * @param $pass
	 * @param $wishlistName
	 * @param string $login     enable Login Require: yes/no
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkWistListAtFrontend($categoryName, $productName, $username, $pass, $wishlistName, $login)
	{
		$I = $this;
		$product = new WishListPage();

		switch ($login)
		{
			case 'no':
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
				$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
				$I->click($productFrontEndManagerPage->productCategory($categoryName));
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
				$I->click($productFrontEndManagerPage->product($productName));
				$I->waitForElementVisible(WishListPage::$addToWishListNoLogin, 30);
				$I->click(WishListPage::$addToWishListNoLogin);
				$I->waitForElementVisible(WishListPage::$selectorMessage, 30);
				$I->waitForText(WishListPage::$messageAddWishListSuccess, 30, WishListPage::$selectorMessage);
				$I->amOnPage(WishListPage::$wishListPageURL);
				$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
				$I->waitForText($productName, 30);
				$I->doFrontEndLogin($username, $pass);
				$I->amOnPage(WishListPage::$wishListPageURL);
				$I->waitForElementVisible(WishListPage::$saveWishListButton, 30);
				$I->click(WishListPage::$saveWishListButton);
				break;

			case 'yes':
				$I->doFrontEndLogin($username, $pass);
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
				$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
				$I->click($productFrontEndManagerPage->productCategory($categoryName));
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
				$I->click($productFrontEndManagerPage->product($productName));
				$I->waitForElementVisible(WishListPage::$addToWishListLogin, 30);
				$I->click(WishListPage::$addToWishListLogin);
				break;
		}

		$I->executeJS($product->jqueryIFrame());
		$I->waitForElementVisible(WishListPage::$iframeWishList, 30);
		$I->switchToIFrame(WishListPage::$iframeWishListName);
		$I->waitForElementVisible(WishListPage::$checkNewWishList, 30);
		$I->checkOption(WishListPage::$checkNewWishList);
		$I->waitForElementVisible(WishListPage::$wishListNameField, 30);
		$I->fillField(WishListPage::$wishListNameField, $wishlistName);
		$I->click(WishListPage::$buttonSave);

		try
		{
			$I->see(WishListPage::$messageAddWishListSuccessPopup);
		} catch (\Exception $e)
		{
			$I->acceptPopup();
			$I->waitForElementVisible(WishListPage::$wishListNameField, 30);
			$I->fillField(WishListPage::$wishListNameField, $wishlistName);
			$I->click(WishListPage::$buttonSave);
			$I->see(WishListPage::$messageAddWishListSuccessPopup);
		}

		switch ($login)
		{
			case 'no':
				$I->waitForText($productName, 60, $productFrontEndManagerPage->product($productName));
				break;

			case 'yes':
				$I->waitForText($productName, 60, $product->productTitle($productName));
				break;
		}

		$I->amOnPage(WishListPage::$wishListPageURL);
		$I->waitForElementVisible($product->wishListName($wishlistName), 30);
		$I->click($product->wishListName($wishlistName));
		$I->waitForText($productName, 60, $productFrontEndManagerPage->product($productName));
	}

	/**
	 * @param $username
	 * @param $pass
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function removeProductInWishList($username, $pass, $wishlistName)
	{
		$I = $this;
		$I->doFrontEndLogin($username, $pass);
		$product = new WishListPage();
		$I->amOnPage(WishListPage::$wishListPageURL);
		$I->waitForElementVisible($product->wishListName($wishlistName), 30);
		$I->click($product->wishListName($wishlistName));
		$I->waitForElementVisible(WishListPage::$removeOnWishList, 30);
		$I->click(WishListPage::$removeOnWishList);
		$I->waitForText(WishListPage::$messageRemoveProductWishList, 30, WishListPage::$selectorMessage);
	}
}