<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class CouponManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CouponManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    /**
     * Function to Add a new Coupon Code
     *
     * @param   string $couponCode Code for the new Coupon
     * @param   string $couponValueIn Value In for the new Coupon
     * @param   string $couponValue Value for the Coupon
     * @param   string $couponType Type of the Coupon
     * @param   string $couponLeft No of Coupons Left in the System
     *
     * @return void
     */
    public function addCoupon($couponCode = 'TestCoupon', $couponValueIn = 'Total', $couponValue = '100', $couponType = 'Globally', $couponLeft = '10', $startDate, $endDate)
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $couponManagerPage = new \CouponManagerJ3Page;
        $I->click(\CouponManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URLNew);
        $I->fillField(\CouponManagerJ3Page::$couponCode, $couponCode);
        $I->fillField(\CouponManagerJ3Page::$couponValue, $couponValue);
        $I->fillField(\CouponManagerJ3Page::$startDate, $startDate);
        $I->fillField(\CouponManagerJ3Page::$endDate, $endDate);
        $I->fillField(\CouponManagerJ3Page::$couponLeft, $couponLeft);

        $I->click(\CouponManagerJ3Page::$couponValueInDropDown);
        $I->click($couponManagerPage->couponValueIn($couponValueIn));

        $I->wait(3);
        $I->click(\CouponManagerJ3Page::$couponTypeDropdown);
        $I->click($couponManagerPage->couponType($couponType));

        $I->click(\CouponManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CouponManagerJ3Page::$selectContainer, 60);
        $I->see(\CouponManagerJ3Page::$saveSuccess, \CouponManagerJ3Page::$selectorSuccess);
        $I->seeElement(['link' => $couponCode]);
    }

    public function addCouponWithUser($couponCode = 'TestCoupon', $couponValueIn = 'Total', $couponValue = '100', $couponType = 'User Specific', $couponLeft = '10', $nameUser)
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $couponManagerPage = new \CouponManagerJ3Page;
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $I->click(\CouponManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URLNew);
        $I->fillField(\CouponManagerJ3Page::$couponCode, $couponCode);
        $I->fillField(\CouponManagerJ3Page::$couponValue, $couponValue);
        $I->fillField(\CouponManagerJ3Page::$couponLeft, $couponLeft);

        $I->click(\CouponManagerJ3Page::$couponValueInDropDown);
        $I->click($couponManagerPage->couponValueIn($couponValueIn));

        $I->click(\CouponManagerJ3Page::$couponTypeDropdown);
        $I->click($couponManagerPage->couponType($couponType));

        $I->click(\CouponManagerJ3Page::$userDropDown);
        $I->fillField(\CouponManagerJ3Page::$searchUser, $nameUser);
        $I->waitForElement($couponManagerPage->returnUser($nameUser), 60);
        $I->click($couponManagerPage->returnUser($nameUser));
        $I->click(\CouponManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CouponManagerJ3Page::$selectContainer, 60);
        $I->see(\CouponManagerJ3Page::$saveSuccess, \CouponManagerJ3Page::$selectorSuccess);
        $I->seeElement(['link' => $couponCode]);
    }

    public function addCouponMissingCouponCode($couponValueIn = 'Total', $couponValue = '100', $couponType = 'Globally', $couponLeft = '10')
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $couponManagerPage = new \CouponManagerJ3Page;
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $I->click(\CouponManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URLNew);
        $I->fillField(\CouponManagerJ3Page::$couponValue, $couponValue);
        $I->fillField(\CouponManagerJ3Page::$couponLeft, $couponLeft);

        $I->click(\CouponManagerJ3Page::$couponValueInDropDown);
        $I->click($couponManagerPage->couponValueIn($couponValueIn));

        $I->click(\CouponManagerJ3Page::$couponTypeDropdown);
        $I->click($couponManagerPage->couponType($couponType));

        $I->click(\CouponManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CouponManagerJ3Page::$selectContainer, 60);
    }

    public function checkCancelButton()
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
//        $couponManagerPage = new \CouponManagerJ3Page;
        $I->click(\CouponManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URLNew);
        $I->click(\CouponManagerJ3Page::$cancelButton);
    }

    /**
     * Function to Edit Coupon Code
     *
     * @param   string $couponCode Coupon Code which is to be Edited
     * @param   string $newCouponCode New Coupon Code for the current one
     *
     * @return void
     */
    public function editCoupon($couponCode = 'Current Code', $newCouponCode = 'Testing New')
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');

        $I->searchCoupon($couponCode);
        $I->wait(3);
        $value = $I->grabTextFrom($couponCode, \CouponManagerJ3Page::$couponId);
        $URLEdit = \GiftCardManagerPage::$URLEdit . $value;

        $I->click(['link' => $couponCode]);
//        $I->see($couponCode, \CouponManagerJ3Page::$firstResultRow);
//        $I->click(\CouponManagerJ3Page::$selectFirst);
//        $I->click(\CouponManagerJ3Page::$editButton);
        $I->checkForPhpNoticesOrWarnings($URLEdit);
        $I->waitForElement(\CouponManagerJ3Page::$couponCode, 20);
        $I->fillField(\CouponManagerJ3Page::$couponCode, $newCouponCode);
        $I->click(\CouponManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CouponManagerJ3Page::$selectContainer, 60);
        $I->see(\CouponManagerJ3Page::$saveSuccess, \CouponManagerJ3Page::$selectorSuccess);
        $I->seeElement(['link' => $newCouponCode]);
    }

    public function deleteCouponWithButton()
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $I->click(\CouponManagerJ3Page::$choiAllCoupons);
        $I->click(\CouponManagerJ3Page::$deleteButton);
        $I->waitForElement(\CouponManagerJ3Page::$selectContainer, 60);
        $I->see(\CouponManagerJ3Page::$deleteSuccess, \CouponManagerJ3Page::$selectorSuccess);
    }

    /**
     * Function to delete the Coupon
     *
     * @param   string $couponCode Code of the Coupon which is to be Deleted
     *
     * @return void
     */
    public function deleteCoupon($couponCode)
    {
        $this->delete(new \CouponManagerJ3Page, $couponCode, \CouponManagerJ3Page::$firstResultRow, \CouponManagerJ3Page::$selectFirst);
    }

    /**
     * Function to Search for a Coupon Code
     *
     * @param   string $couponCode Code of the Coupon for which we are searching
     * @param   string $functionName Name of the function after which Search is being called
     *
     * @return void
     */
    public function searchCoupon($couponCode, $functionName = 'Search')
    {
        $this->search(new \CouponManagerJ3Page, $couponCode, \CouponManagerJ3Page::$firstResultRow, $functionName);
    }

    /**
     * Function to Change State of a Coupon
     *
     * @param   String $couponCode Name of the Card for which the state is to be Changed
     *
     * @return void
     */
    public function changeCouponState($couponCode)
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->filterListBySearching($couponCode);
        $I->wait(3);
        $I->seeElement(['link' => $couponCode]);
        $I->click(\CouponManagerJ3Page::$xPathState);
    }

    public function changeCouponStateUnpublishButton($couponCode)
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(['link' => 'ID']);
        $I->see($couponCode, \CouponManagerJ3Page::$firstResultRow);
        $I->click(\CouponManagerJ3Page::$selectFirst);
        $I->click(\CouponManagerJ3Page::$unpublishButton);
        $I->see(\CouponManagerJ3Page::$unpublishSuccess, \CouponManagerJ3Page::$selectorSuccess);
    }

    public function changeCouponStatePublishButton($couponCode)
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(['link' => 'ID']);
        $I->see($couponCode, \CouponManagerJ3Page::$firstResultRow);
        $I->click(\CouponManagerJ3Page::$selectFirst);
        $I->click(\CouponManagerJ3Page::$publishButton);
        $I->see(\CouponManagerJ3Page::$publishSuccess, \CouponManagerJ3Page::$selectorSuccess);
    }

    public function checkEditButton()
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $I->click(\CouponManagerJ3Page::$editButton);
        $I->acceptPopup();
    }

    /**
     * Function check Delete button
     */
    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $I->click(\CouponManagerJ3Page::$deleteButton);
        $I->acceptPopup();
    }

    /**
     * Function check publish button without choice any gift card
     */
    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $I->click(\CouponManagerJ3Page::$publishButton);
        $I->acceptPopup();
    }

    /**
     * Function check unpublish button without choice any gift card
     */
    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CouponManagerJ3Page::$URL);
        $I->click(\CouponManagerJ3Page::$unpublishButton);
        $I->acceptPopup();
    }


    /**
     *
     * Function get status of coupon
     *
     * @param $couponCode
     * @return string
     */
    public function getCouponState($couponCode)
    {
        $result = $this->getState(new \CouponManagerJ3Page, $couponCode, \CouponManagerJ3Page::$firstResultRow, \CouponManagerJ3Page::$couponState);

        return $result;
    }
}
