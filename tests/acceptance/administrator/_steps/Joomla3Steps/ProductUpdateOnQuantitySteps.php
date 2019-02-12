<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
use AdminJ3Page;

/**
 * Class ProductUpdateOnQuantitySteps
 *
 * @package  AcceptanceTester
 *
 * @since    2.1.2
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
		$I->amOnPage(AdminJ3Page::$menuItemURL);
		$I->waitForText(AdminJ3Page::$menuTitle, 5, array('css' => 'H1'));
		$I->checkForPhpNoticesOrWarnings();

		$I->wantTo("I click in the menu: $menu");
		$I->click(array('link' => $menu));
		$I->waitForText(AdminJ3Page::$menuItemsTitle, 5, array('css' => 'H1'));
		$I->checkForPhpNoticesOrWarnings();

		$I->wantTo("I click new");
		$I->click(AdminJ3Page::$buttonNew);
		$I->waitForText(AdminJ3Page::$menuNewItemTitle, 5, array('css' => 'h1'));
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(AdminJ3Page::$menItemTitle, $menuTitle);

		$I->wantTo("Open the menu types iframe");
		$I->click(AdminJ3Page::$buttonSelect);
		$I->waitForElement(AdminJ3Page::$menuTypeModal, 5);
		$I->switchToIFrame("Menu Item Type");

		$I->wantTo("Open the menu category: $menuCategory");
		$I->waitForElement(AdminJ3Page::getMenuCategory($menuCategory), 5);
		$I->click(AdminJ3Page::getMenuCategory($menuCategory));

		$I->wantTo("Choose the menu item type: $menuItem");
		$I->wait(0.5);
		$I->waitForElement(AdminJ3Page::returnMenuItem($menuItem),5);
		$I->click(AdminJ3Page::returnMenuItem($menuItem));
		$I->wantTo('I switch back to the main window');
		$I->switchToIFrame();
		$I->wantTo('I leave time to the iframe to close');
		$I->selectOptionInChosen('Language', $language);
		$I->waitForText(AdminJ3Page::$menuNewItemTitle, '30', array('css' => 'h1'));
		$I->wantTo('I save the menu');
		$I->click(AdminJ3Page::$buttonSave);
		$I->waitForText('Menu item saved', 5, AdminJ3Page::$idInstallSuccess);
	}

	/**
	 * @param $nameProduct
	 * @param $quantity
	 * @param $menuItem
	 * @param $total
	 * @param $customerInformation
	 * @throws \Exception
	 */
	public function checkProductUpdateQuantity($nameProduct,$quantity,$menuItem,$priceProduct,$total,$customerInformation)
	{
		$I = $this;
		$I ->see($nameProduct);

		for( $a= 0; $a <$quantity; $a++)
		{
			$I->click(AdminJ3Page:: $addToCart);
			$I->waitForText(AdminJ3Page::$alertSuccessMessage,30);
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
		$I->scrollTo(\FrontEndProductManagerJoomla3Page::$bankTransfer);
		$I->click(\FrontEndProductManagerJoomla3Page::$bankTransfer);
		$I->waitForElement(\FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->waitForText($priceProduct, 30, \FrontEndProductManagerJoomla3Page::$priceEnd);
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