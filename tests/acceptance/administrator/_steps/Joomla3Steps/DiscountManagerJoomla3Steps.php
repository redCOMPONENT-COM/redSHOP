<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class DiscountManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class DiscountManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    /**
     * Function to Add a New Discount
     *
     * @param   string $name Discount name
     * @param   string $amount Discount Amount
     * @param   string $discountAmount Amount on the Discount
     * @param   string $shopperGroup Group for the Shopper
     * @param   string $discountType Type of Discount
     *
     * @return void
     */
    public function addDiscount($name, $amount, $discountAmount, $shopperGroup, $discountType)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $verifyAmount = 'DKK ' . $amount . ',00';
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->fillField(\DiscountManagerJ3Page::$name, $name);
        $I->fillField(\DiscountManagerJ3Page::$amount, $amount);
        $I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
        $I->click(['id' => "s2id_discount_type"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $discountType);
        $I->waitForElement(['id' => "select2-results-2"], 30);
        $I->click(['id' => "select2-results-2"]);
        $I->click(['id' => "s2id_shopper_group_id"]);
        $I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
        $I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
        $I->click('Save & Close');
        $I->waitForText('Discount Detail Saved', 60, ['id' => 'system-message-container']);
        $I->filterListBySearching($name, ['id' => 'name_filter']);
        $I->seeElement(['link' => $verifyAmount]);
    }

    public function addDiscountSave($name, $amount, $discountAmount, $shopperGroup, $discountType)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $verifyAmount = 'DKK ' . $amount . ',00';
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->fillField(\DiscountManagerJ3Page::$name, $name);
        $I->fillField(\DiscountManagerJ3Page::$amount, $amount);
        $I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
        $I->click(['id' => "s2id_discount_type"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $discountType);
        $I->waitForElement(['id' => "select2-results-2"], 30);
        $I->click(['id' => "select2-results-2"]);
        $I->click(['id' => "s2id_shopper_group_id"]);
        $I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
        $I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
        $I->click('Save');
        $I->waitForText('Discount Detail Saved', 60, ['id' => 'system-message-container']);
    }

    public function addDiscountCancel()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->click('Cancel');
    }

    public function addDiscountStartThanEnd($name, $amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $verifyAmount = 'DKK ' . $amount . ',00';
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->fillField(\DiscountManagerJ3Page::$name, $name);
        $I->fillField(\DiscountManagerJ3Page::$amount, $amount);
        $I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountManagerJ3Page::$startDate, $endDate);
        $I->fillField(\DiscountManagerJ3Page::$endDate, $startDate);
        $I->click(['id' => "s2id_discount_type"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $discountType);

        $I->waitForElement(['id' => "select2-results-2"], 30);
        $I->click(['id' => "select2-results-2"]);
        $I->click(['id' => "s2id_shopper_group_id"]);
        $I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
        $I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
        $I->click('Save');
        $I->acceptPopup();
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
    }

    public function addDiscountWithAllFieldsEmpty()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->click('Save');
        $I->acceptPopup();
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
    }

    public function addDiscountMissingName($amount, $discountAmount, $shopperGroup, $discountType, $startDate, $endDate)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $verifyAmount = 'DKK ' . $amount . ',00';
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->fillField(\DiscountManagerJ3Page::$amount, $amount);
        $I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountManagerJ3Page::$startDate, $startDate);
        $I->fillField(\DiscountManagerJ3Page::$endDate, $endDate);
        $I->click(['id' => "s2id_discount_type"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $discountType);
        $I->waitForElement(['id' => "select2-results-2"], 30);
        $I->click(['id' => "select2-results-2"]);
        $I->click(['id' => "s2id_shopper_group_id"]);
        $I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
        $I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
        $I->click('Save');
        $I->acceptPopup();
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
    }

    public function addDiscountMissingAmount($name, $amount, $shopperGroup, $discountType, $startDate, $endDate)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $verifyAmount = 'DKK ' . $amount . ',00';
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->fillField(\DiscountManagerJ3Page::$name, $name);
        $I->fillField(\DiscountManagerJ3Page::$amount, $amount);
        $I->fillField(\DiscountManagerJ3Page::$startDate, $endDate);
        $I->fillField(\DiscountManagerJ3Page::$endDate, $startDate);
        $I->click(['id' => "s2id_discount_type"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $discountType);

        $I->waitForElement(['id' => "select2-results-2"], 30);
        $I->click(['id' => "select2-results-2"]);
        $I->click(['id' => "s2id_shopper_group_id"]);
        $I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
        $I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
        $I->click('Save');
        $I->acceptPopup();
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
    }

    public function addDiscountMissingShopperGroups($name, $amount, $discountAmount, $discountType, $startDate, $endDate)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $verifyAmount = 'DKK ' . $amount . ',00';
        $I->checkForPhpNoticesOrWarnings();
        $I->click('New');
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->fillField(\DiscountManagerJ3Page::$name, $name);
        $I->fillField(\DiscountManagerJ3Page::$amount, $amount);
        $I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
        $I->fillField(\DiscountManagerJ3Page::$startDate, $endDate);
        $I->fillField(\DiscountManagerJ3Page::$endDate, $startDate);
        $I->click(['id' => "s2id_discount_type"]);
        $I->fillField(['id' => "s2id_autogen2_search"], $discountType);
        $I->waitForElement(['id' => "select2-results-2"], 30);
        $I->click(['id' => "select2-results-2"]);
        $I->click('Save');
        $I->acceptPopup();
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
    }

    /**
     * Function to edit an existing Discount
     *
     * @param   string $name Discount name
     * @param   string $amount Amount for the Discount
     * @param   string $newAmount New Amount for the Discount
     *
     * @return void
     */
    public function editDiscount($name = '', $amount = '100', $newAmount = '1000')
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $verifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $amount . ',00';
        $newVerifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $newAmount . ',00';
        $I->filterListBySearching($name, ['id' => 'name_filter']);
        $I->executeJS('window.scrollTo(0,0)');
        $I->waitForElement(['link' => $verifyAmount]);
        $I->click(['link' => $verifyAmount]);
        $I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
        $I->fillField(\DiscountManagerJ3Page::$amount, $newAmount);
        $I->click('Save & Close');
        $I->waitForText('Discount Detail Saved', 60, ['id' => 'system-message-container']);
        $I->click('Reset');
        $I->filterListBySearching($name, ['id' => 'name_filter']);
        $I->seeElement(['link' => $newVerifyAmount]);
    }

    /**
     * Function to change State of a Discount
     *
     * @param   string $discountName Discount name
     *
     * @return void
     */
    public function changeDiscountState($discountName)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->searchDiscount($discountName);
        $I->wait(3);
        $I->see($discountName, \DiscountManagerJ3Page::$discountNamePath);
        $I->click(\DiscountManagerJ3Page::$discountState);

    }

    /**
     * Function to change State of a Discount
     *
     * @param   string $discountName Discount name
     *
     * @return void
     */
    public function unpublishDiscountStateButton($discountName)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->searchDiscount($discountName);
        $I->wait(3);
        $I->see($discountName, \DiscountManagerJ3Page::$discountNamePath);

        $I->click(\DiscountManagerJ3Page::$discountCheckBox);
        $I->click('Unpublish');
        $I->wait(3);
        $I->waitForText('Discount Detail UnPublished Successfully', 60, ['id' => 'system-message-container']);
    }

    public function publishDiscountStateButton($discountName)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->searchDiscount($discountName);
        $I->wait(3);
        $I->see($discountName, \DiscountManagerJ3Page::$discountNamePath);
        $I->click(\DiscountManagerJ3Page::$discountCheckBox);
        $I->click('Publish');
        $I->wait(3);
        $I->waitForText('Discount Detail Published Successfully', 60, ['id' => 'system-message-container']);
    }

    public function unpublishAllDiscount()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click(\DiscountManagerJ3Page::$CheckAllDiscount);
        $I->click('Unpublish');
        $I->wait(3);
        $I->waitForText('Discount Detail UnPublished Successfully', 60, ['id' => 'system-message-container']);
    }

    public function publishAllDiscount()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click(\DiscountManagerJ3Page::$CheckAllDiscount);
        $I->click('Publish');
        $I->wait(3);
        $I->waitForText('Discount Detail Published Successfully', 60, ['id' => 'system-message-container']);
    }

    public function deleteAllDiscount()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click(\DiscountManagerJ3Page::$CheckAllDiscount);
        $I->click('Delete');
        $I->wait(3);
        $I->waitForText('Discount Detail Deleted Successfully', 60, ['id' => 'system-message-container']);
    }

    public function getDiscountState($discountName)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click('Reset');
        $I->wait(5);
        $I->searchDiscount($discountName);
        $I->wait(5);
        $I->see($discountName, \DiscountManagerJ3Page::$discountNamePath);
        $text = $I->grabAttributeFrom(\DiscountManagerJ3Page::$discountState, 'onclick');
        if (strpos($text, 'unpublish') > 0) {
            $result = 'published';
        } else {
            $result = 'unpublished';
        }
        return $result;
    }

    /**
     * Function to Search for a Discount
     *
     * @param   string $amount Amount of the Discount
     * @param   string $functionName Name of the function After Which search is being Called
     *
     * @return void
     */
    public function searchDiscount($discountName)
    {
        $I = $this;
        $I->wantTo('Search Discount ');
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->waitForText('Product Discount Management', 30, ['xpath' => "//h1"]);
        $I->filterListBySearchingDiscount($discountName);
    }

    /**
     * Function to Delete Discount
     *
     * @param   string $name Discount name
     * @param   String $amount Amount of the Discount which is to be Deleted
     *
     * @return void
     */
    public function deleteDiscount($name, $amount)
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click('Reset');
        $I->filterListBySearching($name, ['id' => 'name_filter']);
        $I->click(\DiscountManagerJ3Page::$selectFirst);
        $I->click('Delete');
        $I->waitForText('Discount Detail Deleted Successfully', 60, ['id' => 'system-message-container']);
        $I->dontSeeElement(['link' => $name]);
    }

    public function checkEditButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click('Edit');
        $I->acceptPopup();
        $I->see("Product Discount Management", '.page-title');
    }

    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click('Delete');
        $I->acceptPopup();
        $I->see("Product Discount Management", '.page-title');
    }

    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click('Publish');
        $I->acceptPopup();
        $I->see("Product Discount Management", '.page-title');
    }

    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\DiscountManagerJ3Page::$URL);
        $I->click('Unpublish');
        $I->acceptPopup();
        $I->see("Product Discount Management", '.page-title');
    }


}
