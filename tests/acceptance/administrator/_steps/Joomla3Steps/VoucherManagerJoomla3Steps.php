<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class VoucherManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class VoucherManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    /**
     * Function to Create a new Voucher
     *
     * @param   string $code Code for the Voucher
     * @param   string $amount Amount of the Voucher
     * @param   string $count Count of the Vouchers
     *
     * @return void
     */
    public function addVoucher($code, $amount, $count, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URLNew);
        $I->fillField(\VoucherManagerPage::$voucherCode, $code);
        $I->fillField(\VoucherManagerPage::$voucherAmount, $amount);

        // @todo: we need a generic function to select options in a Select2 multiple option field
        $I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
        $I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
        $I->click(\VoucherManagerPage::$waitProduct);
        // end of select2

        $I->fillField(\VoucherManagerPage::$voucherLeft, $count);
        $I->click(\VoucherManagerPage::$saveCloseButton);
        $I->waitForElement(\VoucherManagerPage::$messageContainer, 60);
//        $I->scrollTo(['css' => '.alert-success']);
        $I->see(\VoucherManagerPage::$messageSaveSuccessVocher, \VoucherManagerPage::$selectorSuccess);
        $I->seeElement(['link' => $code]);
    }

    public function addVoucherSave($code, $amount, $voucherStartDate, $voucherEndDate, $count, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URLNew);
        $I->fillField(\VoucherManagerPage::$voucherCode, $code);
        $I->fillField(\VoucherManagerPage::$voucherAmount, $amount);

        // @todo: we need a generic function to select options in a Select2 multiple option field
        $I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
        $I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
        $I->click(\VoucherManagerPage::$waitProduct);
        // end of select2

        $I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherStartDate);
        $I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherEndDate);
        $I->fillField(\VoucherManagerPage::$voucherLeft, $count);
        $I->click(\VoucherManagerPage::$saveButton);
        $I->see(\VoucherManagerPage::$messageSaveSuccessVocher, \VoucherManagerPage::$selectorSuccess);
//        $I->scrollTo(['css' => '.alert-success']);
        $I->see(\VoucherManagerPage::$messageSaveSuccessVocher, \VoucherManagerPage::$selectorSuccess);
    }

    /**
     * function check cancel button
     */
    public function cancelButton()
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URLNew);
        $I->click(\VoucherManagerPage::$cancelButton);
    }

    public function addStartMoreThanEnd($code, $amount, $voucherStartDate, $voucherEndDate, $count, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URLNew);
        $I->fillField(\VoucherManagerPage::$voucherCode, $code);
        $I->fillField(\VoucherManagerPage::$voucherAmount, $amount);

        // @todo: we need a generic function to select options in a Select2 multiple option field
        $I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
        $I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
        $I->click(\VoucherManagerPage::$waitProduct);
        // end of select2

        $I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherEndDate);
        $I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherStartDate);
        $I->fillField(\VoucherManagerPage::$voucherLeft, $count);
        $I->click(\VoucherManagerPage::$saveButton);
        $I->acceptPopup();
    }

    public function addVoucherMissingCode($amount = '100', $voucherStartDate, $voucherEndDate, $count, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URLNew);
        $I->fillField(\VoucherManagerPage::$voucherAmount, $amount);
        $I->fillField(\VoucherManagerPage::$voucherCode, "");
        // @todo: we need a generic function to select options in a Select2 multiple option field
        $I->fillField(\VoucherManagerPage::$fillProduct, $nameProduct);
        $I->waitForElement(\VoucherManagerPage::$waitProduct, 60);
        $I->click(\VoucherManagerPage::$waitProduct);
        // end of select2

        $I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherEndDate);
        $I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherStartDate);
        $I->fillField(\VoucherManagerPage::$voucherLeft, $count);
        $I->click(\VoucherManagerPage::$saveButton);
        $I->see(\VoucherManagerPage::$invalidCode, \VoucherManagerPage::$xPathInvalid);
    }

    public function addVoucherMissingProducts($code, $amount, $voucherStartDate, $voucherEndDate, $count)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URLNew);
        $I->fillField(\VoucherManagerPage::$voucherCode, $code);
        $I->fillField(\VoucherManagerPage::$voucherAmount, $amount);
        $I->fillField(\VoucherManagerPage::$voucherStartDate, $voucherEndDate);
        $I->fillField(\VoucherManagerPage::$voucherEndDate, $voucherStartDate);
        $I->fillField(\VoucherManagerPage::$voucherLeft, $count);
        $I->click(\VoucherManagerPage::$saveButton);
        $I->see(\VoucherManagerPage::$invalidProduct, \VoucherManagerPage::$xPathInvalid);
    }

    /**
     * Function to edit a Voucher Code
     *
     * @param   String $voucherCode Code for the Current Voucher
     * @param   String $voucherNewCode New Code for the Voucher
     *
     * @return void
     */
    public function editVoucher($voucherCode, $voucherNewCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->waitForElement(['link' => $voucherCode], 60);
        $I->searchVoucherCode($voucherCode);
        $I->wait(3);
        $I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
        $value = $I->grabTextFrom(\VoucherManagerPage::$voucherId);
        $URLEdit = \VoucherManagerPage::$URLEdit . $value;
        $I->click(['link' => $voucherCode]);
        $I->checkForPhpNoticesOrWarnings($URLEdit);

        $I->fillField(\VoucherManagerPage::$voucherCode, $voucherNewCode);
        $I->click(\VoucherManagerPage::$saveCloseButton);
        $I->waitForElement(\VoucherManagerPage::$messageContainer, 60);
        $I->see(\VoucherManagerPage::$messageSaveSuccessVocher, \VoucherManagerPage::$selectorSuccess);
        $I->seeElement(['link' => $voucherNewCode]);
    }

    public function editVoucherMissingName($voucherCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->waitForElement(['link' => $voucherCode], 60);
        $I->searchVoucherCode($voucherCode);
        $I->wait(3);
        $I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
        $value = $I->grabTextFrom(\VoucherManagerPage::$voucherId);
        $URLEdit = \VoucherManagerPage::$URLEdit . $value;
        $I->click(['link' => $voucherCode]);
        $I->checkForPhpNoticesOrWarnings($URLEdit);
        $I->fillField(\VoucherManagerPage::$voucherCode, "");
        $I->click(\VoucherManagerPage::$saveCloseButton);
        $I->see(\VoucherManagerPage::$invalidCode, \VoucherManagerPage::$xPathInvalid);
    }

    public function checkCloseButton($voucherCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->waitForElement(['link' => $voucherCode], 60);
        $I->searchVoucherCode($voucherCode);
        $I->wait(3);
        $I->see($voucherCode, \VoucherManagerPage::$voucherResultRow);
        $value = $I->grabTextFrom(\VoucherManagerPage::$voucherId);
        $URLEdit = \VoucherManagerPage::$URLEdit . $value;
        $I->click(['link' => $voucherCode]);
        $I->checkForPhpNoticesOrWarnings($URLEdit);
//        $I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager Edit');
        $I->fillField(\VoucherManagerPage::$voucherCode, "");
        $I->click(\VoucherManagerPage::$closeButton);
        $I->filterListBySearching($voucherCode, \VoucherManagerPage::$filter);
        $I->seeElement(['link' => $voucherCode]);
    }

//    public function editVoucherMissingProduct($voucherCode)
//    {
//        $I = $this;
//        $I->amOnPage(\VoucherManagerPage::$URL);
//        $I->waitForElement(['link' => $voucherCode], 60);
//        $I->click(\VoucherManagerPage::$voucherCheck);
//        $I->click("Edit");
//        $I->verifyNotices(false, $this->checkForNotices(), 'Voucher Manager Edit');
//        $I->fillField(\VoucherManagerPage::$voucherCode, "");
//        $I->click('Save & Close');
//        $I->see('Invalid field:  Voucher Code:','.system-message-container');
//    }

    public function checkEditButton()
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$editButton);
        $I->acceptPopup();
    }

    public function checkDeleteButton()
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$deleteButton);
        $I->acceptPopup();
    }

    public function checkPublishButton()
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$publishButton);
        $I->acceptPopup();
    }

    public function checkUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkForPhpNoticesOrWarnings(\VoucherManagerPage::$URL);
        $I->click(\VoucherManagerPage::$unpublishButton);
        $I->acceptPopup();
    }


    /**
     * Function to Delete a Voucher
     *
     * @param   String $voucherCode Code of the voucher which is to be deleted
     *
     * @return void
     */
    public function deleteVoucher($voucherCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->waitForElement(\VoucherManagerPage::$voucherResultRow, 30);
        $I->fillField(\VoucherManagerPage::$voucherSearchField, $voucherCode);
        $I->pressKey(\VoucherManagerPage::$voucherSearchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(['link' => $voucherCode]);
        $I->click(\VoucherManagerPage::$voucherCheck);
        $I->click(\VoucherManagerPage::$deleteButton);
        $I->dontSeeElement(['link' => $voucherCode]);
    }

    /**
     * Function to Change Voucher State
     *
     * @param   String $voucherCode Code of the voucher for which the state is to be changed
     * @param   String $state State to which we want it to be changed to
     *
     * @return void
     */
    public function changeVoucherState($voucherCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->filterListBySearching($voucherCode, \VoucherManagerPage::$filter);
        $I->wait(3);
        $I->seeElement(['link' => $voucherCode]);
        $I->click(\VoucherManagerPage::$xPathStatus);
    }

    public function changeVoucherUnpublishButton($voucherCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->filterListBySearching($voucherCode, \VoucherManagerPage::$filter);
        $I->wait(3);
        $I->checkAllResults();
        $I->click(\VoucherManagerPage::$unpublishButton);
        $I->wait(3);
        $I->see(\VoucherManagerPage::$messageUnpublishSuccess, \VoucherManagerPage::$selectorSuccess);
    }

    public function changeVoucherPublishButton($voucherCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->filterListBySearching($voucherCode, \VoucherManagerPage::$filter);
        $I->wait(3);
        $I->checkAllResults();
        $I->click(\VoucherManagerPage::$publishButton);
        $I->wait(3);
        $I->see(\VoucherManagerPage::$messagePublishSuccess, \VoucherManagerPage::$selectorSuccess);
    }


    public function changeAllVoucherUnpublishButton()
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\VoucherManagerPage::$unpublishButton);
        $I->wait(3);
        $I->see(\VoucherManagerPage::$messageSuccess, \VoucherManagerPage::$selectorSuccess);
    }

    public function changeAllVoucherPublishButton()
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\VoucherManagerPage::$publishButton);
        $I->wait(3);
        $I->see(\VoucherManagerPage::$messageSuccess, \VoucherManagerPage::$selectorSuccess);
    }

    public function deleteAllVoucher($updatedRandomVoucherCode)
    {
        $I = $this;
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\VoucherManagerPage::$deleteButton);
        $I->wait(3);
        $I->see(\VoucherManagerPage::$messageSuccess, \VoucherManagerPage::$selectorSuccess);
        $I->dontSeeElement(['link' => $updatedRandomVoucherCode]);
    }

    /**
     * Function to return the Result of the State of a Voucher
     *
     * @param   String $voucherCode Code of the Voucher for which State is tobe Determined
     *
     * @return string  State of the Voucher
     */
    public function getVoucherState($voucherCode)
    {
        $result = $this->getState(new \VoucherManagerPage, $voucherCode, \VoucherManagerPage::$voucherResultRow, \VoucherManagerPage::$voucherStatePath);
        return $result;
    }

    public function searchVoucherCode($voucherCode)
    {
        $I = $this;
        $I->wantTo('Search the Category');
        $I->amOnPage(\VoucherManagerPage::$URL);
        $I->waitForText(\VoucherManagerPage::$namePageManagement, 30, \VoucherManagerPage::$headPageName);
        $I->filterListBySearching($voucherCode, \VoucherManagerPage::$filter);
    }
}
