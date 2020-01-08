<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use Frontend\payment\CheckoutWithEWAYPayment;

/**
 * Class ShippingWithOneItemSteps
 * @since 2.1.5
 */
class ShippingWithOneItemSteps extends CheckoutWithEWAYPayment
{
    public function ChekoutWithOneItemShippingSteps($categoryName, $product, $customerInformation, $shipping, $shippingName)
    {
        $I = $this;
        $currencyUnit = $I->getCurrencyValue();
        $I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
        $productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
        $I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 60);
        $I->addToCart($categoryName, $product['name']);

        $I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
        $I->waitForElementVisible(['link' => $product['name']], 30);
        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$quantity1, 30);
        $quantity = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$quantity1);

        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
        $I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
        $I->fillInformationPrivate($customerInformation);

        $I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
        $I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
        $I->scrollTo(FrontEndProductManagerJoomla3Page::$termAndConditions);
        $I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);

        try
        {
            $I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
        }

        catch (\Exception $e)
        {
            $I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
            $I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
        }

        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
        $I->pauseExecution();
        $I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
        $I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);

        $total = $product['price']*$quantity + $shipping['shippingRate'];
        $priceTotal = $currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
        $priceRate = $currencyUnit['currencySymbol'].' '.($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

        $I->waitForText($shippingName, 30);
        $I->pauseExecution();
        $I->see($shippingName);
        $I->see($priceRate);
        $I->see($priceTotal);
    }
}
