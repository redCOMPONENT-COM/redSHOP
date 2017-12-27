<?php
/**
 * ShippingSteps for shipping rate
 */

namespace AcceptanceTester;
use ShippingPage;

class ShippingSteps extends AdminManagerJoomla3Steps
{

    public function createShippingRateStandard($shippingName, $shippingRate, $weightStart, $weightEnd, $volumeStart, $volumeEnd, $shippingRateLenghtStart,
                                               $shippingRateLegnhtEnd, $shippingRateWidthStart, $shippingRateWidthEnd, $shippingRateHeightStart, $shippingRateHeightEnd, $orderTotalStart,
                                               $orderTotalEnd, $zipCodeStart, $zipCodeEnd, $country, $shippingRateProduct, $shippingCategory,
                                               $shippingShopperGroups, $shippingPriority, $shippingRateFor, $shippingVATGroups, $function)
    {
        $I = $this;
        $I->amOnPage(ShippingPage::$shippingManagementUrl);
        $I->click(ShippingPage::$standShipping);
        $I->waitForElement(ShippingPage::$shippingRate, 30);
        $I->click(ShippingPage::$shippingRate);
        $I->click(ShippingPage::$buttonNew);
        $userPage = new ShippingPage();
        $I->waitForElement(ShippingPage::$shippingName, 30);
        if ($shippingName != "") {
            $I->fillField(ShippingPage::$shippingName, $shippingName);
        }
        if ($weightStart != "") {
            $I->fillField(ShippingPage::$weightStart, $weightStart);

        }

        if ($weightEnd != "") {
            $I->fillField(ShippingPage::$weightEnd, $weightEnd);
        }

        if ($weightStart != "") {
            $I->fillField(ShippingPage::$weightStart, $weightStart);

        }

        if ($volumeStart != "") {
            $I->fillField(ShippingPage::$volumeStart, $volumeStart);

        }

        if ($volumeEnd != "") {
            $I->fillField(ShippingPage::$volumeEnd, $volumeEnd);

        }

        if ($shippingRateLenghtStart != "") {
            $I->fillField(ShippingPage::$shippingRateLenghtStart, $shippingRateLenghtStart);

        }
        $I->fillField(ShippingPage::$shippingRateValue, $shippingRate);

        if ($shippingRateLegnhtEnd != "") {
            $I->fillField(ShippingPage::$shippingRateLegnhtEnd, $shippingRateLegnhtEnd);
        }
        if ($shippingRateWidthStart != "") {
            $I->fillField(ShippingPage::$shippingRateWidthStart, $shippingRateWidthStart);
        }

        if ($shippingRateWidthEnd != "") {
            $I->fillField(ShippingPage::$shippingRateWidthEnd, $shippingRateWidthEnd);
        }

        if ($shippingRateHeightEnd != "") {
            $I->fillField(ShippingPage::$shippingRateHeightEnd, $shippingRateHeightEnd);
        }
        if ($shippingRateHeightStart != "") {
            $I->fillField(ShippingPage::$shippingRateHeightStart, $shippingRateHeightStart);
        }
        if ($orderTotalStart != "") {
            $I->fillField(ShippingPage::$orderTotalStart, $orderTotalStart);
        }
        if ($orderTotalEnd != "") {
            $I->fillField(ShippingPage::$orderTotalEnd, $orderTotalEnd);
        }
        if ($zipCodeStart != "") {
            $I->fillField(ShippingPage::$zipCodeStart, $zipCodeStart);
        }
        if ($zipCodeEnd != "") {
            $I->fillField(ShippingPage::$zipCodeEnd, $zipCodeEnd);
        }
        if ($country != "") {
            $I->waitForElement(ShippingPage::$country, 30);
            $I->click(ShippingPage::$countryField);
            $I->fillField(ShippingPage::$countryField, $country);
            $I->waitForElement($userPage->returnChoice($country),30);
            $I->click($userPage->returnChoice($country));
        }

        if ($shippingRateProduct != "") {
            $I->waitForElement(ShippingPage::$country, 30);
            $I->fillField(ShippingPage::$shippingRateProduct, $shippingRateProduct);
            $I->pressKey(ShippingPage::$shippingRateProduct, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        }

        if ($shippingCategory != "") {
            $I->waitForElement(ShippingPage::$shippingCategory, 30);
            $I->waitForElement(ShippingPage::$shippingCategory, 30);
            $I->click(ShippingPage::$shippingCategory);
            $I->fillField(ShippingPage::$shippingCategoryInput, $shippingCategory);
            $I->waitForElement($userPage->returnChoice($shippingCategory),30);
            $I->click($userPage->returnChoice($shippingCategory));
        }

        if ($shippingShopperGroups != "") {
            $I->waitForElement(ShippingPage::$shippingShopperGroups, 30);
            $I->click(ShippingPage::$shippingShopperGroups);

            $I->fillField(ShippingPage::$shippingShopperGroupsInput, $shippingShopperGroups);

            $I->waitForElement($userPage->returnChoice($shippingShopperGroups),30);
            $I->click($userPage->returnChoice($shippingShopperGroups));
        }

        $I->fillField(ShippingPage::$shippingPriority, $shippingPriority);

        if ($shippingRateFor != "") {
            $I->click(ShippingPage::$shippingRateFor);
            $I->seeElement(ShippingPage::$shippingRateForSearch, 30);
            $I->fillField(ShippingPage::$shippingRateForSearch, $shippingRateFor);
            $I->pressKey(ShippingPage::$shippingRateFor, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        }

        if ($shippingVATGroups != "") {
            $I->click(ShippingPage::$shippingVATGroups);
            $I->seeElement(ShippingPage::$shippingVATGroupsSearh, 30);
            $I->fillField(ShippingPage::$shippingVATGroupsSearh, $shippingVATGroups);
            $I->pressKey(ShippingPage::$shippingVATGroups, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        }

        switch ($function) {
            case 'save':
                $I->click(ShippingPage::$buttonSave);
                break;
            case 'saveclose':
                $I->click(ShippingPage::$buttonSaveClose);
                $I->seeLink($shippingName);
                break;
            default:
                break;
        }
    }

    public function editShippingRateStandard($shippingName, $shippingNameEdit, $shippingRate, $function)
    {
        $I = $this;
        $I->amOnPage(ShippingPage::$shippingManagementUrl);
        $I->click(ShippingPage::$standShipping);
        $I->waitForElement(ShippingPage::$shippingRate, 30);
        $I->click(ShippingPage::$shippingRate);
        $I->seeLink($shippingName);
        $I->click($shippingName);
        $I->waitForElement(ShippingPage::$shippingName, 30);
        $I->fillField(ShippingPage::$shippingName, $shippingNameEdit);
        $I->executeJS(ShippingPage::$scrollDown);
        $I->fillField(ShippingPage::$shippingRateValue, $shippingRate);
        switch ($function) {
            case 'save':
                $I->click(ShippingPage::$buttonSave);
                $I->seeInField(ShippingPage::$shippingName, $shippingNameEdit);
                break;
            case 'saveclose':
                $I->click(ShippingPage::$buttonSaveClose);
                $I->seeLink($shippingNameEdit);
        }

    }

    public function deleteShippingRate($shippingName)
    {
        $I = $this;
        $I->amOnPage(ShippingPage::$shippingManagementUrl);
        $I->click(ShippingPage::$standShipping);
        $I->waitForElement(ShippingPage::$shippingRate, 30);
        $I->click(ShippingPage::$shippingRate);
        $I->seeLink($shippingName);
        $I->checkAllResults();
        $I->click(ShippingPage::$buttonDelete);
        $I->dontSee($shippingName);
    }
}