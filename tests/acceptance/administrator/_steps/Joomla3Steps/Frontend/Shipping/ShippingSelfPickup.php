<?php
/**
 * @package     redSHOP
 * @subpackage  ShippingGiaoHangNhanh
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Shipping;
use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;

/**
 * Class ShippingSelfPickup
 * @package Frontend\Shipping
 * @since 2.1.3
 */
class ShippingSelfPickup extends CheckoutMissingData
{
    /**
     * @param $categoryName
     * @param $product
     * @param $customerInformation
     * @param $shipping
     * @param $shippingName
     * @throws \Exception
     * @since 2.1.3
     */
    public function checkoutWithShippingSelfPickup($categoryName, $product, $customerInformation, $shipping, $shippingName)
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
        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$shippingMethod, 30);
        $I->scrollTo(FrontEndProductManagerJoomla3Page::$shippingMethod);
        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$radioShippingRate, 30);
        $I->selectOption(FrontEndProductManagerJoomla3Page::$radioShippingRate, $shipping['shippingName']);

        try
        {
            $I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$iconSelfPickup);
        } catch (\Exception $e)
        {
            $I->selectOption(FrontEndProductManagerJoomla3Page::$radioShippingRate, $shipping['shippingName']);
            $I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$iconSelfPickup);
        }

        $I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
        $I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$bankTransferId));
        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 30);
        $I->scrollTo(FrontEndProductManagerJoomla3Page::$termAndConditions);
        $I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);

        try
        {
            $I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
        }catch (\Exception $e)
        {
            $I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
            $I->canSeeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
        }

        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
        $I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
        $I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
        $I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page::$h1);

        $total = $product['price']*$quantity + $shipping['shippingRate'];
        $priceTotal = $currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
        $priceRate = $currencyUnit['currencySymbol'].' '.($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];

        $I->waitForText($shippingName, 30);
        $I->see($shippingName);
        $I->see($priceRate);
        $I->see($priceTotal);
    }
}