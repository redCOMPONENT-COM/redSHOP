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
use ProductManagerPage;

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
	public function checkWistListAtFrontend($categoryName, $productName, $username, $pass, $wishlistName, $login = 'no')
	{
		$I = $this;

		switch ($login)
		{
			case 'no':
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
				$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
				$I->click($productFrontEndManagerPage->productCategory($categoryName));
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
				$I->click($productFrontEndManagerPage->product($productName));
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToWishListNoLogin, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$addToWishListNoLogin);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$selectorMessage, 30);
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAddWishListSuccess, 30, FrontEndProductManagerJoomla3Page::$selectorMessage);
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$wishListPageURL);
				$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
				$I->waitForText($productName, 30);
				$I->doFrontEndLogin($username, $pass);
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$wishListPageURL);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$saveWishListButton, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$saveWishListButton);
				break;

			case 'yes':
				$I->doFrontEndLogin($username, $pass);
				$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
				$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
				$I->click($productFrontEndManagerPage->productCategory($categoryName));
				$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
				$I->click($productFrontEndManagerPage->product($productName));
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToWishListLogin, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$addToWishListLogin);
				break;
		}

		$I->executeJS('jQuery(".iframe").attr("name", "wishlist-iframe")');
		$I->waitForElementVisible('//iframe[@name="wishlist-iframe"]', 30);
		$I->switchToIFrame('wishlist-iframe');
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkNewWishList, 30);
		$I->checkOption(FrontEndProductManagerJoomla3Page::$checkNewWishList);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$wishListNameField, 30);
		$I->fillField(FrontEndProductManagerJoomla3Page::$wishListNameField, $wishlistName);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonSave);

		try
		{
			$I->see(FrontEndProductManagerJoomla3Page::$messageAddWishListSuccessPopup);
		} catch (\Exception $e)
		{
			$I->acceptPopup();
			$I->fillField(FrontEndProductManagerJoomla3Page::$wishListNameField, $wishlistName);
			$I->click(FrontEndProductManagerJoomla3Page::$buttonSave);
			$I->see(FrontEndProductManagerJoomla3Page::$messageAddWishListSuccessPopup);
		}

		switch ($login)
		{
			case 'no':
				$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
				$I->waitForText($productName, 30);
				break;

			case 'yes':
				$I->waitForElementVisible($productFrontEndManagerPage->productTitle($productName), 30);
				$I->waitForText($productName, 30, FrontEndProductManagerJoomla3Page::$productTitle);
				break;
		}

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$wishListPageURL);
		$I->waitForElementVisible($productFrontEndManagerPage->wishListName($wishlistName), 30);
		$I->click($productFrontEndManagerPage->wishListName($wishlistName));
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForText($productName, 30);
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
		$I->amOnPage(ProductManagerPage::$URL);
		$I->waitForElementVisible(ProductManagerPage::$getProductId);
		$I->doFrontEndLogin($username, $pass);
		$product = new FrontEndProductManagerJoomla3Page();
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$wishListPageURL);
		$I->waitForElementVisible($product->wishListName($wishlistName), 30);
		$I->click($product->wishListName($wishlistName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$removeOnWishList, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$removeOnWishList);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$messageRemoveProductWishList, 30, FrontEndProductManagerJoomla3Page::$selectorMessage);
	}
}