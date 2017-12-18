<?php
/**
 * ShippingSteps for shipping rate
 */

namespace AcceptanceTester;
use ShippingPage;

class ShippingSteps extends AdminManagerJoomla3Steps
{

    public function createShippingRateStandard($shipping = array(), $function)
    {
        $I = $this;
        $I->amOnPage(ShippingPage::$shippingManagementUrl);
        $I->click(ShippingPage::$standShipping);
        $I->waitForElement(ShippingPage::$shippingRate, 30);
        $I->click(ShippingPage::$shippingRate);
        $I->click(ShippingPage::$buttonNew);
        $userPage = new ShippingPage();
        $I->waitForElement(ShippingPage::$shippingName, 30);
        if (isset($shipping['name'])) {
            $I->fillField(ShippingPage::$shippingName, $shipping['name']);
        }
        if (isset($shipping['weightStart'])) {
            $I->fillField(ShippingPage::$weightStart, $shipping['weightStart']);

        }

        if (isset($shipping['weightEnd'])) {
            $I->fillField(ShippingPage::$weightEnd, $shipping['weightEnd']);
        }

        if (isset($shipping['weightStart'])) {
            $I->fillField(ShippingPage::$weightStart, $shipping['weightStart']);

        }

        if (isset($shipping['volumeStart'])) {
            $I->fillField(ShippingPage::$volumeStart, $shipping['volumeStart']);

        }

        if (isset($shipping['volumeEnd'])) {
            $I->fillField(ShippingPage::$volumeEnd, $shipping['volumeEnd']);

        }

        if (isset($shipping['shippingRateLenghtStart'])) {
            $I->fillField(ShippingPage::$shippingRateLenghtStart, $shipping['shippingRateLenghtStart']);

        }
       if (isset($shipping['shippingRate']))
       {
           $I->fillField(ShippingPage::$shippingRateValue, $shipping['shippingRate']);
       }

        if (isset($shipping['shippingRateLegnhtEnd'])) {
            $I->fillField(ShippingPage::$shippingRateLegnhtEnd, $shipping['shippingRateLegnhtEnd']);
        }
        if (isset($shipping['shippingRateWidthStart'])) {
            $I->fillField(ShippingPage::$shippingRateWidthStart, $shipping['shippingRateWidthStart']);
        }

        if (isset($shipping['shippingRateWidthEnd'])) {
            $I->fillField(ShippingPage::$shippingRateWidthEnd, $shipping['shippingRateWidthEnd']);
        }

        if (isset($shipping['shippingRateHeightEnd'])) {
            $I->fillField(ShippingPage::$shippingRateHeightEnd, $shipping['shippingRateHeightEnd']);
        }
        if (isset($shipping['shippingRateHeightStart'])) {
            $I->fillField(ShippingPage::$shippingRateHeightStart, $shipping['shippingRateHeightStart']);
        }
        if (isset($shipping['orderTotalStart'])) {
            $I->fillField(ShippingPage::$orderTotalStart, $shipping['orderTotalStart']);
        }
        if (isset($shipping['orderTotalEnd'])) {
            $I->fillField(ShippingPage::$orderTotalEnd, $shipping['orderTotalEnd']);
        }
        if (isset($shipping['zipCodeStart'])) {
            $I->fillField(ShippingPage::$zipCodeStart, $shipping['zipCodeStart']);
        }
        if (isset($shipping['zipCodeEnd'])) {
            $I->fillField(ShippingPage::$zipCodeEnd, $shipping['zipCodeEnd']);
        }

        if (isset($shipping['country'])) {
            $I->waitForElement(ShippingPage::$country, 30);
            $I->click(ShippingPage::$countryField);
            $I->fillField(ShippingPage::$countryField, $shipping['country']);
            $I->waitForElement($userPage->returnChoice( $shipping['country']),30);
            $I->click($userPage->returnChoice( $shipping['country']));
        }

        if (isset($shipping['shippingRateProduct'])) {
            $I->waitForElement(ShippingPage::$shippingRateProduct, 30);
            $I->click(ShippingPage::$shippingRateProduct);
            $I->pauseExecution();
            $I->fillField(ShippingPage::$fieldShippingRateProduct, $shipping['shippingRateProduct']);
            $I->pauseExecution();
            $I->pressKey(ShippingPage::$fieldShippingRateProduct, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        }

        if (isset($shipping['shippingCategory'])) {
            $I->waitForElement(ShippingPage::$shippingCategory, 30);
            $I->click(ShippingPage::$shippingCategory);
            $I->fillField(ShippingPage::$shippingCategoryInput, $shipping['shippingCategory']);
            $I->waitForElement($userPage->returnChoice($shipping['shippingCategory']),30);
            $I->click($userPage->returnChoice($shipping['shippingCategory']));
        }

        if (isset($shipping['shippingShopperGroups'])) {
            $I->waitForElement(ShippingPage::$shippingShopperGroups, 30);
            $I->click(ShippingPage::$shippingShopperGroups);
            $I->fillField(ShippingPage::$shippingShopperGroupsInput, $shipping['shippingShopperGroups']);
            $I->waitForElement($userPage->returnChoice($shipping['shippingShopperGroups']),30);
            $I->click($userPage->returnChoice($shipping['shippingShopperGroups']));
        }

        if (isset($shipping['shippingPriority'])) {
            $I->fillField(ShippingPage::$shippingPriority, $shipping['shippingPriority']);
        }

        if (isset($shipping['shippingRateFor'])) {
            $I->click(ShippingPage::$shippingRateFor);
            $I->seeElement(ShippingPage::$shippingRateForSearch, 30);
            $I->fillField(ShippingPage::$shippingRateForSearch, $shipping['shippingRateFor']);
            $I->pressKey(ShippingPage::$shippingRateFor, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        }

        if (isset($shipping['shippingVATGroups'])) {
            $I->click(ShippingPage::$shippingVATGroups);
            $I->seeElement(ShippingPage::$shippingVATGroupsSearh, 30);
            $I->fillField(ShippingPage::$shippingVATGroupsSearh, $shipping['shippingVATGroups']);
            $I->pressKey(ShippingPage::$shippingVATGroups, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        }

        switch ($function) {
            case 'save':
                $I->click(ShippingPage::$buttonSave);
                $I->seeInField(ShippingPage::$shippingName, $shipping['name']);
                break;
            case 'saveclose':
                $I->click(ShippingPage::$buttonSaveClose);
                $I->seeLink($shipping['name']);
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