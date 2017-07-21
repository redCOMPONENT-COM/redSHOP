<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageCouponAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageCouponAdministratorCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->couponCode = $this->faker->bothify('Coupon Code ?##?');
        $this->updateCouponCode = 'New ' . $this->couponCode;
        $this->couponValueIn = 'Total';
        $this->couponValue = '100';
        $this->couponType = 'Globally';
        $this->couponTypeSpecific = "User Specific";
        $this->couponLeft = '10';
        $this->startDate = "16-06-2017";
        $this->endDate = "07-07-2017";
        $this->userName = $this->faker->bothify('ManageUserAdministratorCest ?##?');
        $this->password = $this->faker->bothify('Password ?##?');
        $this->email = $this->faker->email;
        $this->shopperGroup = 'Default Private';
        $this->group = 'Public';
        $this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
        $this->updateFirstName = 'Updating ' . $this->firstName;
        $this->lastName = 'Last';
    }

    /**
     * Function to Test User Creation in Backend
     *
     */
    public function createUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName);
        $I->searchUser($this->firstName);
    }
    /**
     * Function to Test Coupon Creation in Backend
     *
     */
    public function createCoupon(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Coupon creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->addCoupon($this->couponCode, $this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft, $this->startDate, $this->endDate);
        $I->searchCoupon($this->couponCode);
    }

    /**
     *
     * Function add Coupon missing coupon code
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addCouponMissingCouponCode(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Coupon creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->addCouponMissingCouponCode($this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft);
    }

    /**
     *
     * Function check Cancel button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkCancelButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Check Cancel button in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->checkCancelButton();
        $I->see('Coupon Management', '.page-title');
    }


    /**
     *
     * Function change state of coupon is unpublish
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends createCoupon
     */
    public function changeCouponStateUnbuplish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in unpublish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->changeCouponState($this->couponCode);
        $I->verifyState('unpublished', $I->getCouponState($this->couponCode), 'State Must be Unpublished');
    }

    /**
     *
     * Function change state of coupon is publish
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeCouponStateUnbuplish
     *
     */
    public function changeCouponStatePuplish(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in publish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->changeCouponState($this->couponCode);
        $I->verifyState('published', $I->getCouponState($this->couponCode), 'State Must be Unpublished');
    }

    /**
     *
     * Function change coupon state is  unpublish with unpublish button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeCouponStatePuplish
     *
     */
    public function changeCouponStateUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in publish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->changeCouponStateUnpublishButton($this->couponCode);
        $I->verifyState('unpublished', $I->getCouponState($this->couponCode), 'State Must be Unpublished');
    }

    /**
     *
     * Function change coupon state is  publish with unpublish button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends changeCouponStateUnpublishButton
     *
     */
    public function changeCouponStatePublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in publish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->changeCouponStatePublishButton($this->couponCode);
        $I->verifyState('published', $I->getCouponState($this->couponCode), 'State Must be Unpublished');
    }

    /**
     *
     * Function check Edit button at coupon management page
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkEditButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in publish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->checkEditButton();
        $I->see(\CouponManagerJ3Page::$namePageManagement, \CouponManagerJ3Page::$selectorNamePage);
    }

    /**
     *
     * Function check Delete button at coupon management page
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkDeleteButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in publish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->checkDeleteButton();
        $I->see(\CouponManagerJ3Page::$namePageManagement, \CouponManagerJ3Page::$selectorNamePage);
    }

    /**
     *
     * Function check Publish button at coupon management page
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkPublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in publish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->checkPublishButton();
        $I->see(\CouponManagerJ3Page::$namePageManagement, \CouponManagerJ3Page::$selectorNamePage);
    }

    /**
     *
     * Function check Unpublish button at coupon management page
     *
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function checkUnpublishButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in publish  in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->checkUnpublishButton();
        $I->see(\CouponManagerJ3Page::$namePageManagement, \CouponManagerJ3Page::$selectorNamePage);
    }

    /**
     * Function to Test Coupon Updation in the Administrator
     *
     * @depends changeCouponStatePublishButton
     */
    public function updateCoupon(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Coupon gets updated in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Update Existing Coupon');
        $I->editCoupon($this->couponCode, $this->updateCouponCode);
        $I->searchCoupon($this->updateCouponCode);
    }

    /**
     * Function to Test Coupon Deletion
     *
     * @depends updateCoupon
     */
    public function deleteCoupon(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of Coupon in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Delete a Coupon');
        $I->deleteCoupon($this->updateCouponCode);
        $I->searchCoupon($this->updateCouponCode, 'Delete');
    }

    /**
     * Function to Test Coupon Creation in Backend
     *
     */
    public function createCouponForDelete(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Coupon creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->addCoupon($this->couponCode, $this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft, $this->startDate, $this->endDate);
        $I->searchCoupon($this->couponCode);
    }

    /**
     *
     * Delete all coupons with button
     *
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends createCouponForDelete
     *
     */
    public function deleteCouponWithButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Coupon creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->deleteCouponWithButton();
        $I->searchCoupon($this->couponCode, 'Delete');
    }

    /**
     *
     * Function to Test coupon code with specific user  in Backend
     * @param AcceptanceTester $I
     * @param $scenario
     *
     * @depends createUser
     */
    public function addCouponWithUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I->doAdministratorLogin();
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->addCouponWithUser($this->couponCode, $this->couponValueIn, $this->couponValue, $this->couponTypeSpecific, $this->couponLeft, $this->userName);
        $I->searchCoupon($this->couponCode);
    }
}
