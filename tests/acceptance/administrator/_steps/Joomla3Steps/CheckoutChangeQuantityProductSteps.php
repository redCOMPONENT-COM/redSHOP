<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ProductManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1
 */
class CheckoutChangeQuantityProductSteps extends AdminManagerJoomla3Steps
{
	public function checkoutChangeQuantity($category, $userName, $password)
	{
		$I = $this;
		$I->amOnPage(\CheckoutChangeQuantityProductPage::$url);
		$I->click(\CheckoutChangeQuantityProductPage::$fillUserName);
		$I->fillField(\CheckoutChangeQuantityProductPage::$fillUserName, $userName);
		$I->click(\CheckoutChangeQuantityProductPage::$fillPassWord);
		$I->fillField(\CheckoutChangeQuantityProductPage::$fillPassWord, $password);
		$I->click(\CheckoutChangeQuantityProductPage::$submitButton);
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$categoryTitle, 30);
		$I->click($category);
		$I->click(\CheckoutChangeQuantityProductPage::$addToCart);
		$I->amOnPage(\CheckoutChangeQuantityProductPage::$cartPageUrL);
		$I->click(\CheckoutChangeQuantityProductPage::$quantityField);
		$I->pressKey(\CheckoutChangeQuantityProductPage::$quantityField, \Facebook\WebDriver\WebDriverKeys::BACKSPACE);
		$quantities = 10;
		$quantity = str_split($quantities);
		foreach ($quantity as $char) {
			$I->pressKey(\CheckoutChangeQuantityProductPage::$quantityField, $char);
		}
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$updateCartButton, 30);
		$I->click(\CheckoutChangeQuantityProductPage::$updateCartButton);
		$I->click(\CheckoutChangeQuantityProductPage::$checkoutButton);
		$I->scrollTo(\CheckoutChangeQuantityProductPage::$bankTransfer);
		$I->click(\CheckoutChangeQuantityProductPage::$bankTransfer);
		$I->waitForElement(\CheckoutChangeQuantityProductPage::$termAndConditions,30);
		$I->click(\CheckoutChangeQuantityProductPage::$termAndConditions);
        $I->scrollTo(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
		$I->click(\CheckoutChangeQuantityProductPage::$checkoutFinalStep);
	}

    public function searchUser($name, $functionName = 'filter')
    {
        $I = $this;
        $I->wantTo('Search the User ');
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->waitForText(\UserManagerJoomla3Page::$namePage, 30, \UserManagerJoomla3Page::$headPage);
        $I->filterListBySearching($name, $functionName = \UserManagerJoomla3Page::$filter);
    }

    public function deleteUser($name, $deleteJoomlaUser = true)
    {
        $I = $this;
        $I->amOnPage(\UserManagerJoomla3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->searchUser($name);
        $I->see($name, \UserManagerJoomla3Page::$firstResultRow);
        $I->click(\UserManagerJoomla3Page::$selectFirst);
        $I->click(\UserManagerJoomla3Page::$deleteButton);

        if ($deleteJoomlaUser) {
            $I->acceptPopup();
        } else {
            $I->cancelPopup();
        }

        $I->dontSee($name, \UserManagerJoomla3Page::$firstResultRow);
    }
}