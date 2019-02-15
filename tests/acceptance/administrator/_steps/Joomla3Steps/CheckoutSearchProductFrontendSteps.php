<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class CheckoutSearchProductFrontendSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since   2.2
 */
use FrontEndProductManagerJoomla3Page;
use ModuleManagerJ3page;

class CheckoutSearchProductFrontendSteps  extends AdminManagerJoomla3Steps
{
    /**
     * Function to create module redShop-search
     *
     * @param $module
     * @throws \Exception
     */
        public function createModuleRedShopSearch($module)
    {
        $I = $this;
        $I->amOnPage(ModuleManagerJ3page::$moduleURL);
        $I->click(ModuleManagerJ3page::$buttonNew);
        $I->wait(1);
        $I->scrollTo(["link" =>$module['module']]);
        $I->click(["link" =>$module['module']]);

        $I->fillField(ModuleManagerJ3page::$fieldName,$module['name']);

        $I->click(ModuleManagerJ3page::$position);
        $I->fillField(ModuleManagerJ3page::$fieldPosition,$module['Position']);
        $I->pressKey(ModuleManagerJ3page::$fieldPosition, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

        $I->scrollTo(ModuleManagerJ3page::$labelSearchTypeField);

        switch ($module['SearchTypeField'])
        {

            case 'yes':
                $I->click(ModuleManagerJ3page::$searchTypeFieldYes);
                break;
            case 'no':
                $I->click(ModuleManagerJ3page::$searchTypeFieldNo);
                break;
        }

        switch ($module['SearchField'] )
        {
            case 'yes':
                $I->click(ModuleManagerJ3page::$searchFieldYes);
                break;

            case 'no':
                $I->click(ModuleManagerJ3page::$searchFieldNo);
                break;
        }
        switch ($module['CategoryField'])
        {
            case 'yes':
                $I->click(ModuleManagerJ3page::$categoryFieldYes);
                break;

            case 'no':
                $I->click(ModuleManagerJ3page::$categoryFieldNo);
                break;
        }
        switch ($module['ManufacturerField'])
        {
            case 'yes':
                $I->click(ModuleManagerJ3page::$manufacturerFieldYes);
                break;

            case 'no':
                $I->click(ModuleManagerJ3page::$manufacturerFieldNo);
                break;
        }
        switch ($module['ProductSearchTitle'])
        {
            case 'yes':
                $I->click(ModuleManagerJ3page::$productSearchTitleYes);
                break;

            case 'no':
                $I->click(ModuleManagerJ3page::$productSearchTitleNo);
                break;
        }
        switch ($module['KeywordTitle'])
        {
            case 'yes':
                $I->click(ModuleManagerJ3page::$keywordTitleYes);
                break;

            case 'no':
                $I->click(ModuleManagerJ3page::$keywordTitleNo);
                break;
        }

        $I->click(ModuleManagerJ3page:: $buttonSaveClose);
        $I->waitForText(ModuleManagerJ3page::$messageSaveModuleSuccess,10,ModuleManagerJ3page::$selectorMessage);


    }

    /**
     * Function Checkout with search product Frontend
     *
     * @param $productName
     * @param $customerInformation
     * @throws \Exception
     */
    public function checkoutSearchProductFrontend($productName,$customerInformation)
    {
        $I = $this;
        $productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
        $I->amOnPage("/");
        $I->waitForElement(FrontEndProductManagerJoomla3Page::$searchProductRedShop,10);
        $I->fillField(FrontEndProductManagerJoomla3Page::$inputSearchProductRedShop,$productName);
        $I->click(FrontEndProductManagerJoomla3Page::$buttonSearchProductRedShop);

        $I->click(FrontEndProductManagerJoomla3Page::$addToCart);
        $I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 10, FrontEndProductManagerJoomla3Page::$selectorMessage);
        $I->see(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, '.alert-message');
        $I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
        $I->checkForPhpNoticesOrWarnings();
        $I->seeElement(['link' => $productName]);
        $I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
        
        $I->waitForElement(FrontEndProductManagerJoomla3Page::$radioCompany, 30);
        $I->comment('checkout with private');
        $I->waitForElement(FrontEndProductManagerJoomla3Page::$addressEmail, 30);
        $I->fillField(FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
        $I->fillField(FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
        $I->fillField(FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
        $I->fillField(FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
        $I->fillField(FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
        $I->fillField(FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
        $I->fillField(FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
        $I->waitForElement(FrontEndProductManagerJoomla3Page::$bankTransfer, 30);
        $I->scrollTo(FrontEndProductManagerJoomla3Page::$bankTransfer);
        $I->wait(0.5);
        $I->click(FrontEndProductManagerJoomla3Page::$bankTransfer);
        $I->waitForElement(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
        $I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
        try
        {
            $I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
        }catch (\Exception $e)
        {
            $I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
        }

        $I->waitForElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
        $I->scrollTo(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
        $I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
        $I->waitForElement(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
    }
}