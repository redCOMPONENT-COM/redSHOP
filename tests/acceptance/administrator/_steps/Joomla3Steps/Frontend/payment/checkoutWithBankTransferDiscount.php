<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;

/**
 * Class checkoutWithBankTransferDiscount
 * @package Frontend\payment
 * @since 2.1.3
 */
class checkoutWithBankTransferDiscount extends CheckoutMissingData
{
	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $customerInformation
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkoutProductWithBankTransferDiscountPayment($productName, $categoryName, $customerInformation, $productPrice, $paymentPrice)
	{
		$I = $this;

		$I->amOnPage(\ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(\ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(\ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(\ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;
		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelPayment, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount, 30);
		$I->wait(0.5);
		$I->click(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);

		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$priceTotalOnCart = 'Total: '.$currencySymbol.' '.($productPrice - $paymentPrice).$decimalSeparator.$NumberZero;
		$pricePaymentDiscount = 'Payment Discount: '.$currencySymbol.' '.($paymentPrice).$decimalSeparator.$NumberZero;

		try
		{
			$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
			$I->wait(0.5);
			$I->see($pricePaymentDiscount);
			$I->see($priceTotalOnCart);
		}
		catch (\Exception $e)
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount, 30);
			$I->wait(0.5);
			$I->click(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount);
			$I->wait(0.5);
			$I->see($pricePaymentDiscount);
			$I->see($priceTotalOnCart);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}
		catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->wait(0.5);

		try
		{
			$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
		}
		catch (\Exception $e)
		{
			try
			{
				$I->waitForText(FrontEndProductManagerJoomla3Page::$messageSelectPayment, 10, FrontEndProductManagerJoomla3Page::$locatorMessagePayment);
				$I->click(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount);
				$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
				$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			}
			catch (\Exception $e)
			{
				try
				{
					$I->waitForText(FrontEndProductManagerJoomla3Page::$messageAcceptTerms, 10, FrontEndProductManagerJoomla3Page::$locatorMessageAcceptTerms);
					$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
				}
				catch (\Exception $e)
				{

				}
			}

			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
			$I->wait(0.5);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}

		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
	}
}