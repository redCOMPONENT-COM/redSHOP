<?php
/**
 * Class support shopper groups management at admin page
 */

namespace AcceptanceTester;



class ShopperGroupManagerJoomla3Steps extends AdminManagerJoomla3Steps
{

    public function addShopperGroups($shopperName, $shopperType, $shopperCustomer,$shopperGroupPortal, $category, $shipping,$shippingRate, $shippingCheckout, $showVat, $catalog, $showPrice, $enableQuotation,$function)
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$buttonNew);
        $I->fillField(\ShopperGroupJ3Page::$shopperName, $shopperName);

        if($shopperType!=null){
	        $I->click(\ShopperGroupJ3Page::$shopperGroupType);
	        $I->waitForElement(\ShopperGroupJ3Page::$shopperType);
	        $I->fillField(\ShopperGroupJ3Page::$shopperType, $shopperType);
	        $userShopperPage = new \ShopperGroupJ3Page();
	        $I->waitForElement($userShopperPage->returnSearch($shopperType), 60);
	        $I->click($userShopperPage->returnSearch($shopperType));
        }

        $I->click(\ShopperGroupJ3Page::$customerType);
        $I->waitForElement(\ShopperGroupJ3Page::$customerTypeSearch);
        $I->fillField(\ShopperGroupJ3Page::$customerTypeSearch, $shopperCustomer);
        $I->pressKey(\ShopperGroupJ3Page::$customerTypeSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->wait(3);
        if ($shopperGroupPortal=='yes'){
	        $I->click(\ShopperGroupJ3Page::$shopperGroupPortalYes);

        }else{
	        $I->click(\ShopperGroupJ3Page::$shopperGroupPortalNo);
        }

        $I->click(\ShopperGroupJ3Page::$categoryFiled);
        $I->fillField(\ShopperGroupJ3Page::$categoryFill, $category);
        $I->pressKey(\ShopperGroupJ3Page::$categoryFill, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        $I->waitForElement(\ShopperGroupJ3Page::$shippingYes,30);
        if ($shipping=='yes'){
	        $I->click(\ShopperGroupJ3Page::$shippingYes);

        }else{
	        $I->click(\ShopperGroupJ3Page::$shippingNo);

        }
        $I->fillField(\ShopperGroupJ3Page::$shippingRate, $shippingRate);
        $I->fillField(\ShopperGroupJ3Page::$shippingCheckout, $shippingCheckout);

        if ($showVat=='yes'){
	        $I->click(\ShopperGroupJ3Page::$vatYes);

        }else{
	        $I->click(\ShopperGroupJ3Page::$vatNo);

        }

        $I->click(\ShopperGroupJ3Page::$showPrice);
        $I->waitForElement(\ShopperGroupJ3Page::$showPriceSearch);
        $I->fillField(\ShopperGroupJ3Page::$showPriceSearch, $showPrice);
        $I->pressKey(\ShopperGroupJ3Page::$showPriceSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        $I->click(\ShopperGroupJ3Page::$catalogId);
        $I->waitForElement(\ShopperGroupJ3Page::$catalogSearch);
        $I->fillField(\ShopperGroupJ3Page::$catalogSearch, $catalog);
        $I->pressKey(\ShopperGroupJ3Page::$catalogSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        if($enableQuotation=='yes'){
	        $I->click(\ShopperGroupJ3Page::$quotationYes);

        }else{
	        $I->click(\ShopperGroupJ3Page::$quoationNo);
        }

        $I->click(\ShopperGroupJ3Page::$publishYes);

        switch ($function){
            case 'save':
                $I->click(\ShopperGroupJ3Page::$buttonSave);
                $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
                break;
            case 'saveclose':
                $I->click(\ShopperGroupJ3Page::$buttonSaveClose);
                $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
                $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$selectorPageTitle);
                break;
        }

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
        $I->click(\ShopperGroupJ3Page::$buttonClose);
        $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$selectorPageTitle);
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
        $I->click(\ShopperGroupJ3Page::$buttonSave);
        $I->see(\ShopperGroupJ3Page::$saveSuccess, \ShopperGroupJ3Page::$selectorSuccess);
    }

    public function deleteShopperGroupsNo()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$shopperFirst);
        $I->click(\ShopperGroupJ3Page::$buttonDelete);
        $I->cancelPopup();
    }

    public function addShopperGroupsMissingName()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->click(\ShopperGroupJ3Page::$buttonNew);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URLNew);
        $I->click(\ShopperGroupJ3Page::$buttonSaveClose);
        $I->acceptPopup();
    }


    public function changStatusAllShopperGroups($status){
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkAllResults();
        switch ($status){
            case 'publish':
                $I->click(\ShopperGroupJ3Page::$buttonPublish);
                $I->see(\ShopperGroupJ3Page::$publishSuccess, \ShopperGroupJ3Page::$selectorSuccess);
                break ;

            case 'unpublish':
                $I->click(\ShopperGroupJ3Page::$buttonUnpublish);
                $I->see(\ShopperGroupJ3Page::$unpublishSuccess, \ShopperGroupJ3Page::$selectorSuccess);
                break;
        }
        $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$selectorPageTitle);
    }


    public function checkButtons($buttonName){
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        switch ($buttonName){
            case 'edit':
                $I->click(\ShopperGroupJ3Page::$buttonEdit);
                $I->acceptPopup();
                break;
            case 'delete':
                $I->click(\ShopperGroupJ3Page::$buttonDelete);
                $I->acceptPopup();
                break;
            case 'unpublish':
                $I->click(\ShopperGroupJ3Page::$buttonUnpublish);
                $I->acceptPopup();
                break;
            case 'publish':
                $I->click(\ShopperGroupJ3Page::$buttonPublish);
                $I->acceptPopup();
                break;
            case 'cancel':
                $I->click(\ShopperGroupJ3Page::$buttonNew);
                $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URLNew);
                $I->click(\ShopperGroupJ3Page::$buttonCancel);
                break;
        }
        $I->see(\ShopperGroupJ3Page::$namePageManagement, \ShopperGroupJ3Page::$headPage);
    }

    public function deleteAllShopperGroups()
    {
        $I = $this;
        $I->amOnPage(\ShopperGroupJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\ShopperGroupJ3Page::$URL);
        $I->checkAllResults();
        $I->click(\ShopperGroupJ3Page::$buttonDelete);
        $I->acceptPopup();

        $I->waitForElement(\ShopperGroupJ3Page::$selectorSuccess);
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
