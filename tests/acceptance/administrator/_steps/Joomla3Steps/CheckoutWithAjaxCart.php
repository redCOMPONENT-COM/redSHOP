<?php
/**
 * @package     redSHOP
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CheckoutWithAjaxCart
 * @since 2.1.4
 */
class CheckoutWithAjaxCart extends CheckoutMissingData
{
	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $continueShopping
	 * @param $viewCart
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function addToCartAjax($categoryName, $productName, $continueShopping, $viewCart)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->waitForElementVisible($productFrontEndManagerPage->productCategory($categoryName), 30);
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);
		$I->waitForElementVisible(FrontEndAjaxCartPage::$ajaxCart, 30);

		switch ($continueShopping)
		{
			case 'yes':
				$I->waitForElementVisible(FrontEndAjaxCartPage::$btnContinueShopping, 30);
				$I->click(FrontEndAjaxCartPage::$btnContinueShopping);
				break;

			case 'no':
				$I->waitForElementVisible(FrontEndAjaxCartPage::$btnContinueShopping, 30);

				switch ($viewCart)
				{
					case 'yes':
						$I->waitForElementVisible(FrontEndAjaxCartPage::$btnViewCart, 30);
						$I->click(FrontEndAjaxCartPage::$btnViewCart);

						$I->waitForElementVisible(FrontEndAjaxCartPage::$tableCart, 30);
						$I->waitForText($productName, 30, FrontEndAjaxCartPage::$productOnCart);
						break;

					case 'no':
						$I->waitForElementVisible(FrontEndAjaxCartPage::$btnViewCart, 30);
						break;
				}

				break;
		}
	}

	/**
	 * @param $categoryName
	 * @param $productName
	 * @param $customerInformation
	 * @throws Exception
	 * @since 2.1.4
	 */
	public function onePageCheckoutWithAjaxCart($categoryName, $productName, $customerInformation)
	{
		$I = $this;
		$I->comment("Add to cart ajax and continue shopping");
		$I->addToCartAjax($categoryName, $productName, 'yes', 'no');
		$I->comment("Add to cart ajax and view cart");
		$I->addToCartAjax($categoryName, $productName, 'no', 'yes');
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
		$I->wait(0.5);
		$I->click(\FrontEndProductManagerJoomla3Page::$bankTransfer);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
	}
}
