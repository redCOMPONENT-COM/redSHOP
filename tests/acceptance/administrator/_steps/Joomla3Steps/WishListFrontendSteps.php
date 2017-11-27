<?php
/**
 * Add more
 */

namespace AcceptanceTester;


class WishListFrontendSteps extends AdminManagerJoomla3Steps
{

	public function wishListLogin($productName, $categoryName,$id,$idProduct)
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$wishListButton,30);
		$userPage = new \FrontEndProductManagerJoomla3Page();
		$I->comment(' I add default wishlist ');
		$I->click(\FrontEndProductManagerJoomla3Page::$wishListButton);
		$I->amOnPage($userPage->wishListNewUrl($idProduct));
		$I->click(\FrontEndProductManagerJoomla3Page::$defaultButton);
		$I->click(\FrontEndProductManagerJoomla3Page::$addToWishListButton);

		$I->amOnPage($userPage->wishListURL($id));
		$I->click(['link'=>$productName]);
	}

	public function wishListLoginNew($productName, $categoryName,$id,$idProduct,$nameWishList)
	{
		$I = $this;
		$I->comment('Add new WishList');
		$I->doFrontEndLogin();
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$wishListButton,30);
		$userPage = new \FrontEndProductManagerJoomla3Page();
		$I->comment(' I add new wishlist ');
		$I->click(\FrontEndProductManagerJoomla3Page::$wishListButton);
		$I->amOnPage($userPage->wishListNewUrl($idProduct));

		$I->click(\FrontEndProductManagerJoomla3Page::$babelAddNewWishList);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$wishListField,30);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$wishListField,$nameWishList);
		$I->click(\FrontEndProductManagerJoomla3Page::$saveWishListButton);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$wishListMessage,30);

		$I->comment("Goes on frontend and check product inside wishlist");
		$I->amOnPage($userPage->wishListURL($id));
		$I->click(['link'=>$productName]);
	}

	public function wishListLoginAtEnd($productName, $categoryName,$nameWishList)
	{
		$I = $this;
		$I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$wishListButton,30);
		$I->click(\FrontEndProductManagerJoomla3Page::$wishListButton);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$usernameID,30);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$wishListField,30);
		$I->fillField(\FrontEndProductManagerJoomla3Page::$wishListField,$nameWishList);
		$I->click(\FrontEndProductManagerJoomla3Page::$saveWishListButton);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$alterMessageDiv);
		$I->see(\FrontEndProductManagerJoomla3Page::$alterMessageDiv,\FrontEndProductManagerJoomla3Page::$alterAddProductWishList);

	}
}