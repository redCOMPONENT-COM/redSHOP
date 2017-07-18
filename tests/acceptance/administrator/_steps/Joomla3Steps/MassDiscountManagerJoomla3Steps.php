<?php
/**
 */

namespace AcceptanceTester;


class MassDiscountManagerJoomla3Steps extends AdminManagerJoomla3Steps
{

    public function addMassDiscount($massDiscountName, $amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);

        $I->waitForElement(['id' => "jform_start_date_img"], 30);

        $I->click(['id' => "jform_start_date_img"]);

        $I->waitForElementVisible(['xpath' => "//td[contains(@class, 'selected')]"]);

        $I->click(['xpath' => "//td[contains(@class, 'selected')]"]);

        $I->wait(2);


        $I->waitForElement(['id' => "jform_end_date_img"], 30);

        $I->click(['id' => "jform_end_date_img"]);

        $I->waitForElementVisible(['xpath' => "//td[contains(@class, 'selected')]"]);

        $I->click(['xpath' => "//td[contains(@class, 'weekend')]"]);

        $I->wait(2);

        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);
        $I->fillField(\MassDiscountManagerPage::$valueAmount, $amountValue);
//        $I->click(\MassDiscountManagerPage::$dateCalender);
//        $I->setFilter(\MassDiscountManagerPage::$dayStart, $discountStart);
////        $I->fillField(\MassDiscountManagerPage::$dayStart, $discountStart);
        $I->fillField(\MassDiscountManagerPage::$dayEnd, $discountEnd);
//
////        $I->click(['xpath' => "//div[@id='s2id_jform_manufacturer_id']//ul/li"]);
////        $I->fillField(['xpath' => "//div[@id='s2id_jform_manufacturer_id']//ul/li//input"], $nameManufacture);
////        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameManufacture . "')]"]);
////        $I->click(['xpath' => "//span[contains(text(), '" . $nameManufacture . "')]"]);
//
        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryFormInput, $nameCategory);
        $useMassDiscountPage = new \MassDiscountManagerPage();
        $I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
        $I->click($useMassDiscountPage->returnXpath($nameCategory));
//
//
//        $I->click(\MassDiscountManagerPage::$discountForm);
//        $I->fillField(\MassDiscountManagerPage::$discountFormInput, $nameProduct);
//        $I->waitForElement($useMassDiscountPage->returnXpath($nameProduct));
//        $I->click($useMassDiscountPage->returnXpath($nameProduct));
////
////
////        $I->click(['xpath' => "//div[@id='s2id_jform_discount_product']//ul/li"]);
////        $I->fillField(['xpath' => "//div[@id='s2id_jform_discount_product']//ul/li//input"], $nameProduct);
////        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameProduct . "')]"]);
////        $I->click(['xpath' => "//span[contains(text(), '" . $nameProduct . "')]"]);
//
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->see(\MassDiscountManagerPage::$saveOneSuccess, \MassDiscountManagerPage::$selectorSuccess);
    }

    public function addMassDiscountSaveClose($massDiscountName, $amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);
        $I->fillField(\MassDiscountManagerPage::$valueAmount, $amountValue);
//        $I->fillField(\MassDiscountManagerPage::$dayStart, $discountStart);
//        $I->fillField(\MassDiscountManagerPage::$dayEnd, $discountEnd);

//        $I->click(['xpath' => "//div[@id='s2id_jform_manufacturer_id']//ul/li"]);
//        $I->fillField(['xpath' => "//div[@id='s2id_jform_manufacturer_id']//ul/li//input"], $nameManufacture);
//        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameManufacture . "')]"]);
//        $I->click(['xpath' => "//span[contains(text(), '" . $nameManufacture . "')]"]);

        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryForm, $nameCategory);
        $useMassDiscountPage = new \MassDiscountManagerPage();
        $I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
        $I->click($useMassDiscountPage->returnXpath($nameCategory));

        $I->click(\MassDiscountManagerPage::$discountForm);
        $I->fillField(\MassDiscountManagerPage::$discountForm, $nameProduct);
        $I->waitForElement($useMassDiscountPage->returnXpath($nameProduct));
        $I->click($useMassDiscountPage->returnXpath($nameProduct));

        $I->click(\MassDiscountManagerPage::$saveCloseButton);
        $I->waitForText(\MassDiscountManagerPage::$saveOneSuccess, 30, \MassDiscountManagerPage::$selectorSuccess);

        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
    }

    public function addMassDiscountStartThanEnd($massDiscountName, $amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);
        $I->fillField(\MassDiscountManagerPage::$valueAmount, $amountValue);
//        $I->fillField(\MassDiscountManagerPage::$dayStart, $discountEnd);
//        $I->fillField(\MassDiscountManagerPage::$dayEnd, $discountStart);
        $useMassDiscountPage = new \MassDiscountManagerPage();

        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryFormInput, $nameCategory);
        $useMassDiscountPage->returnXpath($nameCategory);
        $I->click(\MassDiscountManagerPage::$discountForm);
        $I->fillField(\MassDiscountManagerPage::$discountFormInput, $nameProduct);
        $useMassDiscountPage->returnXpath($nameProduct);

        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->waitForText(\MassDiscountManagerPage::$messageError, 30, \MassDiscountManagerPage::$selectorError);
    }

    public function addMassDiscountMissingAllFields()
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->waitForText(\MassDiscountManagerPage::$fieldName, 30, \MassDiscountManagerPage::$selectorError);
    }

    public function addMassDiscountMissingName($amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->fillField(\MassDiscountManagerPage::$valueAmount, $amountValue);
//        $I->fillField(\MassDiscountManagerPage::$dayStart, $discountEnd);
//        $I->fillField(\MassDiscountManagerPage::$dayEnd, $discountStart);

        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryForm, $nameCategory);
        $useMassDiscountPage = new \MassDiscountManagerPage();
        $I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
        $I->click($useMassDiscountPage->returnXpath($nameCategory));


        $I->click(\MassDiscountManagerPage::$discountForm);
        $I->fillField(\MassDiscountManagerPage::$discountForm, $nameProduct);
        $I->waitForElement($useMassDiscountPage->returnXpath($nameProduct));
        $I->click($useMassDiscountPage->returnXpath($nameProduct));
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->waitForText(\MassDiscountManagerPage::$fieldName, 30, \MassDiscountManagerPage::$selectorError);
    }

    public function addMassDiscountMissingAmount($massDiscountName, $discountStart, $discountEnd, $nameCategory, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
//        $I->verifyNotices(false, $this->checkForNotices(), \MassDiscountManagerPage::$pageNew);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);
//        $I->fillField(\MassDiscountManagerPage::$dayStart, $discountStart);
//        $I->fillField(\MassDiscountManagerPage::$dayEnd, $discountEnd);

        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryForm, $nameCategory);
        $useMassDiscountPage = new \MassDiscountManagerPage();
        $I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
        $I->click($useMassDiscountPage->returnXpath($nameCategory));

        $I->click(\MassDiscountManagerPage::$discountForm);
        $I->fillField(\MassDiscountManagerPage::$discountForm, $nameProduct);
        $I->waitForElement($useMassDiscountPage->returnXpath($nameProduct));
        $I->click($useMassDiscountPage->returnXpath($nameProduct));
        $I->click(\MassDiscountManagerPage::$saveButton);

        $I->waitForText(\MassDiscountManagerPage::$messageError, 30, \MassDiscountManagerPage::$selectorError);
    }

    public function addMassDiscountMissingProducts($massDiscountName, $amountValue, $discountStart, $discountEnd)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);
        $I->fillField(\MassDiscountManagerPage::$valueAmount, $amountValue);
//        $I->fillField(\MassDiscountManagerPage::$dayStart, $discountStart);
//        $I->fillField(\MassDiscountManagerPage::$dayEnd, $discountEnd);
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->waitForText(\MassDiscountManagerPage::$saveError, 30, \MassDiscountManagerPage::$selectorError);
    }

    public function editMassDiscount($massDiscountName, $massDiscountNameEdit)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->searchMassDiscount($massDiscountName);
        $I->wait(3);
        $I->click(['link' => $massDiscountName]);
        $I->waitForElement(\MassDiscountManagerPage::$name, 30);
        $I->verifyNotices(false, $this->checkForNotices(), \MassDiscountManagerPage::$pageEdit);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountNameEdit);
        $I->click(\MassDiscountManagerPage::$saveCloseButton);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
    }

    public function editMassDiscountSave($massDiscountName, $massDiscountNameEdit)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->searchMassDiscount($massDiscountName);
        $I->wait(3);
        $I->click(['link' => $massDiscountName]);
        $I->waitForElement(\MassDiscountManagerPage::$name, 30);
        $I->verifyNotices(false, $this->checkForNotices(), \MassDiscountManagerPage::$pageEdit);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountNameEdit);
        $I->click(\MassDiscountManagerPage::$saveCloseButton);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
    }

    public function editButtonMassDiscountSave($massDiscountName, $massDiscountNameEdit)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->searchMassDiscount($massDiscountName);
        $I->wait(3);
        $I->click(\MassDiscountManagerPage::$checkFirstItems);
        $I->click(\MassDiscountManagerPage::$editButton);
        $I->waitForElement(\MassDiscountManagerPage::$name, 30);
        $I->verifyNotices(false, $this->checkForNotices(), \MassDiscountManagerPage::$pageEdit);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountNameEdit);
        $I->click(\MassDiscountManagerPage::$saveCloseButton);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
    }


    public function checkCloseButton($massDiscountName)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->searchMassDiscount($massDiscountName);
        $I->wait(3);
        $I->click(['link' => $massDiscountName]);
        $I->waitForElement(\MassDiscountManagerPage::$name, 30);
        $I->verifyNotices(false, $this->checkForNotices(), \MassDiscountManagerPage::$pageEdit);

        $I->click(\MassDiscountManagerPage::$closeButton);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
    }

    public function deleteMassDiscountCancel($massDiscountName)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->searchMassDiscount($massDiscountName);
        $I->wait(3);
        $I->click(\MassDiscountManagerPage::$checkFirstItems);
        $I->click(\MassDiscountManagerPage::$deleteButton);
        $I->cancelPopup();
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
    }

    public function deleteMassDiscountOK($massDiscountName)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->searchMassDiscount($massDiscountName);
        $I->wait(3);
        $I->click(\MassDiscountManagerPage::$checkFirstItems);
        $I->click(\MassDiscountManagerPage::$deleteButton);
        $I->acceptPopup();
        $I->waitForText(\MassDiscountManagerPage::$messageSuccess, 30, \MassDiscountManagerPage::$selectorSuccess);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
        $I->fillField(\MassDiscountManagerPage::$MassDiscountFilter, $massDiscountName);
        $I->pressKey(\MassDiscountManagerPage::$MassDiscountFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($massDiscountName, \MassDiscountManagerPage::$MassDicountResultRow);
    }

    public function deleteAllMassDiscountOK($massDiscountName)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->checkAllResults();
        $I->click(\MassDiscountManagerPage::$deleteButton);
        $I->acceptPopup();
        $I->waitForText(\MassDiscountManagerPage::$messageSuccess, 30, \MassDiscountManagerPage::$selectorSuccess);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
        $I->fillField(\MassDiscountManagerPage::$MassDiscountFilter, $massDiscountName);
        $I->pressKey(\MassDiscountManagerPage::$MassDiscountFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($massDiscountName, \MassDiscountManagerPage::$MassDicountResultRow);
    }


    public function checkCancelButton()
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->click(\MassDiscountManagerPage::$cancelButton);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);

    }

    public function searchMassDiscount($massDiscountName)
    {
        $I = $this;
        $I->wantTo('Search the Mass Discount');
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
        $I->filterListBySearching($massDiscountName);
    }

    public function fillDate($dateFieldFrom, $dateFieldTo = NULL)
    {
        $I = $this;
        $dateFrom = date('d'); //Get today's date
        $dateTo = date('d', strtotime("+7 day")); //Set date +7days from today

        $I->click($dateFieldFrom); //Click the field to activate datepicker popup
        $I->waitForElement('#ui-datepicker-div'); //Wait until it's visible
        $I->click(['link' => $dateFrom]); //Click on today's date

        if ($dateFieldTo === null) {
            return true;
        } else {
            $I->click($dateFieldTo);
            $I->click(['link' => $dateTo]);
        }
    }

}