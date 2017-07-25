<?php
/**
 * Class support shopper groups management at admin page
 */

namespace AcceptanceTester;


class ShopperGroupManagerJoomla3Steps extends AdminManagerJoomla3Steps
{

    public function addShopperGroups($shopperName, $shopperType, $shopperCustomer, $category, $shippingRate, $shippingCheckout, $catalog, $showPrice, $function)
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$newButton);
        $I->fillField(\ShopperGroupJ3Page::$shopperName, $shopperName);
        $I->click(\ShopperGroupJ3Page::$shopperGroupType);
        $I->waitForElement(\ShopperGroupJ3Page::$shopperType);
        $I->fillField(\ShopperGroupJ3Page::$shopperType, $shopperType);
        $userShopperPage = new \ShopperGroupJ3Page();
        $I->waitForElement($userShopperPage->returnSearch($shopperType), 60);
        $I->click($userShopperPage->returnSearch($shopperType));

        $I->click(\ShopperGroupJ3Page::$customerType);
        $I->waitForElement(\ShopperGroupJ3Page::$customerTypeSearch);
        $I->fillField(\ShopperGroupJ3Page::$customerTypeSearch, $shopperCustomer);
        $I->pressKey(\ShopperGroupJ3Page::$customerTypeSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        $I->click(\ShopperGroupJ3Page::$shopperGroupPortalYes);

        $I->click(\ShopperGroupJ3Page::$categoryFiled);
        $I->fillField(\ShopperGroupJ3Page::$categoryFill, $category);
        $I->pressKey(\ShopperGroupJ3Page::$categoryFill, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        $I->click(\ShopperGroupJ3Page::$shippingNo);
        $I->fillField(\ShopperGroupJ3Page::$shippingRate, $shippingRate);
        $I->fillField(\ShopperGroupJ3Page::$shippingCheckout, $shippingCheckout);
        $I->click(\ShopperGroupJ3Page::$vatNo);

        $I->click(\ShopperGroupJ3Page::$showPrice);
        $I->waitForElement(\ShopperGroupJ3Page::$showPriceSearch);
        $I->fillField(\ShopperGroupJ3Page::$showPriceSearch, $showPrice);
        $I->pressKey(\ShopperGroupJ3Page::$showPriceSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        $I->click(\ShopperGroupJ3Page::$catalogId);
        $I->waitForElement(\ShopperGroupJ3Page::$catalogSearch);
        $I->fillField(\ShopperGroupJ3Page::$catalogSearch, $catalog);
        $I->pressKey(\ShopperGroupJ3Page::$catalogSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        $I->click(\ShopperGroupJ3Page::$quotationYes);
        $I->click(\ShopperGroupJ3Page::$publishYes);
        switch ($function){
            case 'save':
                $I->click(\ShopperGroupJ3Page::$saveButton);
                $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
                break;
            case 'saveclose':
                $I->click(\ShopperGroupJ3Page::$saveCloseButton);
                $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
                $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$selectorNamePage);
                break;
        }

    }

//    public function addShopperGroupsSave($shopperName, $shopperType, $shopperCustomer, $category, $shippingRate, $shippingCheckout, $catalog, $showPrice)
//    {
//        $I = $this;
//        $I->amOnPage(\ShopperGroupJ3Page::$URL);
//        $I->click(\ShopperGroupJ3Page::$newButton);
//        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URLNew);
//
//        $I->fillField(\ShopperGroupJ3Page::$shopperName, $shopperName);
//        $I->click(\ShopperGroupJ3Page::$shopperGroupType);
//        $I->waitForElement(\ShopperGroupJ3Page::$shopperType);
//        $I->fillField(\ShopperGroupJ3Page::$shopperType, $shopperType);
//        $userShopperPage = new \ShopperGroupJ3Page();
//        $I->waitForElement($userShopperPage->returnSearch($shopperType), 60);
//        $I->click($userShopperPage->returnSearch($shopperType));
//
//        $I->click(\ShopperGroupJ3Page::$customerType);
//        $I->waitForElement(\ShopperGroupJ3Page::$customerTypeSearch);
//        $I->fillField(\ShopperGroupJ3Page::$customerTypeSearch, $shopperCustomer);
//        $I->pressKey(\ShopperGroupJ3Page::$customerTypeSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$shopperGroupPortalYes);
//
//        $I->click(\ShopperGroupJ3Page::$categoryFiled);
//        $I->fillField(\ShopperGroupJ3Page::$categoryFill, $category);
//        $I->pressKey(\ShopperGroupJ3Page::$categoryFill, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$shippingNo);
//        $I->fillField(\ShopperGroupJ3Page::$shippingRate, $shippingRate);
//        $I->fillField(\ShopperGroupJ3Page::$shippingCheckout, $shippingCheckout);
//        $I->click(\ShopperGroupJ3Page::$vatNo);
//
//        $I->click(\ShopperGroupJ3Page::$showPrice);
//        $I->waitForElement(\ShopperGroupJ3Page::$showPriceSearch);
//        $I->fillField(\ShopperGroupJ3Page::$showPriceSearch, $showPrice);
//        $I->pressKey(\ShopperGroupJ3Page::$showPriceSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$catalogId);
//        $I->waitForElement(\ShopperGroupJ3Page::$catalogSearch);
//        $I->fillField(\ShopperGroupJ3Page::$catalogSearch, $catalog);
//        $I->pressKey(\ShopperGroupJ3Page::$catalogSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$quotationYes);
//        $I->click(\ShopperGroupJ3Page::$publishYes);
//
//        $I->click(\ShopperGroupJ3Page::$saveButton);
//        $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
//    }
//
//    public function addShopperGroupsSaveClose($shopperName, $shopperType, $shopperCustomer, $category, $shippingRate, $shippingCheckout, $catalog, $showPrice)
//    {
//        $I = $this;
//        $I->amOnPage(\ShopperGroupJ3Page::$URL);
//        $I->click(\ShopperGroupJ3Page::$newButton);
//        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URLNew);
//
//        $I->fillField(\ShopperGroupJ3Page::$shopperName, $shopperName);
//
//        $I->click(\ShopperGroupJ3Page::$shopperGroupType);
//        $I->waitForElement(\ShopperGroupJ3Page::$shopperType);
//        $I->fillField(\ShopperGroupJ3Page::$shopperType, $shopperType);
//        $userShopperPage = new \ShopperGroupJ3Page();
//        $I->waitForElement($userShopperPage->returnSearch($shopperType), 60);
//        $I->click($userShopperPage->returnSearch($shopperType));
//
//        $I->click(\ShopperGroupJ3Page::$customerType);
//        $I->waitForElement(\ShopperGroupJ3Page::$customerTypeSearch);
//        $I->fillField(\ShopperGroupJ3Page::$customerTypeSearch, $shopperCustomer);
//        $I->pressKey(\ShopperGroupJ3Page::$customerTypeSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$shopperGroupPortalYes);
//
//        $I->click(\ShopperGroupJ3Page::$categoryFiled);
//        $I->fillField(\ShopperGroupJ3Page::$categoryFill, $category);
//        $I->pressKey(\ShopperGroupJ3Page::$categoryFill, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$shippingNo);
//        $I->fillField(\ShopperGroupJ3Page::$shippingRate, $shippingRate);
//        $I->fillField(\ShopperGroupJ3Page::$shippingCheckout, $shippingCheckout);
//        $I->click(\ShopperGroupJ3Page::$vatNo);
//
//        $I->click(\ShopperGroupJ3Page::$showPrice);
//        $I->waitForElement(\ShopperGroupJ3Page::$showPriceSearch);
//        $I->fillField(\ShopperGroupJ3Page::$showPriceSearch, $showPrice);
//        $I->pressKey(\ShopperGroupJ3Page::$showPriceSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$catalogId);
//        $I->waitForElement(\ShopperGroupJ3Page::$catalogSearch);
//        $I->fillField(\ShopperGroupJ3Page::$catalogSearch, $catalog);
//        $I->pressKey(\ShopperGroupJ3Page::$catalogSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
//
//        $I->click(\ShopperGroupJ3Page::$quotationYes);
//        $I->click(\ShopperGroupJ3Page::$publishYes);
//
//        $I->click(\ShopperGroupJ3Page::$saveCloseButton);
//        $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
//        $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$selectorNamePage);
//    }


    public function deleteShopperGroupsYes()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$shopperFours);
        $I->click(\ShopperGroupJ3Page::$deleteButton);
        $I->acceptPopup();
        $I->see(\ShopperGroupJ3Page::$deleteButton, \ShopperGroupJ3Page::$selectorSuccess);
    }

    public function changeStateShopperGroups()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$shopperFirstStatus);
        $I->wait(3);
    }

    public function changeStateShopperGroup($status){
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$shopperFirstStatus);
        $I->wait(3);
        switch ($status){
            case 'unpublished':
                $currentState = $I->getShopperGroupsStates();
                $I->verifyState('unpublished', $currentState);
                break;
            case 'published':
                $currentState = $I->getShopperGroupsStates();
                $I->verifyState('published', $currentState);
                break;
        }
    }
    public function checkCloseButton($idShopperGroups)
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$nameShopperGroupsFirst);
        $URLEdit = \ShopperGroupJ3Page::$URLEdit . $idShopperGroups;
        $I->amOnPage($URLEdit);
        $I->checkForPhpNoticesOrWarnings($URLEdit);
        $I->click(\ShopperGroupJ3Page::$closeButton);
        $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$selectorNamePage);
    }

    public function editShopperGroups($nameShopperGroups, $idShopperGroups, $nameEdit)
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$nameShopperGroupsFirst);
        $URLEdit = \ShopperGroupJ3Page::$URLEdit . $idShopperGroups;
        $I->amOnPage($URLEdit);
        $I->checkForPhpNoticesOrWarnings($URLEdit);
        $I->fillField(\ShopperGroupJ3Page::$shopperName, $nameEdit);
        $I->click(\ShopperGroupJ3Page::$saveButton);
        $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
    }

    public function deleteShopperGroupsNo()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$shopperFirst);
        $I->click(\ShopperGroupJ3Page::$deleteButton);
        $I->cancelPopup();
    }

    public function addShopperGroupsMissingName()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URLNew);
        $I->click(\ShopperGroupJ3Page::$saveCloseButton);
        $I->acceptPopup();
    }


    public function changStatusAllShopperGroups($status){
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkAllResults();
        switch ($status){
            case 'publish':
                $I->click(\ShopperGroupJ3Page::$publishButton);
                $I->see(\ShopperGroupJ3Page::$publishSuccess, \ShopperGroupJ3Page::$xpathMessageSuccess);
                break ;

            case 'unpublish':
                $I->click(\ShopperGroupJ3Page::$unpublishButton);
                $I->see(\ShopperGroupJ3Page::$unpublishSuccess, \ShopperGroupJ3Page::$xpathMessageSuccess);
                break;
        }
        $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$selectorNamePage);
    }


    public function checkButtons($buttonName){
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        switch ($buttonName){
            case 'edit':
                $I->click(\ShopperGroupJ3Page::$editButton);
                $I->acceptPopup();
                break;
            case 'delete':
                $I->click(\ShopperGroupJ3Page::$deleteButton);
                $I->acceptPopup();
                break;
            case 'unpublish':
                $I->click(\ShopperGroupJ3Page::$unpublishButton);
                $I->acceptPopup();
                break;
            case 'publish':
                $I->click(\ShopperGroupJ3Page::$publishButton);
                $I->acceptPopup();
                break;
            case 'cancel':
                $I->click(\ShopperGroupJ3Page::$newButton);
                $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URLNew);
                $I->click(\ShopperGroupJ3Page::$cancelButton);
                break;
        }
        $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$pageHead);
    }

    public function deleteAllShopperGroups()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->checkAllResults();
        $I->click(\ShopperGroupJ3Page::$deleteButton);
        $I->acceptPopup();

        $I->waitForElement(\ShopperGroupJ3Page::$xpathMessageSuccess);
    }

    public function getShopperGroupsStates()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->wait(3);
        $text = $I->grabAttributeFrom(\ShopperGroupJ3Page::$shopperFirstStatus, 'onclick');

        if (strpos($text, 'unpublish') > 0) {
            $result = 'published';
        } else {
            $result = 'unpublished';
        }
        return $result;
    }

}