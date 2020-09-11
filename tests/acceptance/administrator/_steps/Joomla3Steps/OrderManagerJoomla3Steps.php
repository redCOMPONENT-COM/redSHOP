<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use ConfigurationPage;
use FrontEndProductManagerJoomla3Page;
use OrderManagerPage;
use ProductManagerPage;

/**
 * Class OrderManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class OrderManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $nameUser
	 * @param $nameProduct
	 * @param $quantity
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addOrder($nameUser, $nameProduct, $quantity)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);
		$I->click(OrderManagerPage::$buttonNew);
		$I->click(OrderManagerPage::$userId);
		$I->waitForElement(OrderManagerPage::$userSearch, 30);
		$userOrderPage = new OrderManagerPage();

		$I->fillField(OrderManagerPage::$userSearch, $nameUser);
		$I->waitForElement($userOrderPage->returnSearch($nameUser), 30);
		$I->pressKey(OrderManagerPage::$userSearch, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(OrderManagerPage::$fistName, 30);
		$I->see($nameUser);
		$I->wait(2);
		$I->waitForElement(OrderManagerPage::$applyUser, 30);
		$I->executeJS("jQuery('.button-apply').click()");
		$I->waitForElement(OrderManagerPage::$orderDetailTable, 30);
		$I->scrollTo(OrderManagerPage::$orderDetailTable);
		$I->waitForElement(OrderManagerPage::$productId, 30);

		$I->click(OrderManagerPage::$productId);
		$I->waitForElement(OrderManagerPage::$productsSearch, 30);
		$I->fillField(OrderManagerPage::$productsSearch, $nameProduct);
		$I->waitForElement($userOrderPage->returnSearch($nameProduct), 30);
		$I->click($userOrderPage->returnSearch($nameProduct));

		$I->fillField(OrderManagerPage::$quanlityFirst, $quantity);

		$I->click(OrderManagerPage::$buttonSave);
		$I->waitForElement(OrderManagerPage::$close, 30);
		$I->waitForText(OrderManagerPage::$buttonClose, 10, OrderManagerPage::$close);
	}

	/**
	 * @param $nameUser
	 * @param $status
	 * @param $paymentStatus
	 * @param $newQuantity
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function editOrder($nameUser, $status, $paymentStatus, $newQuantity)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);

		$this->searchOrder($nameUser);
		$I->waitForElement(OrderManagerPage::$nameXpath, 30);
		$I->click(OrderManagerPage::$nameXpath);
		$I->waitForElement(OrderManagerPage::$statusOrder, 30);
		$userOrderPage = new OrderManagerPage();
		$I->click(OrderManagerPage::$statusOrder);
		$I->fillField(OrderManagerPage::$statusSearch, $status);
		$I->waitForElement($userOrderPage->returnSearch($status), 30);
		$I->click($userOrderPage->returnSearch($status));

		$I->click(OrderManagerPage::$statusPaymentStatus);
		$I->fillField(OrderManagerPage::$statusPaymentSearch, $paymentStatus);
		$I->waitForElement($userOrderPage->returnSearch($paymentStatus), 30);
		$I->click($userOrderPage->returnSearch($paymentStatus));
		$I->fillField(OrderManagerPage::$quantityp1, $newQuantity);
		$I->pressKey(OrderManagerPage::$quantityp1, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElementVisible(OrderManagerPage::$nameButtonStatus);
		$I->scrollTo(OrderManagerPage::$statusOrder);
		$I->click(OrderManagerPage::$nameButtonStatus);
	}

	/**
	 * @param $name
	 * @since 1.4.0
	 */
	public function searchOrder($name)
	{
		$I = $this;
		$I->wantTo('Search the User ');
		$I->amOnPage(OrderManagerPage::$URL);
		$I->filterListBySearchOrder($name, OrderManagerPage::$filter);
	}

	/**
	 * @param $nameUser
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteOrder($nameUser)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);
		$this->searchOrder($nameUser);
		$I->waitForElementVisible(OrderManagerPage::$checkAllXpath, 30);
		$I->click(OrderManagerPage::$checkAllXpath);
		$I->waitForElementVisible(OrderManagerPage::$buttonDeleteOder, 30);
		$I->click(OrderManagerPage::$buttonDeleteOder);
		$I->acceptPopup();
		$I->waitForText(OrderManagerPage::$messageDeleteSuccess, 30);
		$I->see(OrderManagerPage::$messageDeleteSuccess, OrderManagerPage::$selectorSuccess);
	}

	/**
	 * @param $productName
	 * @since 1.4.0
	 */
	public function searchProduct($productName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(ProductManagerPage::$URL);
		$I->filterListBySearchingProduct($productName);
	}

	/**
	 * @param $name
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkReview($name)
	{
		$I = $this;
		$I->amOnPage(ProductManagerPage::$URL);
		$I->searchProduct($name);
		$I->click(['link' => $name]);
		$I->waitForElement(ProductManagerPage::$productName, 30);
		$I->wait(0.5);
		$I->click(ProductManagerPage::$buttonReview);
		$I->switchToNextTab();
		$I->waitForText($name, 30, ProductManagerPage::$namePageXpath);
	}

	/**
	 * @param $nameProduct
	 * @param $price
	 * @param $username
	 * @param $password
	 * @throws \Exception
	 * @since 2.1.
	 */
	public function addProductToCart($nameProduct,$price, $username, $password)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;
		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}
		$I->checkReview($nameProduct);
		$I->see($nameProduct);
		$I->waitForElementVisible(ProductManagerPage::$addToCart, 30);
		$I->click(ProductManagerPage::$addToCart);

		try
		{
			$I->waitForText(ProductManagerPage::$alertSuccessMessage, 30, ProductManagerPage::$selectorMessage);
		}catch (\Exception $e)
		{
			$I->click(ProductManagerPage::$addToCart);
		}

		$I->fillField(ProductManagerPage::$username, $username);
		$I->fillField(ProductManagerPage::$password, $password);
		$I->click(ProductManagerPage::$buttonLogin);
		$I->amOnPage(ProductManagerPage::$cartPageUrL);
		$quantity = $I->grabTextFrom(ProductManagerPage::$quantity);
		$quantity = (int) $quantity;
		$priceTotalOnCart = 'Total: '.$currencySymbol.' '.$price*$quantity.$decimalSeparator.$NumberZero;
		$I->see($priceTotalOnCart);
		$I->click(ProductManagerPage::$buttonCheckOut);
		$I->waitForElement(ProductManagerPage::$priceEnd, 30);
		$I->see($priceTotalOnCart);
		$I->waitForElement(ProductManagerPage::$acceptTerms, 30);
		$I->click(ProductManagerPage::$acceptTerms);
		$I->click(ProductManagerPage::$checkoutFinalStep);
		$I->waitForElement(ProductManagerPage::$priceTotalOrderFrontend, 30);
		$I->see($priceTotalOnCart);
	}

	/**
	 * @param $nameUser
	 * @param $nameProduct
	 * @param $price
	 * @param $priceAttribute
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addOrderWithAttribute($nameUser, $nameProduct, $price, $priceAttribute)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);
		$I->click(OrderManagerPage::$buttonNew);
		$I->waitForElementVisible(OrderManagerPage::$userId, 30);
		$I->click(OrderManagerPage::$userId);
		$I->waitForElementVisible(OrderManagerPage::$userSearch, 30);
		$userOrderPage = new OrderManagerPage();
		$I->fillField(OrderManagerPage::$userSearch, $nameUser);
		$I->waitForElementVisible($userOrderPage->returnSearch($nameUser), 30);
		$I->wait(0.5);
		$I->pressKey(OrderManagerPage::$userSearch, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForJs('return document.readyState == "complete"', 10);
		$I->waitForElementVisible(OrderManagerPage::$fistName, 30);
		$I->see($nameUser);
		$I->waitForElement(OrderManagerPage::$applyUser, 30);
		$I->wait(0.5);
		$I->click(OrderManagerPage::$applyUser);

		$I->waitForElement(OrderManagerPage::$orderDetailTable, 60);
		$I->scrollTo(OrderManagerPage::$orderDetailTable);
		$I->waitForElementVisible(OrderManagerPage::$productId, 30);
		$I->click(OrderManagerPage::$productId);
		$I->waitForElementVisible(OrderManagerPage::$productsSearch, 30);
		$I->fillField(OrderManagerPage::$productsSearch, $nameProduct);
		$I->waitForElementVisible($userOrderPage->returnSearch($nameProduct), 30);
		$I->click($userOrderPage->returnSearch($nameProduct));
		$I->waitForElementVisible(OrderManagerPage::$valueAttribute, 30);
		$I->wait(1);
		$I->click(OrderManagerPage::$valueAttribute);
		$I->wait(1);
		$adminFinalPriceEnd = $price + $priceAttribute;
		$I->waitForText("$adminFinalPriceEnd", 30);
		$I->scrollTo(OrderManagerPage::$adminFinalPriceEnd);
		$I->waitForElement(OrderManagerPage::$adminFinalPriceEnd, 60);
		$I->click(OrderManagerPage::$buttonSave);
		$I->scrollTo(OrderManagerPage::$adminFinalPriceEnd);
		$I->see($adminFinalPriceEnd);
		$I->see(OrderManagerPage::$buttonClose, OrderManagerPage::$close);
	}

	/**
	 * @param $nameProduct
	 * @param $categoryName
	 * @param $price
	 * @param $username
	 * @param $password
	 * @throws \Exception
	 * @since 2.1.5
	 */
	public function addProductToCartWithBankTransfer($nameProduct, $categoryName, $price, $username, $password)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;
		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($nameProduct));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCart);

		try
		{
			$I->waitForText(ProductManagerPage::$alertSuccessMessage, 30, ProductManagerPage::$selectorMessage);
		}catch (\Exception $e)
		{
			$I->click(ProductManagerPage::$addToCart);
			$I->waitForText(ProductManagerPage::$alertSuccessMessage, 30, ProductManagerPage::$selectorMessage);
		}

		$I->fillField(ProductManagerPage::$username, $username);
		$I->fillField(ProductManagerPage::$password, $password);
		$I->click(ProductManagerPage::$buttonLogin);
		$I->amOnPage(ProductManagerPage::$cartPageUrL);
		$quantity = $I->grabTextFrom(ProductManagerPage::$quantity);
		$quantity = (int) $quantity;
		$priceTotalOnCart = 'Total: '.$currencySymbol.' '.$price*$quantity.$decimalSeparator.$NumberZero;
		$I->see($priceTotalOnCart);
		$I->click(ProductManagerPage::$buttonCheckOut);
		$I->waitForElement(ProductManagerPage::$priceEnd, 60);
		$I->see($priceTotalOnCart);
		$I->click(ProductManagerPage::$bankTransfer);
		$I->waitForElement(ProductManagerPage::$acceptTerms, 30);
		$I->click(ProductManagerPage::$acceptTerms);
		$I->click(ProductManagerPage::$checkoutFinalStep);
		$I->waitForElement(ProductManagerPage::$priceTotalOrderFrontend, 30);
		$I->see($priceTotalOnCart);
	}

	/**
	 * @param $firstName
	 * @param $statusName
	 * @param $statusCode
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function changeOrderStatus($firstName, $statusName, $statusCode)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);
		$I->searchOrder($firstName);
		$I->waitForElementVisible(OrderManagerPage::$iconEdit, 30);
		$idOrder = $I->grabValueFrom(OrderManagerPage::$iconEdit);
		$I->click(OrderManagerPage::$iconEdit);

		$I->waitForElementVisible(OrderManagerPage::$statusOrder, 30);
		$I->chooseOnSelect2(OrderManagerPage::$statusOrder, $statusName);
		$I->click(OrderManagerPage::$nameButtonStatus);
		$I->waitForText(OrderManagerPage::$messageChangeOrderSuccess.$idOrder, 30, OrderManagerPage::$selectorSuccess);
		$I->click(OrderManagerPage::$buttonClose);
		$oderStatus = new OrderManagerPage();
		$I->waitForText($statusName, 30, $oderStatus->xpathOrderStatus($statusCode));
		$I->see($statusName);
	}

	/**
	 * @param $nameUser
	 * @param $function
	 * @param $vatValue
	 * @param array $product
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function addOrderWithVATWithUserForeignCountry($nameUser, $function, $vatValue, $product = array())
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();

		$I->amOnPage(OrderManagerPage::$URL);
		$I->click(OrderManagerPage::$buttonNew);
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForText(OrderManagerPage::$titlePage, 30, OrderManagerPage::$h1);
		$I->waitForElementVisible(OrderManagerPage::$userId, 30);
		$I->click(OrderManagerPage::$userId);
		$I->waitForElementVisible(OrderManagerPage::$userSearch, 30);

		$userOrderPage = new OrderManagerPage();
		$I->fillField(OrderManagerPage::$userSearch, $nameUser);
		$I->waitForElement($userOrderPage->returnSearch($nameUser), 30);
		$I->pressKey(OrderManagerPage::$userSearch, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(OrderManagerPage::$fistName, 30);
		$I->waitForText($nameUser, 30);
		$I->see($nameUser);

		$I->waitForElementVisible(OrderManagerPage::$applyUser, 30);
		$I->wait(0.5);
		$I->click(OrderManagerPage::$applyUser);
		$I->waitForElement(OrderManagerPage::$orderDetailTable, 60);
		$I->scrollTo(OrderManagerPage::$orderDetailTable);

		$I->waitForElementVisible(OrderManagerPage::$productId, 30);
		$I->wait(0.5);
		$I->click(OrderManagerPage::$productId);
		$I->waitForElementVisible(OrderManagerPage::$productsSearch, 30);

		$I->fillField(OrderManagerPage::$productsSearch, $product['productName']);
		$I->waitForElementVisible($userOrderPage->returnSearch($product['productName']), 30);
		$I->wait(0.5);
		$I->click($userOrderPage->returnSearch($product['productName']));

		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForElementVisible($userOrderPage->returnXpathAttributeName($product['attributeName']), 30);
		$I->seeElement($userOrderPage->returnXpathAttributeName($product['attributeName']));
		$I->click($userOrderPage->returnXpathAttributeName($product['attributeName']));

		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForElementVisible($userOrderPage->returnXpathAttributeValue($product['size']), 30);
		$I->wait(0.5);
		$I->seeElement($userOrderPage->returnXpathAttributeName($product['attributeName']));
		$I->click($userOrderPage->returnXpathAttributeValue($product['size']));
		$I->waitForJs('return document.readyState == "complete"', 10);

		switch ($function)
		{
			case 'HaveVAT':
			{
				$priceVATAttribute = ($product['priceProduct'] + $product['priceSize']) * $vatValue;

				$priceProductTotal = $priceVATAttribute + ($product['priceProduct'] + $product['priceSize']);

				$I->waitForElementVisible($userOrderPage->returnPropertyName($product['size']), 30);

				$I->waitForElement(OrderManagerPage::$priceVAT, 30);
				$vatProduct = $I->grabTextFrom(OrderManagerPage::$priceVAT);

				$priceVATString = $currencyUnit['currencySymbol'].' '.$priceVATAttribute.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
				$priceProductString = $currencyUnit['currencySymbol'].' '.$priceProductTotal.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

				$I->assertEquals($vatProduct, $priceVATString);

				$priceProduct = $I->grabTextFrom(OrderManagerPage::$priceProduct);

				$I->assertEquals($priceProduct, $priceProductString);

				$I->waitForElementVisible($userOrderPage->returnXpathAttributeValue($product['color']), 30);
				$I->click($userOrderPage->returnXpathAttributeValue($product['color']));
				$vatProduct = $I->grabTextFrom(OrderManagerPage::$priceVAT);
				$priceProduct = $I->grabTextFrom(OrderManagerPage::$priceProduct);

				$priceVATSubAttribute = ($product['priceProduct'] + $product['priceSize'] + $product['priceColor']) * $vatValue;

				$priceProductTotal = $priceVATSubAttribute + ($product['priceProduct'] + $product['priceSize'] + $product['priceColor']);

				$priceVATString = $currencyUnit['currencySymbol'].' '.$priceVATSubAttribute.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
				$priceProductString = $currencyUnit['currencySymbol'].' '.$priceProductTotal.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

				$I->assertEquals($vatProduct, $priceVATString);
				$I->assertEquals($priceProduct, $priceProductString);

				break;
			}

			case 'NotVAT':
			{
				$I->waitForElementVisible($userOrderPage->returnPropertyName($product['size']), 30);
				$priceProductTotal = $product['priceProduct'] + $product['priceSize'];
				$vatProduct = $I->grabTextFrom(OrderManagerPage::$priceVAT);
				$priceProductString = $currencyUnit['currencySymbol'].' '.$priceProductTotal.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
				$I->assertEquals($vatProduct, $product['priceVAT']);
				$priceProduct = $I->grabTextFrom(OrderManagerPage::$priceProduct);
				$I->assertEquals($priceProduct, $priceProductString);

				$I->waitForElementVisible($userOrderPage->returnXpathAttributeValue($product['color']), 30);
				$I->click($userOrderPage->returnXpathAttributeValue($product['color']));
				$vatProduct = $I->grabTextFrom(OrderManagerPage::$priceVAT);
				$priceProduct =  $I->grabTextFrom(OrderManagerPage::$priceProduct);

				$priceProductTotal = $product['priceProduct'] + $product['priceSize'] + $product['priceColor'];

				$priceProductString = $currencyUnit['currencySymbol'].' '.$priceProductTotal.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

				$I->assertEquals($vatProduct, $product['priceVAT']);
				$I->assertEquals($priceProduct, $priceProductString);

				break;
			}
		}

		$I->click(OrderManagerPage::$buttonSave);
		$I->waitForElement(OrderManagerPage::$close, 30);
		$I->waitForText(OrderManagerPage::$buttonClose, 10, OrderManagerPage::$close);
	}

	/**
	 * @param $firstName
	 * @param $paymentOld
	 * @param $paymentNew
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function changePaymentMethodOnBackend($firstName, $paymentOld, $paymentNew)
	{
		$I = $this;
		$I->amOnPage(OrderManagerPage::$URL);
		$I->searchOrder($firstName);
		$I->waitForElementVisible(OrderManagerPage::$iconEdit, 30);
		$I->click(OrderManagerPage::$iconEdit);
		$I->waitForElementVisible(OrderManagerPage::$paymentTitle, 30);

		if($paymentOld == 'PayPal')
		{
			$I->waitForElementVisible(OrderManagerPage::$checkboxPaymentPayPal, 30);
			$I->seeCheckboxIsChecked(OrderManagerPage::$checkboxPaymentPayPal);
		}else if ($paymentOld == 'RedSHOP - Bank Transfer Payment')
		{
			$I->waitForElementVisible(OrderManagerPage::$checkboxPaymentBanktransfer, 30);
			$I->seeCheckboxIsChecked(OrderManagerPage::$checkboxPaymentBanktransfer);
		}

		if($paymentNew == 'PayPal')
		{
			$I->waitForElementVisible(OrderManagerPage::$checkboxPaymentPayPal, 30);
			$I->click(OrderManagerPage::$checkboxPaymentPayPal);
			$I->waitForElementVisible(OrderManagerPage::$updatePaymentMethod,30);
			$I->click(OrderManagerPage::$updatePaymentMethod);
			$I->waitForText("Payment Method Updated", 30);
			$I->waitForElementVisible(OrderManagerPage::$checkboxPaymentPayPal, 30);
			$I->seeCheckboxIsChecked(OrderManagerPage::$checkboxPaymentPayPal);
		}else if ($paymentNew == 'RedSHOP - Bank Transfer Payment')
		{
			$I->waitForElementVisible(OrderManagerPage::$checkboxPaymentBanktransfer, 30);
			$I->click(OrderManagerPage::$checkboxPaymentBanktransfer);
			$I->waitForElementVisible(OrderManagerPage::$updatePaymentMethod,30);
			$I->click(OrderManagerPage::$updatePaymentMethod);
			$I->waitForText(OrderManagerPage::$messageUpdatePaymentSuccess, 30);
			$I->waitForElementVisible(OrderManagerPage::$checkboxPaymentBanktransfer, 30);
			$I->seeCheckboxIsChecked(OrderManagerPage::$checkboxPaymentBanktransfer);
		}
	}
}
