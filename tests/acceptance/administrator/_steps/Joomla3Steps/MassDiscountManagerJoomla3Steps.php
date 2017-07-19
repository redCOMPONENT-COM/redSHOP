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


        $I->waitForElement(\MassDiscountManagerPage::$startDateIcon, 30);
        $I->click(\MassDiscountManagerPage::$startDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);

        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);
        $I->fillField(\MassDiscountManagerPage::$valueAmount, $amountValue);


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

        $I->waitForElement(\MassDiscountManagerPage::$startDateIcon, 30);
        $I->click(\MassDiscountManagerPage::$startDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);


//        $I->click(['xpath' => "//div[@id='s2id_jform_manufacturer_id']//ul/li"]);
//        $I->fillField(['xpath' => "//div[@id='s2id_jform_manufacturer_id']//ul/li//input"], $nameManufacture);
//        $I->waitForElement(['xpath' => "//span[contains(text(), '" . $nameManufacture . "')]"]);
//        $I->click(['xpath' => "//span[contains(text(), '" . $nameManufacture . "')]"]);

        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryFormInput, $nameCategory);
        $useMassDiscountPage = new \MassDiscountManagerPage();
        $I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
        $I->click($useMassDiscountPage->returnXpath($nameCategory));

//        $I->click(\MassDiscountManagerPage::$discountForm);
//        $I->fillField(\MassDiscountManagerPage::$discountForm, $nameProduct);
//        $I->waitForElement($useMassDiscountPage->returnXpath($nameProduct));
//        $I->click($useMassDiscountPage->returnXpath($nameProduct));

        $I->click(\MassDiscountManagerPage::$saveCloseButton);
        $I->waitForText(\MassDiscountManagerPage::$saveOneSuccess, 30, \MassDiscountManagerPage::$selectorSuccess);

        $I->waitForElement(\MassDiscountManagerPage::$MassDiscountFilter, 30);
    }

//    public function addMassDiscountStartThanEnd($massDiscountName, $amountValue, $discountStart, $discountEnd, $nameCategory, $nameProduct)
//    {
//        $I = $this;
//        $I->amOnPage(\MassDiscountManagerPage::$URL);
//        $I->click(\MassDiscountManagerPage::$newButton);
//        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
//        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);
//        $I->fillField(\MassDiscountManagerPage::$valueAmount, $amountValue);
//
//        $I->waitForElement(\MassDiscountManagerPage::$startDateIcon, 30);
//        $I->click(\MassDiscountManagerPage::$startDateIcon);
//        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
//        $I->click(\MassDiscountManagerPage::$getToday);
//        $I->wait(2);
//        $I->click(\MassDiscountManagerPage::$saveButton);
//        $I->wait(2);
//        $I->click(\MassDiscountManagerPage::$endDateIcon);
//        $I->waitForElementVisible(\MassDiscountManagerPage::$endDateIcon);
//        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
//        $I->click(\MassDiscountManagerPage::$getToday);
//        $I->wait(2);
//
//        $useMassDiscountPage = new \MassDiscountManagerPage();
//
//        $I->click(\MassDiscountManagerPage::$categoryForm);
//        $I->fillField(\MassDiscountManagerPage::$categoryFormInput, $nameCategory);
//        $useMassDiscountPage->returnXpath($nameCategory);
//        $I->click(\MassDiscountManagerPage::$discountForm);
//        $I->fillField(\MassDiscountManagerPage::$discountFormInput, $nameProduct);
//        $useMassDiscountPage->returnXpath($nameProduct);
//
//        $I->click(\MassDiscountManagerPage::$saveButton);
//        $I->waitForText(\MassDiscountManagerPage::$messageError, 30, \MassDiscountManagerPage::$selectorError);
//    }

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

        $I->waitForElement(\MassDiscountManagerPage::$startDateIcon, 30);
        $I->click(\MassDiscountManagerPage::$startDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);

        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryFormInput, $nameCategory);
        $useMassDiscountPage = new \MassDiscountManagerPage();
        $I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
        $I->click($useMassDiscountPage->returnXpath($nameCategory));


//        $I->click(\MassDiscountManagerPage::$discountForm);
//        $I->fillField(\MassDiscountManagerPage::$discountForm, $nameProduct);
//        $I->waitForElement($useMassDiscountPage->returnXpath($nameProduct));
//        $I->click($useMassDiscountPage->returnXpath($nameProduct));
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->waitForText(\MassDiscountManagerPage::$fieldName, 30, \MassDiscountManagerPage::$selectorError);
    }

    public function addMassDiscountMissingAmount($massDiscountName, $discountStart, $discountEnd, $nameCategory, $nameProduct)
    {
        $I = $this;
        $I->amOnPage(\MassDiscountManagerPage::$URL);
        $I->click(\MassDiscountManagerPage::$newButton);
        $I->checkForPhpNoticesOrWarnings(\MassDiscountManagerPage::$URLNew);
        $I->fillField(\MassDiscountManagerPage::$name, $massDiscountName);

        $I->waitForElement(\MassDiscountManagerPage::$startDateIcon, 30);
        $I->click(\MassDiscountManagerPage::$startDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);



        $I->click(\MassDiscountManagerPage::$categoryForm);
        $I->fillField(\MassDiscountManagerPage::$categoryFormInput, $nameCategory);
        $useMassDiscountPage = new \MassDiscountManagerPage();
        $I->waitForElement($useMassDiscountPage->returnXpath($nameCategory));
        $I->click($useMassDiscountPage->returnXpath($nameCategory));

//        $I->click(\MassDiscountManagerPage::$discountForm);
//        $I->fillField(\MassDiscountManagerPage::$discountForm, $nameProduct);
//        $I->waitForElement($useMassDiscountPage->returnXpath($nameProduct));
//        $I->click($useMassDiscountPage->returnXpath($nameProduct));
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

        $I->waitForElement(\MassDiscountManagerPage::$startDateIcon, 30);
        $I->click(\MassDiscountManagerPage::$startDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$saveButton);
        $I->wait(2);
        $I->click(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$endDateIcon);
        $I->waitForElementVisible(\MassDiscountManagerPage::$getToday);
        $I->click(\MassDiscountManagerPage::$getToday);
        $I->wait(2);

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
}