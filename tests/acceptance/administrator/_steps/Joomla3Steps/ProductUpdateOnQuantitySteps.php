<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
use ProductUpdateOnQuantityPage;

/**
 * Class ProductUpdateOnQuantitySteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class ProductUpdateOnQuantitySteps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $menuTitle
	 * @param $menuCategory
	 * @param $menuItem
	 * @param string $menu
	 * @param string $language
	 *
	 *  @throws \Exception
	 */
	public function createNewMenuItem($menuTitle, $menuCategory, $menuItem, $menu = 'Main Menu', $language = 'All')
	{
		$I = $this;
		$I->wantTo("I open the menus page");
		$I->amOnPage(ProductUpdateOnQuantityPage::$menuItemURL);
		$I->waitForText(ProductUpdateOnQuantityPage::$menuTitle, 5, array('css' => 'H1'));
		$I->checkForPhpNoticesOrWarnings();

		$I->wantTo("I click in the menu: $menu");
		$I->click(array('link' => $menu));
		$I->waitForText(ProductUpdateOnQuantityPage::$menuItemsTitle, 5, array('css' => 'H1'));
		$I->checkForPhpNoticesOrWarnings();

		$I->wantTo("I click new");
		$I->click(ProductUpdateOnQuantityPage::$buttonNew);
		$I->waitForText(ProductUpdateOnQuantityPage::$menuNewItemTitle, 5, array('css' => 'h1'));
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(ProductUpdateOnQuantityPage::$menItemTitle, $menuTitle);

		$I->wantTo("Open the menu types iframe");
		$I->click(ProductUpdateOnQuantityPage::$buttonSelect);
		$I->waitForElement(ProductUpdateOnQuantityPage::$menuTypeModal, 5);
		$I->wait(1);
		$I->switchToIFrame("Menu Item Type");

		$I->wantTo("Open the menu category: $menuCategory");

		// Open the category
		$I->wait(1);
		$I->waitForElement(ProductUpdateOnQuantityPage::getMenuCategory($menuCategory), 5);
		$I->click(ProductUpdateOnQuantityPage::getMenuCategory($menuCategory));

		$I->wantTo("Choose the menu item type: $menuItem");
		$I->wait(1);
		$I->waitForElement(ProductUpdateOnQuantityPage::returnMenuItem($menuItem),5);
		$I->click(ProductUpdateOnQuantityPage::returnMenuItem($menuItem));
		$I->wantTo('I switch back to the main window');
		$I->switchToIFrame();
		$I->wantTo('I leave time to the iframe to close');
		$I->selectOptionInChosen('Language', $language);
		$I->waitForText(ProductUpdateOnQuantityPage::$menuNewItemTitle, '30', array('css' => 'h1'));
		$I->wantTo('I save the menu');
		$I->click(ProductUpdateOnQuantityPage::$buttonSave);

		$I->waitForText('Menu item saved', 5, ProductUpdateOnQuantityPage::$message);
	}

    /**
     * @param $nameProduct
     * @param $quantity
     * @param $menuItem
     * @param $total
     * @param $customerInformation
     * @throws \Exception
     */
	public function checkProductUpdateQuantity($nameProduct,$quantity,$menuItem,$total,$customerInformation)
	{
		$I = $this;
		$I ->see($nameProduct);

		for( $a= 0; $a <$quantity; $a++)
		{
			$I->click(ProductUpdateOnQuantityPage:: $addToCart);
			$I->waitForText(ProductUpdateOnQuantityPage::$messageAddToCartSuccess,30);
		}
		$I->click($menuItem);
		$I->see($nameProduct);
		$I->see($total);
        $I->click(\FrontEndProductManagerJoomla3Page::$checkoutButton);
        $productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$radioCompany, 30);
        $I->comment('checkout with private');
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$addressEmail, 30);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressEmail, $customerInformation['email']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressFirstName, $customerInformation['firstName']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressLastName, $customerInformation['lastName']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, $customerInformation['address']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, $customerInformation['postalCode']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, $customerInformation['city']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, $customerInformation['phone']);
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
        $I->waitForText($total, 30, \FrontEndProductManagerJoomla3Page::$priceEnd);
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
        $I->executeJS($productFrontEndManagerPage->radioCheckID(\FrontEndProductManagerJoomla3Page::$termAndConditionsId));
        try
        {
            $I->seeCheckboxIsChecked(\FrontEndProductManagerJoomla3Page::$termAndConditions);
        }catch (\Exception $e)
        {
            $I->click(\FrontEndProductManagerJoomla3Page::$termAndConditions);
        }
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
        $I->scrollTo(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
        $I->click(\FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);
	}
}
