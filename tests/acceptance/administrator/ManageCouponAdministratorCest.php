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
        $this->couponCodeUser=$this->faker->bothify('CouponCodeUser?##');
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

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
    /**
     * Function to Test User Creation in Backend
     *
     */
    public function createUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
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
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->addCouponMissingCouponCode($this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft);
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
    public function changeCouponState(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon unpublish with clicks on icon  in Administrator');
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->changeCouponState($this->couponCode);
        $I->verifyState('unpublished', $I->getCouponState($this->couponCode), 'State Must be Unpublished');

        $I->wantTo('Change state of Coupon publish with clicks on icon  in Administrator');
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
     * @depends changeCouponState
     *
     */
    public function changeCouponStateButton(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Change state of Coupon in unpublish with button  in Administrator');
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->changeCouponStateUnpublishButton($this->couponCode);
        $I->verifyState('unpublished', $I->getCouponState($this->couponCode), 'State Must be Unpublished');

        $I->wantTo('Change state of Coupon in publish with button  in Administrator');
        $I->changeCouponStatePublishButton($this->couponCode);
        $I->verifyState('published', $I->getCouponState($this->couponCode), 'State Must be Unpublished');
    }

    /**
     * Function to Test Coupon Updation in the Administrator
     *
     * @depends changeCouponStateButton
     */
    public function updateCoupon(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test if Coupon gets updated in Administrator');
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
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Coupon');
        $I->addCoupon($this->updateCouponCode, $this->couponValueIn, $this->couponValue, $this->couponType, $this->couponLeft, $this->startDate, $this->endDate);
        $I->searchCoupon($this->updateCouponCode);

        $I->wantTo('Delete  a Coupon');
        $I->deleteCouponWithButton();
        $I->searchCoupon($this->updateCouponCode, 'Delete');
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
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->addCouponWithUser($this->couponCodeUser, $this->couponValueIn, $this->couponValue, $this->couponTypeSpecific, $this->couponLeft, $this->userName);
        $I->searchCoupon($this->couponCodeUser);
    }

    public function checkButtons(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test to validate different buttons on Couppon Views');
        $I = new AcceptanceTester\CouponManagerJoomla3Steps($scenario);
        $I->checkButtons('edit');
        $I->checkButtons('cancel');
        $I->checkButtons('publish');
        $I->checkButtons('unpublish');
    }
}
