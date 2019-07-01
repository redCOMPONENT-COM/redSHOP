<?php


namespace Frontend\payment;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;
use Frontend2PaymentPage;



class CheckoutWithAmazonPayment extends CheckoutOnFrontEnd
{
	/**
	 * @param $userName
	 * @param $password
	 * @param $productName
	 * @param $categoryName
	 * @throws \Exception
	 * since 2.1.2
	 */
	public function CheckoutWithAmazonPayment ($userName , $password, $productName, $categoryName)
	{
		$I = $this;
		$I->doFrontEndLogin($userName, $password);
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page:: $checkoutButton);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page:: $labelPayment, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElementVisible(Frontend2PaymentPage::$buttonAmazon, 30);
		$I->wait(0.5);
		$I->click(Frontend2PaymentPage::$buttonAmazon);
		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		try
		{
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}
		$I->dontSee(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
	}

}